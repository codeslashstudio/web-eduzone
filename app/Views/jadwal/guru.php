<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('jadwal') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$teacher       = $teacher       ?? [];
$jadwal        = $jadwal        ?? [];
$jadwalPerHari = $jadwalPerHari ?? [];
$hariIni       = $hariIni       ?? 'Senin';
$totalJadwal   = $totalJadwal   ?? 0;
$totalJam      = $totalJam      ?? 0;
$hariUrut      = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
?>

<!-- HEADER GURU -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 flex items-center gap-5" data-aos="fade-up">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shrink-0"
         style="background:var(--color-primary)">
        <?= strtoupper(substr($teacher['full_name'] ?? 'G', 0, 1)) ?>
    </div>
    <div class="flex-1">
        <h2 class="text-xl font-bold text-gray-900"><?= esc($teacher['full_name'] ?? '-') ?></h2>
        <p class="text-sm text-gray-500 mt-0.5">
            <?= esc($teacher['nip'] ?? 'NIP tidak tersedia') ?>
            <?php if ($teacher['major_name'] ?? ''): ?>
            · <span style="color:var(--color-primary)"><?= esc($teacher['major_name']) ?></span>
            <?php endif ?>
        </p>
    </div>
    <div class="hidden sm:flex gap-6 text-center">
        <div>
            <p class="text-2xl font-bold text-gray-900"><?= $totalJadwal ?></p>
            <p class="text-xs text-gray-400">Sesi / Minggu</p>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900"><?= $totalJam ?></p>
            <p class="text-xs text-gray-400">Jam / Minggu</p>
        </div>
    </div>
    <a href="<?= base_url('jadwal/cetak/' . ($teacher['id'] ?? '')) ?>"
       target="_blank"
       class="hidden sm:flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">
        <i class="fas fa-print text-xs"></i> Cetak
    </a>
</div>

<!-- JADWAL GRID PER HARI -->
<?php foreach ($hariUrut as $h):
    $sesi = $jadwalPerHari[$h] ?? [];
    if (empty($sesi)) continue;
?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4" data-aos="fade-up">
    <div class="px-5 py-3.5 flex items-center gap-3 border-b border-gray-100"
         style="<?= $h === $hariIni ? 'background:var(--color-primary)' : 'background:#f9fafb' ?>">
        <i class="fas fa-calendar-day text-sm <?= $h === $hariIni ? 'text-white' : 'text-gray-400' ?>"></i>
        <span class="font-bold text-sm <?= $h === $hariIni ? 'text-white' : 'text-gray-700' ?>"><?= $h ?></span>
        <?php if ($h === $hariIni): ?>
        <span class="text-xs bg-white/20 text-white px-2 py-0.5 rounded-full">Hari ini</span>
        <?php endif ?>
        <span class="ml-auto text-xs <?= $h === $hariIni ? 'text-white/70' : 'text-gray-400' ?>"><?= count($sesi) ?> sesi</span>
    </div>
    <div class="divide-y divide-gray-50">
        <?php foreach ($sesi as $j): ?>
        <div class="px-5 py-3.5 flex items-center gap-4">
            <div class="w-24 shrink-0">
                <span class="text-xs font-mono font-semibold text-gray-600">
                    <?= substr($j['start_time'], 0, 5) ?><br>
                    <span class="text-gray-400"><?= substr($j['end_time'], 0, 5) ?></span>
                </span>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-800 text-sm"><?= esc($j['subject']) ?></p>
                <p class="text-xs text-gray-400 mt-0.5">
                    <?= esc($j['nama_kelas'] ?? $j['grade'].' '.$j['major'].' '.$j['class_group']) ?>
                    <?php if ($j['room'] ?? ''): ?>· <?= esc($j['room']) ?><?php endif ?>
                </p>
            </div>
            <?php
            $start  = strtotime($j['start_time']);
            $end    = strtotime($j['end_time']);
            $durasi = round(($end - $start) / 60);
            ?>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg shrink-0"><?= $durasi ?> mnt</span>
        </div>
        <?php endforeach ?>
    </div>
</div>
<?php endforeach ?>

<?php if (empty($jadwal)): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center text-gray-400" data-aos="fade-up">
    <i class="fas fa-calendar-times text-5xl mb-4 text-gray-200"></i>
    <p>Belum ada jadwal untuk guru ini</p>
</div>
<?php endif ?>

<?php $this->endSection() ?>