<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list      = $list      ?? [];
$stats     = $stats     ?? [];
$guruList  = $guruList  ?? [];
$kelasList = $kelasList ?? [];
$bulan     = $bulan     ?? date('m');
$tahun     = $tahun     ?? date('Y');
$guruId    = $guruId    ?? '';
$kelasId   = $kelasId   ?? '';
$canInput  = $canInput  ?? false;
$viewAll   = $viewAll   ?? false;

$namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
$mapelColors = ['bg-blue-100 text-blue-700','bg-purple-100 text-purple-700',
                'bg-emerald-100 text-emerald-700','bg-orange-100 text-orange-700',
                'bg-rose-100 text-rose-700','bg-cyan-100 text-cyan-700',
                'bg-yellow-100 text-yellow-700','bg-indigo-100 text-indigo-700'];
$colorMap = [];
$ci = 0;
foreach ($list as $j) {
    $k = $j['subject'];
    if (!isset($colorMap[$k])) $colorMap[$k] = $mapelColors[$ci++ % count($mapelColors)];
}
?>

<!-- Flash -->
<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-5">
    <?php foreach ([
        ['Total Jurnal',  $stats['total']  ?? 0, 'fa-book-open',        'var(--color-primary)'],
        ['Guru Mengajar', $stats['guru']   ?? 0, 'fa-chalkboard-teacher','#10b981'],
        ['Kelas Tercakup',$stats['kelas']  ?? 0, 'fa-door-open',         '#f59e0b'],
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

<!-- Toolbar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-book-open" style="color:var(--color-primary)"></i>
            Jurnal <?= $namaBulan[(int)$bulan] ?> <?= $tahun ?>
            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($list) ?></span>
        </h3>
        <?php if ($canInput): ?>
        <a href="<?= base_url('jurnal/add') ?>"
           class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap">
            <i class="fas fa-plus text-xs"></i> Input Jurnal
        </a>
        <?php endif ?>
    </div>

    <!-- Filter -->
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
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
            <?php if ($viewAll): ?>
            <select name="guru_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Guru</option>
                <?php foreach ($guruList as $g): ?>
                <option value="<?= $g['id'] ?>" <?= $guruId == $g['id'] ? 'selected' : '' ?>><?= esc($g['full_name']) ?></option>
                <?php endforeach ?>
            </select>
            <select name="class_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelasList as $k): ?>
                <option value="<?= $k['id'] ?>" <?= $kelasId == $k['id'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option>
                <?php endforeach ?>
            </select>
            <?php endif ?>
            <button type="submit" class="btn-primary px-3 py-2 rounded-xl text-sm font-semibold">
                <i class="fas fa-filter text-xs"></i>
            </button>
            <a href="<?= base_url('jurnal') ?>" class="px-3 py-2 text-sm text-gray-400 hover:text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">
                <i class="fas fa-times text-xs"></i>
            </a>
        </form>
    </div>

    <!-- Table -->
    <?php if (empty($list)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-book-open text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada jurnal di bulan ini</p>
        <?php if ($canInput): ?>
        <a href="<?= base_url('jurnal/add') ?>" class="btn-primary mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold">
            <i class="fas fa-plus text-xs"></i> Input Jurnal
        </a>
        <?php endif ?>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Mata Pelajaran</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Kelas</th>
                    <?php if ($viewAll): ?>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Guru</th>
                    <?php endif ?>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Materi / Topik</th>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($list as $j): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-4 shrink-0">
                        <p class="font-semibold text-gray-800 text-xs"><?= date('d', strtotime($j['date'])) ?></p>
                        <p class="text-xs text-gray-400"><?= $namaBulan[(int)date('m', strtotime($j['date']))] ?> <?= date('Y', strtotime($j['date'])) ?></p>
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg <?= $colorMap[$j['subject']] ?? 'bg-gray-100 text-gray-600' ?>">
                            <?= esc($j['subject']) ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-xs text-gray-600 font-semibold">
                        <?= esc($j['nama_kelas'] ?? ($j['grade'] ?? '-')) ?>
                    </td>
                    <?php if ($viewAll): ?>
                    <td class="py-3.5 px-4 text-xs text-gray-600">
                        <?= esc($j['guru_name'] ?? '-') ?>
                    </td>
                    <?php endif ?>
                    <td class="py-3.5 px-4 max-w-xs">
                        <p class="text-sm text-gray-700 truncate"><?= esc($j['topic']) ?></p>
                        <?php if ($j['notes']): ?>
                        <p class="text-xs text-gray-400 truncate mt-0.5"><?= esc($j['notes']) ?></p>
                        <?php endif ?>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= base_url('jurnal/edit/' . $j['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            <form method="POST" action="<?= base_url('jurnal/delete/' . $j['id']) ?>"
                                  onsubmit="return confirm('Hapus jurnal ini?')">
                                <?= csrf_field() ?>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>
<?php $this->endSection() ?>