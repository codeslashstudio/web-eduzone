<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('dashboard/kepsek') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5 menu-icon"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5 menu-icon"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5 menu-icon"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('keuangan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-money-bill-wave w-5 menu-icon"></i>
    <span class="sidebar-text font-semibold text-sm">Keuangan</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chart-line w-5 menu-icon"></i>
    <span class="sidebar-text font-semibold text-sm">Laporan Akademik</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-key w-5 menu-icon"></i>
    <span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<?php
// Set title untuk header di main.php
$title    = 'Dashboard Kepala Sekolah';
$subtitle = 'Selamat datang kembali, ' . esc(session()->get('username')) . '!';
?>

<style>
    .stat-card { transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-8px); }
</style>

<!-- ============================================================
     STATISTICS CARDS
     ============================================================ -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Total Siswa -->
    <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-graduate text-3xl"></i>
            </div>
            <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalSiswa ?? 0 ?></h3>
        <p class="text-blue-100 text-sm font-medium">Total Siswa</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('siswa') ?>" class="text-sm hover:underline flex items-center">
                Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Total Guru -->
    <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
            <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalGuru ?? 0 ?></h3>
        <p class="text-purple-100 text-sm font-medium">Total Guru</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('guru') ?>" class="text-sm hover:underline flex items-center">
                Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Total Staff -->
    <div class="stat-card bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-3xl"></i>
            </div>
            <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalStaff ?? 0 ?></h3>
        <p class="text-pink-100 text-sm font-medium">Total Staff</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('siswa') ?>" class="text-sm hover:underline flex items-center">
                Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Total Jurusan -->
    <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="400">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-book-open text-3xl"></i>
            </div>
            <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalJurusan ?? 0 ?></h3>
        <p class="text-green-100 text-sm font-medium">Total Jurusan</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('laporan') ?>" class="text-sm hover:underline flex items-center">
                Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- ============================================================
     MAIN GRID
     ============================================================ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- LEFT: Quick Actions + Aktivitas -->
    <div class="lg:col-span-2 space-y-8">

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-500 mr-3"></i>
                Quick Actions
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?= base_url('laporan') ?>"
                   class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-all group">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 text-center">Buat Laporan</span>
                </a>
                <a href="<?= base_url('siswa') ?>"
                   class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-all group">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-graduate text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 text-center">Data Siswa</span>
                </a>
                <a href="<?= base_url('guru') ?>"
                   class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-all group">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 text-center">Data Guru</span>
                </a>
                <a href="<?= base_url('keuangan') ?>"
                   class="flex flex-col items-center justify-center p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition-all group">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-money-bill-wave text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 text-center">Keuangan</span>
                </a>
            </div>
        </div>

        <!-- Aktivitas Terkini -->
        <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-history text-blue-500 mr-3"></i>
                Aktivitas Terkini
            </h2>
            <div class="space-y-4">
                <?php if (!empty($recentActivities)): ?>
                    <?php foreach ($recentActivities as $activity): ?>
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
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
                    <!-- Placeholder aktivitas -->
                    <?php
                    $placeholders = [
                        ['icon' => 'fa-user-plus',   'color' => 'blue',   'title' => 'Siswa Baru Terdaftar',        'desc' => 'Ahmad Fauzi berhasil terdaftar di kelas X IPA 1',              'time' => '2 jam yang lalu'],
                        ['icon' => 'fa-file-upload',  'color' => 'purple', 'title' => 'Laporan Akademik Diupload',   'desc' => 'Guru Matematika mengupload nilai ujian semester',               'time' => '5 jam yang lalu'],
                        ['icon' => 'fa-check-circle', 'color' => 'green',  'title' => 'Pembayaran SPP Diterima',     'desc' => 'Pembayaran SPP bulan Januari dari kelas XI telah dikonfirmasi', 'time' => '1 hari yang lalu'],
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

    <!-- RIGHT: Pengumuman + Statistik -->
    <div class="space-y-8">

        <!-- Pengumuman -->
        <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-purple-500 mr-3"></i>
                Pengumuman Terbaru
            </h2>
            <div class="space-y-4">
                <?php if (!empty($announcements)): ?>
                    <?php
                    $annColors = ['blue', 'purple', 'green', 'orange'];
                    foreach ($announcements as $i => $ann):
                        $c = $annColors[$i % count($annColors)];
                    ?>
                    <div class="p-4 bg-<?= $c ?>-50 rounded-xl border-l-4 border-<?= $c ?>-500">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-gray-900 text-sm"><?= esc($ann['title']) ?></h3>
                            <?php if (!empty($ann['is_important'])): ?>
                                <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full ml-2 flex-shrink-0">Penting</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-600 mb-2"><?= esc(substr($ann['content'], 0, 80)) ?>...</p>
                        <p class="text-xs text-gray-400"><i class="fas fa-clock mr-1"></i><?= esc($ann['published_at']) ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 bg-blue-50 rounded-xl border-l-4 border-blue-500">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-gray-900 text-sm">Ujian Tengah Semester</h3>
                            <span class="text-xs bg-blue-500 text-white px-2 py-0.5 rounded-full ml-2 flex-shrink-0">Minggu Depan</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Pelaksanaan UTS untuk semua kelas</p>
                        <p class="text-xs text-gray-400"><i class="fas fa-clock mr-1"></i>3 - 7 Februari 2026</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-xl border-l-4 border-purple-500">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-gray-900 text-sm">Rapat Guru</h3>
                            <span class="text-xs bg-purple-500 text-white px-2 py-0.5 rounded-full ml-2 flex-shrink-0">Besok</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Evaluasi pembelajaran semester ganjil</p>
                        <p class="text-xs text-gray-400"><i class="fas fa-clock mr-1"></i>28 Januari 2026, 09:00 WIB</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistik Cepat -->
        <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-lg p-6 text-white" data-aos="fade-up" data-aos-delay="300">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i class="fas fa-chart-pie mr-3"></i>
                Statistik Cepat
            </h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                    <span class="text-sm">Kehadiran Hari Ini</span>
                    <span class="font-bold text-lg"><?= $kehadiranHariIni ?? '94.5%' ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                    <span class="text-sm">Total Jurusan</span>
                    <span class="font-bold text-lg"><?= $totalJurusan ?? 0 ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                    <span class="text-sm">Prestasi Siswa</span>
                    <span class="font-bold text-lg"><?= $totalPrestasi ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>