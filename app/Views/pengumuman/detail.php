<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$item    = $item    ?? [];
$canEdit = $canEdit ?? false;
$roles   = $item['visibility'] ? explode(',', $item['visibility']) : [];
$roleLabels = [
    'superadmin'=>'Superadmin','kepsek'=>'Kepala Sekolah','tu'=>'Tata Usaha',
    'kurikulum'=>'Kurikulum','guru_mapel'=>'Guru','wali_kelas'=>'Wali Kelas',
    'kesiswaan'=>'Kesiswaan','bk'=>'BK','toolman'=>'Toolman','siswa'=>'Siswa',
];
?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('pengumuman') ?>" class="hover:text-gray-600">Pengumuman</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-600 font-semibold truncate max-w-xs"><?= esc($item['title']) ?></span>
</div>

<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">

        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-wrap gap-2 mb-3">
                <?php if ($item['is_important']): ?>
                <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-1"></i>PENTING
                </span>
                <?php endif ?>
                <?php if (empty($roles)): ?>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-500">Semua</span>
                <?php else: ?>
                <?php foreach ($roles as $r): $r = trim($r); ?>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-700">
                    <?= $roleLabels[$r] ?? $r ?>
                </span>
                <?php endforeach ?>
                <?php endif ?>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-3"><?= esc($item['title']) ?></h1>
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400">
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-calendar text-xs"></i>
                    <?= date('l, d F Y', strtotime($item['published_at'])) ?>
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-user text-xs"></i>
                    <?= esc($item['created_by_name'] ?? '-') ?>
                </span>
            </div>
        </div>

        <!-- Konten -->
        <div class="p-6">
            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                <?= nl2br(esc($item['content'])) ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="<?= base_url('pengumuman') ?>"
               class="text-sm font-semibold text-gray-500 hover:text-gray-700 flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Kembali
            </a>
            <?php if ($canEdit): ?>
            <div class="flex gap-2">
                <a href="<?= base_url('pengumuman/edit/' . $item['id']) ?>"
                   class="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold">
                    <i class="fas fa-pencil text-xs"></i> Edit
                </a>
                <form method="POST" action="<?= base_url('pengumuman/delete/' . $item['id']) ?>"
                      onsubmit="return confirm('Hapus pengumuman ini?')">
                    <?= csrf_field() ?>
                    <button class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                        <i class="fas fa-trash text-xs"></i> Hapus
                    </button>
                </form>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>
<?php $this->endSection() ?>