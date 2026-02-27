<?php

namespace App\Controllers;

use App\Models\AbsensiModel;

class Dashboard extends BaseController
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
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role = session()->get('role');

        switch ($role) {
            case 'superadmin':
                return view('dashboard/index', $this->getUserData());
            case 'kepsek':
                return redirect()->to('/dashboard/kepsek');
            case 'tu':
                return redirect()->to('/dashboard/tu');
            case 'kurikulum':
                return redirect()->to('/dashboard/kurikulum');
            case 'guru_mapel':
                return redirect()->to('/dashboard/guru');
            case 'wali_kelas':
                return redirect()->to('/dashboard/wakel');
            case 'kesiswaan':
                return redirect()->to('/dashboard/kesiswaan');
            case 'bk':
                return redirect()->to('/dashboard/bk');
            case 'toolman':
                return redirect()->to('/dashboard/toolman');
            case 'siswa':
                return redirect()->to('/dashboard/siswa');
            default:
                session()->destroy();
                return redirect()->to('/auth/login')->with('error', 'Role tidak dikenali');
        }
    }

    // ==============================
    // KEPSEK & TU — sudah ada data
    // ==============================
    public function kepsek()
    {
        if ($this->denyAccess(['kepsek', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }
        $data = array_merge($this->getUserData(), $this->getStatsData());
        return view('dashboard/kepsek/index', $data);
    }

    public function tu()
    {
        if ($this->denyAccess(['tu', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }
        $data = array_merge($this->getUserData(), $this->getStatsData());
        return view('dashboard/tu/index', $data);
    }

    // ==============================
    // KURIKULUM
    // ==============================
    public function kurikulum()
    {
        if ($this->denyAccess(['kurikulum', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $bulan = date('m');
        $tahun = date('Y');
        $dayMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
                   'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $hariIni = $dayMap[date('l')] ?? 'Senin';

        $totalGuru   = $this->db->table('teachers')->countAllResults();
        $totalMapel  = $this->db->query("SELECT COUNT(DISTINCT subject) AS total FROM schedules WHERE is_active=1")->getRowArray()['total'] ?? 0;
        $totalJadwal = $this->db->table('schedules')->where('is_active', 1)->countAllResults();
        $totalUjian  = $this->db->table('exams')->countAllResults();

        $jadwalHariIni = $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru
            FROM schedules sc LEFT JOIN teachers t ON sc.teacher_id = t.id
            WHERE sc.day = ? AND sc.is_active = 1 ORDER BY sc.start_time ASC
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
        ")->getResultArray();

        $kehadiranGuru = $this->db->query("
            SELECT t.full_name,
                (SELECT subject FROM schedules WHERE teacher_id=t.id AND is_active=1 LIMIT 1) AS subject,
                (SELECT COUNT(*) FROM schedules WHERE teacher_id=t.id AND is_active=1) AS jml_jadwal,
                SUM(CASE WHEN ta.status='Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN ta.status!='Hadir' THEN 1 ELSE 0 END) AS tidak_hadir
            FROM teachers t
            LEFT JOIN teacher_attendance ta ON t.id=ta.teacher_id
                AND MONTH(ta.date)=? AND YEAR(ta.date)=?
            GROUP BY t.id ORDER BY t.full_name ASC
        ", [$bulan, $tahun])->getResultArray();

        $data = array_merge($this->getUserData(), [
            'title'          => 'Dashboard Kurikulum',
            'totalGuru'      => $totalGuru,
            'totalMapel'     => $totalMapel,
            'totalJadwal'    => $totalJadwal,
            'totalUjian'     => $totalUjian,
            'jadwalHariIni'  => $jadwalHariIni,
            'ujianMendatang' => $ujianMendatang,
            'jurnalTerbaru'  => $jurnalTerbaru,
            'jadwalPerHari'  => $jadwalPerHari,
            'kehadiranGuru'  => $kehadiranGuru,
        ]);
        return view('dashboard/kurikulum/index', $data);
    }

    // ==============================
    // GURU MAPEL
    // ==============================
    public function guru()
    {
        if ($this->denyAccess(['guru_mapel', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $userId = session()->get('user_id');
        $bulan  = date('m');
        $tahun  = date('Y');
        $dayMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
                   'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $hariIni = $dayMap[date('l')] ?? 'Senin';

        $teacher   = $this->db->table('teachers')->where('user_id', $userId)->get()->getRowArray();
        $teacherId = $teacher['id'] ?? null;

        $jadwalHariIni = $teacherId ? $this->db->query("
            SELECT sc.*, m.abbreviation AS major_name FROM schedules sc
            LEFT JOIN majors m ON m.abbreviation = sc.major
            WHERE sc.teacher_id=? AND sc.day=? AND sc.is_active=1 ORDER BY sc.start_time ASC
        ", [$teacherId, $hariIni])->getResultArray() : [];

        $semuaJadwal = $teacherId ? $this->db->query("
            SELECT sc.*, m.abbreviation AS major_name FROM schedules sc
            LEFT JOIN majors m ON m.abbreviation = sc.major
            WHERE sc.teacher_id=? AND sc.is_active=1
            ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), sc.start_time
        ", [$teacherId])->getResultArray() : [];

        $absensiStat = $teacherId ? $this->db->query("
            SELECT SUM(status='Hadir') AS hadir, SUM(status='Sakit') AS sakit,
                   SUM(status='Izin') AS izin, SUM(status='Alpa') AS alpa, COUNT(*) AS total
            FROM teacher_attendance WHERE teacher_id=? AND MONTH(date)=? AND YEAR(date)=?
        ", [$teacherId, $bulan, $tahun])->getRowArray() : [];

        $jurnalSaya = $teacherId ? $this->db->query("
            SELECT * FROM lesson_journals WHERE teacher_id=? ORDER BY date DESC LIMIT 5
        ", [$teacherId])->getResultArray() : [];

        $ujianSaya = $teacherId ? $this->db->query("
            SELECT * FROM exams WHERE supervisor_id=? AND date>=CURDATE() ORDER BY date ASC LIMIT 5
        ", [$teacherId])->getResultArray() : [];

        $totalSiswaAjar = 0;
        if ($teacherId && !empty($semuaJadwal)) {
            $totalSiswaAjar = $this->db->query("
                SELECT COUNT(DISTINCT s.id) AS total FROM students s
                JOIN majors m ON s.major_id = m.id
                WHERE s.status='aktif'
                  AND CONCAT(s.grade,'-',m.abbreviation,'-',s.class_group) IN (
                      SELECT CONCAT(grade,'-',major,'-',class_group)
                      FROM schedules WHERE teacher_id=? AND is_active=1
                  )
            ", [$teacherId])->getRowArray()['total'] ?? 0;
        }

        $data = array_merge($this->getUserData(), [
            'title'          => 'Dashboard Guru',
            'teacher'        => $teacher,
            'jadwalHariIni'  => $jadwalHariIni,
            'semuaJadwal'    => $semuaJadwal,
            'absensiStat'    => $absensiStat,
            'jurnalSaya'     => $jurnalSaya,
            'ujianSaya'      => $ujianSaya,
            'totalSiswaAjar' => $totalSiswaAjar,
            'hariIni'        => $hariIni,
        ]);
        return view('dashboard/guru/index', $data);
    }

    // ==============================
    // WALI KELAS
    // ==============================
    public function wakel()
    {
        if ($this->denyAccess(['wali_kelas', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $absensiModel = new AbsensiModel();
        $userId  = session()->get('user_id');
        $bulan   = date('m');
        $tahun   = date('Y');
        $dayMap  = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
                    'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $hariIni = $dayMap[date('l')] ?? 'Senin';

        // Ambil teacher_id dari user_id
        $teacher = $this->db->table('teachers')->where('user_id', $userId)->get()->getRowArray();
        $teacherId = $teacher['id'] ?? null;

        // Ambil kelas yang diajar guru ini dari schedules (kelas pertama = kelas wali)
        $kelasFromSchedule = $teacherId ? $this->db->query("
            SELECT sc.grade, sc.major AS major_abbr, sc.class_group,
                   m.id AS major_id, m.abbreviation AS major_name,
                   CONCAT(sc.grade,' ',m.abbreviation,' ',sc.class_group) AS nama_kelas,
                   COUNT(DISTINCT s.id) AS jumlah_siswa
            FROM schedules sc
            JOIN majors m ON m.abbreviation = sc.major
            LEFT JOIN students s ON s.grade=sc.grade AND s.major_id=m.id
                AND s.class_group=sc.class_group AND s.status='aktif'
            WHERE sc.teacher_id = ? AND sc.is_active = 1
            GROUP BY sc.grade, sc.major, sc.class_group
            ORDER BY jumlah_siswa DESC
            LIMIT 1
        ", [$teacherId])->getRowArray() : [];

        // Fallback: jika tidak ada di schedules, ambil kelas pertama yang ada siswa aktif
        $kelasSaya = !empty($kelasFromSchedule) ? $kelasFromSchedule : $this->db->query("
            SELECT s.grade, s.major_id, s.class_group,
                   m.abbreviation AS major_name,
                   CONCAT(s.grade,' ',m.abbreviation,' ',s.class_group) AS nama_kelas,
                   COUNT(s.id) AS jumlah_siswa
            FROM students s JOIN majors m ON s.major_id=m.id
            WHERE s.status='aktif'
            GROUP BY s.grade, s.major_id, s.class_group
            ORDER BY s.grade, m.abbreviation, s.class_group
            LIMIT 1
        ")->getRowArray();

        $grade       = $kelasSaya['grade']       ?? null;
        $major_id    = $kelasSaya['major_id']    ?? null;
        $class_group = $kelasSaya['class_group'] ?? null;
        // major_abbr bisa dari schedules query (major_name) atau fallback
        $majorAbbr   = $kelasSaya['major_name']  ?? $kelasSaya['major_abbr'] ?? '';

        $statistikAbsensi = $absensiModel->getStatistikKehadiran($grade, $major_id, $class_group, $bulan, $tahun);
        $absensiHariIni   = ($grade && $major_id && $class_group)
            ? $absensiModel->getAbsensiHarian(date('Y-m-d'), $grade, $major_id, $class_group) : [];
        $daftarSiswa      = ($grade && $major_id && $class_group)
            ? $absensiModel->getRekapBulanan($grade, $major_id, $class_group, $bulan, $tahun) : [];
        $siswaBermasalah  = $absensiModel->getSiswaAlpaTerbanyak($grade, $major_id, $bulan, $tahun, 5);

        $jadwalHariIni = [];
        if ($grade && $majorAbbr && $class_group) {
            $jadwalHariIni = $this->db->query("
                SELECT sc.*, t.full_name AS nama_guru FROM schedules sc
                LEFT JOIN teachers t ON sc.teacher_id=t.id
                WHERE sc.grade=? AND sc.major=? AND sc.class_group=? AND sc.day=? AND sc.is_active=1
                ORDER BY sc.start_time ASC
            ", [$grade, $majorAbbr, $class_group, $hariIni])->getResultArray();
        }

        $data = array_merge($this->getUserData(), [
            'title'            => 'Dashboard Wali Kelas',
            'kelasSaya'        => $kelasSaya        ?? [],
            'statistikAbsensi' => $statistikAbsensi ?? [],
            'absensiHariIni'   => $absensiHariIni   ?? [],
            'daftarSiswa'      => $daftarSiswa      ?? [],
            'siswaBermasalah'  => $siswaBermasalah  ?? [],
            'jadwalHariIni'    => $jadwalHariIni     ?? [],
        ]);
        return view('dashboard/wakel/index', $data);
    }

    // ==============================
    // KESISWAAN
    // ==============================
    public function kesiswaan()
    {
        if ($this->denyAccess(['kesiswaan', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $bulan = date('m');
        $tahun = date('Y');

        $siswaStats = $this->db->query("
            SELECT COUNT(*) AS total,
                SUM(status='aktif') AS aktif, SUM(status='lulus') AS lulus,
                SUM(gender='L') AS laki, SUM(gender='P') AS perempuan
            FROM students
        ")->getRowArray();

        $siswaPerKelas = $this->db->query("
            SELECT CONCAT(s.grade,' ',m.abbreviation,' ',s.class_group) AS nama_kelas,
                   s.grade, s.major_id, s.class_group, COUNT(s.id) AS jumlah_siswa
            FROM students s JOIN majors m ON s.major_id=m.id
            WHERE s.status='aktif'
            GROUP BY s.grade, s.major_id, s.class_group ORDER BY s.grade, m.abbreviation, s.class_group
        ")->getResultArray();

        $prestasi = $this->db->query("
            SELECT sa.*, s.full_name AS nama_siswa FROM student_achievements sa
            JOIN students s ON sa.student_id=s.id ORDER BY sa.created_at DESC LIMIT 8
        ")->getResultArray();

        $pengumuman = $this->db->query("
            SELECT * FROM announcements WHERE published_at <= CURDATE()
            ORDER BY is_important DESC, published_at DESC LIMIT 5
        ")->getResultArray();

        $alpaData = $this->db->query("
            SELECT COUNT(*) AS total_alpa FROM student_attendance
            WHERE status='Alpa' AND MONTH(date)=? AND YEAR(date)=?
        ", [$bulan, $tahun])->getRowArray();

        $rekapAbsensi = $this->db->query("
            SELECT CONCAT(s.grade,' ',m.abbreviation,' ',s.class_group) AS nama_kelas,
                COUNT(DISTINCT s.id) AS jumlah_siswa,
                SUM(sa.status='Hadir') AS hadir, SUM(sa.status='Sakit') AS sakit,
                SUM(sa.status='Izin')  AS izin,  SUM(sa.status='Alpa')  AS alpa
            FROM students s JOIN majors m ON s.major_id=m.id
            LEFT JOIN student_attendance sa ON s.id=sa.student_id
                AND MONTH(sa.date)=? AND YEAR(sa.date)=?
            WHERE s.status='aktif'
            GROUP BY s.grade, s.major_id, s.class_group ORDER BY s.grade, m.abbreviation, s.class_group
        ", [$bulan, $tahun])->getResultArray();

        $data = array_merge($this->getUserData(), [
            'title'            => 'Dashboard Kesiswaan',
            'totalSiswa'       => $siswaStats['total']    ?? 0,
            'totalAktif'       => $siswaStats['aktif']    ?? 0,
            'totalLulus'       => $siswaStats['lulus']    ?? 0,
            'totalAlpa'        => $alpaData['total_alpa'] ?? 0,
            'distribusiGender' => ['L' => $siswaStats['laki'] ?? 0, 'P' => $siswaStats['perempuan'] ?? 0],
            'siswaPerKelas'    => $siswaPerKelas,
            'prestasi'         => $prestasi,
            'pengumuman'       => $pengumuman,
            'rekapAbsensi'     => $rekapAbsensi,
        ]);
        return view('dashboard/kesiswaan/index', $data);
    }

    // ==============================
    // BK
    // ==============================
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
            SELECT cs.*, s.full_name AS nama_siswa, s.nis
            FROM counseling_sessions cs JOIN students s ON cs.student_id=s.id
            ORDER BY cs.date DESC LIMIT 10
        ")->getResultArray();

        $siswaPerhatian = $this->db->query("
            SELECT s.id, s.nis, s.full_name, s.grade, m.abbreviation AS major_name,
                   s.class_group, COUNT(sa.id) AS total_alpa
            FROM students s JOIN student_attendance sa ON s.id=sa.student_id
            JOIN majors m ON s.major_id=m.id
            WHERE sa.status='Alpa' AND MONTH(sa.date)=? AND YEAR(sa.date)=? AND s.status='aktif'
            GROUP BY s.id ORDER BY total_alpa DESC LIMIT 8
        ", [$bulan, $tahun])->getResultArray();

        $studentRecords = $this->db->query("
            SELECT sr.*, s.full_name AS nama_siswa, s.nis FROM student_records sr
            JOIN students s ON sr.student_id=s.id ORDER BY sr.date DESC LIMIT 8
        ")->getResultArray();

        $konselingPerTopik = $this->db->query("
            SELECT topic, COUNT(*) AS total FROM counseling_sessions
            WHERE MONTH(date)=? AND YEAR(date)=? GROUP BY topic ORDER BY total DESC LIMIT 6
        ", [$bulan, $tahun])->getResultArray();

        $totalBermasalah = $this->db->query("
            SELECT COUNT(DISTINCT s.id) AS total FROM students s
            JOIN student_attendance sa ON s.id=sa.student_id
            WHERE sa.status='Alpa' AND MONTH(sa.date)=? AND YEAR(sa.date)=? AND s.status='aktif'
            GROUP BY s.id HAVING COUNT(sa.id) > 3
        ", [$bulan, $tahun])->getNumRows();

        $data = array_merge($this->getUserData(), [
            'title'             => 'Dashboard BK',
            'totalKonseling'    => $totalKonseling,
            'totalBermasalah'   => $totalBermasalah,
            'konselingTerbaru'  => $konselingTerbaru,
            'siswaPerhatian'    => $siswaPerhatian,
            'studentRecords'    => $studentRecords,
            'konselingPerTopik' => $konselingPerTopik,
        ]);
        return view('dashboard/bk/index', $data);
    }

    // ==============================
    // TOOLMAN
    // ==============================
    public function toolman()
    {
        if ($this->denyAccess(['toolman', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $userId  = session()->get('user_id');
        $staff   = $this->db->table('staff')->where('user_id', $userId)->get()->getRowArray();

        $invStat = $this->db->query("
            SELECT COUNT(*) AS total_item, SUM(quantity) AS total_unit,
                SUM(condition='Baik') AS kondisi_baik,
                SUM(condition='Rusak Ringan') AS rusak_ringan,
                SUM(condition='Rusak Berat') AS rusak_berat
            FROM inventory
        ")->getRowArray();

        $inventaris     = $this->db->table('inventory')->orderBy('condition')->orderBy('item_name')->get()->getResultArray();
        $laporanTerbaru = $this->db->query("
            SELECT tr.*, st.full_name AS nama_staff FROM toolman_reports tr
            JOIN staff st ON tr.staff_id=st.id ORDER BY tr.date DESC LIMIT 7
        ")->getResultArray();
        $labBookings    = $this->db->query("
            SELECT lb.*, t.full_name AS nama_guru FROM lab_bookings lb
            LEFT JOIN teachers t ON lb.teacher_id=t.id WHERE lb.date=CURDATE() ORDER BY lb.start_time ASC
        ")->getResultArray();
        $labVisits      = $this->db->query("
            SELECT lv.*, t.full_name AS nama_guru FROM lab_visits lv
            LEFT JOIN teachers t ON lv.teacher_id=t.id ORDER BY lv.visit_date DESC LIMIT 5
        ")->getResultArray();
        $itemRusak      = $this->db->query("
            SELECT * FROM inventory WHERE condition!='Baik' ORDER BY condition DESC, item_name ASC
        ")->getResultArray();

        $data = array_merge($this->getUserData(), [
            'title'          => 'Dashboard Toolman',
            'staff'          => $staff,
            'invStat'        => $invStat,
            'inventaris'     => $inventaris,
            'laporanTerbaru' => $laporanTerbaru,
            'labBookings'    => $labBookings,
            'labVisits'      => $labVisits,
            'itemRusak'      => $itemRusak,
        ]);
        return view('dashboard/toolman/index', $data);
    }

    // ==============================
    // SISWA
    // ==============================
    public function siswa()
    {
        if ($this->denyAccess(['siswa', 'superadmin'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        $userId = session()->get('user_id');
        $bulan  = date('m');
        $tahun  = date('Y');
        $dayMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
                   'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $hariIni = $dayMap[date('l')] ?? 'Senin';

        $siswa = $this->db->query("
            SELECT s.*, m.abbreviation AS major_name, m.name AS major_full
            FROM students s JOIN majors m ON s.major_id=m.id WHERE s.user_id=?
        ", [$userId])->getRowArray();

        $siswaId    = $siswa['id']          ?? null;
        $grade      = $siswa['grade']       ?? null;
        $majorId    = $siswa['major_id']    ?? null;
        $classGroup = $siswa['class_group'] ?? null;
        $majorAbbr  = $siswa['major_name']  ?? '';

        $absensiStat = $siswaId ? $this->db->query("
            SELECT SUM(status='Hadir') AS hadir, SUM(status='Sakit') AS sakit,
                   SUM(status='Izin') AS izin, SUM(status='Alpa') AS alpa, COUNT(*) AS total
            FROM student_attendance WHERE student_id=? AND MONTH(date)=? AND YEAR(date)=?
        ", [$siswaId, $bulan, $tahun])->getRowArray() : [];

        $jadwalHariIni = ($grade && $majorAbbr && $classGroup) ? $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru FROM schedules sc
            LEFT JOIN teachers t ON sc.teacher_id=t.id
            WHERE sc.grade=? AND sc.major=? AND sc.class_group=? AND sc.day=? AND sc.is_active=1
            ORDER BY sc.start_time ASC
        ", [$grade, $majorAbbr, $classGroup, $hariIni])->getResultArray() : [];

        $jadwalMinggu = ($grade && $majorAbbr && $classGroup) ? $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru FROM schedules sc
            LEFT JOIN teachers t ON sc.teacher_id=t.id
            WHERE sc.grade=? AND sc.major=? AND sc.class_group=? AND sc.is_active=1
            ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), sc.start_time
        ", [$grade, $majorAbbr, $classGroup])->getResultArray() : [];

        $absensiTerbaru = $siswaId ? $this->db->query("
            SELECT * FROM student_attendance WHERE student_id=? ORDER BY date DESC LIMIT 10
        ", [$siswaId])->getResultArray() : [];

        $prestasi = $siswaId ? $this->db->query("
            SELECT * FROM student_achievements WHERE student_id=? ORDER BY year DESC LIMIT 5
        ", [$siswaId])->getResultArray() : [];

        $pengumuman = $this->db->query("
            SELECT * FROM announcements
            WHERE (visibility LIKE '%siswa%' OR visibility='semua') AND published_at<=CURDATE()
            ORDER BY is_important DESC, published_at DESC LIMIT 5
        ")->getResultArray();

        $data = array_merge($this->getUserData(), [
            'title'          => 'Dashboard Siswa',
            'siswa'          => $siswa          ?? [],
            'absensiStat'    => $absensiStat    ?? [],
            'jadwalHariIni'  => $jadwalHariIni  ?? [],
            'jadwalMinggu'   => $jadwalMinggu   ?? [],
            'absensiTerbaru' => $absensiTerbaru ?? [],
            'prestasi'       => $prestasi       ?? [],
            'pengumuman'     => $pengumuman     ?? [],
            'hariIni'        => $hariIni,
        ]);
        return view('dashboard/siswa/index', $data);
    }

    // ==============================
    // HELPERS
    // ==============================
    private function getUserData(): array
    {
        return [
            'username' => session()->get('username'),
            'email'    => session()->get('email'),
            'role'     => session()->get('role'),
            'user_id'  => session()->get('user_id'),
        ];
    }

    private function getStatsData(): array
    {
        return [
            'totalSiswa'   => $this->db->table('students')->where('status', 'aktif')->countAllResults(),
            'totalGuru'    => $this->db->table('teachers')->countAllResults(),
            'totalJurusan' => $this->db->table('majors')->where('is_active', 1)->countAllResults(),
        ];
    }

    private function denyAccess(array $allowedRoles): bool
    {
        if (!session()->get('isLoggedIn')) return true;
        return !in_array(session()->get('role'), $allowedRoles);
    }
}