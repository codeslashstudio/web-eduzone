<?php
namespace App\Controllers;

class Bk extends BaseController
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
        if (session()->get('role') !== 'bk') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        $bulan = date('m');
        $tahun = date('Y');

        // Total konseling bulan ini
        $totalKonseling = $this->db->query("
            SELECT COUNT(*) AS total FROM counseling_sessions
            WHERE MONTH(date) = ? AND YEAR(date) = ?
        ", [$bulan, $tahun])->getRowArray()['total'] ?? 0;

        // Riwayat konseling terbaru
        $konselingTerbaru = $this->db->query("
            SELECT cs.*, s.full_name AS nama_siswa, s.nis,
                   st.full_name AS nama_konselor
            FROM counseling_sessions cs
            JOIN students s ON cs.student_id = s.id
            LEFT JOIN staff st ON cs.staff_id = st.id
            ORDER BY cs.date DESC LIMIT 10
        ")->getResultArray();

        // Siswa dengan alpa terbanyak (perlu perhatian BK)
        $siswaPerhatian = $this->db->query("
            SELECT s.id, s.nis, s.full_name, s.grade,
                   m.abbreviation AS major_name, s.class_group,
                   COUNT(sa.id) AS total_alpa
            FROM students s
            JOIN student_attendance sa ON s.id = sa.student_id
            JOIN majors m ON s.major_id = m.id
            WHERE sa.status = 'Alpa'
              AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
              AND s.status = 'aktif'
            GROUP BY s.id
            ORDER BY total_alpa DESC
            LIMIT 8
        ", [$bulan, $tahun])->getResultArray();

        // Student records (pelanggaran / penghargaan)
        $studentRecords = $this->db->query("
            SELECT sr.*, s.full_name AS nama_siswa, s.nis
            FROM student_records sr
            JOIN students s ON sr.student_id = s.id
            ORDER BY sr.date DESC LIMIT 8
        ")->getResultArray();

        // Statistik konseling per topik
        $konselingPerTopik = $this->db->query("
            SELECT topic, COUNT(*) AS total
            FROM counseling_sessions
            WHERE MONTH(date) = ? AND YEAR(date) = ?
            GROUP BY topic
            ORDER BY total DESC LIMIT 6
        ", [$bulan, $tahun])->getResultArray();

        // Total siswa bermasalah (alpa > 3)
        $totalBermasalah = $this->db->query("
            SELECT COUNT(DISTINCT s.id) AS total
            FROM students s
            JOIN student_attendance sa ON s.id = sa.student_id
            WHERE sa.status = 'Alpa'
              AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
              AND s.status = 'aktif'
            GROUP BY s.id
            HAVING COUNT(sa.id) > 3
        ", [$bulan, $tahun])->getNumRows();

        $data = [
            'title'              => 'Dashboard BK',
            'totalKonseling'     => $totalKonseling,
            'totalBermasalah'    => $totalBermasalah,
            'konselingTerbaru'   => $konselingTerbaru,
            'siswaPerhatian'     => $siswaPerhatian,
            'studentRecords'     => $studentRecords,
            'konselingPerTopik'  => $konselingPerTopik,
            'bulan'              => $bulan,
            'tahun'              => $tahun,
        ];

        return view('dashboard/bk/index', $data);
    }
}