<?php

namespace App\Controllers;

class Prestasi extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    private array $editRoles = ['superadmin', 'kesiswaan', 'wali_kelas', 'tu'];
    private array $viewRoles = ['superadmin', 'kepsek', 'kesiswaan', 'wali_kelas', 'tu', 'bk', 'guru_mapel'];

    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        if ($requireEdit && !in_array($role, $this->editRoles)) {
            redirect()->to(base_url('prestasi'))->with('error', 'Akses ditolak')->send(); exit;
        }
        if (!$requireEdit && !in_array($role, array_merge($this->viewRoles, ['siswa']))) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    private function getStudentId(): ?int
    {
        $row = $this->db()->query(
            "SELECT id FROM students WHERE user_id = ? LIMIT 1",
            [session()->get('user_id')]
        )->getRowArray();
        return $row ? (int)$row['id'] : null;
    }

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $this->authCheck();
        $db   = $this->db();
        $role = session()->get('role');

        $search  = $this->request->getGet('search') ?? '';
        $level   = $this->request->getGet('level')  ?? '';
        $tahun   = $this->request->getGet('tahun')  ?? '';
        $classId = $this->request->getGet('class_id') ?? '';

        $sql = "
            SELECT sa.*, s.full_name, s.nis, c.nama_kelas
            FROM student_achievements sa
            LEFT JOIN students s ON s.id = sa.student_id
            LEFT JOIN classes c  ON c.id = s.class_id
            WHERE 1=1
        ";
        $params = [];

        // Siswa hanya lihat prestasi sendiri
        if ($role === 'siswa') {
            $studentId = $this->getStudentId();
            $sql .= " AND sa.student_id = ?";
            $params[] = $studentId;
        }

        if ($search) {
            $sql .= " AND (s.full_name LIKE ? OR sa.title LIKE ?)";
            $params[] = "%$search%"; $params[] = "%$search%";
        }
        if ($level)   { $sql .= " AND sa.level = ?";         $params[] = $level; }
        if ($tahun)   { $sql .= " AND sa.year = ?";          $params[] = $tahun; }
        if ($classId) { $sql .= " AND s.class_id = ?";       $params[] = $classId; }

        $sql .= " ORDER BY sa.year DESC, FIELD(sa.level,'Internasional','Nasional','Provinsi','Kabupaten','Kecamatan','Sekolah')";
        $list = $db->query($sql, $params)->getResultArray();

        // Stats
        $stats = $db->query("
            SELECT
                COUNT(*) total,
                SUM(CASE WHEN level IN ('Nasional','Internasional') THEN 1 ELSE 0 END) nasional,
                SUM(CASE WHEN level = 'Provinsi' THEN 1 ELSE 0 END) provinsi,
                COUNT(DISTINCT student_id) siswa
            FROM student_achievements
        ")->getRowArray();

        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();
        $tahunList = $db->query("SELECT DISTINCT year FROM student_achievements ORDER BY year DESC")->getResultArray();

        return view('prestasi/index', [
            'title'     => 'Prestasi Siswa',
            'list'      => $list,
            'stats'     => $stats,
            'kelasList' => $kelasList,
            'tahunList' => $tahunList,
            'search'    => $search,
            'level'     => $level,
            'tahun'     => $tahun,
            'classId'   => $classId,
            'canEdit'   => in_array($role, $this->editRoles),
        ]);
    }

    // ==============================
    // ADD
    // ==============================
    public function add()
    {
        $this->authCheck(true);
        $kelasList = $this->db()->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();
        return view('prestasi/form', [
            'title'     => 'Tambah Prestasi',
            'mode'      => 'add',
            'item'      => [],
            'kelasList' => $kelasList,
        ]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store()
    {
        $this->authCheck(true);
        $studentId = $this->request->getPost('student_id');
        if (!$studentId) return redirect()->back()->withInput()->with('error', 'Siswa harus dipilih');

        $this->db()->table('student_achievements')->insert([
            'student_id'  => $studentId,
            'title'       => $this->request->getPost('title'),
            'level'       => $this->request->getPost('level'),
            'year'        => $this->request->getPost('year'),
            'description' => $this->request->getPost('description'),
        ]);
        return redirect()->to(base_url('prestasi'))->with('success', 'Prestasi berhasil ditambahkan');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $this->authCheck(true);
        $item = $this->db()->query("
            SELECT sa.*, s.full_name, s.nis
            FROM student_achievements sa
            LEFT JOIN students s ON s.id = sa.student_id
            WHERE sa.id = ?", [$id])->getRowArray();
        if (!$item) return redirect()->to(base_url('prestasi'))->with('error', 'Data tidak ditemukan');

        $kelasList = $this->db()->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();
        return view('prestasi/form', [
            'title'     => 'Edit Prestasi',
            'mode'      => 'edit',
            'item'      => $item,
            'kelasList' => $kelasList,
        ]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update($id)
    {
        $this->authCheck(true);
        $this->db()->table('student_achievements')->update([
            'title'       => $this->request->getPost('title'),
            'level'       => $this->request->getPost('level'),
            'year'        => $this->request->getPost('year'),
            'description' => $this->request->getPost('description'),
        ], ['id' => $id]);
        return redirect()->to(base_url('prestasi'))->with('success', 'Prestasi berhasil diperbarui');
    }

    // ==============================
    // DELETE
    // ==============================
    public function delete($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('prestasi'));
        $this->db()->table('student_achievements')->delete(['id' => $id]);
        return redirect()->to(base_url('prestasi'))->with('success', 'Prestasi berhasil dihapus');
    }

    // ==============================
    // AJAX — cari siswa
    // ==============================
    public function searchSiswa()
    {
        $q = $this->request->getGet('q') ?? '';
        $rows = $this->db()->query("
            SELECT s.id, s.full_name, s.nis, c.nama_kelas
            FROM students s LEFT JOIN classes c ON c.id = s.class_id
            WHERE s.status = 'aktif' AND (s.full_name LIKE ? OR s.nis LIKE ?)
            LIMIT 10
        ", ["%$q%", "%$q%"])->getResultArray();
        return $this->response->setJSON($rows);
    }
}