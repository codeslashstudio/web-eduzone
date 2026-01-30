<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');

$routes->get('register', 'Auth::register');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/doRegister', 'Auth::doRegister');

// app/Config/Routes.php

// Dashboard routes
$routes->get('dashboard', 'Dashboard::index');
$routes->get('dashboard/select/(:any)', 'Dashboard::selectRole/$1');

// Role-specific dashboards
$routes->get('dashboard/kepsek', 'Dashboard::kepsek');
$routes->get('dashboard/tu', 'Dashboard::tu');
$routes->get('dashboard/wakel', 'Dashboard::wakel');
$routes->get('dashboard/bk', 'Dashboard::bk');
$routes->get('dashboard/kurikulum', 'Dashboard::kurikulum');
$routes->get('dashboard/guru', 'Dashboard::guru');

// Logout
$routes->get('logout', 'Auth::logout');

// Routes untuk Kepsek - Siswa
$routes->group('kepsek', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::kepsek');
    $routes->get('siswa', 'KepsekSiswa::index');
});

$routes->group('kepsek/siswa', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KepsekSiswa::index');
    $routes->get('add', 'KepsekSiswa::add');
    $routes->post('create', 'KepsekSiswa::create');
    $routes->get('edit/(:num)', 'KepsekSiswa::edit/$1');
    $routes->post('update/(:num)', 'KepsekSiswa::update/$1');
    $routes->post('delete/(:num)', 'KepsekSiswa::delete/$1');
    $routes->get('detail/(:num)', 'KepsekSiswa::detail/$1');
});

// ==============================
// GURU ROUTES (KEPSEK)
// ==============================
$routes->group('kepsek/guru', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Guru::index');                        // Tampilkan daftar guru
    $routes->get('add', 'Guru::add');                        // Form tambah guru
    $routes->post('store', 'Guru::store');                   // Simpan guru baru
    $routes->get('detail/(:num)', 'Guru::detail/$1');        // Detail guru
    $routes->get('edit/(:num)', 'Guru::edit/$1');            // Form edit guru
    $routes->post('update/(:num)', 'Guru::update/$1');       // Update guru
    $routes->get('confirm-delete/(:num)', 'Guru::confirmDelete/$1');  // Halaman konfirmasi hapus
    $routes->post('delete/(:num)', 'Guru::delete/$1');       // Hapus guru (soft delete via POST)
    $routes->post('restore/(:num)', 'Guru::restore/$1');     // Restore guru (optional)
    $routes->post('permanent-delete/(:num)', 'Guru::permanentDelete/$1'); // Hapus permanen (optional)
});


// Routes untuk TU (Tata Usaha) - Dashboard
$routes->get('tu/dashboard', 'Dashboard::tu', ['filter' => 'auth']);

// Routes untuk TU - Siswa
$routes->group('tu/siswa', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TuSiswa::index');
    $routes->get('add', 'TuSiswa::add');
    $routes->post('create', 'TuSiswa::create');
    $routes->get('edit/(:num)', 'TuSiswa::edit/$1');
    $routes->post('update/(:num)', 'TuSiswa::update/$1');
    $routes->post('delete/(:num)', 'TuSiswa::delete/$1');
    $routes->get('detail/(:num)', 'TuSiswa::detail/$1');
});


// ==============================
// TATA USAHA - GURU ROUTES
// ==============================
$routes->group('tu/guru', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TuGuru::index');                    // Tampilkan daftar guru
    $routes->get('add', 'TuGuru::add');                    // Form tambah guru
    $routes->post('store', 'TuGuru::store');               // Simpan guru baru
    $routes->get('detail/(:num)', 'TuGuru::detail/$1');    // Detail guru
    $routes->get('edit/(:num)', 'TuGuru::edit/$1');        // Form edit guru
    $routes->post('update/(:num)', 'TuGuru::update/$1');   // Update guru
    $routes->post('delete/(:num)', 'TuGuru::delete/$1');   // Hapus guru (soft delete via POST)
});

// ==============================
// KEPALA SEKOLAH - GURU ROUTES
// ==============================
$routes->group('kepsek/guru', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Guru::index');                        // Tampilkan daftar guru
    $routes->get('add', 'Guru::add');                        // Form tambah guru
    $routes->post('store', 'Guru::store');                   // Simpan guru baru
    $routes->get('detail/(:num)', 'Guru::detail/$1');        // Detail guru
    $routes->get('edit/(:num)', 'Guru::edit/$1');            // Form edit guru
    $routes->post('update/(:num)', 'Guru::update/$1');       // Update guru
    $routes->get('confirm-delete/(:num)', 'Guru::confirmDelete/$1');  // Halaman konfirmasi hapus
    $routes->post('delete/(:num)', 'Guru::delete/$1');       // Hapus guru (soft delete via POST)
    $routes->post('restore/(:num)', 'Guru::restore/$1');     // Restore guru (optional)
    $routes->post('permanent-delete/(:num)', 'Guru::permanentDelete/$1'); // Hapus permanen (optional)
});


$routes->group('tu/laporan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TuLaporan::index');
    $routes->get('detail/(:segment)', 'TuLaporan::detail/$1');
    $routes->get('export-excel', 'TuLaporan::exportExcel');
    $routes->get('export-pdf', 'TuLaporan::exportPdf');
});

$routes->group('kepsek/laporan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KepsekLaporan::index');
    $routes->get('detail/(:any)', 'KepsekLaporan::detail/$1');
    $routes->get('export-excel', 'KepsekLaporan::exportExcel');
    $routes->get('export-pdf', 'KepsekLaporan::exportPdf');
});

$routes->group('tu/keuangan', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('/', 'TuKeuangan::index');

    // Pemasukan
    $routes->get('pemasukan', 'TuKeuangan::pemasukan');
    $routes->get('pemasukan/add', 'TuKeuangan::pemasukanAdd');
    $routes->post('pemasukan/store', 'TuKeuangan::pemasukanStore');

    // Pengeluaran
    $routes->get('pengeluaran', 'TuKeuangan::pengeluaran');
    $routes->get('pengeluaran/add', 'TuKeuangan::pengeluaranAdd');
    $routes->post('pengeluaran/store', 'TuKeuangan::pengeluaranStore');

    // BOS/BOP
    $routes->get('bos', 'TuKeuangan::bos');

    // Persetujuan
    $routes->get('persetujuan', 'TuKeuangan::persetujuan');
    $routes->post('persetujuan/approve/(:num)', 'TuKeuangan::persetujuanApprove/$1');
    $routes->post('persetujuan/reject/(:num)', 'TuKeuangan::persetujuanReject/$1');

    // Audit
    $routes->get('audit', 'TuKeuangan::audit');

    // Cetak
    $routes->get('cetak', 'TuKeuangan::cetak');
});

// ====================================================
// ROUTES KEUANGAN - KEPALA SEKOLAH
// ====================================================

$routes->group('kepsek/keuangan', ['filter' => 'auth'], function ($routes) {
    // Dashboard Keuangan
    $routes->get('/', 'KepsekKeuangan::index');

    // Pemasukan
    $routes->get('pemasukan', 'KepsekKeuangan::pemasukan');
    $routes->get('pemasukan/add', 'KepsekKeuangan::pemasukanAdd');
    $routes->post('pemasukan/store', 'KepsekKeuangan::pemasukanStore');
    $routes->get('pemasukan/edit/(:num)', 'KepsekKeuangan::pemasukanEdit/$1');
    $routes->post('pemasukan/update/(:num)', 'KepsekKeuangan::pemasukanUpdate/$1');
    $routes->post('pemasukan/delete/(:num)', 'KepsekKeuangan::pemasukanDelete/$1');

    // Pengeluaran
    $routes->get('pengeluaran', 'KepsekKeuangan::pengeluaran');
    $routes->get('pengeluaran/add', 'KepsekKeuangan::pengeluaranAdd');
    $routes->post('pengeluaran/store', 'KepsekKeuangan::pengeluaranStore');
    $routes->get('pengeluaran/edit/(:num)', 'KepsekKeuangan::pengeluaranEdit/$1');
    $routes->post('pengeluaran/update/(:num)', 'KepsekKeuangan::pengeluaranUpdate/$1');
    $routes->post('pengeluaran/delete/(:num)', 'KepsekKeuangan::pengeluaranDelete/$1');

    // Dana BOS/BOP
    $routes->get('bos', 'KepsekKeuangan::bos');
    $routes->get('bos/detail/(:num)', 'KepsekKeuangan::bosDetail/$1');

    // Persetujuan Anggaran
    $routes->get('persetujuan', 'KepsekKeuangan::persetujuan');
    $routes->post('persetujuan/approve/(:num)', 'KepsekKeuangan::persetujuanApprove/$1');
    $routes->post('persetujuan/reject/(:num)', 'KepsekKeuangan::persetujuanReject/$1');

    // Audit & Riwayat
    $routes->get('audit', 'KepsekKeuangan::audit');
    $routes->get('audit/detail/(:num)', 'KepsekKeuangan::auditDetail/$1');

    // Cetak Laporan
    $routes->get('cetak', 'KepsekKeuangan::cetak');
    $routes->get('export-excel', 'KepsekKeuangan::exportExcel');
    $routes->get('export-pdf', 'KepsekKeuangan::exportPdf');
});
