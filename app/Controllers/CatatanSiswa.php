<?php

namespace App\Controllers;

class CatatanSiswa extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    private array $editRoles = ['superadmin', 'kesiswaan', 'wali_kelas', 'bk', 'guru_mapel'];
    private array $viewRoles = ['superadmin', 'kepsek', 'kesiswaan', 'wali_kelas', 'tu', 'bk', 'guru_mapel'];

    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        if ($requireEdit && !in_array($role, $this->editRoles)) {
            redirect()->to(base_url('catatan-siswa'))->with('error', 'Akses ditolak')->send(); exit;
        }
        if (!$requireEdit && !in_array($role, $this->viewRoles)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $this->authCheck();
        $db   = $this->db();
        $role = session()->get('role');

        $search  = $this->request->getGet('search')   ?? '';
        $classId = $this->request->getGet('class_id') ?? '';
        $bulan   = $this->request->getGet('bulan')    ?? '';
        $tahun   = $this->request->getGet('tahun')    ?? date('Y');

        $sql = "
            SELECT sr.*, s.full_name, s.nis, c.nama_kelas, u.username AS pencatat
            FROM student_records sr
            LEFT JOIN students s ON s.id  = sr.student_id
            LEFT JOIN classes c  ON c.id  = s.class_id
            LEFT JOIN users u    ON u.id  = sr.created_by
            WHERE YEAR(sr.date) = ?
        ";
        $params = [(int)$tahun];

        if ($bulan)   { $sql .= " AND MONTH(sr.date) = ?";  $params[] = (int)$bulan; }
        if ($classId) { $sql .= " AND s.class_id = ?";       $params[] = $classId; }
        if ($search)  {
            $sql .= " AND (s.full_name LIKE ? OR sr.activity LIKE ?)";
            $params[] = "%$search%"; $params[] = "%$search%";
        }

        $sql .= " ORDER BY sr.date DESC, sr.id DESC";
        $list = $db->query($sql, $params)->getResultArray();

        $stats = $db->query("
            SELECT COUNT(*) total, COUNT(DISTINCT student_id) siswa,
                   SUM(CASE WHEN MONTH(date)=MONTH(CURDATE()) AND YEAR(date)=YEAR(CURDATE()) THEN 1 ELSE 0 END) bulan_ini
            FROM student_records")->getRowArray();

        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();
        $namaBulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        return view('catatan_siswa/index', [
            'title'     => 'Catatan Siswa',
            'list'      => $list,
            'stats'     => $stats,
            'kelasList' => $kelasList,
            'namaBulan' => $namaBulan,
            'search'    => $search,
            'classId'   => $classId,
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'canEdit'   => in_array($role, $this->editRoles),
        ]);
    }

    // ==============================
    // ADD
    // ==============================
    public function add()
    {
        $this->authCheck(true);
        $studentId = $this->request->getGet('student_id') ?? '';
        $student = $studentId
            ? $this->db()->query("SELECT s.*, c.nama_kelas FROM students s LEFT JOIN classes c ON c.id=s.class_id WHERE s.id=?", [$studentId])->getRowArray()
            : null;

        return view('catatan_siswa/form', [
            'title'   => 'Tambah Catatan',
            'mode'    => 'add',
            'item'    => [],
            'student' => $student,
        ]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store()
    {
        $this->authCheck(true);
        if (!$this->request->getPost('student_id')) return redirect()->back()->withInput()->with('error', 'Siswa harus dipilih');

        $this->db()->table('student_records')->insert([
            'student_id'  => $this->request->getPost('student_id'),
            'activity'    => $this->request->getPost('activity'),
            'date'        => $this->request->getPost('date'),
            'description' => $this->request->getPost('description'),
            'created_by'  => session()->get('user_id'),
        ]);
        return redirect()->to(base_url('catatan-siswa'))->with('success', 'Catatan berhasil ditambahkan');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $this->authCheck(true);
        $item = $this->db()->query("
            SELECT sr.*, s.full_name, s.nis, c.nama_kelas
            FROM student_records sr
            LEFT JOIN students s ON s.id = sr.student_id
            LEFT JOIN classes c  ON c.id = s.class_id
            WHERE sr.id = ?", [$id])->getRowArray();
        if (!$item) return redirect()->to(base_url('catatan-siswa'))->with('error', 'Catatan tidak ditemukan');

        return view('catatan_siswa/form', [
            'title'   => 'Edit Catatan',
            'mode'    => 'edit',
            'item'    => $item,
            'student' => null,
        ]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update($id)
    {
        $this->authCheck(true);
        $this->db()->table('student_records')->update([
            'activity'    => $this->request->getPost('activity'),
            'date'        => $this->request->getPost('date'),
            'description' => $this->request->getPost('description'),
        ], ['id' => $id]);
        return redirect()->to(base_url('catatan-siswa'))->with('success', 'Catatan berhasil diperbarui');
    }

    // ==============================
    // DELETE
    // ==============================
    public function delete($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('catatan-siswa'));
        $this->db()->table('student_records')->delete(['id' => $id]);
        return redirect()->to(base_url('catatan-siswa'))->with('success', 'Catatan berhasil dihapus');
    }

    // AJAX cari siswa
    public function searchSiswa()
    {
        $q = $this->request->getGet('q') ?? '';
        $rows = $this->db()->query("
            SELECT s.id, s.full_name, s.nis, c.nama_kelas
            FROM students s LEFT JOIN classes c ON c.id = s.class_id
            WHERE s.status='aktif' AND (s.full_name LIKE ? OR s.nis LIKE ?)
            LIMIT 10", ["%$q%", "%$q%"])->getResultArray();
        return $this->response->setJSON($rows);
    }
}