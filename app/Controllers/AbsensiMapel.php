<?php

namespace App\Controllers;

class AbsensiMapel extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    private function authCheck(): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        if (!in_array($role, ['superadmin', 'guru_mapel', 'wali_kelas', 'kurikulum'])) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    private function getTeacherId(): ?int
    {
        $row = $this->db()->query(
            "SELECT id FROM teachers WHERE user_id = ? LIMIT 1",
            [session()->get('user_id')]
        )->getRowArray();
        return $row ? (int)$row['id'] : null;
    }

    // ============================================================
    // INDEX — Jadwal hari ini milik guru
    // ============================================================
    public function index()
    {
        $this->authCheck();
        $db        = $this->db();
        $teacherId = $this->getTeacherId();
        $role      = session()->get('role');

        // Hari ini dalam bahasa Indonesia
        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $hariIni  = $hariMap[date('l')] ?? date('l');
        $today    = date('Y-m-d');

        // Ambil jadwal hari ini milik guru
        $sql = "
            SELECT sc.id AS schedule_id, sc.subject, sc.day, sc.start_time, sc.end_time,
                   sc.room, sc.class_id, sc.grade, sc.major, sc.class_group,
                   c.nama_kelas,
                   t.full_name AS teacher_name,
                   ta.id AS sesi_id, ta.topic,
                   COUNT(DISTINCT s.id) AS jumlah_siswa,
                   SUM(CASE WHEN ssa.status = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
                   SUM(CASE WHEN ssa.status = 'Alpa'  THEN 1 ELSE 0 END) AS alpa
            FROM schedules sc
            LEFT JOIN classes c ON c.id = sc.class_id
            LEFT JOIN teachers t ON t.id = sc.teacher_id
            LEFT JOIN teaching_attendance ta ON ta.schedule_id = sc.id AND ta.date = ?
            LEFT JOIN students s ON s.class_id = sc.class_id AND s.status = 'aktif'
            LEFT JOIN student_subject_attendance ssa ON ssa.teaching_attendance_id = ta.id
            WHERE sc.day = ? AND sc.is_active = 1
        ";
        $params = [$today, $hariIni];

        if ($role === 'guru_mapel' && $teacherId) {
            $sql .= " AND sc.teacher_id = ?";
            $params[] = $teacherId;
        }

        $sql .= " GROUP BY sc.id, ta.id ORDER BY sc.start_time";
        $jadwalList = $db->query($sql, $params)->getResultArray();

        return view('absensi_mapel/index', [
            'title'      => 'Absensi Per Jam Pelajaran',
            'jadwalList' => $jadwalList,
            'hariIni'    => $hariIni,
            'today'      => $today,
        ]);
    }

    // ============================================================
    // FORM INPUT ABSENSI
    // ============================================================
    public function input($scheduleId)
    {
        $this->authCheck();
        $db        = $this->db();
        $teacherId = $this->getTeacherId();
        $today     = date('Y-m-d');

        // Ambil info jadwal
        $jadwal = $db->query("
            SELECT sc.*, c.nama_kelas, t.full_name AS teacher_name
            FROM schedules sc
            LEFT JOIN classes c ON c.id = sc.class_id
            LEFT JOIN teachers t ON t.id = sc.teacher_id
            WHERE sc.id = ? AND sc.is_active = 1
        ", [$scheduleId])->getRowArray();

        if (!$jadwal) {
            return redirect()->to(base_url('absensi-mapel'))
                ->with('error', 'Jadwal tidak ditemukan');
        }

        // Security Check: Pastikan guru hanya bisa input jadwal miliknya sendiri
        $role = session()->get('role');
        if ($role === 'guru_mapel' && $teacherId && $jadwal['teacher_id'] != $teacherId) {
            return redirect()->to(base_url('absensi-mapel'))->with('error', 'Akses ditolak: Jadwal ini bukan milik Anda');
        }

        // Cek apakah sudah ada sesi hari ini
        $sesi = $db->query("
            SELECT * FROM teaching_attendance
            WHERE schedule_id = ? AND date = ?
        ", [$scheduleId, $today])->getRowArray();

        // Ambil daftar siswa + status absensi jika sudah ada
        $siswaList = $db->query("
            SELECT s.id, s.nis, s.full_name, s.gender,
                   COALESCE(ssa.status, 'Hadir') AS status,
                   ssa.notes
            FROM students s
            LEFT JOIN student_subject_attendance ssa
                ON ssa.student_id = s.id
                AND ssa.schedule_id = ?
                AND ssa.date = ?
            WHERE s.class_id = ? AND s.status = 'aktif'
            ORDER BY s.full_name
        ", [$scheduleId, $today, $jadwal['class_id']])->getResultArray();

        return view('absensi_mapel/input', [
            'title'     => 'Input Absensi — ' . $jadwal['subject'],
            'jadwal'    => $jadwal,
            'siswaList' => $siswaList,
            'sesi'      => $sesi,
            'today'     => $today,
        ]);
    }

    // ============================================================
    // STORE ABSENSI
    // ============================================================
    public function store($scheduleId)
    {
        $this->authCheck();
        $db        = $this->db();
        $teacherId = $this->getTeacherId();
        $today     = date('Y-m-d');

        $jadwal = $db->query("SELECT * FROM schedules WHERE id = ?", [$scheduleId])->getRowArray();
        if (!$jadwal) return redirect()->to(base_url('absensi-mapel'))->with('error', 'Jadwal tidak ditemukan');

        // Security Check: Pastikan guru hanya bisa simpan jadwal miliknya sendiri
        $role = session()->get('role');
        if ($role === 'guru_mapel' && $teacherId && $jadwal['teacher_id'] != $teacherId) {
            return redirect()->to(base_url('absensi-mapel'))->with('error', 'Akses ditolak: Anda tidak berhak mengubah data ini');
        }

        $topic = $this->request->getPost('topic') ?? '';
        $notes = $this->request->getPost('notes') ?? '';

        // Upsert teaching_attendance (sesi mengajar)
        $sesi = $db->query("
            SELECT id FROM teaching_attendance
            WHERE schedule_id = ? AND date = ?
        ", [$scheduleId, $today])->getRowArray();

        if ($sesi) {
            $db->table('teaching_attendance')->update([
                'topic' => $topic,
                'notes' => $notes,
            ], ['id' => $sesi['id']]);
            $sesiId = $sesi['id'];
        } else {
            $db->table('teaching_attendance')->insert([
                'schedule_id' => $scheduleId,
                'teacher_id'  => $teacherId,
                'date'        => $today,
                'start_time'  => $jadwal['start_time'],
                'end_time'    => $jadwal['end_time'],
                'topic'       => $topic,
                'notes'       => $notes,
            ]);
            $sesiId = $db->insertID();
        }

        // Simpan absensi per siswa
        $siswaIds = $this->request->getPost('student_id') ?? [];
        $statuses = $this->request->getPost('status') ?? [];

        foreach ($siswaIds as $i => $sid) {
            $status = $statuses[$i] ?? 'Hadir';

            $exists = $db->query("
                SELECT id FROM student_subject_attendance
                WHERE student_id = ? AND schedule_id = ? AND date = ?
            ", [$sid, $scheduleId, $today])->getRowArray();

            $data = [
                'teaching_attendance_id' => $sesiId,
                'student_id'             => $sid,
                'schedule_id'            => $scheduleId,
                'date'                   => $today,
                'status'                 => $status,
            ];

            if ($exists) {
                $db->table('student_subject_attendance')->update($data, ['id' => $exists['id']]);
            } else {
                $db->table('student_subject_attendance')->insert($data);
            }
        }

        return redirect()->to(base_url('absensi-mapel'))
            ->with('success', 'Absensi ' . $jadwal['subject'] . ' berhasil disimpan');
    }

    // ============================================================
    // REKAP — per mapel/kelas
    // ============================================================
    public function rekap()
    {
        $this->authCheck();
        $db        = $this->db();
        $teacherId = $this->getTeacherId();
        $role      = session()->get('role');

        $bulan     = $this->request->getGet('bulan')      ?? date('m');
        $tahun     = $this->request->getGet('tahun')      ?? date('Y');
        $classId   = $this->request->getGet('class_id')   ?? '';
        $scheduleId= $this->request->getGet('schedule_id')?? '';

        // Daftar kelas untuk filter
        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY grade, nama_kelas")->getResultArray();

        // Daftar jadwal untuk filter
        $jadwalSql = "SELECT sc.id, sc.subject, c.nama_kelas FROM schedules sc
            LEFT JOIN classes c ON c.id = sc.class_id WHERE sc.is_active = 1";
        $jadwalParams = [];
        if ($role === 'guru_mapel' && $teacherId) {
            $jadwalSql .= " AND sc.teacher_id = ?";
            $jadwalParams[] = $teacherId;
        }
        $jadwalSql .= " ORDER BY sc.subject";
        $jadwalList = $db->query($jadwalSql, $jadwalParams)->getResultArray();

        // Data rekap
        $rekapList = [];
        if ($scheduleId || $classId) {
            $sql = "
                SELECT s.id, s.nis, s.full_name,
                       sc.subject, c.nama_kelas,
                       COUNT(DISTINCT ssa.date) AS total_pertemuan,
                       SUM(CASE WHEN ssa.status='Hadir' THEN 1 ELSE 0 END) AS hadir,
                       SUM(CASE WHEN ssa.status='Alpa'  THEN 1 ELSE 0 END) AS alpa,
                       SUM(CASE WHEN ssa.status='Sakit' THEN 1 ELSE 0 END) AS sakit,
                       SUM(CASE WHEN ssa.status='Izin'  THEN 1 ELSE 0 END) AS izin
                FROM students s
                JOIN classes c ON c.id = s.class_id
                JOIN schedules sc ON sc.class_id = s.class_id
                LEFT JOIN student_subject_attendance ssa
                    ON ssa.student_id = s.id
                    AND ssa.schedule_id = sc.id
                    AND MONTH(ssa.date) = ?
                    AND YEAR(ssa.date)  = ?
                WHERE s.status = 'aktif'
            ";
            $params = [$bulan, $tahun];

            if ($scheduleId) { $sql .= " AND sc.id = ?"; $params[] = $scheduleId; }
            if ($classId)    { $sql .= " AND s.class_id = ?"; $params[] = $classId; }
            if ($role === 'guru_mapel' && $teacherId) { $sql .= " AND sc.teacher_id = ?"; $params[] = $teacherId; }

            $sql .= " GROUP BY s.id, sc.id ORDER BY s.full_name";
            $rekapList = $db->query($sql, $params)->getResultArray();
        }

        return view('absensi_mapel/rekap', [
            'title'      => 'Rekap Absensi Per Mapel',
            'rekapList'  => $rekapList,
            'kelasList'  => $kelasList,
            'jadwalList' => $jadwalList,
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'classId'    => $classId,
            'scheduleId' => $scheduleId,
        ]);
    }
}