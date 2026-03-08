<?php
/**
 * SIDEBAR MENU — EduZone
 * Di-include dari layout/main.php
 * Usage: <?php include view('layout/sidebar') ?>
 * Atau di main.php: $this->include('layout/sidebar')
 *
 * Variabel yang dibutuhkan (dari session):
 * - role        : string
 * - color_primary / color_secondary : dari session (sudah di main.php)
 */

$role        = session()->get('role') ?? '';
$currentUrl  = current_url();

// Helper: apakah URL aktif
function isActive(string $path): string {
    $base = base_url($path);
    return str_starts_with(current_url(), $base) ? 'active' : '';
}
?>

<?php if ($role === 'kepsek'): ?>
<!-- ==============================
     KEPALA SEKOLAH
     ============================== -->
<a href="<?= base_url('dashboard/kepsek') ?>" class="menu-item <?= isActive('dashboard/kepsek') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Data Sekolah</p>
</div>
<a href="<?= base_url('siswa') ?>" class="menu-item <?= isActive('siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item <?= isActive('guru') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chalkboard-teacher w-5"></i><span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('jadwal') ?>" class="menu-item <?= isActive('jadwal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kehadiran</p>
</div>
<a href="<?= base_url('absensi') ?>" class="menu-item <?= isActive('absensi') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-check w-5"></i><span class="sidebar-text font-semibold text-sm">Rekap Absensi</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keuangan & Laporan</p>
</div>
<a href="<?= base_url('keuangan') ?>" class="menu-item <?= isActive('keuangan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-money-bill-wave w-5"></i><span class="sidebar-text font-semibold text-sm">Keuangan</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item <?= isActive('laporan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chart-line w-5"></i><span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<a href="<?= base_url('pengumuman') ?>" class="menu-item <?= isActive('pengumuman') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-bullhorn w-5"></i><span class="sidebar-text font-semibold text-sm">Pengumuman</span>
</a>

<?php elseif ($role === 'tu'): ?>
<!-- ==============================
     TATA USAHA
     ============================== -->
<a href="<?= base_url('dashboard/tu') ?>" class="menu-item <?= isActive('dashboard/tu') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Data Master</p>
</div>
<a href="<?= base_url('siswa') ?>" class="menu-item <?= isActive('siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item <?= isActive('guru') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chalkboard-teacher w-5"></i><span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('jadwal') ?>" class="menu-item <?= isActive('jadwal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Absensi</p>
</div>
<a href="<?= base_url('absensi/harian') ?>" class="menu-item <?= isActive('absensi/harian') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-check w-5"></i><span class="sidebar-text font-semibold text-sm">Absensi Harian</span>
</a>
<a href="<?= base_url('absensi/rekap') ?>" class="menu-item <?= isActive('absensi/rekap') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-table w-5"></i><span class="sidebar-text font-semibold text-sm">Rekap Absensi</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keuangan</p>
</div>
<a href="<?= base_url('keuangan') ?>" class="menu-item <?= isActive('keuangan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-tachometer-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Ringkasan Keuangan</span>
</a>
<a href="<?= base_url('keuangan/pemasukan') ?>" class="menu-item <?= isActive('keuangan/pemasukan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-circle-down w-5"></i><span class="sidebar-text font-semibold text-sm">Pemasukan</span>
</a>
<a href="<?= base_url('keuangan/pengeluaran') ?>" class="menu-item <?= isActive('keuangan/pengeluaran') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-circle-up w-5"></i><span class="sidebar-text font-semibold text-sm">Pengeluaran</span>
</a>
<a href="<?= base_url('keuangan/bos') ?>" class="menu-item <?= isActive('keuangan/bos') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-university w-5"></i><span class="sidebar-text font-semibold text-sm">Dana BOS</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item <?= isActive('laporan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chart-line w-5"></i><span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<a href="<?= base_url('sekolah') ?>" class="menu-item <?= isActive('sekolah') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-school w-5"></i><span class="sidebar-text font-semibold text-sm">Info Sekolah</span>
</a>

<?php elseif ($role === 'kurikulum'): ?>
<!-- ==============================
     KURIKULUM
     ============================== -->
<a href="<?= base_url('dashboard/kurikulum') ?>" class="menu-item <?= isActive('dashboard/kurikulum') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Akademik</p>
</div>
<a href="<?= base_url('jadwal') ?>" class="menu-item <?= isActive('jadwal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item <?= isActive('guru') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chalkboard-teacher w-5"></i><span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('jurnal') ?>" class="menu-item <?= isActive('jurnal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-book-open w-5"></i><span class="sidebar-text font-semibold text-sm">Jurnal Mengajar</span>
</a>
<a href="<?= base_url('ujian') ?>" class="menu-item <?= isActive('ujian') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-file-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Ujian</span>
</a>
<a href="<?= base_url('nilai') ?>" class="menu-item <?= isActive('nilai') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-star-half-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Nilai & Rapor</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Monitoring</p>
</div>
<a href="<?= base_url('absensi/rekap') ?>" class="menu-item <?= isActive('absensi/rekap') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-check w-5"></i><span class="sidebar-text font-semibold text-sm">Rekap Absensi</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item <?= isActive('laporan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chart-bar w-5"></i><span class="sidebar-text font-semibold text-sm">Laporan Akademik</span>
</a>

<?php elseif ($role === 'guru_mapel'): ?>
<!-- ==============================
     GURU MATA PELAJARAN
     ============================== -->
<a href="<?= base_url('dashboard/guru') ?>" class="menu-item <?= isActive('dashboard/guru') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mengajar</p>
</div>
<a href="<?= base_url('jadwal') ?>" class="menu-item <?= isActive('jadwal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Jadwal Saya</span>
</a>
<a href="<?= base_url('absensi-mapel') ?>" class="menu-item <?= isActive('absensi-mapel') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-list w-5"></i><span class="sidebar-text font-semibold text-sm">Absensi Per JP</span>
</a>
<a href="<?= base_url('absensi-mapel/rekap') ?>" class="menu-item <?= isActive('absensi-mapel/rekap') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chart-bar w-5"></i><span class="sidebar-text font-semibold text-sm">Rekap Absensi</span>
</a>
<a href="<?= base_url('jurnal') ?>" class="menu-item <?= isActive('jurnal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-book-open w-5"></i><span class="sidebar-text font-semibold text-sm">Jurnal Mengajar</span>
</a>
<a href="<?= base_url('ujian') ?>" class="menu-item <?= isActive('ujian') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-file-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Ujian Saya</span>
</a>
<a href="<?= base_url('nilai') ?>" class="menu-item <?= isActive('nilai') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-star-half-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Input Nilai</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Siswa</p>
</div>
<a href="<?= base_url('siswa') ?>" class="menu-item <?= isActive('siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Siswa Yang Diajar</span>
</a>

<?php elseif ($role === 'wali_kelas'): ?>
<!-- ==============================
     WALI KELAS
     ============================== -->
<a href="<?= base_url('dashboard/wakel') ?>" class="menu-item <?= isActive('dashboard/wakel') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kelas Saya</p>
</div>
<a href="<?= base_url('siswa') ?>" class="menu-item <?= isActive('siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Data Siswa Kelas</span>
</a>
<a href="<?= base_url('jadwal') ?>" class="menu-item <?= isActive('jadwal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Jadwal Kelas</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Absensi</p>
</div>
<a href="<?= base_url('absensi/harian') ?>" class="menu-item <?= isActive('absensi/harian') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-check w-5"></i><span class="sidebar-text font-semibold text-sm">Input Absensi Harian</span>
</a>
<a href="<?= base_url('absensi/rekap') ?>" class="menu-item <?= isActive('absensi/rekap') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-table w-5"></i><span class="sidebar-text font-semibold text-sm">Rekap Bulanan</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Catatan Siswa</p>
</div>
<a href="<?= base_url('catatan-siswa') ?>" class="menu-item <?= isActive('catatan-siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-sticky-note w-5"></i><span class="sidebar-text font-semibold text-sm">Catatan & Pelanggaran</span>
</a>
<a href="<?= base_url('prestasi') ?>" class="menu-item <?= isActive('prestasi') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-trophy w-5"></i><span class="sidebar-text font-semibold text-sm">Prestasi Siswa</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nilai</p>
</div>
<a href="<?= base_url('nilai') ?>" class="menu-item <?= isActive('nilai') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-star-half-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Nilai & Rapor</span>
</a>

<?php elseif ($role === 'kesiswaan'): ?>
<!-- ==============================
     KESISWAAN
     ============================== -->
<a href="<?= base_url('dashboard/kesiswaan') ?>" class="menu-item <?= isActive('dashboard/kesiswaan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Data Siswa</p>
</div>
<a href="<?= base_url('siswa') ?>" class="menu-item <?= isActive('siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('prestasi') ?>" class="menu-item <?= isActive('prestasi') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-trophy w-5"></i><span class="sidebar-text font-semibold text-sm">Prestasi Siswa</span>
</a>
<a href="<?= base_url('catatan-siswa') ?>" class="menu-item <?= isActive('catatan-siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-exclamation-triangle w-5"></i><span class="sidebar-text font-semibold text-sm">Catatan Pelanggaran</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Monitoring</p>
</div>
<a href="<?= base_url('absensi/rekap') ?>" class="menu-item <?= isActive('absensi/rekap') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-check w-5"></i><span class="sidebar-text font-semibold text-sm">Rekap Absensi</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item <?= isActive('laporan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chart-bar w-5"></i><span class="sidebar-text font-semibold text-sm">Laporan Kesiswaan</span>
</a>
<a href="<?= base_url('pengumuman') ?>" class="menu-item <?= isActive('pengumuman') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-bullhorn w-5"></i><span class="sidebar-text font-semibold text-sm">Pengumuman</span>
</a>

<?php elseif ($role === 'bk'): ?>
<!-- ==============================
     BIMBINGAN KONSELING
     ============================== -->
<a href="<?= base_url('dashboard/bk') ?>" class="menu-item <?= isActive('dashboard/bk') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Konseling</p>
</div>
<a href="<?= base_url('konseling') ?>" class="menu-item <?= isActive('konseling') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-comments w-5"></i><span class="sidebar-text font-semibold text-sm">Sesi Konseling</span>
</a>
<a href="<?= base_url('catatan-siswa') ?>" class="menu-item <?= isActive('catatan-siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-sticky-note w-5"></i><span class="sidebar-text font-semibold text-sm">Catatan Siswa</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Monitoring</p>
</div>
<a href="<?= base_url('siswa') ?>" class="menu-item <?= isActive('siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('catatan-siswa') ?>" class="menu-item <?= isActive('catatan-siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-times w-5"></i><span class="sidebar-text font-semibold text-sm">Siswa Bermasalah</span>
</a>

<?php elseif ($role === 'toolman'): ?>
<!-- ==============================
     TOOLMAN / LABORAN
     ============================== -->
<a href="<?= base_url('dashboard/toolman') ?>" class="menu-item <?= isActive('dashboard/toolman') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Inventaris</p>
</div>
<a href="<?= base_url('inventaris') ?>" class="menu-item <?= isActive('inventaris') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-boxes w-5"></i><span class="sidebar-text font-semibold text-sm">Data Inventaris</span>
</a>
<a href="<?= base_url('inventaris?condition=Rusak+Berat') ?>" class="menu-item <?= isActive('inventaris') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-tools w-5"></i><span class="sidebar-text font-semibold text-sm">Laporan Kerusakan</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lab</p>
</div>
<a href="<?= base_url('lab') ?>" class="menu-item <?= isActive('lab') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-flask w-5"></i><span class="sidebar-text font-semibold text-sm">Peminjaman Lab</span>
</a>
<a href="<?= base_url('lab') ?>" class="menu-item <?= isActive('lab') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-door-open w-5"></i><span class="sidebar-text font-semibold text-sm">Kunjungan Lab</span>
</a>

<?php elseif ($role === 'siswa'): ?>
<!-- ==============================
     SISWA
     ============================== -->
<a href="<?= base_url('dashboard/siswa') ?>" class="menu-item <?= isActive('dashboard/siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Akademik</p>
</div>
<a href="<?= base_url('jadwal') ?>" class="menu-item <?= isActive('jadwal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<a href="<?= base_url('absensi/rekap') ?>" class="menu-item <?= isActive('absensi/rekap') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-check w-5"></i><span class="sidebar-text font-semibold text-sm">Absensi Saya</span>
</a>
<a href="<?= base_url('ujian') ?>" class="menu-item <?= isActive('ujian') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-file-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Jadwal Ujian</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Informasi</p>
</div>
<a href="<?= base_url('pengumuman') ?>" class="menu-item <?= isActive('pengumuman') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-bullhorn w-5"></i><span class="sidebar-text font-semibold text-sm">Pengumuman</span>
</a>
<a href="<?= base_url('prestasi') ?>" class="menu-item <?= isActive('prestasi') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-trophy w-5"></i><span class="sidebar-text font-semibold text-sm">Prestasi Saya</span>
</a>
<a href="<?= base_url('nilai/rapor') ?>" class="menu-item <?= isActive('nilai') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-file-invoice w-5"></i><span class="sidebar-text font-semibold text-sm">Rapor Saya</span>
</a>

<?php elseif ($role === 'superadmin'): ?>
<!-- ==============================
     SUPERADMIN — akses semua
     ============================== -->
<a href="<?= base_url('dashboard') ?>" class="menu-item <?= isActive('dashboard') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Data Master</p>
</div>
<a href="<?= base_url('siswa') ?>" class="menu-item <?= isActive('siswa') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item <?= isActive('guru') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chalkboard-teacher w-5"></i><span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('jadwal') ?>" class="menu-item <?= isActive('jadwal') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Operasional</p>
</div>
<a href="<?= base_url('absensi') ?>" class="menu-item <?= isActive('absensi') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-check w-5"></i><span class="sidebar-text font-semibold text-sm">Absensi</span>
</a>
<a href="<?= base_url('keuangan') ?>" class="menu-item <?= isActive('keuangan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-money-bill-wave w-5"></i><span class="sidebar-text font-semibold text-sm">Keuangan</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item <?= isActive('laporan') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chart-line w-5"></i><span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<div class="sidebar-text px-4 pt-4 pb-1">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Administrasi</p>
</div>
<a href="<?= base_url('users') ?>" class="menu-item <?= isActive('users') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-users-cog w-5"></i><span class="sidebar-text font-semibold text-sm">Manajemen User</span>
</a>
<a href="<?= base_url('sekolah') ?>" class="menu-item <?= isActive('sekolah') ?> flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-school w-5"></i><span class="sidebar-text font-semibold text-sm">Info Sekolah</span>
</a>
<?php endif ?>

<!-- Divider + Account Settings (semua role) -->
<div class="mt-4 pt-4 border-t border-gray-100">
    <a href="<?= base_url('password') ?>" class="menu-item <?= isActive('password') ?> flex items-center space-x-3 px-4 py-3 rounded-xl mb-1">
        <i class="fas fa-key w-5"></i><span class="sidebar-text font-semibold text-sm">Ubah Password</span>
    </a>
    <a href="<?= base_url('auth/logout') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50">
        <i class="fas fa-sign-out-alt w-5"></i><span class="sidebar-text font-semibold text-sm">Logout</span>
    </a>
</div>