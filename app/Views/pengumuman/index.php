<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list    = $list    ?? [];
$canEdit = $canEdit ?? false;
$penting = array_filter($list, fn($p) => $p['is_important']);
$biasa   = array_filter($list, fn($p) => !$p['is_important']);

$roleLabels = [
    'superadmin' => 'Superadmin', 'kepsek' => 'Kepala Sekolah',
    'tu' => 'Tata Usaha', 'kurikulum' => 'Kurikulum',
    'guru_mapel' => 'Guru', 'wali_kelas' => 'Wali Kelas',
    'kesiswaan' => 'Kesiswaan', 'bk' => 'BK',
    'toolman' => 'Toolman', 'siswa' => 'Siswa',
];
$roleColors = [
    'kepsek' => 'bg-purple-100 text-purple-700',
    'tu' => 'bg-blue-100 text-blue-700',
    'guru_mapel' => 'bg-indigo-100 text-indigo-700',
    'wali_kelas' => 'bg-cyan-100 text-cyan-700',
    'siswa' => 'bg-emerald-100 text-emerald-700',
    'kesiswaan' => 'bg-orange-100 text-orange-700',
    'bk' => 'bg-rose-100 text-rose-700',
    'kurikulum' => 'bg-yellow-100 text-yellow-700',
    'toolman' => 'bg-gray-100 text-gray-700',
];
?>

<!-- Flash -->
<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Pengumuman</h1>
        <p class="text-sm text-gray-400 mt-0.5"><?= count($list) ?> pengumuman tersedia</p>
    </div>
    <?php if ($canEdit): ?>
    <a href="<?= base_url('pengumuman/add') ?>"
       class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold">
        <i class="fas fa-plus text-xs"></i> Buat Pengumuman
    </a>
    <?php endif ?>
</div>

<?php if (empty($list)): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center" data-aos="fade-up">
    <i class="fas fa-bullhorn text-5xl text-gray-200 mb-4"></i>
    <p class="font-semibold text-gray-500">Belum ada pengumuman</p>
</div>

<?php else: ?>

<!-- PENTING -->
<?php if (!empty($penting)): ?>
<div class="mb-6">
    <div class="flex items-center gap-2 mb-3">
        <i class="fas fa-exclamation-circle text-red-500"></i>
        <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Pengumuman Penting</h2>
    </div>
    <div class="space-y-3">
        <?php foreach ($penting as $p): ?>
        <?php
        $roles = $p['visibility'] ? explode(',', $p['visibility']) : [];
        ?>
        <div class="bg-white rounded-2xl shadow-sm border-l-4 border-red-400 border border-gray-100 p-5 hover:shadow-md transition-shadow" data-aos="fade-up">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-lg">
                            <i class="fas fa-exclamation-circle mr-1"></i>PENTING
                        </span>
                        <?php foreach ($roles as $r): $r = trim($r); ?>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $roleColors[$r] ?? 'bg-gray-100 text-gray-600' ?>">
                            <?= $roleLabels[$r] ?? $r ?>
                        </span>
                        <?php endforeach ?>
                        <?php if (empty($roles)): ?>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Semua</span>
                        <?php endif ?>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1">
                        <a href="<?= base_url('pengumuman/detail/' . $p['id']) ?>" class="hover:underline">
                            <?= esc($p['title']) ?>
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-2"><?= esc(substr(strip_tags($p['content']), 0, 150)) ?>...</p>
                    <div class="flex items-center gap-3 mt-3 text-xs text-gray-400">
                        <span><i class="fas fa-calendar mr-1"></i><?= date('d F Y', strtotime($p['published_at'])) ?></span>
                        <span><i class="fas fa-user mr-1"></i><?= esc($p['created_by_name'] ?? '-') ?></span>
                    </div>
                </div>
                <?php if ($canEdit): ?>
                <div class="flex gap-1 shrink-0">
                    <a href="<?= base_url('pengumuman/edit/' . $p['id']) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                        <i class="fas fa-pencil text-xs"></i>
                    </a>
                    <form method="POST" action="<?= base_url('pengumuman/delete/' . $p['id']) ?>"
                          onsubmit="return confirm('Hapus pengumuman ini?')">
                        <?= csrf_field() ?>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
                <?php endif ?>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>

<!-- BIASA -->
<?php if (!empty($biasa)): ?>
<div>
    <?php if (!empty($penting)): ?>
    <div class="flex items-center gap-2 mb-3">
        <i class="fas fa-bullhorn text-gray-400"></i>
        <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Pengumuman Lainnya</h2>
    </div>
    <?php endif ?>
    <div class="space-y-3">
        <?php foreach ($biasa as $p): ?>
        <?php $roles = $p['visibility'] ? explode(',', $p['visibility']) : []; ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow" data-aos="fade-up">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <?php foreach ($roles as $r): $r = trim($r); ?>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $roleColors[$r] ?? 'bg-gray-100 text-gray-600' ?>">
                            <?= $roleLabels[$r] ?? $r ?>
                        </span>
                        <?php endforeach ?>
                        <?php if (empty($roles)): ?>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Semua</span>
                        <?php endif ?>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">
                        <a href="<?= base_url('pengumuman/detail/' . $p['id']) ?>" class="hover:underline">
                            <?= esc($p['title']) ?>
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-2"><?= esc(substr(strip_tags($p['content']), 0, 150)) ?>...</p>
                    <div class="flex items-center gap-3 mt-3 text-xs text-gray-400">
                        <span><i class="fas fa-calendar mr-1"></i><?= date('d F Y', strtotime($p['published_at'])) ?></span>
                        <span><i class="fas fa-user mr-1"></i><?= esc($p['created_by_name'] ?? '-') ?></span>
                    </div>
                </div>
                <?php if ($canEdit): ?>
                <div class="flex gap-1 shrink-0">
                    <a href="<?= base_url('pengumuman/detail/' . $p['id']) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-100 hover:text-blue-600 text-gray-500 transition-colors">
                        <i class="fas fa-eye text-xs"></i>
                    </a>
                    <a href="<?= base_url('pengumuman/edit/' . $p['id']) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                        <i class="fas fa-pencil text-xs"></i>
                    </a>
                    <form method="POST" action="<?= base_url('pengumuman/delete/' . $p['id']) ?>"
                          onsubmit="return confirm('Hapus pengumuman ini?')">
                        <?= csrf_field() ?>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
                <?php endif ?>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>

<?php endif ?>
<?php $this->endSection() ?>