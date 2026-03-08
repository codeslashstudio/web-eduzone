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
// DASHBOARD
// ==============================
$routes->get('dashboard',           'Dashboard::index',     ['filter' => 'auth']);
$routes->get('dashboard/kepsek',    'Dashboard::kepsek',    ['filter' => 'auth']);
$routes->get('dashboard/tu',        'Dashboard::tu',        ['filter' => 'auth']);
$routes->get('dashboard/kurikulum', 'Dashboard::kurikulum', ['filter' => 'auth']);
$routes->get('dashboard/guru',      'Dashboard::guru',      ['filter' => 'auth']); // guru_mapel
$routes->get('dashboard/wakel',     'Dashboard::wakel',     ['filter' => 'auth']); // wali_kelas
$routes->get('dashboard/kesiswaan', 'Dashboard::kesiswaan', ['filter' => 'auth']);
$routes->get('dashboard/bk',        'Dashboard::bk',        ['filter' => 'auth']);
$routes->get('dashboard/toolman',   'Dashboard::toolman',   ['filter' => 'auth']);
$routes->get('dashboard/siswa',     'Dashboard::siswa',     ['filter' => 'auth']);
// superadmin → redirect ke dashboard utama
$routes->get('dashboard/superadmin', static fn() => redirect()->to('/dashboard'), ['filter' => 'auth']);

// ==============================
// DATA SISWA (DataSiswa controller)
// ==============================
$routes->group('siswa', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'DataSiswa::index');
    $routes->get('add',            'DataSiswa::add');
    $routes->post('store',         'DataSiswa::store');
    $routes->get('detail/(:num)',  'DataSiswa::detail/$1');
    $routes->get('edit/(:num)',    'DataSiswa::edit/$1');
    $routes->post('update/(:num)', 'DataSiswa::update/$1');
    $routes->post('delete/(:num)', 'DataSiswa::delete/$1');
});

// ==============================
// JADWAL PELAJARAN
// ==============================
$routes->group('jadwal', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                'Jadwal::index');
    $routes->get('guru/(:num)',      'Jadwal::guru/$1');
    $routes->get('kelas/(:num)',     'Jadwal::kelas/$1');
    $routes->get('cetak/(:num)',     'Jadwal::cetak/$1');
    $routes->get('add',              'Jadwal::add');
    $routes->post('store',           'Jadwal::store');
    $routes->get('edit/(:num)',      'Jadwal::edit/$1');
    $routes->post('update/(:num)',   'Jadwal::update/$1');
    $routes->post('delete/(:num)',   'Jadwal::delete/$1');
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
    $routes->post('bos/store',                   'Keuangan::bosStore');
    $routes->get('persetujuan',                  'Keuangan::persetujuan');
    $routes->post('pengajuan/store',             'Keuangan::pengajuanStore');
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
// ACCOUNT
// ==============================
$routes->get('password',         'Account::password',       ['filter' => 'auth']);
$routes->post('password/update', 'Account::updatePassword', ['filter' => 'auth']);
$routes->get('pengaturan',       'Account::pengaturan',     ['filter' => 'auth']);

// Pengumuman
$routes->group('pengumuman', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Pengumuman::index');
    $routes->get('detail/(:num)', 'Pengumuman::detail/$1');
    $routes->get('add',            'Pengumuman::add');
    $routes->post('store',         'Pengumuman::store');
    $routes->get('edit/(:num)',    'Pengumuman::edit/$1');
    $routes->post('update/(:num)', 'Pengumuman::update/$1');
    $routes->post('delete/(:num)', 'Pengumuman::delete/$1');
});

// Manajemen User (superadmin only)
$routes->group('users', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                    'Users::index');
    $routes->get('add',                  'Users::add');
    $routes->post('store',               'Users::store');
    $routes->get('edit/(:num)',          'Users::edit/$1');
    $routes->post('update/(:num)',       'Users::update/$1');
    $routes->post('toggle/(:num)',       'Users::toggleStatus/$1');
    $routes->post('reset-password/(:num)', 'Users::resetPassword/$1');
    $routes->post('delete/(:num)',       'Users::delete/$1');
});

// Info Sekolah
$routes->get('sekolah',         'Sekolah::index',  ['filter' => 'auth']);
$routes->get('sekolah/edit',    'Sekolah::edit',   ['filter' => 'auth']);
$routes->post('sekolah/update', 'Sekolah::update', ['filter' => 'auth']);

// Jurnal Mengajar
$routes->group('jurnal', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Jurnal::index');
    $routes->get('add',            'Jurnal::add');
    $routes->post('store',         'Jurnal::store');
    $routes->get('edit/(:num)',    'Jurnal::edit/$1');
    $routes->post('update/(:num)', 'Jurnal::update/$1');
    $routes->post('delete/(:num)', 'Jurnal::delete/$1');
});

// Prestasi Siswa
$routes->group('prestasi', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Prestasi::index');
    $routes->get('add',            'Prestasi::add');
    $routes->post('store',         'Prestasi::store');
    $routes->get('edit/(:num)',    'Prestasi::edit/$1');
    $routes->post('update/(:num)', 'Prestasi::update/$1');
    $routes->post('delete/(:num)', 'Prestasi::delete/$1');
    $routes->get('search-siswa',   'Prestasi::searchSiswa');
});

// Catatan Siswa
$routes->group('catatan-siswa', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'CatatanSiswa::index');
    $routes->get('add',            'CatatanSiswa::add');
    $routes->post('store',         'CatatanSiswa::store');
    $routes->get('edit/(:num)',    'CatatanSiswa::edit/$1');
    $routes->post('update/(:num)', 'CatatanSiswa::update/$1');
    $routes->post('delete/(:num)', 'CatatanSiswa::delete/$1');
    $routes->get('search-siswa',   'CatatanSiswa::searchSiswa');
});

// Konseling BK
$routes->group('konseling', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Konseling::index');
    $routes->get('add',            'Konseling::add');
    $routes->post('store',         'Konseling::store');
    $routes->get('(:num)',         'Konseling::detail/$1');
    $routes->get('edit/(:num)',    'Konseling::edit/$1');
    $routes->post('update/(:num)', 'Konseling::update/$1');
    $routes->post('delete/(:num)', 'Konseling::delete/$1');
    $routes->get('search-siswa',   'Konseling::searchSiswa');
});

// Inventaris
$routes->group('inventaris', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Inventaris::index');
    $routes->get('add',            'Inventaris::add');
    $routes->post('store',         'Inventaris::store');
    $routes->get('edit/(:num)',    'Inventaris::edit/$1');
    $routes->post('update/(:num)', 'Inventaris::update/$1');
    $routes->post('delete/(:num)', 'Inventaris::delete/$1');
});

// Lab
$routes->group('lab', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',              'Lab::index');
    $routes->get('add',            'Lab::add');
    $routes->post('store',         'Lab::store');
    $routes->post('approve/(:num)','Lab::approve/$1');
    $routes->post('reject/(:num)', 'Lab::reject/$1');
    $routes->post('delete/(:num)', 'Lab::delete/$1');
    $routes->post('visit/(:num)',  'Lab::storeVisit/$1');
});

// Ujian & Soal
$routes->group('ujian', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                                   'Ujian::index');
    $routes->get('add',                                 'Ujian::add');
    $routes->post('store',                              'Ujian::store');
    $routes->get('(:num)',                              'Ujian::detail/$1');
    $routes->get('edit/(:num)',                         'Ujian::edit/$1');
    $routes->post('update/(:num)',                      'Ujian::update/$1');
    $routes->post('delete/(:num)',                      'Ujian::delete/$1');
    // Soal
    $routes->get('(:num)/soal/add',                    'Ujian::addSoal/$1');
    $routes->post('(:num)/soal/store',                  'Ujian::storeSoal/$1');
    $routes->get('(:num)/soal/edit/(:num)',             'Ujian::editSoal/$1/$2');
    $routes->post('(:num)/soal/update/(:num)',          'Ujian::updateSoal/$1/$2');
    $routes->post('(:num)/soal/delete/(:num)',          'Ujian::deleteSoal/$1/$2');
});

// Master Data — Kelas & Jurusan
$routes->group('master', ['filter' => 'auth'], function ($routes) {
    // Kelas
    $routes->get('kelas',                  'MasterData::kelas');
    $routes->get('kelas/add',              'MasterData::kelasAdd');
    $routes->post('kelas/store',           'MasterData::kelasStore');
    $routes->get('kelas/edit/(:num)',      'MasterData::kelasEdit/$1');
    $routes->post('kelas/update/(:num)',   'MasterData::kelasUpdate/$1');
    $routes->post('kelas/toggle/(:num)',   'MasterData::kelasToggle/$1');
    $routes->post('kelas/delete/(:num)',   'MasterData::kelasDelete/$1');
    // Jurusan
    $routes->get('jurusan',                'MasterData::jurusan');
    $routes->post('jurusan/store',         'MasterData::jurusanStore');
    $routes->post('jurusan/update/(:num)', 'MasterData::jurusanUpdate/$1');
    $routes->post('jurusan/toggle/(:num)', 'MasterData::jurusanToggle/$1');
    $routes->post('jurusan/delete/(:num)', 'MasterData::jurusanDelete/$1');
});

// =============================================
// NILAI & RAPOR
// =============================================
$routes->group('nilai', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                        'Nilai::index');
    $routes->get('kelas/(:num)',             'Nilai::kelas/$1');
    $routes->get('input/(:num)',             'Nilai::inputMapel/$1');
    $routes->post('store/(:num)',            'Nilai::storeMapel/$1');
    $routes->get('sikap/(:num)',             'Nilai::inputSikap/$1');
    $routes->post('store-sikap/(:num)',      'Nilai::storeSikap/$1');
    $routes->post('finalize/(:num)',         'Nilai::finalize/$1');
    $routes->get('rapor',                    'Nilai::rapor');
    $routes->get('rapor/(:num)',             'Nilai::rapor/$1');
});
