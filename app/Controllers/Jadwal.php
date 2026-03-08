<?php

namespace App\Controllers;

class Jadwal extends BaseController
{
    protected $db;

    protected $viewRoles = [
        'kepsek', 'tu', 'kurikulum', 'guru_mapel', 'wali_kelas',
        'kesiswaan', 'bk', 'toolman', 'siswa', 'superadmin'
    ];
    protected $editRoles = ['kurikulum', 'superadmin'];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    private function checkAuth(bool $requireEdit = false)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $role    = session()->get('role');
        $allowed = $requireEdit ? $this->editRoles : $this->viewRoles;
        if (!in_array($role, $allowed)) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }
        return null;
    }

    private function baseData(): array
    {
        $role = session()->get('role');
        return [
            'username' => session()->get('username'),
            'role'     => $role,
            'canEdit'  => in_array($role, $this->editRoles),
        ];
    }

    private function hariIni(): string
    {
        $map = [
            'Monday'    => 'Senin',   'Tuesday'  => 'Selasa',
            'Wednesday' => 'Rabu',    'Thursday' => 'Kamis',
            'Friday'    => 'Jumat',   'Saturday' => 'Sabtu',
        ];
        return $map[date('l')] ?? 'Senin';
    }

    // ============================================================
    // INDEX — tampilan jadwal sesuai role
    // ============================================================
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $role     = session()->get('role');
        $userId   = session()->get('user_id');
        $hariIni  = $this->hariIni();

        // Filter dari query string
        $classId    = $this->request->getGet('class_id') ?? '';
        $teacherId  = $this->request->getGet('teacher_id') ?? '';
        $day        = $this->request->getGet('day') ?? '';

        // Auto-set filter berdasarkan role
        if ($role === 'guru_mapel' && empty($teacherId)) {
            $teacher   = $this->db->table('teachers')->where('user_id', $userId)->get()->getRowArray();
            $teacherId = $teacher['id'] ?? '';
        }

        if ($role === 'wali_kelas' && empty($classId)) {
            $teacher = $this->db->table('teachers')->where('user_id', $userId)->get()->getRowArray();
            if ($teacher) {
                $ha = $this->db->table('homeroom_assignments')
                    ->where('teacher_id', $teacher['id'])
                    ->where('is_active', 1)
                    ->get()->getRowArray();
                $classId = $ha['class_id'] ?? '';
            }
        }

        if ($role === 'siswa' && empty($classId)) {
            $siswa   = $this->db->table('students')->where('user_id', $userId)->get()->getRowArray();
            $classId = $siswa['class_id'] ?? '';
        }

        // Build query jadwal
        $builder = $this->db->table('schedules sc')
            ->select('sc.*, t.full_name AS nama_guru, c.nama_kelas,
                      m.abbreviation AS major_name')
            ->join('teachers t', 't.id = sc.teacher_id', 'left')
            ->join('classes c', 'c.id = sc.class_id', 'left')
            ->join('majors m', 'm.abbreviation = sc.major', 'left')
            ->where('sc.is_active', 1);

        if ($classId)   $builder->where('sc.class_id', $classId);
        if ($teacherId) $builder->where('sc.teacher_id', $teacherId);
        if ($day)       $builder->where('sc.day', $day);

        $jadwal = $builder
            ->orderBy("FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('sc.start_time', 'ASC')
            ->get()->getResultArray();

        // Kelompokkan per hari
        $jadwalPerHari = [];
        $hariUrut = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        foreach ($hariUrut as $h) {
            $jadwalPerHari[$h] = array_filter($jadwal, fn($j) => $j['day'] === $h);
        }

        $kelasList   = $this->getKelasList();
        $guruList    = $this->db->table('teachers')
            ->select('id, full_name')
            ->where('is_active', 1)
            ->orderBy('full_name')
            ->get()->getResultArray();

        $data = array_merge($this->baseData(), [
            'title'        => 'Jadwal Pelajaran',
            'subtitle'     => $day ? "Hari $day" : 'Semua Hari',
            'jadwal'       => $jadwal,
            'jadwalPerHari'=> $jadwalPerHari,
            'hariIni'      => $hariIni,
            'kelasList'    => $kelasList,
            'guruList'     => $guruList,
            'filterClassId'  => $classId,
            'filterTeacherId'=> $teacherId,
            'filterDay'      => $day,
        ]);

        return view('jadwal/index', $data);
    }

    // ============================================================
    // JADWAL PER GURU
    // ============================================================
    public function guru($teacherId = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Guru mapel hanya bisa lihat jadwal sendiri
        if (session()->get('role') === 'guru_mapel') {
            $teacher   = $this->db->table('teachers')
                ->where('user_id', session()->get('user_id'))
                ->get()->getRowArray();
            $teacherId = $teacher['id'] ?? null;
        }

        if (!$teacherId) {
            return redirect()->to('/jadwal')->with('error', 'Guru tidak ditemukan');
        }

        $teacher = $this->db->table('teachers t')
            ->select('t.*, m.name AS major_name')
            ->join('majors m', 'm.id = t.major_id', 'left')
            ->where('t.id', $teacherId)
            ->get()->getRowArray();

        if (!$teacher) {
            return redirect()->to('/jadwal')->with('error', 'Data guru tidak ditemukan');
        }

        $jadwal = $this->db->query("
            SELECT sc.*, c.nama_kelas, m.abbreviation AS major_name
            FROM schedules sc
            LEFT JOIN classes c ON c.id = sc.class_id
            LEFT JOIN majors m ON m.abbreviation = sc.major
            WHERE sc.teacher_id = ? AND sc.is_active = 1
            ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),
                     sc.start_time ASC
        ", [$teacherId])->getResultArray();

        $jadwalPerHari = [];
        foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h) {
            $jadwalPerHari[$h] = array_values(
                array_filter($jadwal, fn($j) => $j['day'] === $h)
            );
        }

        // Hitung total jam mengajar per minggu
        $totalMenit = 0;
        foreach ($jadwal as $j) {
            $start = strtotime($j['start_time']);
            $end   = strtotime($j['end_time']);
            $totalMenit += ($end - $start) / 60;
        }

        $data = array_merge($this->baseData(), [
            'title'         => 'Jadwal Guru',
            'subtitle'      => $teacher['full_name'],
            'teacher'       => $teacher,
            'jadwal'        => $jadwal,
            'jadwalPerHari' => $jadwalPerHari,
            'totalJadwal'   => count($jadwal),
            'totalJam'      => round($totalMenit / 60, 1),
            'hariIni'       => $this->hariIni(),
        ]);

        return view('jadwal/guru', $data);
    }

    // ============================================================
    // JADWAL PER KELAS (cetak friendly)
    // ============================================================
    public function kelas($classId = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if (!$classId) {
            return redirect()->to('/jadwal')->with('error', 'Kelas tidak dipilih');
        }

        $kelas = $this->db->query("
            SELECT c.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   t.full_name AS nama_wakel
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN homeroom_assignments ha ON ha.class_id = c.id AND ha.is_active = 1
            LEFT JOIN teachers t ON ha.teacher_id = t.id
            WHERE c.id = ?
        ", [$classId])->getRowArray();

        if (!$kelas) {
            return redirect()->to('/jadwal')->with('error', 'Kelas tidak ditemukan');
        }

        $jadwal = $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru,
                   CONCAT(sc.grade,' ',sc.major,' ',sc.class_group) AS nama_kelas_sc
            FROM schedules sc
            LEFT JOIN teachers t ON sc.teacher_id = t.id
            WHERE sc.class_id = ? AND sc.is_active = 1
            ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),
                     sc.start_time ASC
        ", [$classId])->getResultArray();

        $jadwalPerHari = [];
        foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h) {
            $jadwalPerHari[$h] = array_values(
                array_filter($jadwal, fn($j) => $j['day'] === $h)
            );
        }

        $data = array_merge($this->baseData(), [
            'title'         => 'Jadwal Kelas',
            'subtitle'      => $kelas['nama_kelas'],
            'kelas'         => $kelas,
            'jadwal'        => $jadwal,
            'jadwalPerHari' => $jadwalPerHari,
            'totalMapel'    => count(array_unique(array_column($jadwal, 'subject'))),
            'hariIni'       => $this->hariIni(),
        ]);

        return view('jadwal/kelas', $data);
    }

    // ============================================================
    // FORM TAMBAH
    // ============================================================
    public function add()
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $data = array_merge($this->baseData(), [
            'title'      => 'Tambah Jadwal',
            'subtitle'   => 'Tambahkan jadwal pelajaran baru',
            'mode'       => 'add',
            'jadwal'     => null,
            'guruList'   => $this->getGuruList(),
            'kelasList'  => $this->getKelasList(),
            'hariList'   => ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
            'mapelList'  => $this->getMapelList(),
        ]);

        return view('jadwal/form', $data);
    }

    // ============================================================
    // SIMPAN JADWAL BARU
    // ============================================================
    public function store()
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $rules = [
            'subject'    => ['rules' => 'required|max_length[100]',
                             'errors' => ['required' => 'Mata pelajaran harus diisi']],
            'teacher_id' => ['rules' => 'required',
                             'errors' => ['required' => 'Guru harus dipilih']],
            'class_id'   => ['rules' => 'required',
                             'errors' => ['required' => 'Kelas harus dipilih']],
            'day'        => ['rules' => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu]'],
            'start_time' => ['rules' => 'required'],
            'end_time'   => ['rules' => 'required'],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $classId = (int)$this->request->getPost('class_id');
        $kelas   = $this->db->table('classes c')
            ->select('c.*, m.abbreviation AS major_abbr')
            ->join('majors m', 'm.id = c.major_id')
            ->where('c.id', $classId)
            ->get()->getRowArray();

        // Cek konflik jadwal: guru yang sama, hari+waktu yang sama
        $conflict = $this->checkKonflik(
            (int)$this->request->getPost('teacher_id'),
            $this->request->getPost('day'),
            $this->request->getPost('start_time'),
            $this->request->getPost('end_time')
        );

        if ($conflict) {
            return redirect()->back()->withInput()
                ->with('error', 'Konflik jadwal: guru sudah mengajar di waktu tersebut (' . $conflict . ')');
        }

        $insertData = [
            'teacher_id'  => (int)$this->request->getPost('teacher_id'),
            'subject'     => $this->request->getPost('subject'),
            'grade'       => $kelas['grade']       ?? '',
            'major'       => $kelas['major_abbr']  ?? '',
            'class_group' => $kelas['class_group'] ?? '',
            'class_id'    => $classId,
            'day'         => $this->request->getPost('day'),
            'start_time'  => $this->request->getPost('start_time'),
            'end_time'    => $this->request->getPost('end_time'),
            'room'        => $this->request->getPost('room') ?: null,
            'is_active'   => 1,
        ];

        if ($this->db->table('schedules')->insert($insertData)) {
            return redirect()->to('/jadwal')
                ->with('success', 'Jadwal berhasil ditambahkan');
        }

        return redirect()->back()->withInput()
            ->with('error', 'Gagal menambahkan jadwal');
    }

    // ============================================================
    // FORM EDIT
    // ============================================================
    public function edit($id)
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $jadwal = $this->db->table('schedules sc')
            ->select('sc.*, t.full_name AS nama_guru, c.nama_kelas')
            ->join('teachers t', 't.id = sc.teacher_id', 'left')
            ->join('classes c', 'c.id = sc.class_id', 'left')
            ->where('sc.id', $id)
            ->get()->getRowArray();

        if (!$jadwal) {
            return redirect()->to('/jadwal')->with('error', 'Jadwal tidak ditemukan');
        }

        $data = array_merge($this->baseData(), [
            'title'     => 'Edit Jadwal',
            'subtitle'  => $jadwal['subject'],
            'mode'      => 'edit',
            'jadwal'    => $jadwal,
            'guruList'  => $this->getGuruList(),
            'kelasList' => $this->getKelasList(),
            'hariList'  => ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
            'mapelList' => $this->getMapelList(),
        ]);

        return view('jadwal/form', $data);
    }

    // ============================================================
    // UPDATE JADWAL
    // ============================================================
    public function update($id)
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $jadwal = $this->db->table('schedules')->where('id', $id)->get()->getRowArray();
        if (!$jadwal) {
            return redirect()->to('/jadwal')->with('error', 'Jadwal tidak ditemukan');
        }

        $rules = [
            'subject'    => 'required|max_length[100]',
            'teacher_id' => 'required',
            'class_id'   => 'required',
            'day'        => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu]',
            'start_time' => 'required',
            'end_time'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $classId = (int)$this->request->getPost('class_id');
        $kelas   = $this->db->table('classes c')
            ->select('c.*, m.abbreviation AS major_abbr')
            ->join('majors m', 'm.id = c.major_id')
            ->where('c.id', $classId)
            ->get()->getRowArray();

        // Cek konflik (exclude jadwal yang sedang diedit)
        $conflict = $this->checkKonflik(
            (int)$this->request->getPost('teacher_id'),
            $this->request->getPost('day'),
            $this->request->getPost('start_time'),
            $this->request->getPost('end_time'),
            (int)$id
        );

        if ($conflict) {
            return redirect()->back()->withInput()
                ->with('error', 'Konflik jadwal: guru sudah mengajar di waktu tersebut (' . $conflict . ')');
        }

        $updateData = [
            'teacher_id'  => (int)$this->request->getPost('teacher_id'),
            'subject'     => $this->request->getPost('subject'),
            'grade'       => $kelas['grade']       ?? '',
            'major'       => $kelas['major_abbr']  ?? '',
            'class_group' => $kelas['class_group'] ?? '',
            'class_id'    => $classId,
            'day'         => $this->request->getPost('day'),
            'start_time'  => $this->request->getPost('start_time'),
            'end_time'    => $this->request->getPost('end_time'),
            'room'        => $this->request->getPost('room') ?: null,
            'is_active'   => (int)$this->request->getPost('is_active', 1),
        ];

        if ($this->db->table('schedules')->where('id', $id)->update($updateData)) {
            return redirect()->to('/jadwal')
                ->with('success', 'Jadwal berhasil diperbarui');
        }

        return redirect()->back()->withInput()
            ->with('error', 'Gagal memperbarui jadwal');
    }

    // ============================================================
    // HAPUS JADWAL
    // ============================================================
    public function delete($id)
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $jadwal = $this->db->table('schedules')->where('id', $id)->get()->getRowArray();
        if (!$jadwal) {
            return redirect()->to('/jadwal')->with('error', 'Jadwal tidak ditemukan');
        }

        if ($this->db->table('schedules')->where('id', $id)->delete()) {
            return redirect()->to('/jadwal')
                ->with('success', 'Jadwal berhasil dihapus');
        }

        return redirect()->to('/jadwal')
            ->with('error', 'Gagal menghapus jadwal');
    }

    // ============================================================
    // CETAK — render view khusus cetak (tanpa sidebar)
    // ============================================================
    public function cetak($classId = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if (!$classId) {
            return redirect()->to('/jadwal');
        }

        $kelas = $this->db->query("
            SELECT c.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   t.full_name AS nama_wakel
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN homeroom_assignments ha ON ha.class_id = c.id AND ha.is_active = 1
            LEFT JOIN teachers t ON ha.teacher_id = t.id
            WHERE c.id = ?
        ", [$classId])->getRowArray();

        $jadwal = $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru
            FROM schedules sc
            LEFT JOIN teachers t ON sc.teacher_id = t.id
            WHERE sc.class_id = ? AND sc.is_active = 1
            ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),
                     sc.start_time ASC
        ", [$classId])->getResultArray();

        $jadwalPerHari = [];
        foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h) {
            $jadwalPerHari[$h] = array_values(
                array_filter($jadwal, fn($j) => $j['day'] === $h)
            );
        }

        $sekolah = $this->db->table('school_info')->get()->getRowArray();

        return view('jadwal/cetak', [
            'kelas'         => $kelas,
            'jadwalPerHari' => $jadwalPerHari,
            'sekolah'       => $sekolah ?? [],
        ]);
    }

    // ============================================================
    // HELPERS
    // ============================================================
    private function getKelasList(): array
    {
        return $this->db->query("
            SELECT c.id AS class_id, c.nama_kelas, c.grade,
                   m.abbreviation AS major_abbr
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            WHERE c.is_active = 1 AND c.academic_year = '2025/2026'
            ORDER BY c.grade, m.abbreviation, c.class_group
        ")->getResultArray();
    }

    private function getGuruList(): array
    {
        return $this->db->table('teachers')
            ->select('id, full_name, nip')
            ->where('is_active', 1)
            ->orderBy('full_name')
            ->get()->getResultArray();
    }

    private function getMapelList(): array
    {
        $existing = $this->db->query("
            SELECT DISTINCT subject FROM schedules
            ORDER BY subject ASC
        ")->getResultArray();
        return array_column($existing, 'subject');
    }

    // Cek konflik jadwal guru: waktu bertabrakan di hari yang sama
    private function checkKonflik(
        int $teacherId,
        string $day,
        string $startTime,
        string $endTime,
        ?int $excludeId = null
    ): string {
        $builder = $this->db->table('schedules sc')
            ->select('sc.subject, sc.start_time, sc.end_time, c.nama_kelas')
            ->join('classes c', 'c.id = sc.class_id', 'left')
            ->where('sc.teacher_id', $teacherId)
            ->where('sc.day', $day)
            ->where('sc.is_active', 1)
            ->groupStart()
                // start_time baru ada di dalam range jadwal lama
                ->groupStart()
                    ->where('sc.start_time <=', $startTime)
                    ->where('sc.end_time >', $startTime)
                ->groupEnd()
                ->orGroupStart()
                    // end_time baru ada di dalam range jadwal lama
                    ->where('sc.start_time <', $endTime)
                    ->where('sc.end_time >=', $endTime)
                ->groupEnd()
                ->orGroupStart()
                    // jadwal baru mencakup seluruh jadwal lama
                    ->where('sc.start_time >=', $startTime)
                    ->where('sc.end_time <=', $endTime)
                ->groupEnd()
            ->groupEnd();

        if ($excludeId) {
            $builder->where('sc.id !=', $excludeId);
        }

        $conflict = $builder->get()->getRowArray();

        if ($conflict) {
            return sprintf(
                '%s (%s - %s) di %s',
                $conflict['subject'],
                substr($conflict['start_time'], 0, 5),
                substr($conflict['end_time'], 0, 5),
                $conflict['nama_kelas'] ?? '-'
            );
        }

        return '';
    }
}