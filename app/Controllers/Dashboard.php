<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\ClassModel;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    // ============================================================
    // INDEX — redirect ke dashboard sesuai role
    // ============================================================
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role = session()->get('role');

        switch ($role) {
            case 'superadmin': return view('dashboard/index', $this->baseData('Dashboard Utama', 'Selamat datang, Super Admin'));
            case 'kepsek':     return redirect()->to('/dashboard/kepsek');
            case 'tu':         return redirect()->to('/dashboard/tu');
            case 'kurikulum':  return redirect()->to('/dashboard/kurikulum');
            case 'guru_mapel': return redirect()->to('/dashboard/guru');
            case 'wali_kelas': return redirect()->to('/dashboard/wakel');
            case 'kesiswaan':  return redirect()->to('/dashboard/kesiswaan');
            case 'bk':         return redirect()->to('/dashboard/bk');
            case 'toolman':    return redirect()->to('/dashboard/toolman');
            case 'siswa':      return redirect()->to('/dashboard/siswa');
            default:
                session()->destroy();
                return redirect()->to('/auth/login')->with('error', 'Role tidak dikenali');
        }
    }

    // ============================================================
    // KEPSEK
    // ============================================================
    public function kepsek()
    {
        if ($this->denyAccess(['kepsek', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $bulan = date('m');
        $tahun = date('Y');

        // Statistik utama
        $totalSiswa   = $this->db->table('students')->where('status', 'aktif')->countAllResults();
        $totalGuru    = $this->db->table('teachers')->where('is_active', 1)->countAllResults();
        $totalKelas   = $this->db->table('classes')->where('is_active', 1)->where('academic_year', '2025/2026')->countAllResults();
        $totalJurusan = $this->db->table('majors')->where('is_active', 1)->countAllResults();

        // Absensi siswa bulan ini
        $absensiStats = $this->db->query("
            SELECT
                SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN status='Sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN status='Izin'  THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN status='Alpa'  THEN 1 ELSE 0 END) AS alpa,
                COUNT(*) AS total
            FROM student_attendance
            WHERE MONTH(date)=? AND YEAR(date)=?
        ", [$bulan, $tahun])->getRowArray();

        // Absensi guru bulan ini
        $absensiGuru = $this->db->query("
            SELECT
                SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN status='Alpa'  THEN 1 ELSE 0 END) AS alpa,
                COUNT(*) AS total
            FROM teacher_attendance
            WHERE MONTH(date)=? AND YEAR(date)=?
        ", [$bulan, $tahun])->getRowArray();

        // Distribusi siswa per kelas
        $siswaPerKelas = $this->db->query("
            SELECT c.nama_kelas, c.grade, m.abbreviation AS major_abbr,
                   COUNT(s.id) AS jumlah
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            WHERE c.is_active = 1 AND c.academic_year = '2025/2026'
            GROUP BY c.id
            ORDER BY c.grade, m.abbreviation, c.class_group
        ")->getResultArray();

        // Prestasi terbaru
        $prestasi = $this->db->query("
            SELECT sa.*, s.full_name AS nama_siswa, s.grade, m.abbreviation AS major_name
            FROM student_achievements sa
            JOIN students s ON sa.student_id = s.id
            JOIN majors m ON s.major_id = m.id
            ORDER BY sa.created_at DESC LIMIT 5
        ")->getResultArray();

        // Pengumuman terbaru
        $pengumuman = $this->db->query("
            SELECT * FROM announcements
            WHERE published_at <= CURDATE()
            ORDER BY is_important DESC, published_at DESC LIMIT 5
        ")->getResultArray();

        // Info sekolah
        $sekolah = $this->db->table('school_info')->get()->getRowArray();

        $data = array_merge($this->baseData('Dashboard Kepala Sekolah', date('l, d F Y')), [
            'totalSiswa'    => $totalSiswa,
            'totalGuru'     => $totalGuru,
            'totalKelas'    => $totalKelas,
            'totalJurusan'  => $totalJurusan,
            'absensiStats'  => $absensiStats  ?? [],
            'absensiGuru'   => $absensiGuru   ?? [],
            'siswaPerKelas' => $siswaPerKelas,
            'prestasi'      => $prestasi,
            'pengumuman'    => $pengumuman,
            'sekolah'       => $sekolah       ?? [],
        ]);

        return view('dashboard/kepsek/index', $data);
    }

    // ============================================================
    // TU
    // ============================================================
    public function tu()
    {
        if ($this->denyAccess(['tu', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $bulan = date('m');
        $tahun = date('Y');

        $totalSiswa      = $this->db->table('students')->where('status', 'aktif')->countAllResults();
        $totalGuru       = $this->db->table('teachers')->where('is_active', 1)->countAllResults();
        $totalStaff      = $this->db->table('staff')->where('is_active', 1)->countAllResults();
        $siswaBaruBulanIni = $this->db->query("
            SELECT COUNT(*) AS total FROM students
            WHERE MONTH(joined_date)=? AND YEAR(joined_date)=?
        ", [$bulan, $tahun])->getRowArray()['total'] ?? 0;

        // Data siswa terbaru
        $siswaTerbaru = $this->db->query("
            SELECT s.*, m.abbreviation AS major_name
            FROM students s JOIN majors m ON s.major_id = m.id
            ORDER BY s.created_at DESC LIMIT 8
        ")->getResultArray();

        // Distribusi gender
        $genderStats = $this->db->query("
            SELECT gender, COUNT(*) AS total FROM students
            WHERE status='aktif' GROUP BY gender
        ")->getResultArray();

        // Siswa per grade
        $siswaPerGrade = $this->db->query("
            SELECT grade, COUNT(*) AS total FROM students
            WHERE status='aktif' GROUP BY grade ORDER BY grade
        ")->getResultArray();

        // Keuangan bulan ini (ringkasan)
        $keuangan = $this->db->query("
            SELECT
                (SELECT COALESCE(SUM(jumlah),0) FROM transaksi_pemasukan WHERE MONTH(tanggal_transaksi)=? AND YEAR(tanggal_transaksi)=?) AS total_masuk,
                (SELECT COALESCE(SUM(jumlah),0) FROM transaksi_pengeluaran WHERE MONTH(tanggal_transaksi)=? AND YEAR(tanggal_transaksi)=?) AS total_keluar
        ", [$bulan, $tahun, $bulan, $tahun])->getRowArray();

        $data = array_merge($this->baseData('Dashboard Tata Usaha', date('l, d F Y')), [
            'totalSiswa'         => $totalSiswa,
            'totalGuru'          => $totalGuru,
            'totalStaff'         => $totalStaff,
            'siswaBaruBulanIni'  => $siswaBaruBulanIni,
            'siswaTerbaru'       => $siswaTerbaru,
            'genderStats'        => $genderStats,
            'siswaPerGrade'      => $siswaPerGrade,
            'keuangan'           => $keuangan ?? [],
        ]);

        return view('dashboard/tu/index', $data);
    }

    // ============================================================
    // KURIKULUM
    // ============================================================
    public function kurikulum()
    {
        if ($this->denyAccess(['kurikulum', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $bulan   = date('m');
        $tahun   = date('Y');
        $hariIni = $this->hariIni();

        $totalGuru   = $this->db->table('teachers')->where('is_active', 1)->countAllResults();
        $totalMapel  = $this->db->query("SELECT COUNT(DISTINCT subject) AS total FROM schedules WHERE is_active=1")->getRowArray()['total'] ?? 0;
        $totalJadwal = $this->db->table('schedules')->where('is_active', 1)->countAllResults();
        $totalUjian  = $this->db->table('exams')->countAllResults();

        $jadwalHariIni = $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru,
                   CONCAT(sc.grade,' ',sc.major,' ',sc.class_group) AS nama_kelas
            FROM schedules sc LEFT JOIN teachers t ON sc.teacher_id = t.id
            WHERE sc.day=? AND sc.is_active=1 ORDER BY sc.start_time ASC
        ", [$hariIni])->getResultArray();

        $ujianMendatang = $this->db->query("
            SELECT * FROM exams
            WHERE date >= CURDATE() AND date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY date ASC LIMIT 10
        ")->getResultArray();

        $jurnalTerbaru = $this->db->query("
            SELECT lj.*, t.full_name AS nama_guru FROM lesson_journals lj
            LEFT JOIN teachers t ON lj.teacher_id = t.id
            ORDER BY lj.date DESC LIMIT 6
        ")->getResultArray();

        $jadwalPerHari = $this->db->query("
            SELECT day, COUNT(*) AS total FROM schedules WHERE is_active=1 GROUP BY day
            ORDER BY FIELD(day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')
        ")->getResultArray();

        $kehadiranGuru = $this->db->query("
            SELECT t.full_name,
                (SELECT subject FROM schedules WHERE teacher_id=t.id AND is_active=1 LIMIT 1) AS subject,
                SUM(CASE WHEN ta.status='Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN ta.status!='Hadir' THEN 1 ELSE 0 END) AS tidak_hadir,
                COUNT(ta.id) AS total_absen
            FROM teachers t
            LEFT JOIN teacher_attendance ta ON t.id=ta.teacher_id
                AND MONTH(ta.date)=? AND YEAR(ta.date)=?
            WHERE t.is_active=1
            GROUP BY t.id ORDER BY t.full_name ASC
        ", [$bulan, $tahun])->getResultArray();

        $data = array_merge($this->baseData('Dashboard Kurikulum', date('l, d F Y')), [
            'totalGuru'      => $totalGuru,
            'totalMapel'     => $totalMapel,
            'totalJadwal'    => $totalJadwal,
            'totalUjian'     => $totalUjian,
            'jadwalHariIni'  => $jadwalHariIni,
            'ujianMendatang' => $ujianMendatang,
            'jurnalTerbaru'  => $jurnalTerbaru,
            'jadwalPerHari'  => $jadwalPerHari,
            'kehadiranGuru'  => $kehadiranGuru,
            'hariIni'        => $hariIni,
        ]);

        return view('dashboard/kurikulum/index', $data);
    }

    // ============================================================
    // GURU MAPEL
    // ============================================================
    public function guru()
    {
        if ($this->denyAccess(['guru_mapel', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $userId  = session()->get('user_id');
        $bulan   = date('m');
        $tahun   = date('Y');
        $hariIni = $this->hariIni();

        $teacher   = $this->db->table('teachers')->where('user_id', $userId)->get()->getRowArray();
        $teacherId = $teacher['id'] ?? null;

        $jadwalHariIni = $teacherId ? $this->db->query("
            SELECT sc.*, m.abbreviation AS major_name,
                   CONCAT(sc.grade,' ',sc.major,' ',sc.class_group) AS nama_kelas
            FROM schedules sc LEFT JOIN majors m ON m.abbreviation = sc.major
            WHERE sc.teacher_id=? AND sc.day=? AND sc.is_active=1
            ORDER BY sc.start_time ASC
        ", [$teacherId, $hariIni])->getResultArray() : [];

        $semuaJadwal = $teacherId ? $this->db->query("
            SELECT sc.*, m.abbreviation AS major_name,
                   CONCAT(sc.grade,' ',sc.major,' ',sc.class_group) AS nama_kelas
            FROM schedules sc LEFT JOIN majors m ON m.abbreviation = sc.major
            WHERE sc.teacher_id=? AND sc.is_active=1
            ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), sc.start_time
        ", [$teacherId])->getResultArray() : [];

        $absensiStat = $teacherId ? $this->db->query("
            SELECT
                SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN status='Sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN status='Izin'  THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN status='Alpa'  THEN 1 ELSE 0 END) AS alpa,
                COUNT(*) AS total
            FROM teacher_attendance
            WHERE teacher_id=? AND MONTH(date)=? AND YEAR(date)=?
        ", [$teacherId, $bulan, $tahun])->getRowArray() : [];

        $jurnalSaya = $teacherId ? $this->db->query("
            SELECT * FROM lesson_journals WHERE teacher_id=?
            ORDER BY date DESC LIMIT 5
        ", [$teacherId])->getResultArray() : [];

        $ujianSaya = $teacherId ? $this->db->query("
            SELECT * FROM exams WHERE supervisor_id=? AND date>=CURDATE()
            ORDER BY date ASC LIMIT 5
        ", [$teacherId])->getResultArray() : [];

        // Hitung total siswa yang diajar
        $totalSiswaAjar = 0;
        if ($teacherId && !empty($semuaJadwal)) {
            $totalSiswaAjar = $this->db->query("
                SELECT COUNT(DISTINCT s.id) AS total FROM students s
                JOIN schedules sc ON sc.class_id = s.class_id
                WHERE sc.teacher_id=? AND sc.is_active=1 AND s.status='aktif'
            ", [$teacherId])->getRowArray()['total'] ?? 0;
        }

        $data = array_merge($this->baseData('Dashboard Guru', $teacher['full_name'] ?? ''), [
            'teacher'        => $teacher        ?? [],
            'jadwalHariIni'  => $jadwalHariIni,
            'semuaJadwal'    => $semuaJadwal,
            'absensiStat'    => $absensiStat    ?? [],
            'jurnalSaya'     => $jurnalSaya,
            'ujianSaya'      => $ujianSaya,
            'totalSiswaAjar' => $totalSiswaAjar,
            'hariIni'        => $hariIni,
        ]);

        return view('dashboard/guru/index', $data);
    }

    // ============================================================
    // WALI KELAS
    // ============================================================
    public function wakel()
    {
        if ($this->denyAccess(['wali_kelas', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $absensiModel = new AbsensiModel();
        $classModel   = new ClassModel();
        $userId       = session()->get('user_id');
        $bulan        = date('m');
        $tahun        = date('Y');
        $hariIni      = $this->hariIni();

        $teacher   = $this->db->table('teachers')->where('user_id', $userId)->get()->getRowArray();
        $teacherId = $teacher['id'] ?? null;

        // Kelas yang dipegang via homeroom_assignments
        $kelasSaya = $teacherId ? $classModel->getByTeacher($teacherId) : [];

        // Fallback jika belum di-assign
        if (empty($kelasSaya)) {
            $allKelas  = $classModel->getAll();
            $kelasSaya = !empty($allKelas) ? $allKelas[0] : [];
        }

        $classId = $kelasSaya['id'] ?? null;

        $statistikAbsensi = $classId ? $absensiModel->getStatistikKehadiranByClassId($classId, $bulan, $tahun) : [];
        $absensiHariIni   = $classId ? $absensiModel->getAbsensiHarianByClassId(date('Y-m-d'), $classId) : [];
        $daftarSiswa      = $classId ? $absensiModel->getRekapBulananByClassId($classId, $bulan, $tahun) : [];
        $siswaBermasalah  = $classId ? $absensiModel->getSiswaAlpaTerbanyakByClassId($classId, $bulan, $tahun, 5) : [];
        $jadwalHariIni    = $classId ? $classModel->getJadwal($classId, $hariIni) : [];

        // Total siswa di kelas
        $totalSiswaKelas = $classId
            ? $this->db->table('students')->where('class_id', $classId)->where('status', 'aktif')->countAllResults()
            : 0;

        $data = array_merge($this->baseData('Dashboard Wali Kelas', $kelasSaya['nama_kelas'] ?? ''), [
            'kelasSaya'        => $kelasSaya        ?? [],
            'statistikAbsensi' => $statistikAbsensi ?? [],
            'absensiHariIni'   => $absensiHariIni   ?? [],
            'daftarSiswa'      => $daftarSiswa      ?? [],
            'siswaBermasalah'  => $siswaBermasalah  ?? [],
            'jadwalHariIni'    => $jadwalHariIni     ?? [],
            'totalSiswaKelas'  => $totalSiswaKelas,
            'hariIni'          => $hariIni,
        ]);

        return view('dashboard/wakel/index', $data);
    }

    // ============================================================
    // KESISWAAN
    // ============================================================
    public function kesiswaan()
    {
        if ($this->denyAccess(['kesiswaan', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $bulan = date('m');
        $tahun = date('Y');

        $siswaStats = $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status='aktif'  THEN 1 ELSE 0 END) AS aktif,
                SUM(CASE WHEN status='lulus'  THEN 1 ELSE 0 END) AS lulus,
                SUM(CASE WHEN gender='L'      THEN 1 ELSE 0 END) AS laki,
                SUM(CASE WHEN gender='P'      THEN 1 ELSE 0 END) AS perempuan
            FROM students
        ")->getRowArray();

        $siswaPerKelas = $this->db->query("
            SELECT c.id AS class_id, c.nama_kelas, c.grade, c.class_group,
                   m.abbreviation AS major_abbr, COUNT(s.id) AS jumlah_siswa
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            WHERE c.is_active = 1 AND c.academic_year = '2025/2026'
            GROUP BY c.id ORDER BY c.grade, m.abbreviation, c.class_group
        ")->getResultArray();

        $prestasi = $this->db->query("
            SELECT sa.*, s.full_name AS nama_siswa, s.grade, m.abbreviation AS major_name
            FROM student_achievements sa
            JOIN students s ON sa.student_id = s.id
            JOIN majors m ON s.major_id = m.id
            ORDER BY sa.created_at DESC LIMIT 8
        ")->getResultArray();

        $pengumuman = $this->db->query("
            SELECT * FROM announcements
            WHERE published_at <= CURDATE()
            ORDER BY is_important DESC, published_at DESC LIMIT 5
        ")->getResultArray();

        $totalAlpa = $this->db->query("
            SELECT COUNT(*) AS total FROM student_attendance
            WHERE status='Alpa' AND MONTH(date)=? AND YEAR(date)=?
        ", [$bulan, $tahun])->getRowArray()['total'] ?? 0;

        $rekapAbsensi = $this->db->query("
            SELECT c.id AS class_id, c.nama_kelas, c.grade, m.abbreviation AS major_abbr,
                COUNT(DISTINCT s.id) AS jumlah_siswa,
                SUM(CASE WHEN sa.status='Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN sa.status='Sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN sa.status='Izin'  THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN sa.status='Alpa'  THEN 1 ELSE 0 END) AS alpa
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            LEFT JOIN student_attendance sa ON s.id = sa.student_id
                AND MONTH(sa.date)=? AND YEAR(sa.date)=?
            WHERE c.is_active = 1 AND c.academic_year = '2025/2026'
            GROUP BY c.id ORDER BY c.grade, m.abbreviation, c.class_group
        ", [$bulan, $tahun])->getResultArray();

        $data = array_merge($this->baseData('Dashboard Kesiswaan', date('l, d F Y')), [
            'totalSiswa'       => $siswaStats['total']     ?? 0,
            'totalAktif'       => $siswaStats['aktif']     ?? 0,
            'totalLulus'       => $siswaStats['lulus']     ?? 0,
            'totalAlpa'        => $totalAlpa,
            'distribusiGender' => ['L' => $siswaStats['laki'] ?? 0, 'P' => $siswaStats['perempuan'] ?? 0],
            'siswaPerKelas'    => $siswaPerKelas,
            'prestasi'         => $prestasi,
            'pengumuman'       => $pengumuman,
            'rekapAbsensi'     => $rekapAbsensi,
        ]);

        return view('dashboard/kesiswaan/index', $data);
    }

    // ============================================================
    // BK
    // ============================================================
    public function bk()
    {
        if ($this->denyAccess(['bk', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $bulan = date('m');
        $tahun = date('Y');

        $totalKonseling = $this->db->query("
            SELECT COUNT(*) AS total FROM counseling_sessions
            WHERE MONTH(date)=? AND YEAR(date)=?
        ", [$bulan, $tahun])->getRowArray()['total'] ?? 0;

        $konselingTerbaru = $this->db->query("
            SELECT cs.*, s.full_name AS nama_siswa, s.nis,
                   s.grade, m.abbreviation AS major_name
            FROM counseling_sessions cs
            JOIN students s ON cs.student_id = s.id
            JOIN majors m ON s.major_id = m.id
            ORDER BY cs.date DESC LIMIT 10
        ")->getResultArray();

        $siswaPerhatian = $this->db->query("
            SELECT s.id, s.nis, s.full_name, s.grade,
                   m.abbreviation AS major_name, s.class_group,
                   COUNT(sa.id) AS total_alpa
            FROM students s
            JOIN student_attendance sa ON s.id = sa.student_id
            JOIN majors m ON s.major_id = m.id
            WHERE sa.status='Alpa' AND MONTH(sa.date)=? AND YEAR(sa.date)=?
              AND s.status='aktif'
            GROUP BY s.id ORDER BY total_alpa DESC LIMIT 8
        ", [$bulan, $tahun])->getResultArray();

        $studentRecords = $this->db->query("
            SELECT sr.*, s.full_name AS nama_siswa, s.nis,
                   m.abbreviation AS major_name
            FROM student_records sr
            JOIN students s ON sr.student_id = s.id
            JOIN majors m ON s.major_id = m.id
            ORDER BY sr.date DESC LIMIT 8
        ")->getResultArray();

        $konselingPerTopik = $this->db->query("
            SELECT topic, COUNT(*) AS total FROM counseling_sessions
            WHERE MONTH(date)=? AND YEAR(date)=?
            GROUP BY topic ORDER BY total DESC LIMIT 6
        ", [$bulan, $tahun])->getResultArray();

        $totalBermasalah = $this->db->query("
            SELECT COUNT(*) AS total FROM (
                SELECT s.id FROM students s
                JOIN student_attendance sa ON s.id = sa.student_id
                WHERE sa.status='Alpa' AND MONTH(sa.date)=? AND YEAR(sa.date)=?
                  AND s.status='aktif'
                GROUP BY s.id HAVING COUNT(sa.id) > 3
            ) AS sub
        ", [$bulan, $tahun])->getRowArray()['total'] ?? 0;

        $data = array_merge($this->baseData('Dashboard BK', date('l, d F Y')), [
            'totalKonseling'    => $totalKonseling,
            'totalBermasalah'   => $totalBermasalah,
            'konselingTerbaru'  => $konselingTerbaru,
            'siswaPerhatian'    => $siswaPerhatian,
            'studentRecords'    => $studentRecords,
            'konselingPerTopik' => $konselingPerTopik,
        ]);

        return view('dashboard/bk/index', $data);
    }

    // ============================================================
    // TOOLMAN
    // ============================================================
    public function toolman()
    {
        if ($this->denyAccess(['toolman', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $userId = session()->get('user_id');
        $staff  = $this->db->table('staff')->where('user_id', $userId)->get()->getRowArray();

        $invStat = $this->db->query("
            SELECT
                COUNT(*) AS total_item,
                COALESCE(SUM(quantity), 0) AS total_unit,
                SUM(CASE WHEN `condition`='Baik'        THEN 1 ELSE 0 END) AS kondisi_baik,
                SUM(CASE WHEN `condition`='Rusak Ringan' THEN 1 ELSE 0 END) AS rusak_ringan,
                SUM(CASE WHEN `condition`='Rusak Berat'  THEN 1 ELSE 0 END) AS rusak_berat
            FROM inventory
        ")->getRowArray();

        $inventaris = $this->db->table('inventory')
            ->orderBy('`condition`')
            ->orderBy('item_name')
            ->get()->getResultArray();

        $laporanTerbaru = $this->db->query("
            SELECT tr.*, st.full_name AS nama_staff
            FROM toolman_reports tr
            JOIN staff st ON tr.staff_id = st.id
            ORDER BY tr.date DESC LIMIT 7
        ")->getResultArray();

        $labBookings = $this->db->query("
            SELECT lb.*, t.full_name AS nama_guru
            FROM lab_bookings lb
            LEFT JOIN teachers t ON lb.teacher_id = t.id
            WHERE lb.date = CURDATE()
            ORDER BY lb.start_time ASC
        ")->getResultArray();

        $labVisits = $this->db->query("
            SELECT lv.*, lb.lab_name, lb.purpose, lb.date AS visit_date,
                   t.full_name AS nama_guru
            FROM lab_visits lv
            JOIN lab_bookings lb ON lv.lab_booking_id = lb.id
            LEFT JOIN teachers t ON lb.teacher_id = t.id
            ORDER BY lv.created_at DESC LIMIT 5
        ")->getResultArray();

        $itemRusak = $this->db->query("
            SELECT * FROM inventory
            WHERE `condition` != 'Baik'
            ORDER BY `condition` DESC, item_name ASC
        ")->getResultArray();

        $data = array_merge($this->baseData('Dashboard Toolman', $staff['full_name'] ?? ''), [
            'staff'          => $staff          ?? [],
            'invStat'        => $invStat        ?? [],
            'inventaris'     => $inventaris,
            'laporanTerbaru' => $laporanTerbaru,
            'labBookings'    => $labBookings,
            'labVisits'      => $labVisits,
            'itemRusak'      => $itemRusak,
        ]);

        return view('dashboard/toolman/index', $data);
    }

    // ============================================================
    // SISWA — redirect ke SiswaDashboard
    // ============================================================
    public function siswa()
    {
        return (new SiswaDashboard())->index();
    }

    // ============================================================
    // HELPERS
    // ============================================================
    private function baseData(string $title = 'Dashboard', string $subtitle = ''): array
    {
        return [
            'title'    => $title,
            'subtitle' => $subtitle,
            'username' => session()->get('username'),
            'email'    => session()->get('email'),
            'role'     => session()->get('role'),
            'user_id'  => session()->get('user_id'),
        ];
    }

    private function hariIni(): string
    {
        $map = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        return $map[date('l')] ?? 'Senin';
    }

    private function denyAccess(array $allowedRoles): bool
    {
        if (!session()->get('isLoggedIn')) return true;
        return !in_array(session()->get('role'), $allowedRoles);
    }
}