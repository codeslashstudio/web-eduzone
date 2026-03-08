<?php

namespace App\Controllers;

class Jurnal extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    // Role yang bisa INPUT jurnal
    private array $inputRoles = ['guru_mapel', 'wali_kelas', 'superadmin', 'kurikulum'];
    // Role yang bisa LIHAT semua jurnal
    private array $viewAllRoles = ['superadmin', 'kepsek', 'kurikulum', 'tu'];

    private function authCheck(): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
    }

    private function getTeacherId(): ?int
    {
        $userId = session()->get('user_id');
        $row = $this->db()->query(
            "SELECT id FROM teachers WHERE user_id = ? LIMIT 1", [$userId]
        )->getRowArray();
        return $row ? (int)$row['id'] : null;
    }

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $this->authCheck();

        $role      = session()->get('role');
        $db        = $this->db();
        $teacherId = $this->getTeacherId();

        $bulan  = $this->request->getGet('bulan')  ?? date('m');
        $tahun  = $this->request->getGet('tahun')  ?? date('Y');
        $guruId = $this->request->getGet('guru_id') ?? '';
        $kelasId= $this->request->getGet('class_id') ?? '';

        // Base query
        $sql = "
            SELECT lj.*, t.full_name AS guru_name, c.nama_kelas
            FROM lesson_journals lj
            LEFT JOIN teachers t ON t.id = lj.teacher_id
            LEFT JOIN classes c  ON c.id = lj.class_id
            WHERE MONTH(lj.date) = ? AND YEAR(lj.date) = ?
        ";
        $params = [(int)$bulan, (int)$tahun];

        // Guru mapel hanya lihat jurnal sendiri
        if (in_array($role, ['guru_mapel', 'wali_kelas']) && $teacherId) {
            $sql .= " AND lj.teacher_id = ?";
            $params[] = $teacherId;
        } elseif ($guruId) {
            $sql .= " AND lj.teacher_id = ?";
            $params[] = $guruId;
        }

        if ($kelasId) {
            $sql .= " AND lj.class_id = ?";
            $params[] = $kelasId;
        }

        $sql .= " ORDER BY lj.date DESC, lj.id DESC";
        $list = $db->query($sql, $params)->getResultArray();

        // Stats bulan ini
        $statSql = "SELECT COUNT(*) total, COUNT(DISTINCT teacher_id) guru, COUNT(DISTINCT class_id) kelas
                    FROM lesson_journals WHERE MONTH(date) = ? AND YEAR(date) = ?";
        $statParams = [(int)$bulan, (int)$tahun];
        if (in_array($role, ['guru_mapel', 'wali_kelas']) && $teacherId) {
            $statSql .= " AND teacher_id = ?";
            $statParams[] = $teacherId;
        }
        $stats = $db->query($statSql, $statParams)->getRowArray();

        // Filter options
        $guruList  = $db->query("SELECT id, full_name FROM teachers WHERE is_active=1 ORDER BY full_name")->getResultArray();
        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();

        return view('jurnal/index', [
            'title'      => 'Jurnal Mengajar',
            'list'       => $list,
            'stats'      => $stats,
            'guruList'   => $guruList,
            'kelasList'  => $kelasList,
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'guruId'     => $guruId,
            'kelasId'    => $kelasId,
            'canInput'   => in_array($role, $this->inputRoles),
            'viewAll'    => in_array($role, $this->viewAllRoles),
            'teacherId'  => $teacherId,
        ]);
    }

    // ==============================
    // ADD
    // ==============================
    public function add()
    {
        $this->authCheck();
        if (!in_array(session()->get('role'), $this->inputRoles)) {
            return redirect()->to(base_url('jurnal'))->with('error', 'Akses ditolak');
        }

        $teacherId = $this->getTeacherId();
        $db = $this->db();

        // Jadwal guru ini untuk datalist
        $jadwal = $teacherId
            ? $db->query("SELECT sc.*, c.nama_kelas FROM schedules sc
                          LEFT JOIN classes c ON c.id = sc.class_id
                          WHERE sc.teacher_id = ? AND sc.is_active = 1
                          ORDER BY sc.subject", [$teacherId])->getResultArray()
            : [];

        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();

        return view('jurnal/form', [
            'title'     => 'Input Jurnal Mengajar',
            'mode'      => 'add',
            'item'      => [],
            'jadwal'    => $jadwal,
            'kelasList' => $kelasList,
            'teacherId' => $teacherId,
        ]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store()
    {
        $this->authCheck();
        if (!in_array(session()->get('role'), $this->inputRoles)) {
            return redirect()->to(base_url('jurnal'))->with('error', 'Akses ditolak');
        }

        $teacherId = (int)($this->request->getPost('teacher_id') ?: $this->getTeacherId());
        $classId   = $this->request->getPost('class_id') ?: null;
        $grade     = $this->request->getPost('grade') ?: null;

        // Auto-ambil grade dari kelas
        if ($classId && !$grade) {
            $kelas = $this->db()->query("SELECT grade FROM classes WHERE id = ?", [$classId])->getRowArray();
            $grade = $kelas['grade'] ?? null;
        }

        $this->db()->table('lesson_journals')->insert([
            'teacher_id'  => $teacherId,
            'schedule_id' => $this->request->getPost('schedule_id') ?: null,
            'class_id'    => $classId,
            'date'        => $this->request->getPost('date'),
            'subject'     => $this->request->getPost('subject'),
            'grade'       => $grade,
            'topic'       => $this->request->getPost('topic'),
            'notes'       => $this->request->getPost('notes'),
        ]);

        return redirect()->to(base_url('jurnal'))->with('success', 'Jurnal berhasil disimpan');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $this->authCheck();
        $item = $this->db()->query(
            "SELECT lj.*, t.full_name AS guru_name FROM lesson_journals lj
             LEFT JOIN teachers t ON t.id = lj.teacher_id
             WHERE lj.id = ?", [$id]
        )->getRowArray();

        if (!$item) return redirect()->to(base_url('jurnal'))->with('error', 'Jurnal tidak ditemukan');

        // Hanya pemilik atau admin yg bisa edit
        $teacherId = $this->getTeacherId();
        $role = session()->get('role');
        if (!in_array($role, $this->viewAllRoles) && $item['teacher_id'] != $teacherId) {
            return redirect()->to(base_url('jurnal'))->with('error', 'Akses ditolak');
        }

        $db = $this->db();
        $jadwal    = $teacherId ? $db->query("SELECT sc.*, c.nama_kelas FROM schedules sc LEFT JOIN classes c ON c.id = sc.class_id WHERE sc.teacher_id = ? AND sc.is_active = 1 ORDER BY sc.subject", [$item['teacher_id']])->getResultArray() : [];
        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();

        return view('jurnal/form', [
            'title'     => 'Edit Jurnal',
            'mode'      => 'edit',
            'item'      => $item,
            'jadwal'    => $jadwal,
            'kelasList' => $kelasList,
            'teacherId' => $teacherId,
        ]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update($id)
    {
        $this->authCheck();
        $item = $this->db()->query("SELECT * FROM lesson_journals WHERE id = ?", [$id])->getRowArray();
        if (!$item) return redirect()->to(base_url('jurnal'))->with('error', 'Jurnal tidak ditemukan');

        $teacherId = $this->getTeacherId();
        $role = session()->get('role');
        if (!in_array($role, $this->viewAllRoles) && $item['teacher_id'] != $teacherId) {
            return redirect()->to(base_url('jurnal'))->with('error', 'Akses ditolak');
        }

        $this->db()->table('lesson_journals')->update([
            'date'    => $this->request->getPost('date'),
            'subject' => $this->request->getPost('subject'),
            'class_id'=> $this->request->getPost('class_id') ?: null,
            'topic'   => $this->request->getPost('topic'),
            'notes'   => $this->request->getPost('notes'),
        ], ['id' => $id]);

        return redirect()->to(base_url('jurnal'))->with('success', 'Jurnal berhasil diperbarui');
    }

    // ==============================
    // DELETE
    // ==============================
    public function delete($id)
    {
        $this->authCheck();
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('jurnal'));

        $item = $this->db()->query("SELECT * FROM lesson_journals WHERE id = ?", [$id])->getRowArray();
        if (!$item) return redirect()->to(base_url('jurnal'))->with('error', 'Jurnal tidak ditemukan');

        $teacherId = $this->getTeacherId();
        $role = session()->get('role');
        if (!in_array($role, $this->viewAllRoles) && $item['teacher_id'] != $teacherId) {
            return redirect()->to(base_url('jurnal'))->with('error', 'Akses ditolak');
        }

        $this->db()->table('lesson_journals')->delete(['id' => $id]);
        return redirect()->to(base_url('jurnal'))->with('success', 'Jurnal berhasil dihapus');
    }
}