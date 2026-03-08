<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$jadwalList = $jadwalList ?? [];
$hariIni    = $hariIni    ?? '';
$today      = $today      ?? date('Y-m-d');
$namaHari   = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$namaBulan  = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$tglFormat  = date('j', strtotime($today)) . ' ' . $namaBulan[(int)date('n', strtotime($today))] . ' ' . date('Y', strtotime($today));
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Header -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                 style="background:var(--color-primary)22">
                <i class="fas fa-clipboard-list text-xl" style="color:var(--color-primary)"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-900">Jadwal Mengajar Hari Ini</h2>
                <p class="text-sm text-gray-400 mt-0.5">
                    <i class="fas fa-calendar-day mr-1"></i><?= $hariIni ?>, <?= $tglFormat ?>
                </p>
            </div>
        </div>
        <a href="<?= base_url('absensi-mapel/rekap') ?>"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-50 text-indigo-700 text-sm font-semibold hover:bg-indigo-100 transition-colors">
            <i class="fas fa-chart-bar text-xs"></i> Rekap Absensi
        </a>
    </div>
</div>

<!-- Jadwal List -->
<?php if (empty($jadwalList)): ?>
<div class="bg-white rounded-2xl py-16 text-center text-gray-400 shadow-sm border border-gray-100" data-aos="fade-up">
    <i class="fas fa-calendar-times text-5xl text-gray-200 mb-4"></i>
    <p class="font-semibold text-gray-500">Tidak ada jadwal mengajar hari ini</p>
    <p class="text-sm mt-1">Hari <?= $hariIni ?> tidak ada kelas yang dijadwalkan</p>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php foreach ($jadwalList as $j): ?>
    <?php
    $sudahInput = !empty($j['sesi_id']);
    $pct = $j['jumlah_siswa'] > 0 ? round(($j['hadir'] ?? 0) / $j['jumlah_siswa'] * 100) : 0;
    ?>
    <div class="bg-white rounded-2xl shadow-sm border <?= $sudahInput ? 'border-emerald-200' : 'border-gray-100' ?> overflow-hidden hover:shadow-md transition-shadow" data-aos="fade-up">
        <div class="p-5">
            <!-- Jam & status -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-xl bg-indigo-100 text-indigo-700">
                        <i class="fas fa-clock mr-1"></i>
                        <?= substr($j['start_time'],0,5) ?> – <?= substr($j['end_time'],0,5) ?>
                    </span>
                </div>
                <?php if ($sudahInput): ?>
                <span class="text-xs font-bold px-2.5 py-1 rounded-xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-check mr-1"></i>Sudah Input
                </span>
                <?php else: ?>
                <span class="text-xs font-bold px-2.5 py-1 rounded-xl bg-yellow-100 text-yellow-700">
                    <i class="fas fa-exclamation mr-1"></i>Belum Input
                </span>
                <?php endif ?>
            </div>

            <!-- Info kelas -->
            <h3 class="font-bold text-gray-900 text-lg"><?= esc($j['subject']) ?></h3>
            <p class="text-sm text-gray-500 mt-0.5">
                <i class="fas fa-door-open mr-1 text-xs"></i><?= esc($j['nama_kelas'] ?? $j['grade'].' '.$j['major'].' '.$j['class_group']) ?>
                <?php if ($j['room']): ?>
                <span class="mx-1">·</span><i class="fas fa-map-marker-alt mr-1 text-xs"></i><?= esc($j['room']) ?>
                <?php endif ?>
            </p>

            <?php if ($sudahInput): ?>
            <!-- Progress hadir -->
            <div class="mt-4">
                <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                    <span>Kehadiran</span>
                    <span class="font-bold text-emerald-600"><?= $j['hadir'] ?>/<?= $j['jumlah_siswa'] ?> hadir</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full bg-emerald-500 transition-all" style="width:<?= $pct ?>%"></div>
                </div>
                <?php if ($j['alpa'] > 0): ?>
                <p class="text-xs text-red-500 mt-1"><i class="fas fa-times-circle mr-1"></i><?= $j['alpa'] ?> siswa alpa</p>
                <?php endif ?>
                <?php if ($j['topic']): ?>
                <p class="text-xs text-gray-400 mt-1 italic truncate"><i class="fas fa-book mr-1"></i><?= esc($j['topic']) ?></p>
                <?php endif ?>
            </div>
            <?php else: ?>
            <div class="mt-4">
                <p class="text-xs text-gray-400"><i class="fas fa-users mr-1"></i><?= $j['jumlah_siswa'] ?> siswa terdaftar</p>
            </div>
            <?php endif ?>
        </div>

        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
            <a href="<?= base_url('absensi-mapel/input/' . $j['schedule_id']) ?>"
               class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-sm font-semibold transition-colors
               <?= $sudahInput ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'btn-primary' ?>">
                <i class="fas <?= $sudahInput ? 'fa-edit' : 'fa-clipboard-check' ?> text-xs"></i>
                <?= $sudahInput ? 'Edit Absensi' : 'Input Absensi' ?>
            </a>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php endif ?>

<?php $this->endSection() ?>