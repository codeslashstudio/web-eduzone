<?php

namespace App\Controllers;

use App\Models\KeuanganModel;

class KepsekKeuangan extends BaseController
{
    protected $keuanganModel;

    public function __construct()
    {
        $this->keuanganModel = new KeuanganModel();
        helper(['form', 'url']);
    }

    // ==========================================
    // DASHBOARD KEUANGAN
    // ==========================================
    public function index()
    {
        $this->checkRole('kepsek');

        $filters = [
            'tahun_ajaran' => $this->request->getGet('tahun_ajaran') ?? '2025/2026',
            'start_date' => $this->request->getGet('start_date') ?? date('Y-m-01'),
            'end_date' => $this->request->getGet('end_date') ?? date('Y-m-d')
        ];

        $stats = $this->keuanganModel->getDashboardStats($filters);

        $data = [
            'username' => session()->get('fullname'),
            'stats' => $stats,
            'filters' => $filters
        ];

        return view('Kepsek/keuangan/dashboard', $data);
    }

    // ==========================================
    // PEMASUKAN
    // ==========================================
    public function pemasukan()
    {
        $this->checkRole('kepsek');

        $filters = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'kategori' => $this->request->getGet('kategori'),
            'status' => $this->request->getGet('status')
        ];

        $data = [
            'username' => session()->get('fullname'),
            'pemasukan' => $this->keuanganModel->getPemasukan($filters),
            'kategori' => $this->keuanganModel->getKategoriPemasukan(),
            'total' => $this->keuanganModel->getTotalPemasukan($filters)
        ];

        return view('Kepsek/keuangan/pemasukan/index', $data);
    }

    public function pemasukanAdd()
    {
        $this->checkRole('kepsek');

        $data = [
            'username' => session()->get('fullname'),
            'kategori' => $this->keuanganModel->getKategoriPemasukan()
        ];

        return view('Kepsek/keuangan/pemasukan/add', $data);
    }

    public function pemasukanStore()
    {
        $this->checkRole('kepsek');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'tanggal_transaksi' => 'required',
            'id_kategori' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required|decimal',
            'metode_pembayaran' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $no_transaksi = $this->keuanganModel->generateNoTransaksi('IN');

        $data = [
            'no_transaksi' => $no_transaksi,
            'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'keterangan' => $this->request->getPost('keterangan'),
            'sumber' => $this->request->getPost('sumber'),
            'jumlah' => $this->request->getPost('jumlah'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'no_bukti' => $this->request->getPost('no_bukti'),
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil',
            'status' => 'Verified',
            'user_id' => session()->get('id') ?? session()->get('user_id') ?? 1,
            'created_by' => session()->get('id') ?? session()->get('user_id') ?? 1,
            'verified_by' => session()->get('id') ?? session()->get('user_id') ?? 1,
            'verified_at' => date('Y-m-d H:i:s')
        ];

        $file = $this->request->getFile('file_bukti');
        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/keuangan/bukti', $newName);
            $data['file_bukti'] = $newName;
        }

        if ($this->keuanganModel->insertPemasukan($data)) {
            $this->keuanganModel->logAudit([
                'tabel' => 'transaksi_pemasukan',
                'id_record' => $this->keuanganModel->db->insertID(),
                'aksi' => 'CREATE',
                'data_baru' => json_encode($data),
                'user_id' => session()->get('id') ?? session()->get('user_id') ?? 1,
                'ip_address' => $this->request->getIPAddress()
            ]);

            return redirect()->to(base_url('kepsek/keuangan/pemasukan'))->with('success', 'Pemasukan berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pemasukan');
    }

    // ==========================================
    // PENGELUARAN
    // ==========================================
    public function pengeluaran()
    {
        $this->checkRole('kepsek');

        $filters = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'kategori' => $this->request->getGet('kategori'),
            'status' => $this->request->getGet('status')
        ];

        $data = [
            'username' => session()->get('fullname'),
            'pengeluaran' => $this->keuanganModel->getPengeluaran($filters),
            'kategori' => $this->keuanganModel->getKategoriPengeluaran(),
            'total' => $this->keuanganModel->getTotalPengeluaran($filters)
        ];

        return view('Kepsek/keuangan/pengeluaran/index', $data);
    }

    public function pengeluaranAdd()
    {
        $this->checkRole('kepsek');

        $data = [
            'username' => session()->get('fullname'),
            'kategori' => $this->keuanganModel->getKategoriPengeluaran()
        ];

        return view('Kepsek/keuangan/pengeluaran/add', $data);
    }

    public function pengeluaranStore()
    {
        $this->checkRole('kepsek');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'tanggal_transaksi' => 'required',
            'id_kategori' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required|decimal',
            'metode_pembayaran' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $no_transaksi = $this->keuanganModel->generateNoTransaksi('OUT');

        $data = [
            'no_transaksi' => $no_transaksi,
            'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'keterangan' => $this->request->getPost('keterangan'),
            'tujuan' => $this->request->getPost('tujuan'),
            'jumlah' => $this->request->getPost('jumlah'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'no_bukti' => $this->request->getPost('no_bukti'),
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil',
            'is_from_bos' => $this->request->getPost('is_from_bos') ?? 0,
            'status' => 'Paid',
            'user_id' => session()->get('id') ?? session()->get('user_id') ?? 1,
            'created_by' => session()->get('id') ?? session()->get('user_id') ?? 1
        ];

        $file = $this->request->getFile('file_bukti');
        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/keuangan/bukti', $newName);
            $data['file_bukti'] = $newName;
        }

        if ($this->keuanganModel->insertPengeluaran($data)) {
            $this->keuanganModel->logAudit([
                'tabel' => 'transaksi_pengeluaran',
                'id_record' => $this->keuanganModel->db->insertID(),
                'aksi' => 'CREATE',
                'data_baru' => json_encode($data),
                'user_id' => session()->get('id') ?? session()->get('user_id') ?? 1,
                'ip_address' => $this->request->getIPAddress()
            ]);

            return redirect()->to(base_url('kepsek/keuangan/pengeluaran'))->with('success', 'Pengeluaran berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pengeluaran');
    }

    // ==========================================
    // DANA BOS/BOP
    // ==========================================
    public function bos()
    {
        $this->checkRole('kepsek');

        $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';

        $data = [
            'username' => session()->get('fullname'),
            'dana_bos' => $this->keuanganModel->getDanaBOS($tahun_ajaran)
        ];

        return view('Kepsek/keuangan/bos/index', $data);
    }

    // ==========================================
    // PERSETUJUAN ANGGARAN
    // ==========================================
    public function persetujuan()
    {
        $this->checkRole('kepsek');

        $status = $this->request->getGet('status') ?? 'Pending';

        $data = [
            'username' => session()->get('fullname'),
            'pengajuan' => $this->keuanganModel->getPengajuan($status)
        ];

        return view('Kepsek/keuangan/persetujuan/index', $data);
    }

    public function persetujuanApprove($id)
    {
        $this->checkRole('kepsek');

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back()->with('error', 'Method not allowed');
        }

        $catatan = $this->request->getPost('catatan');

        if ($this->keuanganModel->updateStatusPengajuan($id, 'Approved', $catatan, session()->get('id'))) {
            $this->keuanganModel->logAudit([
                'tabel' => 'pengajuan_anggaran',
                'id_record' => $id,
                'aksi' => 'APPROVE',
                'data_baru' => json_encode(['status' => 'Approved', 'catatan' => $catatan]),
                'user_id' => session()->get('id') ?? session()->get('user_id') ?? 1,
                'ip_address' => $this->request->getIPAddress()
            ]);

            return redirect()->back()->with('success', 'Pengajuan berhasil disetujui');
        }

        return redirect()->back()->with('error', 'Gagal menyetujui pengajuan');
    }

    public function persetujuanReject($id)
    {
        $this->checkRole('kepsek');

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back()->with('error', 'Method not allowed');
        }

        $catatan = $this->request->getPost('catatan');

        if ($this->keuanganModel->updateStatusPengajuan($id, 'Rejected', $catatan, session()->get('id'))) {
            $this->keuanganModel->logAudit([
                'tabel' => 'pengajuan_anggaran',
                'id_record' => $id,
                'aksi' => 'REJECT',
                'data_baru' => json_encode(['status' => 'Rejected', 'catatan' => $catatan]),
                'user_id' => session()->get('id') ?? session()->get('user_id') ?? 1,
                'ip_address' => $this->request->getIPAddress()
            ]);

            return redirect()->back()->with('success', 'Pengajuan berhasil ditolak');
        }

        return redirect()->back()->with('error', 'Gagal menolak pengajuan');
    }

    // ==========================================
    // AUDIT & RIWAYAT
    // ==========================================
    public function audit()
    {
        $this->checkRole('kepsek');

        $filters = [
            'tabel' => $this->request->getGet('tabel'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date')
        ];

        $data = [
            'username' => session()->get('fullname'),
            'audit_log' => $this->keuanganModel->getAuditLog($filters)
        ];

        return view('Kepsek/keuangan/audit/index', $data);
    }

    // ==========================================
    // CETAK LAPORAN
    // ==========================================
    public function cetak()
    {
        $this->checkRole('kepsek');

        $jenis = $this->request->getGet('jenis') ?? 'ringkasan';
        $periode = [
            'start_date' => $this->request->getGet('start_date') ?? date('Y-m-01'),
            'end_date' => $this->request->getGet('end_date') ?? date('Y-m-d')
        ];

        $data = [
            'jenis' => $jenis,
            'periode' => $periode,
            'pemasukan' => $this->keuanganModel->getPemasukan($periode),
            'pengeluaran' => $this->keuanganModel->getPengeluaran($periode),
            'stats' => $this->keuanganModel->getDashboardStats($periode)
        ];

        return view('Kepsek/keuangan/cetak', $data);
    }

    // ==========================================
    // CHECK USER ROLE
    // ==========================================
    private function checkRole($role)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (session()->get('selectedRole') !== $role && session()->get('role') !== $role) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }
    }
}
