<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list      = $list      ?? [];
$stats     = $stats     ?? [];
$locations = $locations ?? [];
$canEdit   = $canEdit   ?? false;
$search    = $search    ?? '';
$condition = $condition ?? '';
$location  = $location  ?? '';

$condColors = [
    'Baik'         => 'bg-emerald-100 text-emerald-700',
    'Rusak Ringan' => 'bg-yellow-100 text-yellow-700',
    'Rusak Berat'  => 'bg-red-100 text-red-700',
];
$condIcons = [
    'Baik'         => 'fa-check-circle',
    'Rusak Ringan' => 'fa-exclamation-circle',
    'Rusak Berat'  => 'fa-times-circle',
];
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <?php foreach ([
        ['Total Item',     $stats['total']       ?? 0, 'fa-boxes',          '#6366f1'],
        ['Total Unit',     $stats['total_unit']  ?? 0, 'fa-cubes',          '#f59e0b'],
        ['Kondisi Baik',   $stats['baik']        ?? 0, 'fa-check-circle',   '#10b981'],
        ['Perlu Perhatian',($stats['rusak_ringan']??0)+($stats['rusak_berat']??0), 'fa-exclamation-triangle','#ef4444'],
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
            <i class="fas fa-boxes" style="color:var(--color-primary)"></i>
            Daftar Inventaris
            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($list) ?></span>
        </h3>
        <?php if ($canEdit): ?>
        <a href="<?= base_url('inventaris/add') ?>"
           class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap">
            <i class="fas fa-plus text-xs"></i> Tambah Barang
        </a>
        <?php endif ?>
    </div>

    <!-- Filter -->
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <div class="relative">
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari nama barang..."
                       class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none w-44">
                <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
            <select name="condition" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Kondisi</option>
                <option value="Baik"         <?= $condition==='Baik'         ? 'selected':'' ?>>Baik</option>
                <option value="Rusak Ringan" <?= $condition==='Rusak Ringan' ? 'selected':'' ?>>Rusak Ringan</option>
                <option value="Rusak Berat"  <?= $condition==='Rusak Berat'  ? 'selected':'' ?>>Rusak Berat</option>
            </select>
            <select name="location" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Lokasi</option>
                <?php foreach ($locations as $loc): ?>
                <option value="<?= esc($loc['location']) ?>" <?= $location === $loc['location'] ? 'selected' : '' ?>>
                    <?= esc($loc['location']) ?>
                </option>
                <?php endforeach ?>
            </select>
            <button type="submit" class="btn-primary px-3 py-2 rounded-xl text-sm">
                <i class="fas fa-filter text-xs"></i>
            </button>
            <a href="<?= base_url('inventaris') ?>" class="px-3 py-2 text-sm text-gray-400 border border-gray-200 rounded-xl hover:bg-gray-50">
                <i class="fas fa-times text-xs"></i>
            </a>
        </form>
    </div>

    <?php if (empty($list)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-boxes text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada data inventaris</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Nama Barang</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Jumlah</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Kondisi</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Lokasi</th>
                    <?php if ($canEdit): ?>
                    <th class="py-3 px-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($list as $item): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-4">
                        <p class="font-semibold text-gray-800"><?= esc($item['item_name']) ?></p>
                        <?php if (!empty($item['description'] ?? '')): ?>
                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs"><?= esc($item['description']) ?></p>
                        <?php endif ?>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <span class="text-lg font-bold text-gray-900"><?= $item['quantity'] ?></span>
                        <span class="text-xs text-gray-400 ml-1">unit</span>
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg <?= $condColors[$item['condition']] ?? 'bg-gray-100 text-gray-600' ?>">
                            <i class="fas <?= $condIcons[$item['condition']] ?? 'fa-circle' ?> text-xs"></i>
                            <?= $item['condition'] ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-sm text-gray-500">
                        <?php if ($item['location']): ?>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-xs text-gray-400"></i>
                            <?= esc($item['location']) ?>
                        </span>
                        <?php else: ?>
                        <span class="text-gray-300">-</span>
                        <?php endif ?>
                    </td>
                    <?php if ($canEdit): ?>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= base_url('inventaris/edit/' . $item['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            <form method="POST" action="<?= base_url('inventaris/delete/' . $item['id']) ?>"
                                  onsubmit="return confirm('Hapus barang ini?')">
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