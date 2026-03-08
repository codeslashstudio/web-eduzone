<?php

namespace App\Controllers;

class MasterData extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    private array $editRoles = ['superadmin', 'kurikulum', 'tu'];
    private array $viewRoles = ['superadmin', 'kurikulum', 'tu', 'kepsek'];

    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        if (!in_array($role, $this->viewRoles)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
        if ($requireEdit && !in_array($role, $this->editRoles)) {
            redirect()->to(base_url('master/kelas'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    // ============================
    // KELAS — INDEX
    // ============================
    public function kelas()
    {
        $this->authCheck();
        $db = $this->db();

        $tahun  = $this->request->getGet('tahun')  ?? '2025/2026';
        $grade  = $this->request->getGet('grade')  ?? '';
        $major  = $this->request->getGet('major')  ?? '';
        $status = $this->request->getGet('status') ?? '';

        $sql = "
            SELECT c.*, m.name AS major_name, m.abbreviation,
                   COUNT(DISTINCT s.id) AS jumlah_siswa,
                   t.full_name AS wakel_name
            FROM classes c
            LEFT JOIN majors m ON m.id = c.major_id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            LEFT JOIN homeroom_assignments ha ON ha.class_id = c.id AND ha.academic_year = c.academic_year
            LEFT JOIN teachers t ON t.id = ha.teacher_id
            WHERE c.academic_year = ?
        ";
        $params = [$tahun];

        if ($grade)         { $sql .= " AND c.grade = ?";    $params[] = $grade; }
        if ($major)         { $sql .= " AND c.major_id = ?"; $params[] = $major; }
        if ($status !== '') { $sql .= " AND c.is_active = ?"; $params[] = $status; }

        $sql .= " GROUP BY c.id ORDER BY c.grade, m.name, c.class_group";
        $list = $db->query($sql, $params)->getResultArray();

        $stats = $db->query("
            SELECT COUNT(*) total,
                   SUM(is_active) aktif,
                   SUM(CASE WHEN is_active=0 THEN 1 ELSE 0 END) nonaktif
            FROM classes WHERE academic_year = ?", [$tahun])->getRowArray();

        $majorList = $db->query("SELECT id, name, abbreviation FROM majors WHERE is_active=1 ORDER BY name")->getResultArray();

        return view('master/kelas_index', [
            'title'     => 'Manajemen Kelas',
            'list'      => $list,
            'stats'     => $stats,
            'majorList' => $majorList,
            'tahun'     => $tahun,
            'grade'     => $grade,
            'major'     => $major,
            'status'    => $status,
            'canEdit'   => in_array(session()->get('role'), $this->editRoles),
        ]);
    }

    // ============================
    // KELAS — ADD
    // ============================
    public function kelasAdd()
    {
        $this->authCheck(true);
        $majorList = $this->db()->query("SELECT id, name, abbreviation FROM majors WHERE is_active=1 ORDER BY name")->getResultArray();
        return view('master/kelas_form', [
            'title'     => 'Tambah Kelas',
            'mode'      => 'add',
            'item'      => [],
            'majorList' => $majorList,
        ]);
    }

    // ============================
    // KELAS — STORE
    // ============================
    public function kelasStore()
    {
        $this->authCheck(true);
        $db       = $this->db();
        $majorId  = $this->request->getPost('major_id');
        $grade    = $this->request->getPost('grade');
        $group    = $this->request->getPost('class_group');
        $tahun    = $this->request->getPost('academic_year');

        // Auto-generate nama_kelas jika kosong
        $namaKelas = $this->request->getPost('nama_kelas');
        if (!$namaKelas) {
            $major = $db->query("SELECT abbreviation FROM majors WHERE id=?", [$majorId])->getRowArray();
            $namaKelas = $grade . ' ' . ($major['abbreviation'] ?? '') . ' ' . $group;
        }

        // Cek duplikat
        $exists = $db->query(
            "SELECT id FROM classes WHERE grade=? AND major_id=? AND class_group=? AND academic_year=?",
            [$grade, $majorId, $group, $tahun]
        )->getRowArray();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Kelas sudah ada untuk kombinasi ini');
        }

        $db->table('classes')->insert([
            'grade'         => $grade,
            'major_id'      => $majorId,
            'class_group'   => $group,
            'academic_year' => $tahun,
            'nama_kelas'    => $namaKelas,
            'kapasitas'     => $this->request->getPost('kapasitas') ?: 36,
            'is_active'     => 1,
        ]);

        return redirect()->to(base_url('master/kelas'))->with('success', 'Kelas berhasil ditambahkan');
    }

    // ============================
    // KELAS — EDIT
    // ============================
    public function kelasEdit($id)
    {
        $this->authCheck(true);
        $item = $this->db()->query("SELECT * FROM classes WHERE id=?", [$id])->getRowArray();
        if (!$item) return redirect()->to(base_url('master/kelas'))->with('error', 'Kelas tidak ditemukan');

        $majorList = $this->db()->query("SELECT id, name, abbreviation FROM majors WHERE is_active=1 ORDER BY name")->getResultArray();
        return view('master/kelas_form', [
            'title'     => 'Edit Kelas',
            'mode'      => 'edit',
            'item'      => $item,
            'majorList' => $majorList,
        ]);
    }

    // ============================
    // KELAS — UPDATE
    // ============================
    public function kelasUpdate($id)
    {
        $this->authCheck(true);
        $this->db()->table('classes')->update([
            'grade'         => $this->request->getPost('grade'),
            'major_id'      => $this->request->getPost('major_id'),
            'class_group'   => $this->request->getPost('class_group'),
            'academic_year' => $this->request->getPost('academic_year'),
            'nama_kelas'    => $this->request->getPost('nama_kelas'),
            'kapasitas'     => $this->request->getPost('kapasitas') ?: 36,
        ], ['id' => $id]);
        return redirect()->to(base_url('master/kelas'))->with('success', 'Kelas berhasil diperbarui');
    }

    // ============================
    // KELAS — TOGGLE STATUS
    // ============================
    public function kelasToggle($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('master/kelas'));
        $item = $this->db()->query("SELECT is_active FROM classes WHERE id=?", [$id])->getRowArray();
        if (!$item) return redirect()->to(base_url('master/kelas'));
        $this->db()->table('classes')->update(['is_active' => $item['is_active'] ? 0 : 1], ['id' => $id]);
        return redirect()->to(base_url('master/kelas'))->with('success', 'Status kelas diperbarui');
    }

    // ============================
    // KELAS — DELETE
    // ============================
    public function kelasDelete($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('master/kelas'));

        // Cek apakah ada siswa aktif
        $jumlah = $this->db()->query(
            "SELECT COUNT(*) jumlah FROM students WHERE class_id=? AND status='aktif'", [$id]
        )->getRowArray();

        if ($jumlah['jumlah'] > 0) {
            return redirect()->to(base_url('master/kelas'))
                ->with('error', 'Tidak bisa hapus — masih ada ' . $jumlah['jumlah'] . ' siswa aktif di kelas ini');
        }

        $this->db()->table('classes')->delete(['id' => $id]);
        return redirect()->to(base_url('master/kelas'))->with('success', 'Kelas berhasil dihapus');
    }

    // ============================
    // JURUSAN — INDEX
    // ============================
    public function jurusan()
    {
        $this->authCheck();
        $db = $this->db();

        $list = $db->query("
            SELECT m.*,
                   COUNT(DISTINCT c.id) jumlah_kelas,
                   COUNT(DISTINCT s.id) jumlah_siswa
            FROM majors m
            LEFT JOIN classes c ON c.major_id = m.id AND c.is_active = 1
            LEFT JOIN students s ON s.major_id = m.id AND s.status = 'aktif'
            GROUP BY m.id
            ORDER BY m.name ASC
        ")->getResultArray();

        $stats = $db->query("
            SELECT COUNT(*) total,
                   SUM(is_active) aktif,
                   SUM(CASE WHEN is_active=0 THEN 1 ELSE 0 END) nonaktif
            FROM majors")->getRowArray();

        return view('master/jurusan_index', [
            'title'   => 'Manajemen Jurusan',
            'list'    => $list,
            'stats'   => $stats,
            'canEdit' => in_array(session()->get('role'), $this->editRoles),
        ]);
    }

    // ============================
    // JURUSAN — STORE (via modal)
    // ============================
    public function jurusanStore()
    {
        $this->authCheck(true);
        $abbr = strtoupper(trim($this->request->getPost('abbreviation')));

        $exists = $this->db()->query("SELECT id FROM majors WHERE abbreviation=?", [$abbr])->getRowArray();
        if ($exists) {
            return redirect()->back()->with('error', 'Singkatan jurusan sudah digunakan');
        }

        $this->db()->table('majors')->insert([
            'name'         => $this->request->getPost('name'),
            'abbreviation' => $abbr,
            'description'  => $this->request->getPost('description'),
            'is_active'    => 1,
        ]);
        return redirect()->to(base_url('master/jurusan'))->with('success', 'Jurusan berhasil ditambahkan');
    }

    // ============================
    // JURUSAN — UPDATE (via modal)
    // ============================
    public function jurusanUpdate($id)
    {
        $this->authCheck(true);
        $abbr = strtoupper(trim($this->request->getPost('abbreviation')));

        $exists = $this->db()->query("SELECT id FROM majors WHERE abbreviation=? AND id != ?", [$abbr, $id])->getRowArray();
        if ($exists) {
            return redirect()->back()->with('error', 'Singkatan jurusan sudah digunakan');
        }

        $this->db()->table('majors')->update([
            'name'         => $this->request->getPost('name'),
            'abbreviation' => $abbr,
            'description'  => $this->request->getPost('description'),
        ], ['id' => $id]);
        return redirect()->to(base_url('master/jurusan'))->with('success', 'Jurusan berhasil diperbarui');
    }

    // ============================
    // JURUSAN — TOGGLE
    // ============================
    public function jurusanToggle($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('master/jurusan'));
        $item = $this->db()->query("SELECT is_active FROM majors WHERE id=?", [$id])->getRowArray();
        if (!$item) return redirect()->to(base_url('master/jurusan'));
        $this->db()->table('majors')->update(['is_active' => $item['is_active'] ? 0 : 1], ['id' => $id]);
        return redirect()->to(base_url('master/jurusan'))->with('success', 'Status jurusan diperbarui');
    }

    // ============================
    // JURUSAN — DELETE
    // ============================
    public function jurusanDelete($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('master/jurusan'));

        $jumlah = $this->db()->query(
            "SELECT COUNT(*) jumlah FROM classes WHERE major_id=? AND is_active=1", [$id]
        )->getRowArray();

        if ($jumlah['jumlah'] > 0) {
            return redirect()->to(base_url('master/jurusan'))
                ->with('error', 'Tidak bisa hapus — masih ada ' . $jumlah['jumlah'] . ' kelas aktif di jurusan ini');
        }

        $this->db()->table('majors')->delete(['id' => $id]);
        return redirect()->to(base_url('master/jurusan'))->with('success', 'Jurusan berhasil dihapus');
    }
}