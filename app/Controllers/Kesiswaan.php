<?php

namespace App\Controllers;

use App\Models\AbsensiModel;

class Kesiswaan extends BaseController
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
        if (session()->get('role') !== 'kesiswaan') {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    public function index()
    {
        $this->authCheck();

        $absensiModel = new AbsensiModel();
        $bulan = date('m');
        $tahun = date('Y');

        // Total siswa per status
        $siswaStats = $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'aktif'  THEN 1 ELSE 0 END) AS aktif,
                SUM(CASE WHEN status = 'lulus'  THEN 1 ELSE 0 END) AS lulus,
                SUM(gender = 'L') AS laki,
                SUM(gender = 'P') AS perempuan
            FROM students
        ")->getRowArray();

        // Siswa per kelas
        $siswaPerKelas = $this->db->query("
            SELECT
                CONCAT(s.grade, ' ', m.abbreviation, ' ', s.class_group) AS nama_kelas,
                s.grade, s.major_id, s.class_group,
                COUNT(s.id) AS jumlah_siswa
            FROM students s
            JOIN majors m ON s.major_id = m.id
            WHERE s.status = 'aktif'
            GROUP BY s.grade, s.major_id, s.class_group
            ORDER BY s.grade, m.abbreviation, s.class_group
        ")->getResultArray();

        // Prestasi terbaru
        $prestasi = $this->db->query("
            SELECT sa.*, s.full_name AS nama_siswa
            FROM student_achievements sa
            JOIN students s ON sa.student_id = s.id
            ORDER BY sa.created_at DESC
            LIMIT 8
        ")->getResultArray();

        // Pengumuman aktif
        $pengumuman = $this->db->query("
            SELECT * FROM announcements
            WHERE published_at <= CURDATE()
            ORDER BY is_important DESC, published_at DESC
            LIMIT 5
        ")->getResultArray();

        // Total alpa bulan ini
        $alpaData = $this->db->query("
            SELECT COUNT(*) AS total_alpa
            FROM student_attendance
            WHERE status = 'Alpa'
              AND MONTH(date) = ? AND YEAR(date) = ?
        ", [$bulan, $tahun])->getRowArray();

        // Rekap absensi per kelas bulan ini
        $rekapAbsensi = $this->db->query("
            SELECT
                CONCAT(s.grade, ' ', m.abbreviation, ' ', s.class_group) AS nama_kelas,
                COUNT(DISTINCT s.id) AS jumlah_siswa,
                SUM(CASE WHEN sa.status = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN sa.status = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN sa.status = 'Izin'  THEN 1 ELSE 0 END) AS izin,
                SUM(CASE WHEN sa.status = 'Alpa'  THEN 1 ELSE 0 END) AS alpa
            FROM students s
            JOIN majors m ON s.major_id = m.id
            LEFT JOIN student_attendance sa
                ON s.id = sa.student_id
                AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
            WHERE s.status = 'aktif'
            GROUP BY s.grade, s.major_id, s.class_group
            ORDER BY s.grade, m.abbreviation, s.class_group
        ", [$bulan, $tahun])->getResultArray();

        $data = [
            'title'           => 'Dashboard Kesiswaan',
            'totalSiswa'      => $siswaStats['total']     ?? 0,
            'totalAktif'      => $siswaStats['aktif']     ?? 0,
            'totalLulus'      => $siswaStats['lulus']     ?? 0,
            'totalAlpa'       => $alpaData['total_alpa']  ?? 0,
            'distribusiGender'=> ['L' => $siswaStats['laki'] ?? 0, 'P' => $siswaStats['perempuan'] ?? 0],
            'siswaPerKelas'   => $siswaPerKelas,
            'prestasi'        => $prestasi,
            'pengumuman'      => $pengumuman,
            'rekapAbsensi'    => $rekapAbsensi,
            'bulan'           => $bulan,
            'tahun'           => $tahun,
        ];

        return view('dashboard/kesiswaan/index', $data);
    }
}