<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\ClassModel;

class Absensi extends BaseController
{
    protected $absensiModel;
    protected $classModel;
    protected $db;

    protected $viewRoles  = ['kepsek', 'tu', 'wali_kelas', 'guru_mapel', 'superadmin', 'kesiswaan', 'kurikulum'];
    protected $harianRoles = ['wali_kelas', 'tu', 'superadmin'];
    protected $mapelRoles  = ['guru_mapel', 'tu', 'superadmin'];

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->classModel   = new ClassModel();
        $this->db           = \Config\Database::connect();
        helper(['form', 'url']);
    }

    private function authCheck(array $roles = []): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $allowed = empty($roles) ? $this->viewRoles : $roles;
        if (!in_array(session()->get('role'), $allowed)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    private function userId(): int { return session()->get('user_id') ?? 1; }

    // ============================================================
    // DASHBOARD ABSENSI
    // ============================================================
    public function index()
    {
        $this->authCheck();

        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $statistik     = $this->absensiModel->getStatistikKehadiran(null, null, null, $bulan, $tahun);
        $alpaTerbanyak = $this->absensiModel->getSiswaAlpaTerbanyak(null, null, $bulan, $tahun, 5);
        $trend         = $this->absensiModel->getAbsensiTrend(null, null, null, $bulan, $tahun);
        $kelasList     = $this->absensiModel->getKelasList();

        return view('absensi/index', [
            'title'          => 'Dashboard Absensi Siswa',
            'canInputHarian' => in_array(session()->get('role'), $this->harianRoles),
            'canInputMapel'  => in_array(session()->get('role'), $this->mapelRoles),
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'statistik'      => $statistik,
            'alpaTerbanyak'  => $alpaTerbanyak,
            'trend'          => $trend,
            'kelasList'      => $kelasList,
        ]);
    }

    // ============================================================
    // ABSENSI HARIAN — form input
    // ============================================================
    public function harian()
    {
        $this->authCheck($this->harianRoles);

        $date     = $this->request->getGet('date')     ?? date('Y-m-d');
        $class_id = $this->request->getGet('class_id') ?? '';

        // Jika wali kelas, auto-set class_id ke kelas yang diampu
        if (empty($class_id) && session()->get('role') === 'wali_kelas') {
            $teacher   = $this->db->table('teachers')->where('user_id', $this->userId())->get()->getRowArray();
            $teacherId = $teacher['id'] ?? null;
            if ($teacherId) {
                $kelas    = $this->classModel->getByTeacher($teacherId);
                $class_id = $kelas['id'] ?? '';
            }
        }

        $siswa     = $class_id ? $this->absensiModel->getAbsensiHarianByClassId($date, (int)$class_id) : [];
        $kelasList = $this->absensiModel->getKelasList();
        $kelasInfo = $class_id ? $this->classModel->getById((int)$class_id) : [];

        return view('absensi/harian', [
            'title'     => 'Input Absensi Harian',
            'date'      => $date,
            'class_id'  => $class_id,
            'siswa'     => $siswa,
            'kelasList' => $kelasList,
            'kelasInfo' => $kelasInfo,
        ]);
    }

    public function harianStore()
    {
        $this->authCheck($this->harianRoles);

        $date     = $this->request->getPost('date');
        $class_id = $this->request->getPost('class_id');
        $students = $this->request->getPost('students');

        if (empty($students)) {
            return redirect()->back()->with('error', 'Tidak ada data siswa');
        }

        $rows = [];
        foreach ($students as $student_id => $val) {
            $rows[] = [
                'student_id' => $student_id,
                'status'     => $val['status'],
                'check_in'   => !empty($val['check_in'])  ? $val['check_in']  : null,
                'check_out'  => !empty($val['check_out']) ? $val['check_out'] : null,
                'notes'      => $val['notes'] ?? null,
            ];
        }

        if ($this->absensiModel->saveAbsensiHarian($rows, $date, $this->userId())) {
            return redirect()->to(base_url("absensi/harian?date=$date&class_id=$class_id"))
                ->with('success', 'Absensi harian berhasil disimpan');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan absensi');
    }

    // ============================================================
    // ABSENSI MAPEL — pilih jadwal
    // ============================================================
    public function mapel()
    {
        $this->authCheck($this->mapelRoles);

        $role      = session()->get('role');
        $teacherId = null;

        if ($role === 'guru_mapel') {
            $teacher   = $this->db->table('teachers')->where('user_id', $this->userId())->get()->getRowArray();
            $teacherId = $teacher['id'] ?? null;
        }

        $date    = $this->request->getGet('date') ?? date('Y-m-d');
        $dayMap  = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
                    'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $day     = $dayMap[date('l', strtotime($date))] ?? 'Senin';

        $jadwal = $this->absensiModel->getJadwalGuru($teacherId, $day);

        return view('absensi/mapel', [
            'title'      => 'Absensi Per Mapel',
            'date'       => $date,
            'day'        => $day,
            'jadwal'     => $jadwal,
            'teacher_id' => $teacherId,
        ]);
    }

    // ABSENSI MAPEL — form input per sesi
    public function mapelInput($schedule_id)
    {
        $this->authCheck($this->mapelRoles);

        $date       = $this->request->getGet('date')       ?? date('Y-m-d');
        $teacher_id = $this->request->getGet('teacher_id');
        $topic      = $this->request->getGet('topic')      ?? '';

        $teaching_id = $this->absensiModel->getOrCreateTeachingAttendance(
            $schedule_id, $teacher_id, $date, $topic ?: null
        );

        $siswa = $this->absensiModel->getAbsensiMapel($teaching_id, $schedule_id, $date);

        $schedule = $this->db->table('schedules sc')
            ->select('sc.*, t.full_name AS nama_guru, c.nama_kelas, m.abbreviation AS major_name')
            ->join('teachers t', 't.id = sc.teacher_id', 'left')
            ->join('classes c', 'c.id = sc.class_id', 'left')
            ->join('majors m', 'm.abbreviation = sc.major', 'left')
            ->where('sc.id', $schedule_id)
            ->get()->getRowArray();

        return view('absensi/mapel_input', [
            'title'       => 'Input Absensi ' . ($schedule['subject'] ?? ''),
            'date'        => $date,
            'schedule_id' => $schedule_id,
            'teaching_id' => $teaching_id,
            'teacher_id'  => $teacher_id,
            'schedule'    => $schedule,
            'siswa'       => $siswa,
        ]);
    }

    public function mapelStore()
    {
        $this->authCheck($this->mapelRoles);

        $date        = $this->request->getPost('date');
        $schedule_id = $this->request->getPost('schedule_id');
        $teaching_id = $this->request->getPost('teaching_id');
        $teacher_id  = $this->request->getPost('teacher_id');
        $topic       = $this->request->getPost('topic');
        $students    = $this->request->getPost('students');

        if ($topic) {
            $this->db->table('teaching_attendance')->where('id', $teaching_id)->update(['topic' => $topic]);
        }

        if (empty($students)) {
            return redirect()->back()->with('error', 'Tidak ada data siswa');
        }

        $rows = [];
        foreach ($students as $student_id => $val) {
            $rows[] = [
                'student_id' => $student_id,
                'status'     => $val['status'],
                'notes'      => $val['notes'] ?? null,
            ];
        }

        if ($this->absensiModel->saveAbsensiMapel($rows, $teaching_id, $schedule_id, $date)) {
            return redirect()->to(base_url("absensi/mapel?date=$date&teacher_id=$teacher_id"))
                ->with('success', 'Absensi mapel berhasil disimpan');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan absensi');
    }

    // ============================================================
    // REKAP ABSENSI
    // ============================================================
    public function rekap()
    {
        $this->authCheck();

        $bulan    = $this->request->getGet('bulan')    ?? date('m');
        $tahun    = $this->request->getGet('tahun')    ?? date('Y');
        $class_id = $this->request->getGet('class_id') ?? '';

        // Wali kelas: auto-set ke kelas sendiri
        if (empty($class_id) && session()->get('role') === 'wali_kelas') {
            $teacher   = $this->db->table('teachers')->where('user_id', $this->userId())->get()->getRowArray();
            $teacherId = $teacher['id'] ?? null;
            if ($teacherId) {
                $kelas    = $this->classModel->getByTeacher($teacherId);
                $class_id = $kelas['id'] ?? '';
            }
        }

        $rekap     = $class_id ? $this->absensiModel->getRekapBulananByClassId((int)$class_id, $bulan, $tahun) : [];
        $kelasList = $this->absensiModel->getKelasList();
        $kelasInfo = $class_id ? $this->classModel->getById((int)$class_id) : [];

        return view('absensi/rekap', [
            'title'     => 'Rekap Absensi Bulanan',
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'class_id'  => $class_id,
            'rekap'     => $rekap,
            'kelasList' => $kelasList,
            'kelasInfo' => $kelasInfo,
        ]);
    }

    // ============================================================
    // EXPORT EXCEL
    // ============================================================
    public function exportExcel()
    {
        $this->authCheck();

        $bulan    = $this->request->getGet('bulan')    ?? date('m');
        $tahun    = $this->request->getGet('tahun')    ?? date('Y');
        $class_id = $this->request->getGet('class_id') ?? '';
        $kelasInfo = $class_id ? $this->classModel->getById((int)$class_id) : [];

        $rekap = $class_id
            ? $this->absensiModel->getRekapBulananByClassId((int)$class_id, $bulan, $tahun)
            : [];

        return $this->response->setJSON([
            'success'   => true,
            'data'      => $rekap,
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'kelasInfo' => $kelasInfo,
        ]);
    }
}