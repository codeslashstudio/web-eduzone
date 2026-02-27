<?php
namespace App\Controllers;

class GuruMapel extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'));
        }
        if (session()->get('role') !== 'guru_mapel') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        $userId = session()->get('user_id');
        $bulan  = date('m');
        $tahun  = date('Y');

        // Ambil data teacher dari user_id
        $teacher = $this->db->table('teachers')->where('user_id', $userId)->get()->getRowArray();
        $teacherId = $teacher['id'] ?? null;

        // Jadwal mengajar hari ini
        $dayMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
                   'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $hariIni = $dayMap[date('l')] ?? 'Senin';

        $jadwalHariIni = $teacherId ? $this->db->query("
            SELECT sc.*, m.abbreviation AS major_name
            FROM schedules sc
            LEFT JOIN majors m ON m.abbreviation = sc.major
            WHERE sc.teacher_id = ? AND sc.day = ? AND sc.is_active = 1
            ORDER BY sc.start_time ASC
        ", [$teacherId, $hariIni])->getResultArray() : [];

        // Semua jadwal minggu ini
        $semuaJadwal = $teacherId ? $this->db->query("
            SELECT sc.*, m.abbreviation AS major_name
            FROM schedules sc
            LEFT JOIN majors m ON m.abbreviation = sc.major
            WHERE sc.teacher_id = ? AND sc.is_active = 1
            ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), sc.start_time
        ", [$teacherId])->getResultArray() : [];

        // Statistik kehadiran guru bulan ini
        $absensiStat = $teacherId ? $this->db->query("
            SELECT
                SUM(status = 'Hadir') AS hadir,
                SUM(status = 'Sakit') AS sakit,
                SUM(status = 'Izin')  AS izin,
                SUM(status = 'Alpa')  AS alpa,
                COUNT(*) AS total
            FROM teacher_attendance
            WHERE teacher_id = ? AND MONTH(date) = ? AND YEAR(date) = ?
        ", [$teacherId, $bulan, $tahun])->getRowArray() : [];

        // Jurnal terbaru saya
        $jurnalSaya = $teacherId ? $this->db->query("
            SELECT * FROM lesson_journals
            WHERE teacher_id = ?
            ORDER BY date DESC LIMIT 5
        ", [$teacherId])->getResultArray() : [];

        // Ujian yang saya awasi
        $ujianSaya = $teacherId ? $this->db->query("
            SELECT * FROM exams
            WHERE supervisor_id = ? AND date >= CURDATE()
            ORDER BY date ASC LIMIT 5
        ", [$teacherId])->getResultArray() : [];

        // Total siswa yang saya ajar (semua kelas unik)
        $totalSiswaAjar = 0;
        if ($teacherId && !empty($semuaJadwal)) {
            $totalSiswaAjar = $this->db->query("
                SELECT COUNT(DISTINCT s.id) AS total
                FROM students s
                JOIN majors m ON s.major_id = m.id
                WHERE s.status = 'aktif'
                  AND CONCAT(s.grade, '-', m.abbreviation, '-', s.class_group) IN (
                      SELECT CONCAT(grade, '-', major, '-', class_group)
                      FROM schedules WHERE teacher_id = ? AND is_active = 1
                  )
            ", [$teacherId])->getRowArray()['total'] ?? 0;
        }

        $data = [
            'title'          => 'Dashboard Guru',
            'teacher'        => $teacher,
            'jadwalHariIni'  => $jadwalHariIni,
            'semuaJadwal'    => $semuaJadwal,
            'absensiStat'    => $absensiStat,
            'jurnalSaya'     => $jurnalSaya,
            'ujianSaya'      => $ujianSaya,
            'totalSiswaAjar' => $totalSiswaAjar,
            'hariIni'        => $hariIni,
            'bulan'          => $bulan,
            'tahun'          => $tahun,
        ];

        return view('dashboard/guru/index', $data);
    }
}