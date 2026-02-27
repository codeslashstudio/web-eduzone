<?php

namespace App\Controllers;

use App\Models\AbsensiModel;

class Wakel extends BaseController
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
        if (session()->get('role') !== 'wali_kelas') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        $absensiModel = new AbsensiModel();
        $userId       = session()->get('user_id');
        $bulan        = date('m');
        $tahun        = date('Y');

        // ===========================
        // Cari kelas yang diampu wali kelas ini
        // dari tabel teachers (wali_kelas field) → kelas
        // ===========================
        $kelasSaya = $this->db->query("
            SELECT
                s.grade,
                s.major_id,
                s.class_group,
                m.abbreviation AS major_name,
                CONCAT(s.grade, ' ', m.abbreviation, ' ', s.class_group) AS nama_kelas,
                COUNT(s.id) AS jumlah_siswa
            FROM students s
            JOIN majors m ON s.major_id = m.id
            JOIN teachers t ON (
                t.grade = s.grade
                AND t.major_id = s.major_id
                AND t.class_group = s.class_group
            )
            WHERE t.user_id = ?
              AND s.status = 'aktif'
            GROUP BY s.grade, s.major_id, s.class_group
            LIMIT 1
        ", [$userId])->getRowArray();

        // Fallback jika belum ada assignment kelas
        $grade       = $kelasSaya['grade']       ?? null;
        $major_id    = $kelasSaya['major_id']    ?? null;
        $class_group = $kelasSaya['class_group'] ?? null;

        // ===========================
        // Statistik absensi bulan ini
        // ===========================
        $statistikAbsensi = $absensiModel->getStatistikKehadiran($grade, $major_id, $class_group, $bulan, $tahun);

        // ===========================
        // Absensi hari ini untuk kelas ini
        // ===========================
        $absensiHariIni = [];
        if ($grade && $major_id && $class_group) {
            $absensiHariIni = $absensiModel->getAbsensiHarian(date('Y-m-d'), $grade, $major_id, $class_group);
        }

        // ===========================
        // Daftar siswa + rekap absensi bulan ini
        // ===========================
        $daftarSiswa = [];
        if ($grade && $major_id && $class_group) {
            $daftarSiswa = $absensiModel->getRekapBulanan($grade, $major_id, $class_group, $bulan, $tahun);
        }

        // ===========================
        // Siswa alpa terbanyak di kelas ini
        // ===========================
        $siswaBermasalah = $absensiModel->getSiswaAlpaTerbanyak($grade, $major_id, $bulan, $tahun, 5);

        // ===========================
        // Jadwal kelas hari ini
        // ===========================
        $dayName = date('l');
        $dayMap  = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $hariIni = $dayMap[$dayName] ?? 'Senin';

        $jadwalHariIni = [];
        if ($grade && $major_id && $class_group) {
            $major = $this->db->table('majors')->where('id', $major_id)->get()->getRowArray();
            $majorAbbr = $major['abbreviation'] ?? '';

            $jadwalHariIni = $this->db->query("
                SELECT
                    sc.subject,
                    sc.start_time,
                    sc.end_time,
                    sc.room,
                    t.full_name AS nama_guru
                FROM schedules sc
                LEFT JOIN teachers t ON sc.teacher_id = t.id
                WHERE sc.grade       = ?
                  AND sc.major       = ?
                  AND sc.class_group = ?
                  AND sc.day         = ?
                  AND sc.is_active   = 1
                ORDER BY sc.start_time ASC
            ", [$grade, $majorAbbr, $class_group, $hariIni])->getResultArray();
        }

        $data = [
            'title'            => 'Dashboard Wali Kelas',
            'kelasSaya'        => $kelasSaya,
            'statistikAbsensi' => $statistikAbsensi,
            'absensiHariIni'   => $absensiHariIni,
            'daftarSiswa'      => $daftarSiswa,
            'siswaBermasalah'  => $siswaBermasalah,
            'jadwalHariIni'    => $jadwalHariIni,
            'bulan'            => $bulan,
            'tahun'            => $tahun,
        ];

        return view('dashboard/wakel/index', $data);
    }
}