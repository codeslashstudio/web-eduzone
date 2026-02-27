<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('dashboard/tu') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('keuangan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-money-bill-wave w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Keuangan</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chart-line w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Laporan Akademik</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-key w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<style>
    .stat-card { transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-8px); }
</style>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-graduate text-3xl"></i>
            </div>
            <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalSiswa ?? 0 ?></h3>
        <p class="text-green-100 text-sm font-medium">Total Siswa</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('siswa') ?>" class="text-sm hover:underline flex items-center">
                Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
            <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalGuru ?? 0 ?></h3>
        <p class="text-teal-100 text-sm font-medium">Total Guru</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('guru') ?>" class="text-sm hover:underline flex items-center">
                Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-envelope text-3xl"></i>
            </div>
            <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Baru</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalSuratMasuk ?? 0 ?></h3>
        <p class="text-cyan-100 text-sm font-medium">Surat Masuk</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="#" class="text-sm hover:underline flex items-center">
                Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="400">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-3xl"></i>
            </div>
            <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalStaff ?? 0 ?></h3>
        <p class="text-emerald-100 text-sm font-medium">Total Staff</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('laporan') ?>" class="text-sm hover:underline flex items-center">
                Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Left: Quick Actions + Aktivitas -->
    <div class="lg:col-span-2 space-y-8">

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-500 mr-3"></i>
                Quick Actions
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?= base_url('siswa/add') ?>" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-all group">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 text-center">Tambah Siswa</span>
                </a>
                <a href="<?= base_url('guru/add') ?>" class="flex flex-col items-center justify-center p-4 bg-teal-50 rounded-xl hover:bg-teal-100 transition-all group">
                    <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tie text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 text-center">Tambah Guru</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-cyan-50 rounded-xl hover:bg-cyan-100 transition-all group">
                    <div class="w-12 h-12 bg-cyan-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-envelope-open-text text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 text-center">Buat Surat</span>
                </a>
                <a href="<?= base_url('laporan') ?>" class="flex flex-col items-center justify-center p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-all group">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 text-center">Laporan</span>
                </a>
            </div>
        </div>

        <!-- Aktivitas Terkini -->
        <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-history text-green-500 mr-3"></i>
                Aktivitas Terkini
            </h2>
            <div class="space-y-4">
                <?php if (!empty($recentActivities)): ?>
                    <?php foreach ($recentActivities as $activity): ?>
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-history text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-sm"><?= esc($activity['activity']) ?></p>
                            <p class="text-sm text-gray-600"><?= esc($activity['description']) ?></p>
                            <p class="text-xs text-gray-400 mt-1"><?= esc($activity['created_at']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php
                    $placeholders = [
                        ['icon' => 'fa-user-plus', 'color' => 'green',  'title' => 'Siswa Baru Terdaftar',    'desc' => 'Ahmad Fauzi berhasil terdaftar di kelas X IPA 1',              'time' => '2 jam yang lalu'],
                        ['icon' => 'fa-envelope',  'color' => 'teal',   'title' => 'Surat Masuk Baru',        'desc' => 'Surat undangan rapat dari Dinas Pendidikan',                   'time' => '4 jam yang lalu'],
                        ['icon' => 'fa-file-alt',  'color' => 'cyan',   'title' => 'Dokumen Diupload',        'desc' => 'Laporan kehadiran siswa bulan Januari telah diupload',         'time' => '1 hari yang lalu'],
                    ];
                    foreach ($placeholders as $p):
                    ?>
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all">
                        <div class="w-10 h-10 bg-<?= $p['color'] ?>-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas <?= $p['icon'] ?> text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-sm"><?= $p['title'] ?></p>
                            <p class="text-sm text-gray-600"><?= $p['desc'] ?></p>
                            <p class="text-xs text-gray-400 mt-1"><?= $p['time'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right: Tugas Pending + Statistik -->
    <div class="space-y-8">

        <!-- Tugas Pending -->
        <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-tasks text-teal-500 mr-3"></i>
                Tugas Pending
            </h2>
            <div class="space-y-4">
                <div class="p-4 bg-green-50 rounded-xl border-l-4 border-green-500">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-bold text-gray-900 text-sm">Verifikasi Data Siswa</h3>
                        <span class="text-xs bg-green-500 text-white px-2 py-0.5 rounded-full ml-2 flex-shrink-0">Urgent</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">15 data siswa baru perlu diverifikasi</p>
                    <p class="text-xs text-gray-400"><i class="fas fa-clock mr-1"></i>Deadline: Hari ini</p>
                </div>
                <div class="p-4 bg-teal-50 rounded-xl border-l-4 border-teal-500">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-bold text-gray-900 text-sm">Update Absensi</h3>
                        <span class="text-xs bg-teal-500 text-white px-2 py-0.5 rounded-full ml-2 flex-shrink-0">Normal</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Input absensi minggu ini</p>
                    <p class="text-xs text-gray-400"><i class="fas fa-clock mr-1"></i>Deadline: 2 hari lagi</p>
                </div>
                <div class="p-4 bg-cyan-50 rounded-xl border-l-4 border-cyan-500">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-bold text-gray-900 text-sm">Arsip Dokumen</h3>
                        <span class="text-xs bg-cyan-500 text-white px-2 py-0.5 rounded-full ml-2 flex-shrink-0">Low</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Mengarsipkan dokumen semester lalu</p>
                    <p class="text-xs text-gray-400"><i class="fas fa-clock mr-1"></i>Deadline: Minggu depan</p>
                </div>
            </div>
        </div>

        <!-- Statistik Cepat — warna dari CSS variable -->
        <div class="rounded-2xl shadow-lg p-6 text-white" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));" data-aos="fade-up" data-aos-delay="300">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i class="fas fa-chart-pie mr-3"></i>
                Statistik Cepat
            </h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                    <span class="text-sm">Kehadiran Hari Ini</span>
                    <span class="font-bold text-lg"><?= $kehadiranHariIni ?? '0%' ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                    <span class="text-sm">Total Jurusan</span>
                    <span class="font-bold text-lg"><?= $totalJurusan ?? 0 ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                    <span class="text-sm">Total Pengumuman</span>
                    <span class="font-bold text-lg"><?= $totalAnnouncements ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>