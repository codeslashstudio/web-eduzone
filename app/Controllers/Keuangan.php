<?php

namespace App\Controllers;

use App\Models\KeuanganModel;

class Keuangan extends BaseController
{
    protected $keuanganModel;

    // Role yang boleh VIEW
    protected $viewRoles = ['kepsek', 'tu', 'superadmin'];

    // Role yang boleh CRUD
    protected $editRoles = ['tu', 'superadmin'];

    public function __construct()
    {
        $this->keuanganModel = new KeuanganModel();
        helper(['form', 'url']);
    }

    // ==============================
    // AUTH HELPER
    // ==============================
    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send();
            exit;
        }

        $role = session()->get('role');

        if (!in_array($role, $this->viewRoles)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send();
            exit;
        }

        if ($requireEdit && !in_array($role, $this->editRoles)) {
            redirect()->to(base_url('keuangan'))->with('error', 'Akses ditolak')->send();
            exit;
        }
    }

    private function canEdit(): bool
    {
        return in_array(session()->get('role'), $this->editRoles);
    }

    private function userId(): int
    {
        return session()->get('user_id') ?? 1;
    }

    // ==============================
    // DASHBOARD
    // ==============================
    public function index()
    {
        $this->authCheck();

        $filters = [
            'tahun_ajaran' => $this->request->getGet('tahun_ajaran') ?? '2025/2026',
            'start_date'   => $this->request->getGet('start_date')   ?? date('Y-m-01'),
            'end_date'     => $this->request->getGet('end_date')     ?? date('Y-m-d'),
        ];

        $stats = $this->keuanganModel->getDashboardStats($filters);

        $data = [
            'title'            => 'Dashboard Keuangan',
            'canEdit'          => $this->canEdit(),
            'filters'          => $filters,
            'totalPemasukan'   => $stats['total_pemasukan']   ?? 0,
            'totalPengeluaran' => $stats['total_pengeluaran'] ?? 0,
            'totalBos'         => $stats['total_bos']         ?? 0,
            'transaksiTerbaru' => $stats['transaksi_terbaru'] ?? [],
        ];

        return view('keuangan/index', $data);
    }

    // ==============================
    // PEMASUKAN — index
    // ==============================
    public function pemasukan()
    {
        $this->authCheck();

        $filters = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'kategori'   => $this->request->getGet('kategori'),
            'status'     => $this->request->getGet('status'),
        ];

        $data = [
            'title'     => 'Data Pemasukan',
            'canEdit'   => $this->canEdit(),
            'pemasukan' => $this->keuanganModel->getPemasukan($filters),
            'kategori'  => $this->keuanganModel->getKategoriPemasukan(),
            'total'     => $this->keuanganModel->getTotalPemasukan($filters),
        ];

        return view('keuangan/pemasukan/index', $data);
    }

    // PEMASUKAN — form tambah
    public function pemasukanAdd()
    {
        $this->authCheck(true);

        $data = [
            'title'    => 'Tambah Pemasukan',
            'kategori' => $this->keuanganModel->getKategoriPemasukan(),
        ];

        return view('keuangan/pemasukan/add', $data);
    }

    // PEMASUKAN — simpan
    public function pemasukanStore()
    {
        $this->authCheck(true);

        $rules = [
            'tanggal_transaksi' => ['rules' => 'required',         'errors' => ['required' => 'Tanggal harus diisi']],
            'id_kategori'       => ['rules' => 'required',         'errors' => ['required' => 'Kategori harus dipilih']],
            'keterangan'        => ['rules' => 'required',         'errors' => ['required' => 'Keterangan harus diisi']],
            'jumlah'            => ['rules' => 'required|decimal', 'errors' => ['required' => 'Jumlah harus diisi', 'decimal' => 'Jumlah harus berupa angka']],
            'metode_pembayaran' => ['rules' => 'required',         'errors' => ['required' => 'Metode pembayaran harus dipilih']],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'no_transaksi'      => $this->keuanganModel->generateNoTransaksi('IN'),
            'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi'),
            'id_kategori'       => $this->request->getPost('id_kategori'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'sumber'            => $this->request->getPost('sumber'),
            'jumlah'            => $this->request->getPost('jumlah'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'no_bukti'          => $this->request->getPost('no_bukti'),
            'tahun_ajaran'      => '2025/2026',
            'semester'          => 'Ganjil',
            'status'            => 'Verified',
            'user_id'           => $this->userId(),
            'created_by'        => $this->userId(),
            'verified_by'       => $this->userId(),
            'verified_at'       => date('Y-m-d H:i:s'),
        ];

        $file = $this->request->getFile('file_bukti');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/keuangan/bukti', $newName);
            $saveData['file_bukti'] = $newName;
        }

        if ($this->keuanganModel->insertPemasukan($saveData)) {
            $this->keuanganModel->logAudit([
                'tabel'       => 'transaksi_pemasukan',
                'id_record'   => $this->keuanganModel->db->insertID(),
                'aksi'        => 'CREATE',
                'data_baru'   => json_encode($saveData),
                'user_id'     => $this->userId(),
                'ip_address'  => $this->request->getIPAddress(),
            ]);

            return redirect()->to(base_url('keuangan/pemasukan'))->with('success', 'Pemasukan berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pemasukan');
    }

    // PEMASUKAN — form edit
    public function pemasukanEdit($id)
    {
        $this->authCheck(true);

        $pemasukan = $this->keuanganModel->getPemasukanById($id);
        if (!$pemasukan) {
            return redirect()->to(base_url('keuangan/pemasukan'))->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title'     => 'Edit Pemasukan',
            'pemasukan' => $pemasukan,
            'kategori'  => $this->keuanganModel->getKategoriPemasukan(),
        ];

        return view('keuangan/pemasukan/edit', $data);
    }

    // PEMASUKAN — update
    public function pemasukanUpdate($id)
    {
        $this->authCheck(true);

        $rules = [
            'tanggal_transaksi' => ['rules' => 'required',         'errors' => ['required' => 'Tanggal harus diisi']],
            'id_kategori'       => ['rules' => 'required',         'errors' => ['required' => 'Kategori harus dipilih']],
            'keterangan'        => ['rules' => 'required',         'errors' => ['required' => 'Keterangan harus diisi']],
            'jumlah'            => ['rules' => 'required|decimal', 'errors' => ['required' => 'Jumlah harus diisi']],
            'metode_pembayaran' => ['rules' => 'required',         'errors' => ['required' => 'Metode harus dipilih']],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi'),
            'id_kategori'       => $this->request->getPost('id_kategori'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'sumber'            => $this->request->getPost('sumber'),
            'jumlah'            => $this->request->getPost('jumlah'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'no_bukti'          => $this->request->getPost('no_bukti'),
        ];

        $file = $this->request->getFile('file_bukti');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/keuangan/bukti', $newName);
            $saveData['file_bukti'] = $newName;
        }

        if ($this->keuanganModel->updatePemasukan($id, $saveData)) {
            return redirect()->to(base_url('keuangan/pemasukan'))->with('success', 'Data pemasukan berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data');
    }

    // PEMASUKAN — hapus
    public function pemasukanDelete($id)
    {
        $this->authCheck(true);

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('keuangan/pemasukan'));
        }

        if ($this->keuanganModel->deletePemasukan($id)) {
            return redirect()->to(base_url('keuangan/pemasukan'))->with('success', 'Data pemasukan berhasil dihapus');
        }

        return redirect()->to(base_url('keuangan/pemasukan'))->with('error', 'Gagal menghapus data');
    }

    // ==============================
    // PENGELUARAN — index
    // ==============================
    public function pengeluaran()
    {
        $this->authCheck();

        $filters = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'kategori'   => $this->request->getGet('kategori'),
            'status'     => $this->request->getGet('status'),
        ];

        $data = [
            'title'       => 'Data Pengeluaran',
            'canEdit'     => $this->canEdit(),
            'pengeluaran' => $this->keuanganModel->getPengeluaran($filters),
            'kategori'    => $this->keuanganModel->getKategoriPengeluaran(),
            'total'       => $this->keuanganModel->getTotalPengeluaran($filters),
        ];

        return view('keuangan/pengeluaran/index', $data);
    }

    // PENGELUARAN — form tambah
    public function pengeluaranAdd()
    {
        $this->authCheck(true);

        $data = [
            'title'    => 'Tambah Pengeluaran',
            'kategori' => $this->keuanganModel->getKategoriPengeluaran(),
        ];

        return view('keuangan/pengeluaran/add', $data);
    }

    // PENGELUARAN — simpan
    public function pengeluaranStore()
    {
        $this->authCheck(true);

        $rules = [
            'tanggal_transaksi' => ['rules' => 'required',         'errors' => ['required' => 'Tanggal harus diisi']],
            'id_kategori'       => ['rules' => 'required',         'errors' => ['required' => 'Kategori harus dipilih']],
            'keterangan'        => ['rules' => 'required',         'errors' => ['required' => 'Keterangan harus diisi']],
            'jumlah'            => ['rules' => 'required|decimal', 'errors' => ['required' => 'Jumlah harus diisi', 'decimal' => 'Jumlah harus berupa angka']],
            'metode_pembayaran' => ['rules' => 'required',         'errors' => ['required' => 'Metode pembayaran harus dipilih']],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'no_transaksi'      => $this->keuanganModel->generateNoTransaksi('OUT'),
            'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi'),
            'id_kategori'       => $this->request->getPost('id_kategori'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'tujuan'            => $this->request->getPost('tujuan'),
            'jumlah'            => $this->request->getPost('jumlah'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'no_bukti'          => $this->request->getPost('no_bukti'),
            'tahun_ajaran'      => '2025/2026',
            'semester'          => 'Ganjil',
            'is_from_bos'       => $this->request->getPost('is_from_bos') ?? 0,
            'status'            => 'Paid',
            'user_id'           => $this->userId(),
            'created_by'        => $this->userId(),
        ];

        $file = $this->request->getFile('file_bukti');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/keuangan/bukti', $newName);
            $saveData['file_bukti'] = $newName;
        }

        if ($this->keuanganModel->insertPengeluaran($saveData)) {
            $this->keuanganModel->logAudit([
                'tabel'      => 'transaksi_pengeluaran',
                'id_record'  => $this->keuanganModel->db->insertID(),
                'aksi'       => 'CREATE',
                'data_baru'  => json_encode($saveData),
                'user_id'    => $this->userId(),
                'ip_address' => $this->request->getIPAddress(),
            ]);

            return redirect()->to(base_url('keuangan/pengeluaran'))->with('success', 'Pengeluaran berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pengeluaran');
    }

    // PENGELUARAN — form edit
    public function pengeluaranEdit($id)
    {
        $this->authCheck(true);

        $pengeluaran = $this->keuanganModel->getPengeluaranById($id);
        if (!$pengeluaran) {
            return redirect()->to(base_url('keuangan/pengeluaran'))->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title'       => 'Edit Pengeluaran',
            'pengeluaran' => $pengeluaran,
            'kategori'    => $this->keuanganModel->getKategoriPengeluaran(),
        ];

        return view('keuangan/pengeluaran/edit', $data);
    }

    // PENGELUARAN — update
    public function pengeluaranUpdate($id)
    {
        $this->authCheck(true);

        $rules = [
            'tanggal_transaksi' => ['rules' => 'required',         'errors' => ['required' => 'Tanggal harus diisi']],
            'id_kategori'       => ['rules' => 'required',         'errors' => ['required' => 'Kategori harus dipilih']],
            'keterangan'        => ['rules' => 'required',         'errors' => ['required' => 'Keterangan harus diisi']],
            'jumlah'            => ['rules' => 'required|decimal', 'errors' => ['required' => 'Jumlah harus diisi']],
            'metode_pembayaran' => ['rules' => 'required',         'errors' => ['required' => 'Metode harus dipilih']],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi'),
            'id_kategori'       => $this->request->getPost('id_kategori'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'tujuan'            => $this->request->getPost('tujuan'),
            'jumlah'            => $this->request->getPost('jumlah'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'no_bukti'          => $this->request->getPost('no_bukti'),
            'is_from_bos'       => $this->request->getPost('is_from_bos') ?? 0,
        ];

        $file = $this->request->getFile('file_bukti');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/keuangan/bukti', $newName);
            $saveData['file_bukti'] = $newName;
        }

        if ($this->keuanganModel->updatePengeluaran($id, $saveData)) {
            return redirect()->to(base_url('keuangan/pengeluaran'))->with('success', 'Data pengeluaran berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data');
    }

    // PENGELUARAN — hapus
    public function pengeluaranDelete($id)
    {
        $this->authCheck(true);

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('keuangan/pengeluaran'));
        }

        if ($this->keuanganModel->deletePengeluaran($id)) {
            return redirect()->to(base_url('keuangan/pengeluaran'))->with('success', 'Data pengeluaran berhasil dihapus');
        }

        return redirect()->to(base_url('keuangan/pengeluaran'))->with('error', 'Gagal menghapus data');
    }

    // ==============================
    // DANA BOS/BOP
    // ==============================
    public function bos()
    {
        $this->authCheck();

        $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';

        $data = [
            'title'    => 'Dana BOS/BOP',
            'canEdit'  => $this->canEdit(),
            'dana_bos' => $this->keuanganModel->getDanaBOS($tahun_ajaran),
        ];

        return view('keuangan/bos/index', $data);
    }

    // ==============================
    // PERSETUJUAN ANGGARAN
    // ==============================
    public function persetujuan()
    {
        $this->authCheck();

        $status = $this->request->getGet('status') ?? 'Pending';

        $data = [
            'title'     => 'Persetujuan Anggaran',
            'canEdit'   => $this->canEdit(),
            'pengajuan' => $this->keuanganModel->getPengajuan($status),
            'status'    => $status,
        ];

        return view('keuangan/persetujuan/index', $data);
    }

    public function persetujuanApprove($id)
    {
        $this->authCheck(true);

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back()->with('error', 'Method tidak diizinkan');
        }

        $catatan = $this->request->getPost('catatan');

        if ($this->keuanganModel->updateStatusPengajuan($id, 'Approved', $catatan, $this->userId())) {
            $this->keuanganModel->logAudit([
                'tabel'      => 'pengajuan_anggaran',
                'id_record'  => $id,
                'aksi'       => 'APPROVE',
                'data_baru'  => json_encode(['status' => 'Approved', 'catatan' => $catatan]),
                'user_id'    => $this->userId(),
                'ip_address' => $this->request->getIPAddress(),
            ]);

            return redirect()->back()->with('success', 'Pengajuan berhasil disetujui');
        }

        return redirect()->back()->with('error', 'Gagal menyetujui pengajuan');
    }

    public function persetujuanReject($id)
    {
        $this->authCheck(true);

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back()->with('error', 'Method tidak diizinkan');
        }

        $catatan = $this->request->getPost('catatan');

        if ($this->keuanganModel->updateStatusPengajuan($id, 'Rejected', $catatan, $this->userId())) {
            $this->keuanganModel->logAudit([
                'tabel'      => 'pengajuan_anggaran',
                'id_record'  => $id,
                'aksi'       => 'REJECT',
                'data_baru'  => json_encode(['status' => 'Rejected', 'catatan' => $catatan]),
                'user_id'    => $this->userId(),
                'ip_address' => $this->request->getIPAddress(),
            ]);

            return redirect()->back()->with('success', 'Pengajuan berhasil ditolak');
        }

        return redirect()->back()->with('error', 'Gagal menolak pengajuan');
    }

    // ==============================
    // AUDIT & RIWAYAT
    // ==============================
    public function audit()
    {
        $this->authCheck();

        $filters = [
            'tabel'      => $this->request->getGet('tabel'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
        ];

        $data = [
            'title'     => 'Riwayat Audit',
            'canEdit'   => $this->canEdit(),
            'audit_log' => $this->keuanganModel->getAuditLog($filters),
        ];

        return view('keuangan/audit/index', $data);
    }

    // ==============================
    // BOS DETAIL
    // ==============================
    public function bosDetail($id)
    {
        $this->authCheck();

        $data = [
            'title'   => 'Detail Dana BOS',
            'canEdit' => $this->canEdit(),
            'detail'  => $this->keuanganModel->getDanaBOSById($id),
        ];

        return view('keuangan/bos/detail', $data);
    }

    // ==============================
    // AUDIT DETAIL
    // ==============================
    public function auditDetail($id)
    {
        $this->authCheck();

        $data = [
            'title'  => 'Detail Audit',
            'detail' => $this->keuanganModel->getAuditLogById($id),
        ];

        return view('keuangan/audit/detail', $data);
    }

    // ==============================
    // EXPORT
    // ==============================
    public function exportExcel()
    {
        $this->authCheck();
        // TODO: implementasi export Excel
        return redirect()->to(base_url('keuangan'))->with('error', 'Fitur export Excel belum tersedia');
    }

    public function exportPdf()
    {
        $this->authCheck();
        // TODO: implementasi export PDF
        return redirect()->to(base_url('keuangan'))->with('error', 'Fitur export PDF belum tersedia');
    }

    // ==============================
    // CETAK LAPORAN
    // ==============================
    public function cetak()
    {
        $this->authCheck();

        $jenis   = $this->request->getGet('jenis') ?? 'ringkasan';
        $periode = [
            'start_date' => $this->request->getGet('start_date') ?? date('Y-m-01'),
            'end_date'   => $this->request->getGet('end_date')   ?? date('Y-m-d'),
        ];

        $data = [
            'title'       => 'Cetak Laporan Keuangan',
            'jenis'       => $jenis,
            'periode'     => $periode,
            'pemasukan'   => $this->keuanganModel->getPemasukan($periode),
            'pengeluaran' => $this->keuanganModel->getPengeluaran($periode),
            'stats'       => $this->keuanganModel->getDashboardStats($periode),
        ];

        return view('keuangan/cetak', $data);
    }
}