<?php

namespace App\Controllers;

class Konseling extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    private array $editRoles = ['superadmin', 'bk'];
    private array $viewRoles = ['superadmin', 'bk', 'kepsek', 'kesiswaan', 'wali_kelas'];

    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        if (!in_array($role, array_merge($this->viewRoles, $this->editRoles))) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
        if ($requireEdit && !in_array($role, $this->editRoles)) {
            redirect()->to(base_url('konseling'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    private function getStaffId(): ?int
    {
        $row = $this->db()->query(
            "SELECT id FROM staff WHERE user_id = ? LIMIT 1",
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
        $db      = $this->db();
        $role    = session()->get('role');
        $staffId = $this->getStaffId();

        $search  = $this->request->getGet('search')   ?? '';
        $bulan   = $this->request->getGet('bulan')    ?? date('m');
        $tahun   = $this->request->getGet('tahun')    ?? date('Y');
        $classId = $this->request->getGet('class_id') ?? '';

        $sql = "
            SELECT cs.*, s.full_name AS siswa_name, s.nis, c.nama_kelas,
                   st.full_name AS konselor_name
            FROM counseling_sessions cs
            LEFT JOIN students s ON s.id = cs.student_id
            LEFT JOIN classes c  ON c.id = s.class_id
            LEFT JOIN staff st   ON st.id = cs.staff_id
            WHERE MONTH(cs.date) = ? AND YEAR(cs.date) = ?
        ";
        $params = [(int)$bulan, (int)$tahun];

        // BK hanya lihat sesi miliknya sendiri
        if ($role === 'bk' && $staffId) {
            $sql .= " AND cs.staff_id = ?";
            $params[] = $staffId;
        }

        if ($search) {
            $sql .= " AND (s.full_name LIKE ? OR cs.topic LIKE ?)";
            $params[] = "%$search%"; $params[] = "%$search%";
        }
        if ($classId) {
            $sql .= " AND s.class_id = ?";
            $params[] = $classId;
        }

        $sql .= " ORDER BY cs.date DESC, cs.id DESC";
        $list = $db->query($sql, $params)->getResultArray();

        // Stats bulan ini
        $stats = $db->query("
            SELECT
                COUNT(*) total,
                COUNT(DISTINCT student_id) siswa,
                COUNT(DISTINCT staff_id) konselor
            FROM counseling_sessions
            WHERE MONTH(date) = ? AND YEAR(date) = ?
        ", [(int)$bulan, (int)$tahun])->getRowArray();

        // Topik terbanyak
        $topTopik = $db->query("
            SELECT topic, COUNT(*) jumlah
            FROM counseling_sessions
            WHERE MONTH(date) = ? AND YEAR(date) = ?
            GROUP BY topic ORDER BY jumlah DESC LIMIT 5
        ", [(int)$bulan, (int)$tahun])->getResultArray();

        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();

        return view('konseling/index', [
            'title'     => 'Sesi Konseling',
            'list'      => $list,
            'stats'     => $stats,
            'topTopik'  => $topTopik,
            'kelasList' => $kelasList,
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'search'    => $search,
            'classId'   => $classId,
            'canEdit'   => in_array($role, $this->editRoles),
        ]);
    }

    // ==============================
    // DETAIL
    // ==============================
    public function detail($id)
    {
        $this->authCheck();
        $item = $this->db()->query("
            SELECT cs.*, s.full_name AS siswa_name, s.nis, s.gender, c.nama_kelas,
                   st.full_name AS konselor_name
            FROM counseling_sessions cs
            LEFT JOIN students s ON s.id = cs.student_id
            LEFT JOIN classes c  ON c.id = s.class_id
            LEFT JOIN staff st   ON st.id = cs.staff_id
            WHERE cs.id = ?", [$id])->getRowArray();

        if (!$item) return redirect()->to(base_url('konseling'))->with('error', 'Sesi tidak ditemukan');

        // Riwayat konseling siswa ini
        $riwayat = $this->db()->query("
            SELECT cs.*, st.full_name AS konselor_name
            FROM counseling_sessions cs
            LEFT JOIN staff st ON st.id = cs.staff_id
            WHERE cs.student_id = ? AND cs.id != ?
            ORDER BY cs.date DESC LIMIT 10
        ", [$item['student_id'], $id])->getResultArray();

        return view('konseling/detail', [
            'title'   => 'Detail Sesi Konseling',
            'item'    => $item,
            'riwayat' => $riwayat,
            'canEdit' => in_array(session()->get('role'), $this->editRoles),
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

        return view('konseling/form', [
            'title'    => 'Input Sesi Konseling',
            'mode'     => 'add',
            'item'     => [],
            'student'  => $student,
            'staffId'  => $this->getStaffId(),
        ]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store()
    {
        $this->authCheck(true);
        if (!$this->request->getPost('student_id')) {
            return redirect()->back()->withInput()->with('error', 'Siswa harus dipilih');
        }

        $staffId = $this->request->getPost('staff_id') ?: $this->getStaffId();

        $this->db()->table('counseling_sessions')->insert([
            'student_id' => $this->request->getPost('student_id'),
            'staff_id'   => $staffId,
            'date'       => $this->request->getPost('date'),
            'topic'      => $this->request->getPost('topic'),
            'result'     => $this->request->getPost('result'),
        ]);

        return redirect()->to(base_url('konseling'))->with('success', 'Sesi konseling berhasil disimpan');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $this->authCheck(true);
        $item = $this->db()->query("
            SELECT cs.*, s.full_name AS siswa_name, s.nis, c.nama_kelas
            FROM counseling_sessions cs
            LEFT JOIN students s ON s.id = cs.student_id
            LEFT JOIN classes c  ON c.id = s.class_id
            WHERE cs.id = ?", [$id])->getRowArray();

        if (!$item) return redirect()->to(base_url('konseling'))->with('error', 'Sesi tidak ditemukan');

        return view('konseling/form', [
            'title'   => 'Edit Sesi Konseling',
            'mode'    => 'edit',
            'item'    => $item,
            'student' => null,
            'staffId' => $this->getStaffId(),
        ]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update($id)
    {
        $this->authCheck(true);
        $this->db()->table('counseling_sessions')->update([
            'date'   => $this->request->getPost('date'),
            'topic'  => $this->request->getPost('topic'),
            'result' => $this->request->getPost('result'),
        ], ['id' => $id]);

        return redirect()->to(base_url('konseling/' . $id))->with('success', 'Sesi konseling berhasil diperbarui');
    }

    // ==============================
    // DELETE
    // ==============================
    public function delete($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('konseling'));
        $this->db()->table('counseling_sessions')->delete(['id' => $id]);
        return redirect()->to(base_url('konseling'))->with('success', 'Sesi konseling berhasil dihapus');
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
            WHERE s.status='aktif' AND (s.full_name LIKE ? OR s.nis LIKE ?)
            LIMIT 10", ["%$q%", "%$q%"])->getResultArray();
        return $this->response->setJSON($rows);
    }
}