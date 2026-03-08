<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list      = $list      ?? [];
$stats     = $stats     ?? [];
$topTopik  = $topTopik  ?? [];
$kelasList = $kelasList ?? [];
$canEdit   = $canEdit   ?? false;
$bulan     = $bulan     ?? date('m');
$tahun     = $tahun     ?? date('Y');
$search    = $search    ?? '';
$classId   = $classId   ?? '';
$namaBulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

$topikList = [
    'Masalah Akademik','Masalah Kehadiran','Masalah Perilaku',
    'Masalah Keluarga','Masalah Sosial','Karier & Studi Lanjut',
    'Kesehatan Mental','Lainnya'
];
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-5">
    <?php foreach ([
        ['Sesi Bulan Ini',   $stats['total']    ?? 0, 'fa-comments',      '#6366f1'],
        ['Siswa Dikonseling',$stats['siswa']    ?? 0, 'fa-user-graduate', '#f59e0b'],
        ['Konselor Aktif',   $stats['konselor'] ?? 0, 'fa-user-tie',      '#10b981'],
    ] as [$lbl,$val,$icon,$color]): ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3"
             style="background:<?= $color ?>22">
            <i class="fas <?= $icon ?> text-base" style="color:<?= $color ?>"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= $val ?></p>
        <p class="text-xs text-gray-400 mt-0.5"><?= $lbl ?></p>
    </div>
    <?php endforeach ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
    <!-- Tabel sesi -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-comments" style="color:var(--color-primary)"></i>
                    Sesi Konseling
                    <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($list) ?></span>
                </h3>
                <?php if ($canEdit): ?>
                <a href="<?= base_url('konseling/add') ?>"
                   class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap">
                    <i class="fas fa-plus text-xs"></i> Input Sesi
                </a>
                <?php endif ?>
            </div>

            <!-- Filter -->
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                <form method="GET" class="flex flex-wrap gap-2 items-center">
                    <div class="relative">
                        <input type="text" name="search" value="<?= esc($search) ?>"
                               placeholder="Cari nama/topik..."
                               class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none w-40">
                        <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                    <select name="class_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $classId == $k['id'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option>
                        <?php endforeach ?>
                    </select>
                    <select name="bulan" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= (int)$bulan === $m ? 'selected' : '' ?>><?= $namaBulan[$m] ?></option>
                        <?php endfor ?>
                    </select>
                    <select name="tahun" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                        <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                        <option value="<?= $y ?>" <?= (int)$tahun === $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor ?>
                    </select>
                    <button type="submit" class="btn-primary px-3 py-2 rounded-xl text-sm">
                        <i class="fas fa-filter text-xs"></i>
                    </button>
                    <a href="<?= base_url('konseling') ?>" class="px-3 py-2 text-sm text-gray-400 border border-gray-200 rounded-xl hover:bg-gray-50">
                        <i class="fas fa-times text-xs"></i>
                    </a>
                </form>
            </div>

            <?php if (empty($list)): ?>
            <div class="py-16 text-center text-gray-400">
                <i class="fas fa-comments text-5xl text-gray-200 mb-4"></i>
                <p class="font-semibold">Belum ada sesi konseling</p>
            </div>
            <?php else: ?>
            <div class="divide-y divide-gray-50">
                <?php foreach ($list as $s): ?>
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0 mt-0.5"
                                 style="background:var(--color-primary)">
                                <?= strtoupper(substr($s['siswa_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                    <p class="font-bold text-gray-900 text-sm"><?= esc($s['siswa_name']) ?></p>
                                    <span class="text-xs text-gray-400"><?= esc($s['nama_kelas'] ?? '-') ?></span>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">
                                        <i class="fas fa-tag text-xs"></i> <?= esc($s['topic']) ?>
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-calendar mr-1"></i><?= date('d F Y', strtotime($s['date'])) ?>
                                    </span>
                                    <?php if ($s['konselor_name']): ?>
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-user-tie mr-1"></i><?= esc($s['konselor_name']) ?>
                                    </span>
                                    <?php endif ?>
                                </div>
                                <?php if ($s['result']): ?>
                                <p class="text-xs text-gray-500 mt-1.5 line-clamp-2"><?= esc($s['result']) ?></p>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <a href="<?= base_url('konseling/' . $s['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-100 hover:text-blue-600 text-gray-500 transition-colors">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <?php if ($canEdit): ?>
                            <a href="<?= base_url('konseling/edit/' . $s['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            <form method="POST" action="<?= base_url('konseling/delete/' . $s['id']) ?>"
                                  onsubmit="return confirm('Hapus sesi konseling ini?')">
                                <?= csrf_field() ?>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
            <?php endif ?>
        </div>
    </div>

    <!-- Sidebar: Topik terbanyak -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" data-aos="fade-up">
            <h4 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar" style="color:var(--color-primary)"></i>
                Topik Terbanyak
            </h4>
            <?php if (empty($topTopik)): ?>
            <p class="text-xs text-gray-400 text-center py-4">Belum ada data</p>
            <?php else: ?>
            <?php $maxJumlah = max(array_column($topTopik, 'jumlah')) ?: 1; ?>
            <div class="space-y-3">
                <?php foreach ($topTopik as $t): ?>
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-semibold text-gray-700 truncate max-w-[130px]"><?= esc($t['topic']) ?></span>
                        <span class="font-bold text-gray-500 shrink-0 ml-1"><?= $t['jumlah'] ?></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full" style="width:<?= round($t['jumlah']/$maxJumlah*100) ?>%;background:var(--color-primary)"></div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
            <?php endif ?>
        </div>

        <?php if ($canEdit): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" data-aos="fade-up">
            <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                <i class="fas fa-bolt" style="color:var(--color-primary)"></i>
                Aksi Cepat
            </h4>
            <a href="<?= base_url('konseling/add') ?>"
               class="flex items-center gap-2.5 p-3 rounded-xl hover:bg-gray-50 transition-colors border border-dashed border-gray-200">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:var(--color-primary)22">
                    <i class="fas fa-plus text-xs" style="color:var(--color-primary)"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700">Input Sesi Baru</span>
            </a>
        </div>
        <?php endif ?>
    </div>
</div>

<?php $this->endSection() ?>