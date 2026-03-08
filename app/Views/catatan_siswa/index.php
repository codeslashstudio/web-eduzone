<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list      = $list      ?? [];
$stats     = $stats     ?? [];
$kelasList = $kelasList ?? [];
$namaBulan = $namaBulan ?? [];
$canEdit   = $canEdit   ?? false;
$search    = $search    ?? '';
$classId   = $classId   ?? '';
$bulan     = $bulan     ?? '';
$tahun     = $tahun     ?? date('Y');
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
        ['Total Catatan',  $stats['total']     ?? 0, 'fa-sticky-note',        '#6366f1'],
        ['Siswa Tercatat', $stats['siswa']     ?? 0, 'fa-user-graduate',      '#f59e0b'],
        ['Bulan Ini',      $stats['bulan_ini'] ?? 0, 'fa-calendar-check',     '#10b981'],
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

<!-- Table Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-sticky-note" style="color:var(--color-primary)"></i>
            Catatan Siswa
            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($list) ?></span>
        </h3>
        <?php if ($canEdit): ?>
        <a href="<?= base_url('catatan-siswa/add') ?>"
           class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap">
            <i class="fas fa-plus text-xs"></i> Tambah Catatan
        </a>
        <?php endif ?>
    </div>

    <!-- Filter -->
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <div class="relative">
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari nama/kegiatan..."
                       class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none w-44">
                <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
            <select name="class_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelasList as $k): ?>
                <option value="<?= $k['id'] ?>" <?= $classId == $k['id'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option>
                <?php endforeach ?>
            </select>
            <select name="bulan" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Bulan</option>
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
            <a href="<?= base_url('catatan-siswa') ?>" class="px-3 py-2 text-sm text-gray-400 border border-gray-200 rounded-xl hover:bg-gray-50">
                <i class="fas fa-times text-xs"></i>
            </a>
        </form>
    </div>

    <?php if (empty($list)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-sticky-note text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada catatan</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Siswa</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Kejadian / Aktivitas</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Dicatat Oleh</th>
                    <?php if ($canEdit): ?>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Aksi</th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($list as $c): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-4 shrink-0">
                        <p class="font-semibold text-xs text-gray-800"><?= date('d', strtotime($c['date'])) ?></p>
                        <p class="text-xs text-gray-400"><?= $namaBulan[(int)date('m', strtotime($c['date']))] ?> <?= date('Y', strtotime($c['date'])) ?></p>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs font-bold shrink-0"
                                 style="background:var(--color-primary)">
                                <?= strtoupper(substr($c['full_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm"><?= esc($c['full_name']) ?></p>
                                <p class="text-xs text-gray-400"><?= esc($c['nama_kelas'] ?? '-') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 max-w-xs">
                        <p class="font-semibold text-gray-800"><?= esc($c['activity']) ?></p>
                        <?php if ($c['description']): ?>
                        <p class="text-xs text-gray-400 truncate"><?= esc($c['description']) ?></p>
                        <?php endif ?>
                    </td>
                    <td class="py-3.5 px-4 text-xs text-gray-500"><?= esc($c['pencatat'] ?? '-') ?></td>
                    <?php if ($canEdit): ?>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= base_url('catatan-siswa/edit/' . $c['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            <form method="POST" action="<?= base_url('catatan-siswa/delete/' . $c['id']) ?>"
                                  onsubmit="return confirm('Hapus catatan ini?')">
                                <?= csrf_field() ?>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    <?php endif ?>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>
<?php $this->endSection() ?>