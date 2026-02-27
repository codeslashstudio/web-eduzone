<?php
namespace App\Controllers;

class SiswaDashboard extends BaseController
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
        if (session()->get('role') !== 'siswa') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        $userId = session()->get('user_id');
        $bulan  = date('m');
        $tahun  = date('Y');

        // Data siswa dari user_id
        $siswa = $this->db->query("
            SELECT s.*, m.abbreviation AS major_name, m.name AS major_full
            FROM students s
            JOIN majors m ON s.major_id = m.id
            WHERE s.user_id = ?
        ", [$userId])->getRowArray();

        $siswaId     = $siswa['id']          ?? null;
        $grade       = $siswa['grade']       ?? null;
        $majorId     = $siswa['major_id']    ?? null;
        $classGroup  = $siswa['class_group'] ?? null;

        // Absensi bulan ini
        $absensiStat = $siswaId ? $this->db->query("
            SELECT
                SUM(status = 'Hadir') AS hadir,
                SUM(status = 'Sakit') AS sakit,
                SUM(status = 'Izin')  AS izin,
                SUM(status = 'Alpa')  AS alpa,
                COUNT(*) AS total
            FROM student_attendance
            WHERE student_id = ? AND MONTH(date) = ? AND YEAR(date) = ?
        ", [$siswaId, $bulan, $tahun])->getRowArray() : [];

        // Jadwal hari ini untuk kelas siswa
        $dayMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
                   'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $hariIni = $dayMap[date('l')] ?? 'Senin';
        $majorAbbr = $siswa['major_name'] ?? '';

        $jadwalHariIni = ($grade && $majorAbbr && $classGroup) ? $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru
            FROM schedules sc
            LEFT JOIN teachers t ON sc.teacher_id = t.id
            WHERE sc.grade = ? AND sc.major = ? AND sc.class_group = ?
              AND sc.day = ? AND sc.is_active = 1
            ORDER BY sc.start_time ASC
        ", [$grade, $majorAbbr, $classGroup, $hariIni])->getResultArray() : [];

        // Jadwal semua hari (untuk tampilan mingguan)
        $jadwalMinggu = ($grade && $majorAbbr && $classGroup) ? $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru
            FROM schedules sc
            LEFT JOIN teachers t ON sc.teacher_id = t.id
            WHERE sc.grade = ? AND sc.major = ? AND sc.class_group = ?
              AND sc.is_active = 1
            ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), sc.start_time
        ", [$grade, $majorAbbr, $classGroup])->getResultArray() : [];

        // Absensi terbaru (10 hari terakhir)
        $absensiTerbaru = $siswaId ? $this->db->query("
            SELECT * FROM student_attendance
            WHERE student_id = ?
            ORDER BY date DESC LIMIT 10
        ", [$siswaId])->getResultArray() : [];

        // Prestasi siswa ini
        $prestasi = $siswaId ? $this->db->query("
            SELECT * FROM student_achievements
            WHERE student_id = ?
            ORDER BY year DESC, created_at DESC
        ", [$siswaId])->getResultArray() : [];

        // Pengumuman untuk siswa
        $pengumuman = $this->db->query("
            SELECT * FROM announcements
            WHERE (visibility LIKE '%siswa%' OR visibility = 'semua')
              AND published_at <= CURDATE()
            ORDER BY is_important DESC, published_at DESC
            LIMIT 5
        ")->getResultArray();

        $data = [
            'title'          => 'Dashboard Siswa',
            'siswa'          => $siswa,
            'absensiStat'    => $absensiStat,
            'jadwalHariIni'  => $jadwalHariIni,
            'jadwalMinggu'   => $jadwalMinggu,
            'absensiTerbaru' => $absensiTerbaru,
            'prestasi'       => $prestasi,
            'pengumuman'     => $pengumuman,
            'hariIni'        => $hariIni,
            'bulan'          => $bulan,
            'tahun'          => $tahun,
        ];

        return view('dashboard/siswa/index', $data);
    }
}