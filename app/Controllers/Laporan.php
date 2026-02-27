<?php

namespace App\Controllers;

use App\Models\LaporanModel;

class Laporan extends BaseController
{
    protected $laporanModel;

    protected $viewRoles = ['kepsek', 'tu', 'superadmin'];

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        helper(['form', 'url']);
    }

    private function authCheck(): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send();
            exit;
        }
        if (!in_array(session()->get('role'), $this->viewRoles)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send();
            exit;
        }
    }

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $this->authCheck();

        $tahunAjaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester    = $this->request->getGet('semester')     ?? 'Ganjil';

        $laporan = $this->laporanModel->getLaporanAkademik($tahunAjaran, $semester);

        // Hitung stats
        $rataRataArr = array_column($laporan, 'rata_rata');
        $tertinggiArr = array_column($laporan, 'tertinggi');

        $data = [
            'title'               => 'Laporan Akademik',
            'laporan'             => $laporan,
            'tahun_ajaran'        => $tahunAjaran,
            'semester'            => $semester,
            'totalKelas'          => count($laporan),
            'totalSiswa'          => array_sum(array_column($laporan, 'jumlah_siswa')),
            'rataRataKeseluruhan' => !empty($rataRataArr) ? array_sum($rataRataArr) / count($rataRataArr) : 0,
            'nilaiTertinggi'      => !empty($tertinggiArr) ? max($tertinggiArr) : 0,
        ];

        return view('laporan/index', $data);
    }

    // ==============================
    // DETAIL PER KELAS
    // ==============================
    public function detail($kelas)
    {
        $this->authCheck();

        // Parse slug: "X-IPA-1" → kelas=X, jurusan=IPA 1
        $parts    = explode('-', $kelas);
        $kelasNama = $parts[0] ?? '';
        $jurusan   = isset($parts[1], $parts[2]) ? $parts[1] . ' ' . $parts[2] : ($parts[1] ?? '');

        $detail = $this->laporanModel->getDetailKelas($kelasNama, $jurusan);

        if (!$detail) {
            return redirect()->to(base_url('laporan'))->with('error', 'Data kelas tidak ditemukan');
        }

        $data = [
            'title'   => 'Detail Laporan ' . $kelasNama . ' ' . $jurusan,
            'detail'  => $detail,
            'kelas'   => $kelasNama,
            'jurusan' => $jurusan,
        ];

        return view('laporan/detail', $data);
    }

    // ==============================
    // EXPORT EXCEL (client-side via JSON)
    // ==============================
    public function exportExcel()
    {
        $this->authCheck();

        $tahunAjaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester    = $this->request->getGet('semester')     ?? 'Ganjil';

        $laporan = $this->laporanModel->getLaporanAkademik($tahunAjaran, $semester);

        return $this->response->setJSON([
            'success'      => true,
            'data'         => $laporan,
            'tahun_ajaran' => $tahunAjaran,
            'semester'     => $semester,
        ]);
    }

    // ==============================
    // EXPORT PDF (print view)
    // ==============================
    public function exportPdf()
    {
        $this->authCheck();

        $tahunAjaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester    = $this->request->getGet('semester')     ?? 'Ganjil';

        $laporan = $this->laporanModel->getLaporanAkademik($tahunAjaran, $semester);

        $data = [
            'title'         => 'Cetak Laporan Akademik',
            'laporan'       => $laporan,
            'tahun_ajaran'  => $tahunAjaran,
            'semester'      => $semester,
            'tanggal_cetak' => date('d F Y'),
        ];

        return view('laporan/pdf', $data);
    }
}