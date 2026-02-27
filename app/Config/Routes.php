<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==============================
// HOME
// ==============================
$routes->get('/', 'Home::index');

// ==============================
// AUTH
// ==============================
$routes->get('auth/login',    'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('auth/logout',   'Auth::logout');

$routes->get('login',  static fn() => redirect()->to('/auth/login'));
$routes->get('logout', static fn() => redirect()->to('/auth/logout'));

// ==============================
// DASHBOARD — semua pakai Dashboard controller
// superadmin bisa akses semua route dashboard
// ==============================
$routes->get('dashboard',           'Dashboard::index',     ['filter' => 'auth']);
$routes->get('dashboard/kepsek',    'Dashboard::kepsek',    ['filter' => 'auth']);
$routes->get('dashboard/tu',        'Dashboard::tu',        ['filter' => 'auth']);
$routes->get('dashboard/kurikulum', 'Dashboard::kurikulum', ['filter' => 'auth']);
$routes->get('dashboard/guru',      'Dashboard::guru',      ['filter' => 'auth']);
$routes->get('dashboard/wakel',     'Dashboard::wakel',     ['filter' => 'auth']);
$routes->get('dashboard/kesiswaan', 'Dashboard::kesiswaan', ['filter' => 'auth']);
$routes->get('dashboard/bk',        'Dashboard::bk',        ['filter' => 'auth']);
$routes->get('dashboard/toolman',   'Dashboard::toolman',   ['filter' => 'auth']);
$routes->get('dashboard/siswa',     'Dashboard::siswa',     ['filter' => 'auth']);

// ==============================
// SISWA
// ==============================
$routes->group('siswa', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Siswa::index');
    $routes->get('add',            'Siswa::add');
    $routes->post('store',         'Siswa::store');
    $routes->get('detail/(:num)',  'Siswa::detail/$1');
    $routes->get('edit/(:num)',    'Siswa::edit/$1');
    $routes->post('update/(:num)', 'Siswa::update/$1');
    $routes->post('delete/(:num)', 'Siswa::delete/$1');
});

// ==============================
// GURU
// ==============================
$routes->group('guru', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Guru::index');
    $routes->get('add',            'Guru::add');
    $routes->post('store',         'Guru::store');
    $routes->get('detail/(:num)',  'Guru::detail/$1');
    $routes->get('edit/(:num)',    'Guru::edit/$1');
    $routes->post('update/(:num)', 'Guru::update/$1');
    $routes->post('delete/(:num)', 'Guru::delete/$1');
});

// ==============================
// KEUANGAN
// ==============================
$routes->group('keuangan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                            'Keuangan::index');
    $routes->get('pemasukan',                    'Keuangan::pemasukan');
    $routes->get('pemasukan/add',                'Keuangan::pemasukanAdd');
    $routes->post('pemasukan/store',             'Keuangan::pemasukanStore');
    $routes->get('pemasukan/edit/(:num)',        'Keuangan::pemasukanEdit/$1');
    $routes->post('pemasukan/update/(:num)',     'Keuangan::pemasukanUpdate/$1');
    $routes->post('pemasukan/delete/(:num)',     'Keuangan::pemasukanDelete/$1');
    $routes->get('pengeluaran',                  'Keuangan::pengeluaran');
    $routes->get('pengeluaran/add',              'Keuangan::pengeluaranAdd');
    $routes->post('pengeluaran/store',           'Keuangan::pengeluaranStore');
    $routes->get('pengeluaran/edit/(:num)',      'Keuangan::pengeluaranEdit/$1');
    $routes->post('pengeluaran/update/(:num)',   'Keuangan::pengeluaranUpdate/$1');
    $routes->post('pengeluaran/delete/(:num)',   'Keuangan::pengeluaranDelete/$1');
    $routes->get('bos',                          'Keuangan::bos');
    $routes->get('bos/detail/(:num)',            'Keuangan::bosDetail/$1');
    $routes->get('persetujuan',                  'Keuangan::persetujuan');
    $routes->post('persetujuan/approve/(:num)', 'Keuangan::persetujuanApprove/$1');
    $routes->post('persetujuan/reject/(:num)',  'Keuangan::persetujuanReject/$1');
    $routes->get('audit',                        'Keuangan::audit');
    $routes->get('audit/detail/(:num)',          'Keuangan::auditDetail/$1');
    $routes->get('cetak',                        'Keuangan::cetak');
    $routes->get('export-excel',                 'Keuangan::exportExcel');
    $routes->get('export-pdf',                   'Keuangan::exportPdf');
});

// ==============================
// LAPORAN
// ==============================
$routes->group('laporan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Laporan::index');
    $routes->get('detail/(:any)', 'Laporan::detail/$1');
    $routes->get('export-excel',  'Laporan::exportExcel');
    $routes->get('export-pdf',    'Laporan::exportPdf');
});

// ==============================
// ABSENSI
// ==============================
$routes->group('absensi', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',             'Absensi::index');
    $routes->get('harian',        'Absensi::harian');
    $routes->post('harian/store', 'Absensi::harianStore');
    $routes->get('mapel',         'Absensi::mapel');
    $routes->get('mapel/(:num)',  'Absensi::mapelInput/$1');
    $routes->post('mapel/store',  'Absensi::mapelStore');
    $routes->get('rekap',         'Absensi::rekap');
    $routes->get('export',        'Absensi::exportExcel');
});

// ==============================
// PRESENSI (legacy)
// ==============================
$routes->get('presensi', 'Presensi::index', ['filter' => 'auth']);

// ==============================
// ACCOUNT
// ==============================
$routes->get('password',         'Account::password',       ['filter' => 'auth']);
$routes->post('password/update', 'Account::updatePassword', ['filter' => 'auth']);
$routes->get('pengaturan',       'Account::pengaturan',     ['filter' => 'auth']);