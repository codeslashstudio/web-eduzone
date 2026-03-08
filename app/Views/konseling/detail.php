<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$item    = $item    ?? [];
$riwayat = $riwayat ?? [];
$canEdit = $canEdit ?? false;
?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('konseling') ?>" class="hover:text-gray-600">Konseling</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold">Detail Sesi</span>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Detail sesi -->
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Detail Sesi Konseling</h3>
                <?php if ($canEdit): ?>
                <div class="flex gap-2">
                    <a href="<?= base_url('konseling/edit/' . $item['id']) ?>"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-pencil text-xs"></i> Edit
                    </a>
                    <form method="POST" action="<?= base_url('konseling/delete/' . $item['id']) ?>"
                          onsubmit="return confirm('Hapus sesi ini?')">
                        <?= csrf_field() ?>
                        <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                            <i class="fas fa-trash text-xs"></i> Hapus
                        </button>
                    </form>
                </div>
                <?php endif ?>
            </div>
            <div class="p-6 space-y-5">
                <!-- Siswa info -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-bold shrink-0"
                         style="background:var(--color-primary)">
                        <?= strtoupper(substr($item['siswa_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-base"><?= esc($item['siswa_name']) ?></p>
                        <p class="text-sm text-gray-500"><?= esc($item['nama_kelas'] ?? '-') ?> · NIS: <?= esc($item['nis'] ?? '-') ?></p>
                        <?php if ($item['gender']): ?>
                        <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block
                            <?= $item['gender'] === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' ?>">
                            <?= $item['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                        </span>
                        <?php endif ?>
                    </div>
                </div>

                <!-- Info sesi -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Sesi</p>
                        <p class="font-semibold text-gray-800"><?= date('d F Y', strtotime($item['date'])) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Konselor</p>
                        <p class="font-semibold text-gray-800"><?= esc($item['konselor_name'] ?? '-') ?></p>
                    </div>
                </div>

                <!-- Topik -->
                <div>
                    <p class="text-xs text-gray-400 mb-2">Topik / Permasalahan</p>
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-sm font-bold bg-indigo-100 text-indigo-700">
                        <i class="fas fa-tag text-xs"></i>
                        <?= esc($item['topic']) ?>
                    </span>
                </div>

                <!-- Hasil -->
                <div>
                    <p class="text-xs text-gray-400 mb-2">Hasil / Tindak Lanjut</p>
                    <?php if ($item['result']): ?>
                    <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-700 leading-relaxed">
                        <?= nl2br(esc($item['result'])) ?>
                    </div>
                    <?php else: ?>
                    <p class="text-sm text-gray-400 italic">Belum ada catatan hasil</p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat siswa -->
    <div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-history" style="color:var(--color-primary)"></i>
                    Riwayat Konseling Siswa
                </h4>
            </div>
            <?php if (empty($riwayat)): ?>
            <div class="py-10 text-center text-gray-400">
                <i class="fas fa-history text-3xl text-gray-200 mb-2"></i>
                <p class="text-xs">Belum ada riwayat lain</p>
            </div>
            <?php else: ?>
            <div class="divide-y divide-gray-50">
                <?php foreach ($riwayat as $r): ?>
                <a href="<?= base_url('konseling/' . $r['id']) ?>"
                   class="block p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5"
                             style="background:var(--color-primary)22">
                            <i class="fas fa-comments text-xs" style="color:var(--color-primary)"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-700"><?= esc($r['topic']) ?></p>
                            <p class="text-xs text-gray-400"><?= date('d F Y', strtotime($r['date'])) ?></p>
                            <?php if ($r['konselor_name']): ?>
                            <p class="text-xs text-gray-400"><?= esc($r['konselor_name']) ?></p>
                            <?php endif ?>
                        </div>
                    </div>
                </a>
                <?php endforeach ?>
            </div>
            <?php endif ?>
        </div>

        <div class="mt-4">
            <a href="<?= base_url('konseling') ?>"
               class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?php $this->endSection() ?>