<?php $this->extend('layout/main') ?>
<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('dashboard') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('absensi/rekap') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-list w-5"></i><span class="sidebar-text font-semibold text-sm">Rekap Absensi</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-key w-5"></i><span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$totalKonseling    = $totalKonseling    ?? 0;
$totalBermasalah   = $totalBermasalah   ?? 0;
$konselingTerbaru  = $konselingTerbaru  ?? [];
$siswaPerhatian    = $siswaPerhatian    ?? [];
$studentRecords    = $studentRecords    ?? [];
$konselingPerTopik = $konselingPerTopik ?? [];
?>

<!-- Banner -->
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden" data-aos="fade-up"
     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
    <div class="relative z-10">
        <p class="text-white/70 text-sm mb-1">Selamat datang,</p>
        <h1 class="text-2xl font-bold mb-1"><?= esc(session()->get('username')) ?></h1>
        <p class="text-white/80 text-sm"><i class="fas fa-heart mr-2"></i>Bimbingan Konseling — <?= date('l, d F Y') ?></p>
    </div>
    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute -right-4 top-10 w-24 h-24 bg-white/10 rounded-full"></div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="rounded-2xl p-5 text-white shadow-xl" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" data-aos="fade-up">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-comments text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $totalKonseling ?></h3>
        <p class="text-white/80 text-sm">Konseling Bulan Ini</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-exclamation-triangle text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $totalBermasalah ?></h3>
        <p class="text-white/80 text-sm">Siswa Perlu Perhatian</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-clipboard text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= count($studentRecords) ?></h3>
        <p class="text-white/80 text-sm">Catatan Siswa</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-chart-pie text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= count($konselingPerTopik) ?></h3>
        <p class="text-white/80 text-sm">Topik Konseling</p>
    </div>
</div>

<!-- Siswa Perlu Perhatian + Konseling Terbaru -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-500"></i>Siswa Alpa Terbanyak
            </h3>
            <p class="text-gray-400 text-xs mt-0.5">Bulan <?= date('F Y') ?></p>
        </div>
        <?php if (empty($siswaPerhatian)): ?>
        <div class="p-8 text-center text-gray-400"><i class="fas fa-smile text-4xl mb-2 text-gray-200"></i><p class="text-sm">Tidak ada siswa bermasalah</p></div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($siswaPerhatian as $i => $s): ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50">
                <span class="w-6 h-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center font-bold"><?= $i+1 ?></span>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($s['full_name']) ?></p>
                    <p class="text-gray-400 text-xs"><?= $s['grade'] ?> <?= $s['major_name'] ?> <?= $s['class_group'] ?> • NIS: <?= $s['nis'] ?></p>
                </div>
                <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full"><?= $s['total_alpa'] ?>x Alpa</span>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-comments" style="color:var(--color-primary)"></i>Konseling Terbaru
            </h3>
        </div>
        <?php if (empty($konselingTerbaru)): ?>
        <div class="p-8 text-center text-gray-400"><p class="text-sm">Belum ada sesi konseling</p></div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($konselingTerbaru as $k): ?>
            <div class="px-5 py-3.5 hover:bg-gray-50">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm"><?= esc($k['nama_siswa']) ?></p>
                        <p class="text-gray-500 text-xs mt-0.5"><?= esc($k['topic'] ?? '-') ?></p>
                        <p class="text-gray-300 text-xs mt-1"><?= date('d M Y', strtotime($k['date'])) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Catatan Siswa -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-clipboard-list" style="color:var(--color-primary)"></i>Catatan & Rekam Jejak Siswa
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" class="text-white">
                    <th class="px-5 py-3 text-left text-sm font-bold">Siswa</th>
                    <th class="px-5 py-3 text-left text-sm font-bold">Aktivitas</th>
                    <th class="px-5 py-3 text-left text-sm font-bold">Keterangan</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($studentRecords)): ?>
                <tr><td colspan="4" class="text-center py-8 text-gray-400">Belum ada catatan</td></tr>
                <?php else: ?>
                <?php foreach ($studentRecords as $r): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-semibold text-gray-800 text-sm"><?= esc($r['nama_siswa']) ?></td>
                    <td class="px-5 py-3 text-sm text-gray-600"><?= esc($r['activity']) ?></td>
                    <td class="px-5 py-3 text-sm text-gray-500 max-w-xs truncate"><?= esc($r['description'] ?? '-') ?></td>
                    <td class="px-5 py-3 text-center text-xs text-gray-400"><?= date('d M Y', strtotime($r['date'])) ?></td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->endSection() ?>