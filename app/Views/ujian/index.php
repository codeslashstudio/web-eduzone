<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list      = $list      ?? [];
$stats     = $stats     ?? [];
$kelasList = $kelasList ?? [];
$canEdit   = $canEdit   ?? false;
$search    = $search    ?? '';
$bulan     = $bulan     ?? date('m');
$tahun     = $tahun     ?? date('Y');
$classId   = $classId   ?? '';
$namaBulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
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
        ['Total Ujian',   $stats['total']    ?? 0, 'fa-file-alt',    '#6366f1'],
        ['Mendatang',     $stats['mendatang']?? 0, 'fa-calendar-alt','#f59e0b'],
        ['Selesai',       $stats['selesai']  ?? 0, 'fa-check-double','#10b981'],
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

<!-- Table card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-file-alt" style="color:var(--color-primary)"></i>
            Daftar Ujian
            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($list) ?></span>
        </h3>
        <?php if ($canEdit): ?>
        <a href="<?= base_url('ujian/add') ?>"
           class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap">
            <i class="fas fa-plus text-xs"></i> Buat Ujian
        </a>
        <?php endif ?>
    </div>

    <!-- Filter -->
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <div class="relative">
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari ujian/mapel..."
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
                <?php for ($y = date('Y') + 1; $y >= date('Y') - 2; $y--): ?>
                <option value="<?= $y ?>" <?= (int)$tahun === $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor ?>
            </select>
            <button type="submit" class="btn-primary px-3 py-2 rounded-xl text-sm">
                <i class="fas fa-filter text-xs"></i>
            </button>
            <a href="<?= base_url('ujian') ?>" class="px-3 py-2 text-sm text-gray-400 border border-gray-200 rounded-xl hover:bg-gray-50">
                <i class="fas fa-times text-xs"></i>
            </a>
        </form>
    </div>

    <?php if (empty($list)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-file-alt text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada ujian</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Ujian</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Jadwal</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Kelas</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Soal</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Pengawas</th>
                    <th class="py-3 px-4 text-right text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($list as $e): ?>
                <?php $isPast = strtotime($e['date']) < strtotime('today'); ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-4">
                        <p class="font-bold text-gray-900"><?= esc($e['name']) ?></p>
                        <p class="text-xs text-gray-400 mt-0.5"><?= esc($e['subject']) ?></p>
                    </td>
                    <td class="py-3.5 px-4">
                        <p class="text-sm font-semibold <?= $isPast ? 'text-gray-400' : 'text-gray-800' ?>">
                            <?= date('d F Y', strtotime($e['date'])) ?>
                        </p>
                        <p class="text-xs text-gray-400">
                            <?= substr($e['start_time'],0,5) ?> – <?= substr($e['end_time'],0,5) ?>
                        </p>
                        <?php if (!$isPast): ?>
                        <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded-md">Mendatang</span>
                        <?php else: ?>
                        <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded-md">Selesai</span>
                        <?php endif ?>
                    </td>
                    <td class="py-3.5 px-4 text-sm text-gray-600">
                        <?= esc($e['nama_kelas'] ?? ($e['grade'] . ' ' . $e['major'])) ?>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-sm font-bold
                            <?= $e['jumlah_soal'] > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-400' ?>">
                            <?= $e['jumlah_soal'] ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-sm text-gray-600"><?= esc($e['supervisor_name'] ?? '-') ?></td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= base_url('ujian/' . $e['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-100 hover:text-blue-600 text-gray-500 transition-colors">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <?php if ($canEdit): ?>
                            <a href="<?= base_url('ujian/edit/' . $e['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            <form method="POST" action="<?= base_url('ujian/delete/' . $e['id']) ?>"
                                  onsubmit="return confirm('Hapus ujian dan semua soalnya?')">
                                <?= csrf_field() ?>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                            <?php endif ?>
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