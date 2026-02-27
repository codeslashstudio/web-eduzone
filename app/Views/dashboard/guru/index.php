<?php $this->extend('layout/main') ?>
<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('dashboard') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('absensi/mapel') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-check w-5"></i><span class="sidebar-text font-semibold text-sm">Absensi Mapel</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-key w-5"></i><span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$teacher        = $teacher        ?? [];
$jadwalHariIni  = $jadwalHariIni  ?? [];
$semuaJadwal    = $semuaJadwal    ?? [];
$absensiStat    = $absensiStat    ?? [];
$jurnalSaya     = $jurnalSaya     ?? [];
$ujianSaya      = $ujianSaya      ?? [];
$totalSiswaAjar = $totalSiswaAjar ?? 0;
$hariIni        = $hariIni        ?? '';
$hadir = $absensiStat['hadir'] ?? 0;
$total = $absensiStat['total'] ?? 0;
$pct   = $total > 0 ? round($hadir / $total * 100, 1) : 0;
?>

<!-- Banner -->
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden" data-aos="fade-up"
     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
    <div class="relative z-10">
        <p class="text-white/70 text-sm mb-1">Selamat datang,</p>
        <h1 class="text-2xl font-bold mb-1"><?= esc($teacher['full_name'] ?? session()->get('username')) ?></h1>
        <p class="text-white/80 text-sm"><i class="fas fa-chalkboard-teacher mr-2"></i>Guru Mata Pelajaran — <?= date('l, d F Y') ?></p>
    </div>
    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute -right-4 top-10 w-24 h-24 bg-white/10 rounded-full"></div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="rounded-2xl p-5 text-white shadow-xl" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" data-aos="fade-up">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-calendar-alt text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= count($semuaJadwal) ?></h3>
        <p class="text-white/80 text-sm">Total Jadwal</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-clock text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= count($jadwalHariIni) ?></h3>
        <p class="text-white/80 text-sm">Jadwal Hari Ini</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-user-graduate text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $totalSiswaAjar ?></h3>
        <p class="text-white/80 text-sm">Siswa Diajar</p>
    </div>
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-check-circle text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $pct ?>%</h3>
        <p class="text-white/80 text-sm">Kehadiran Saya</p>
    </div>
</div>

<!-- Jadwal Hari Ini + Ujian -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-clock" style="color:var(--color-primary)"></i>Jadwal Hari Ini — <?= $hariIni ?>
            </h3>
        </div>
        <?php if (empty($jadwalHariIni)): ?>
        <div class="p-10 text-center text-gray-400">
            <i class="fas fa-coffee text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Tidak ada jadwal hari ini</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($jadwalHariIni as $j): ?>
            <div class="px-5 py-4 flex items-center gap-3 hover:bg-gray-50">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: linear-gradient(135deg, var(--color-primary)22, var(--color-secondary)22)">
                    <i class="fas fa-book text-sm" style="color:var(--color-primary)"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-800 text-sm"><?= esc($j['subject']) ?></p>
                    <p class="text-gray-400 text-xs">Kelas <?= $j['grade'] ?> <?= $j['major'] ?> <?= $j['class_group'] ?> • <?= esc($j['room'] ?? '') ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold" style="color:var(--color-primary)"><?= date('H:i', strtotime($j['start_time'])) ?>–<?= date('H:i', strtotime($j['end_time'])) ?></p>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <div class="p-4 border-t border-gray-100">
            <a href="<?= base_url('absensi/mapel?date=' . date('Y-m-d')) ?>" class="btn-primary w-full text-center py-2 rounded-xl text-sm font-bold flex items-center justify-center gap-2">
                <i class="fas fa-clipboard-check"></i>Input Absensi Mapel
            </a>
        </div>
        <?php endif ?>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-file-alt text-orange-500"></i>Ujian yang Saya Awasi
            </h3>
        </div>
        <?php if (empty($ujianSaya)): ?>
        <div class="p-10 text-center text-gray-400">
            <i class="fas fa-check text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Tidak ada ujian mendatang</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($ujianSaya as $u): ?>
            <div class="px-5 py-4 flex items-center gap-3 hover:bg-gray-50">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-orange-500 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-800 text-sm"><?= esc($u['name']) ?></p>
                    <p class="text-gray-400 text-xs"><?= $u['grade'] ?> <?= $u['major'] ?> • <?= esc($u['subject']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-orange-500"><?= date('d M', strtotime($u['date'])) ?></p>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Jurnal Terbaru -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-book-open" style="color:var(--color-primary)"></i>Jurnal Mengajar Saya
        </h3>
    </div>
    <?php if (empty($jurnalSaya)): ?>
    <div class="p-10 text-center text-gray-400"><p class="text-sm">Belum ada jurnal</p></div>
    <?php else: ?>
    <div class="divide-y divide-gray-100">
        <?php foreach ($jurnalSaya as $j): ?>
        <div class="px-5 py-4 hover:bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($j['subject']) ?> — Kelas <?= $j['grade'] ?></p>
                    <p class="text-gray-500 text-xs mt-0.5"><?= esc($j['topic'] ?? '-') ?></p>
                </div>
                <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($j['date'])) ?></span>
            </div>
        </div>
        <?php endforeach ?>
    </div>
    <?php endif ?>
</div>
<?php $this->endSection() ?>