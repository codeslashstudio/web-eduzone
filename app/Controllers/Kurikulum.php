<?php

namespace App\Controllers;

class Kurikulum extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    private function authCheck(): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        if (session()->get('role') !== 'kurikulum') {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    public function index()
    {
        $this->authCheck();

        $bulan = date('m');
        $tahun = date('Y');

        // Total guru aktif
        $totalGuru = $this->db->table('teachers')->countAllResults();

        // Total mata pelajaran unik
        $totalMapel = $this->db->query("
            SELECT COUNT(DISTINCT subject) AS total FROM schedules WHERE is_active = 1
        ")->getRowArray()['total'] ?? 0;

        // Total jadwal aktif
        $totalJadwal = $this->db->table('schedules')->where('is_active', 1)->countAllResults();

        // Total ujian terjadwal
        $totalUjian = $this->db->table('exams')->countAllResults();

        // Jadwal hari ini
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $hariIni = $dayMap[date('l')] ?? 'Senin';

        $jadwalHariIni = $this->db->query("
            SELECT sc.*, t.full_name AS nama_guru
            FROM schedules sc
            LEFT JOIN teachers t ON sc.teacher_id = t.id
            WHERE sc.day = ? AND sc.is_active = 1
            ORDER BY sc.start_time ASC
        ", [$hariIni])->getResultArray();

        // Ujian mendatang (30 hari ke depan)
        $ujianMendatang = $this->db->query("
            SELECT * FROM exams
            WHERE date >= CURDATE() AND date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY date ASC, start_time ASC
            LIMIT 10
        ")->getResultArray();

        // Jurnal terbaru
        $jurnalTerbaru = $this->db->query("
            SELECT lj.*, t.full_name AS nama_guru
            FROM lesson_journals lj
            LEFT JOIN teachers t ON lj.teacher_id = t.id
            ORDER BY lj.date DESC, lj.created_at DESC
            LIMIT 6
        ")->getResultArray();

        // Jadwal per hari (untuk chart)
        $jadwalPerHari = $this->db->query("
            SELECT day, COUNT(*) AS total
            FROM schedules
            WHERE is_active = 1
            GROUP BY day
        ")->getResultArray();

        // Kehadiran guru bulan ini
        $kehadiranGuru = $this->db->query("
            SELECT
                t.full_name,
                t.id AS teacher_id,
                (SELECT subject FROM schedules WHERE teacher_id = t.id AND is_active = 1 LIMIT 1) AS subject,
                (SELECT COUNT(*) FROM schedules WHERE teacher_id = t.id AND is_active = 1) AS jml_jadwal,
                SUM(CASE WHEN ta.status = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN ta.status != 'Hadir' THEN 1 ELSE 0 END) AS tidak_hadir
            FROM teachers t
            LEFT JOIN teacher_attendance ta
                ON t.id = ta.teacher_id
                AND MONTH(ta.date) = ? AND YEAR(ta.date) = ?
            GROUP BY t.id
            ORDER BY t.full_name ASC
        ", [$bulan, $tahun])->getResultArray();

        $data = [
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
            'bulan'          => $bulan,
            'tahun'          => $tahun,
        ];

        return view('dashboard/kurikulum/index', $data);
    }
}