<?php

namespace App\Controllers;

class Lab extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    // Yang bisa booking: semua guru + superadmin
    private array $bookingRoles = ['superadmin', 'guru_mapel', 'wali_kelas', 'kurikulum', 'toolman'];
    // Yang bisa approve: toolman + superadmin + tu
    private array $approveRoles = ['superadmin', 'toolman', 'tu'];
    // Yang bisa lihat semua: tambah kepsek
    private array $viewRoles    = ['superadmin', 'toolman', 'tu', 'kepsek', 'guru_mapel', 'wali_kelas', 'kurikulum'];

    private function authCheck(string $need = 'view'): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        $allowed = match($need) {
            'book'    => $this->bookingRoles,
            'approve' => $this->approveRoles,
            default   => $this->viewRoles,
        };
        if (!in_array($role, $allowed)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    private function getTeacherId(): ?int
    {
        $row = $this->db()->query(
            "SELECT id FROM teachers WHERE user_id = ? LIMIT 1",
            [session()->get('user_id')]
        )->getRowArray();
        return $row ? (int)$row['id'] : null;
    }

    // ==============================
    // INDEX — daftar peminjaman
    // ==============================
    public function index()
    {
        $this->authCheck('view');
        $db        = $this->db();
        $role      = session()->get('role');
        $teacherId = $this->getTeacherId();

        $status  = $this->request->getGet('status') ?? '';
        $bulan   = $this->request->getGet('bulan')  ?? date('m');
        $tahun   = $this->request->getGet('tahun')  ?? date('Y');
        $lab     = $this->request->getGet('lab')    ?? '';

        $sql = "
            SELECT lb.*, t.full_name AS guru_name,
                   COALESCE(lv.student_count, 0) AS student_count,
                   lv.activity
            FROM lab_bookings lb
            LEFT JOIN teachers t ON t.id = lb.teacher_id
            LEFT JOIN lab_visits lv ON lv.lab_booking_id = lb.id
            WHERE MONTH(lb.date) = ? AND YEAR(lb.date) = ?
        ";
        $params = [(int)$bulan, (int)$tahun];

        // Guru hanya lihat booking sendiri
        if (in_array($role, ['guru_mapel', 'wali_kelas', 'kurikulum']) && $teacherId) {
            $sql .= " AND lb.teacher_id = ?";
            $params[] = $teacherId;
        }

        if ($status) { $sql .= " AND lb.status = ?"; $params[] = $status; }
        if ($lab)    { $sql .= " AND lb.lab_name LIKE ?"; $params[] = "%$lab%"; }

        $sql .= " ORDER BY lb.date DESC, lb.start_time ASC";
        $list = $db->query($sql, $params)->getResultArray();

        // Stats bulan ini
        $stats = $db->query("
            SELECT
                COUNT(*) total,
                SUM(CASE WHEN status='Menunggu'  THEN 1 ELSE 0 END) menunggu,
                SUM(CASE WHEN status='Disetujui' THEN 1 ELSE 0 END) disetujui,
                SUM(CASE WHEN status='Ditolak'   THEN 1 ELSE 0 END) ditolak
            FROM lab_bookings
            WHERE MONTH(date) = ? AND YEAR(date) = ?
        ", [(int)$bulan, (int)$tahun])->getRowArray();

        // Nama lab unik
        $labList = $db->query("SELECT DISTINCT lab_name FROM lab_bookings ORDER BY lab_name")->getResultArray();

        return view('lab/index', [
            'title'   => 'Peminjaman Lab',
            'list'    => $list,
            'stats'   => $stats,
            'labList' => $labList,
            'status'  => $status,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
            'lab'     => $lab,
            'canBook'    => in_array($role, $this->bookingRoles),
            'canApprove' => in_array($role, $this->approveRoles),
        ]);
    }

    // ==============================
    // ADD — form booking
    // ==============================
    public function add()
    {
        $this->authCheck('book');
        return view('lab/form', [
            'title'     => 'Ajukan Peminjaman Lab',
            'mode'      => 'add',
            'item'      => [],
            'teacherId' => $this->getTeacherId(),
        ]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store()
    {
        $this->authCheck('book');
        $teacherId = $this->request->getPost('teacher_id') ?: $this->getTeacherId();

        // Cek konflik jadwal
        $konflik = $this->db()->query("
            SELECT id FROM lab_bookings
            WHERE lab_name = ? AND date = ? AND status != 'Ditolak'
              AND (
                (start_time < ? AND end_time > ?) OR
                (start_time < ? AND end_time > ?) OR
                (start_time >= ? AND end_time <= ?)
              )
        ", [
            $this->request->getPost('lab_name'),
            $this->request->getPost('date'),
            $this->request->getPost('end_time'),   $this->request->getPost('start_time'),
            $this->request->getPost('end_time'),   $this->request->getPost('start_time'),
            $this->request->getPost('start_time'), $this->request->getPost('end_time'),
        ])->getRowArray();

        if ($konflik) {
            return redirect()->back()->withInput()->with('error', 'Lab sudah dibooking pada jam tersebut');
        }

        $this->db()->table('lab_bookings')->insert([
            'teacher_id'   => $teacherId,
            'date'         => $this->request->getPost('date'),
            'start_time'   => $this->request->getPost('start_time'),
            'end_time'     => $this->request->getPost('end_time'),
            'lab_name'     => $this->request->getPost('lab_name'),
            'purpose'      => $this->request->getPost('purpose'),
            'status'       => 'Menunggu',
            'reviewed_by'  => null,
        ]);

        return redirect()->to(base_url('lab'))->with('success', 'Peminjaman berhasil diajukan');
    }

    // ==============================
    // APPROVE
    // ==============================
    public function approve($id)
    {
        $this->authCheck('approve');
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('lab'));

        $this->db()->table('lab_bookings')->update([
            'status'      => 'Disetujui',
            'reviewed_by' => session()->get('user_id'),
        ], ['id' => $id]);

        return redirect()->to(base_url('lab'))->with('success', 'Peminjaman disetujui');
    }

    // ==============================
    // REJECT
    // ==============================
    public function reject($id)
    {
        $this->authCheck('approve');
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('lab'));

        $this->db()->table('lab_bookings')->update([
            'status'      => 'Ditolak',
            'reviewed_by' => session()->get('user_id'),
        ], ['id' => $id]);

        return redirect()->to(base_url('lab'))->with('success', 'Peminjaman ditolak');
    }

    // ==============================
    // DELETE
    // ==============================
    public function delete($id)
    {
        $this->authCheck('book');
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('lab'));

        // Hanya bisa hapus booking sendiri yang masih Menunggu, kecuali admin
        $role = session()->get('role');
        $booking = $this->db()->query("SELECT * FROM lab_bookings WHERE id = ?", [$id])->getRowArray();

        if (!$booking) return redirect()->to(base_url('lab'))->with('error', 'Data tidak ditemukan');

        if (!in_array($role, $this->approveRoles)) {
            $teacherId = $this->getTeacherId();
            if ($booking['teacher_id'] != $teacherId || $booking['status'] !== 'Menunggu') {
                return redirect()->to(base_url('lab'))->with('error', 'Tidak dapat menghapus booking ini');
            }
        }

        $this->db()->table('lab_bookings')->delete(['id' => $id]);
        return redirect()->to(base_url('lab'))->with('success', 'Peminjaman berhasil dihapus');
    }

    // ==============================
    // LAPORAN KUNJUNGAN (toolman input)
    // ==============================
    public function storeVisit($bookingId)
    {
        $this->authCheck('approve');

        $this->db()->table('lab_visits')->insert([
            'lab_booking_id' => $bookingId,
            'student_count'  => $this->request->getPost('student_count'),
            'activity'       => $this->request->getPost('activity'),
        ]);

        return redirect()->to(base_url('lab'))->with('success', 'Laporan kunjungan disimpan');
    }
}