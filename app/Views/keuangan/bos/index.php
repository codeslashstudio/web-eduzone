<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$dana_bos = $dana_bos ?? [];
$canEdit  = $canEdit  ?? false;

$totalDiterima = array_sum(array_column($dana_bos, 'jumlah_diterima'));

$triwulanLabels = ['1' => 'Triwulan I', '2' => 'Triwulan II', '3' => 'Triwulan III', '4' => 'Triwulan IV'];
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Dana BOS / BOP</h1>
        <p class="text-sm text-gray-400 mt-0.5">Bantuan Operasional Sekolah</p>
    </div>
    <div class="flex items-center gap-2">
        <form method="GET" class="flex gap-2">
            <select name="tahun_ajaran" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <?php foreach (['2025/2026','2024/2025','2023/2024'] as $ta): ?>
                <option value="<?= $ta ?>" <?= ($tahun_ajaran ?? '2025/2026') === $ta ? 'selected' : '' ?>><?= $ta ?></option>
                <?php endforeach ?>
            </select>
        </form>
        <?php if ($canEdit): ?>
        <button onclick="document.getElementById('modalTambahBOS').classList.remove('hidden')"
            class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold">
            <i class="fas fa-plus text-xs"></i> Tambah Dana BOS
        </button>
        <?php endif ?>
    </div>
</div>

<!-- Summary -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 sm:col-span-1" data-aos="fade-up">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#10b98122">
            <i class="fas fa-university text-base" style="color:#10b981"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp <?= number_format($totalDiterima, 0, ',', '.') ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Total Dana Diterima</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#6366f122">
            <i class="fas fa-list-ol text-base" style="color:#6366f1"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= count($dana_bos) ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Total Entri BOS</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#f59e0b22">
            <i class="fas fa-chart-pie text-base" style="color:#f59e0b"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= count(array_unique(array_column($dana_bos, 'semester'))) ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Semester Tercatat</p>
    </div>
</div>

<!-- List Dana BOS -->
<div class="space-y-3">
    <?php if (empty($dana_bos)): ?>
    <div class="bg-white rounded-2xl py-16 text-center text-gray-400 shadow-sm border border-gray-100">
        <i class="fas fa-university text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada data dana BOS</p>
    </div>
    <?php else: ?>
    <?php foreach ($dana_bos as $bos): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="p-5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                     style="background:var(--color-primary)22">
                    <i class="fas fa-money-check-alt text-lg" style="color:var(--color-primary)"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">
                            <?= esc($bos['semester']) ?>
                        </span>
                        <?php if ($bos['triwulan']): ?>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                            <?= $triwulanLabels[$bos['triwulan']] ?? '' ?>
                        </span>
                        <?php endif ?>
                        <span class="text-xs text-gray-400"><?= esc($bos['tahun_ajaran']) ?></span>
                    </div>
                    <p class="text-lg font-bold text-gray-900">
                        Rp <?= number_format($bos['jumlah_diterima'], 0, ',', '.') ?>
                    </p>
                    <p class="text-xs text-gray-400">
                        Diterima: <?= date('d F Y', strtotime($bos['tanggal_terima'])) ?>
                        <?php if ($bos['keterangan']): ?>
                        · <?= esc($bos['keterangan']) ?>
                        <?php endif ?>
                    </p>
                </div>
            </div>
            <a href="<?= base_url('keuangan/bos/detail/' . $bos['id_bos']) ?>"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors whitespace-nowrap">
                <i class="fas fa-eye text-xs"></i> Realisasi
            </a>
        </div>
    </div>
    <?php endforeach ?>
    <?php endif ?>
</div>

<!-- Modal Tambah BOS -->
<?php if ($canEdit): ?>
<div id="modalTambahBOS" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-university" style="color:var(--color-primary)"></i>
            Tambah Dana BOS
        </h3>
        <form method="POST" action="<?= base_url('keuangan/bos/store') ?>">
            <?= csrf_field() ?>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" value="2025/2026" required
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Semester</label>
                        <select name="semester" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Triwulan</label>
                        <select name="triwulan" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                            <option value="">-</option>
                            <option value="1">Triwulan I</option>
                            <option value="2">Triwulan II</option>
                            <option value="3">Triwulan III</option>
                            <option value="4">Triwulan IV</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tgl Terima</label>
                        <input type="date" name="tanggal_terima" value="<?= date('Y-m-d') ?>" required
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Diterima (Rp)</label>
                    <input type="number" name="jumlah_diterima" placeholder="0" required min="0"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" placeholder="opsional"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="button" onclick="document.getElementById('modalTambahBOS').classList.add('hidden')"
                    class="flex-1 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm font-semibold">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif ?>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
document.getElementById('modalTambahBOS')?.addEventListener('click', function(e) {
    if (e.target === this) this.classList.add('hidden');
});
</script>
<?php $this->endSection() ?>