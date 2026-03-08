<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list      = $list      ?? [];
$stats     = $stats     ?? [];
$majorList = $majorList ?? [];
$canEdit   = $canEdit   ?? false;
$tahun     = $tahun     ?? '2025/2026';
$grade     = $grade     ?? '';
$major     = $major     ?? '';
$status    = $status    ?? '';

$gradeColors = ['X' => 'bg-blue-100 text-blue-700', 'XI' => 'bg-purple-100 text-purple-700', 'XII' => 'bg-rose-100 text-rose-700'];
$tahunList   = ['2025/2026','2024/2025','2023/2024','2026/2027'];
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-5">
    <?php foreach ([
        ['Total Kelas',  $stats['total']   ?? 0, 'fa-door-open',    '#6366f1'],
        ['Aktif',        $stats['aktif']   ?? 0, 'fa-check-circle', '#10b981'],
        ['Tidak Aktif',  $stats['nonaktif']?? 0, 'fa-times-circle', '#ef4444'],
    ] as [$lbl,$val,$icon,$color]): ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:<?= $color ?>22">
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
        <div class="flex items-center gap-3">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-door-open" style="color:var(--color-primary)"></i>
                Daftar Kelas
                <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($list) ?></span>
            </h3>
            <!-- Tab link ke Jurusan -->
            <a href="<?= base_url('master/jurusan') ?>"
               class="text-xs font-semibold px-3 py-1.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                <i class="fas fa-layer-group mr-1"></i> Jurusan
            </a>
        </div>
        <?php if ($canEdit): ?>
        <a href="<?= base_url('master/kelas/add') ?>"
           class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap">
            <i class="fas fa-plus text-xs"></i> Tambah Kelas
        </a>
        <?php endif ?>
    </div>

    <!-- Filter -->
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <select name="tahun" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none font-semibold">
                <?php foreach ($tahunList as $ty): ?>
                <option value="<?= $ty ?>" <?= $tahun === $ty ? 'selected' : '' ?>><?= $ty ?></option>
                <?php endforeach ?>
            </select>
            <select name="grade" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Tingkat</option>
                <?php foreach (['X','XI','XII'] as $g): ?>
                <option value="<?= $g ?>" <?= $grade === $g ? 'selected' : '' ?>>Kelas <?= $g ?></option>
                <?php endforeach ?>
            </select>
            <select name="major" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Jurusan</option>
                <?php foreach ($majorList as $m): ?>
                <option value="<?= $m['id'] ?>" <?= $major == $m['id'] ? 'selected' : '' ?>><?= esc($m['name']) ?></option>
                <?php endforeach ?>
            </select>
            <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Status</option>
                <option value="1" <?= $status==='1' ? 'selected':'' ?>>Aktif</option>
                <option value="0" <?= $status==='0' ? 'selected':'' ?>>Nonaktif</option>
            </select>
            <button type="submit" class="btn-primary px-3 py-2 rounded-xl text-sm">
                <i class="fas fa-filter text-xs"></i>
            </button>
            <a href="<?= base_url('master/kelas') ?>" class="px-3 py-2 text-sm text-gray-400 border border-gray-200 rounded-xl hover:bg-gray-50">
                <i class="fas fa-times text-xs"></i>
            </a>
        </form>
    </div>

    <?php if (empty($list)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-door-open text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada kelas</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Kelas</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Jurusan</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Siswa / Kapasitas</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Wali Kelas</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Status</th>
                    <?php if ($canEdit): ?>
                    <th class="py-3 px-4 text-right text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($list as $k): ?>
                <tr class="hover:bg-gray-50 transition-colors <?= !$k['is_active'] ? 'opacity-60' : '' ?>">
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-lg <?= $gradeColors[$k['grade']] ?? 'bg-gray-100 text-gray-600' ?>">
                                <?= $k['grade'] ?>
                            </span>
                            <div>
                                <p class="font-bold text-gray-900"><?= esc($k['nama_kelas']) ?></p>
                                <p class="text-xs text-gray-400">Rombel <?= $k['class_group'] ?> · <?= $k['academic_year'] ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 px-4">
                        <p class="text-sm font-semibold text-gray-700"><?= esc($k['major_name'] ?? '-') ?></p>
                        <p class="text-xs text-gray-400"><?= esc($k['abbreviation'] ?? '') ?></p>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <?php
                        $pct = $k['kapasitas'] > 0 ? min(100, round($k['jumlah_siswa'] / $k['kapasitas'] * 100)) : 0;
                        $barColor = $pct >= 90 ? '#ef4444' : ($pct >= 75 ? '#f59e0b' : '#10b981');
                        ?>
                        <p class="text-sm font-bold text-gray-900"><?= $k['jumlah_siswa'] ?> <span class="text-gray-400 font-normal">/ <?= $k['kapasitas'] ?></span></p>
                        <div class="w-20 mx-auto bg-gray-100 rounded-full h-1.5 mt-1">
                            <div class="h-1.5 rounded-full" style="width:<?= $pct ?>%;background:<?= $barColor ?>"></div>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 text-sm text-gray-600">
                        <?php if ($k['wakel_name']): ?>
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-user-tie text-xs text-gray-400"></i>
                            <?= esc($k['wakel_name']) ?>
                        </span>
                        <?php else: ?>
                        <span class="text-xs text-orange-500 font-semibold bg-orange-50 px-2 py-0.5 rounded-lg">
                            <i class="fas fa-exclamation mr-1"></i>Belum ada
                        </span>
                        <?php endif ?>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <?php if ($canEdit): ?>
                        <form method="POST" action="<?= base_url('master/kelas/toggle/' . $k['id']) ?>">
                            <?= csrf_field() ?>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg transition-colors
                                    <?= $k['is_active'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' ?>">
                                <i class="fas <?= $k['is_active'] ? 'fa-check-circle' : 'fa-times-circle' ?> text-xs"></i>
                                <?= $k['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg
                            <?= $k['is_active'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' ?>">
                            <?= $k['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                        <?php endif ?>
                    </td>
                    <?php if ($canEdit): ?>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= base_url('master/kelas/edit/' . $k['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>
                            <form method="POST" action="<?= base_url('master/kelas/delete/' . $k['id']) ?>"
                                  onsubmit="return confirm('Hapus kelas <?= esc($k['nama_kelas']) ?>?')">
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