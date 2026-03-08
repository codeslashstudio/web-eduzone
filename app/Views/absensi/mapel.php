<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('absensi') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-list w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard Absensi</span>
</a>
<a href="<?= base_url('absensi/mapel') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-book-open w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Absensi Per Mapel</span>
</a>
<a href="<?= base_url('absensi/harian') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-day w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Absensi Harian</span>
</a>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$jadwal     = $jadwal     ?? [];
$date       = $date       ?? date('Y-m-d');
$day        = $day        ?? '';
$teacher_id = $teacher_id ?? null;
?>

<!-- DATE PICKER -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5" data-aos="fade-up">
    <form method="GET" action="<?= base_url('absensi/mapel') ?>" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="text-xs font-semibold text-gray-500 mb-1 block">Tanggal</label>
            <input type="date" name="date" value="<?= esc($date) ?>"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none">
        </div>
        <button type="submit" class="btn-primary px-4 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fas fa-search text-xs"></i> Tampilkan
        </button>
    </form>
</div>

<!-- JADWAL LIST -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
        <div class="stat-icon-bg w-10 h-10 rounded-xl flex items-center justify-center">
            <i class="fas fa-book-open text-sm"></i>
        </div>
        <div>
            <h3 class="font-bold text-gray-900">Jadwal Hari <?= esc($day) ?></h3>
            <p class="text-xs text-gray-400"><?= date('d F Y', strtotime($date)) ?> · <?= count($jadwal) ?> sesi</p>
        </div>
    </div>

    <?php if (empty($jadwal)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-calendar-times text-5xl mb-4 text-gray-200"></i>
        <p class="text-sm">Tidak ada jadwal untuk hari ini</p>
        <p class="text-xs mt-1 text-gray-300">Coba pilih tanggal lain</p>
    </div>
    <?php else: ?>
    <div class="divide-y divide-gray-50">
        <?php foreach ($jadwal as $j): ?>
        <div class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-4">
                <!-- Waktu -->
                <div class="w-20 text-center shrink-0">
                    <span class="text-xs font-mono font-bold text-gray-700">
                        <?= substr($j['start_time'], 0, 5) ?>
                    </span>
                    <div class="text-gray-300 text-xs">↓</div>
                    <span class="text-xs font-mono text-gray-400">
                        <?= substr($j['end_time'], 0, 5) ?>
                    </span>
                </div>
                <!-- Info -->
                <div>
                    <p class="font-bold text-gray-800"><?= esc($j['subject']) ?></p>
                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-2">
                        <span class="font-semibold px-2 py-0.5 rounded-md text-xs"
                              style="background:rgba(var(--color-primary-rgb),0.1);color:var(--color-primary)">
                            <?= esc($j['nama_kelas'] ?? $j['grade'].' '.$j['major'].' '.$j['class_group']) ?>
                        </span>
                        <?php if ($j['room'] ?? ''): ?>
                        <span class="text-gray-300">·</span>
                        <i class="fas fa-door-open text-gray-300"></i> <?= esc($j['room']) ?>
                        <?php endif ?>
                    </p>
                </div>
            </div>
            <!-- Tombol input -->
            <a href="<?= base_url('absensi/mapel/' . $j['id']) . '?date=' . $date . '&teacher_id=' . ($j['teacher_id'] ?? '') ?>"
               class="btn-primary px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 shrink-0">
                <i class="fas fa-clipboard-check text-xs"></i>
                Input Absensi
            </a>
        </div>
        <?php endforeach ?>
    </div>
    <?php endif ?>
</div>

<?php $this->endSection() ?>