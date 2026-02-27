<?php
namespace App\Controllers;

class Toolman extends BaseController
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
        if (session()->get('role') !== 'toolman') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        $userId = session()->get('user_id');

        // Ambil staff_id
        $staff = $this->db->table('staff')->where('user_id', $userId)->get()->getRowArray();
        $staffId = $staff['id'] ?? null;

        // Statistik inventaris
        $invStat = $this->db->query("
            SELECT
                COUNT(*) AS total_item,
                SUM(quantity) AS total_unit,
                SUM(condition = 'Baik') AS kondisi_baik,
                SUM(condition = 'Rusak Ringan') AS rusak_ringan,
                SUM(condition = 'Rusak Berat') AS rusak_berat
            FROM inventory
        ")->getRowArray();

        // Daftar inventaris
        $inventaris = $this->db->table('inventory')
            ->orderBy('condition', 'ASC')
            ->orderBy('item_name', 'ASC')
            ->get()->getResultArray();

        // Laporan toolman terbaru
        $laporanTerbaru = $staffId ? $this->db->query("
            SELECT tr.*, st.full_name AS nama_staff
            FROM toolman_reports tr
            JOIN staff st ON tr.staff_id = st.id
            ORDER BY tr.date DESC LIMIT 7
        ")->getResultArray() : [];

        // Lab bookings hari ini
        $labBookings = $this->db->query("
            SELECT lb.*, t.full_name AS nama_guru
            FROM lab_bookings lb
            LEFT JOIN teachers t ON lb.teacher_id = t.id
            WHERE lb.date = CURDATE()
            ORDER BY lb.start_time ASC
        ")->getResultArray();

        // Lab visits terbaru
        $labVisits = $this->db->query("
            SELECT lv.*, t.full_name AS nama_guru
            FROM lab_visits lv
            LEFT JOIN teachers t ON lv.teacher_id = t.id
            ORDER BY lv.visit_date DESC LIMIT 5
        ")->getResultArray();

        // Item rusak perlu perbaikan
        $itemRusak = $this->db->query("
            SELECT * FROM inventory
            WHERE condition != 'Baik'
            ORDER BY condition DESC, item_name ASC
        ")->getResultArray();

        $data = [
            'title'          => 'Dashboard Toolman',
            'staff'          => $staff,
            'invStat'        => $invStat,
            'inventaris'     => $inventaris,
            'laporanTerbaru' => $laporanTerbaru,
            'labBookings'    => $labBookings,
            'labVisits'      => $labVisits,
            'itemRusak'      => $itemRusak,
        ];

        return view('dashboard/toolman/index', $data);
    }
}