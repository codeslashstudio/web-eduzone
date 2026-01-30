<?php

namespace App\Controllers;

use App\Models\LaporanModel;

class KepsekLaporan extends BaseController
{
    protected $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        helper(['form', 'url']);
    }

    // ==============================
    // TAMPILKAN LAPORAN AKADEMIK
    // ==============================
    public function index()
    {
        $this->checkRole('kepsek');

        // Get filter parameters
        $tahunAjaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'Ganjil';

        // Get laporan data from model
        $laporan = $this->laporanModel->getLaporanAkademik($tahunAjaran, $semester);

        $data = [
            'username' => session()->get('fullname'),
            'laporan' => $laporan,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester
        ];

        return view('Kepsek/laporan/index', $data);
    }

    // ==============================
    // DETAIL LAPORAN PER KELAS
    // ==============================
    public function detail($kelas)
    {
        $this->checkRole('kepsek');

        // Parse kelas parameter (e.g., "X-IPA-1" -> kelas: X, jurusan: IPA 1)
        $kelasArray = explode('-', $kelas);
        $kelasNama = $kelasArray[0];
        $jurusan = isset($kelasArray[1]) && isset($kelasArray[2])
            ? $kelasArray[1] . ' ' . $kelasArray[2]
            : '';

        // Get detailed data
        $detail = $this->laporanModel->getDetailKelas($kelasNama, $jurusan);

        if (!$detail) {
            return redirect()->to(base_url('kepsek/laporan'))->with('error', 'Data kelas tidak ditemukan');
        }

        $data = [
            'username' => session()->get('fullname'),
            'detail' => $detail,
            'kelas' => $kelasNama,
            'jurusan' => $jurusan
        ];

        return view('Kepsek/laporan/detail', $data);
    }

    // ==============================
    // EXPORT TO EXCEL
    // ==============================
    public function exportExcel()
    {
        $this->checkRole('kepsek');

        // Get filter parameters
        $tahunAjaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'Ganjil';

        // Get data
        $laporan = $this->laporanModel->getLaporanAkademik($tahunAjaran, $semester);

        // Return as JSON for client-side Excel generation
        // Or use PhpSpreadsheet for server-side generation
        return $this->response->setJSON([
            'success' => true,
            'data' => $laporan,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester
        ]);
    }

    // ==============================
    // EXPORT TO PDF
    // ==============================
    public function exportPdf()
    {
        $this->checkRole('kepsek');

        // Get filter parameters
        $tahunAjaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'Ganjil';

        // Get data
        $laporan = $this->laporanModel->getLaporanAkademik($tahunAjaran, $semester);

        $data = [
            'laporan' => $laporan,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'tanggal_cetak' => date('d F Y')
        ];

        // Load view for PDF
        return view('Kepsek/laporan/pdf', $data);
    }

    // ==============================
    // CHECK USER ROLE
    // ==============================
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
