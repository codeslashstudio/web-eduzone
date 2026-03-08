<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$kelasList   = $kelasList   ?? [];
$tahun       = $tahun       ?? '2025/2026';
$semester    = $semester    ?? 'Ganjil';
$canInput    = $canInput    ?? false;
$canFinalize = $canFinalize ?? false;
$gradeColors = ['X'=>'bg-blue-100 text-blue-700','XI'=>'bg-purple-100 text-purple-700','XII'=>'bg-rose-100 text-rose-700'];
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="tahun" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none font-semibold">
            <?php foreach (['2025/2026','2024/2025','2026/2027'] as $ty): ?>
            <option value="<?= $ty ?>" <?= $tahun === $ty ? 'selected':'' ?>><?= $ty ?></option>
            <?php endforeach ?>
        </select>
        <select name="semester" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
            <option value="Ganjil" <?= $semester==='Ganjil'?'selected':'' ?>>Semester Ganjil</option>
            <option value="Genap"  <?= $semester==='Genap' ?'selected':'' ?>>Semester Genap</option>
        </select>
        <button type="submit" class="btn-primary px-4 py-2 rounded-xl text-sm">
            <i class="fas fa-filter mr-1 text-xs"></i> Filter
        </button>
    </form>
</div>

<!-- Daftar Kelas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php if (empty($kelasList)): ?>
    <div class="col-span-3 bg-white rounded-2xl py-16 text-center text-gray-400 shadow-sm border border-gray-100">
        <i class="fas fa-graduation-cap text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Tidak ada kelas yang tersedia</p>
    </div>
    <?php endif ?>

    <?php foreach ($kelasList as $k): ?>
    <?php
    $pct = $k['jumlah_siswa'] > 0 ? round(($k['sudah_dinilai'] ?? 0) / $k['jumlah_siswa'] * 100) : 0;
    $barColor = $pct >= 100 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#6366f1');
    ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow" data-aos="fade-up">
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <span class="text-xs font-bold px-2 py-0.5 rounded-lg <?= $gradeColors[$k['grade']] ?? 'bg-gray-100 text-gray-600' ?>">
                        <?= $k['grade'] ?>
                    </span>
                    <?php if ($k['is_finalized']): ?>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-lg bg-emerald-100 text-emerald-700">
                        <i class="fas fa-lock mr-1"></i>Final
                    </span>
                    <?php endif ?>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg bg-indigo-50 text-indigo-600">
                    <?= $k['kurikulum'] ?? 'Merdeka' ?>
                </span>
            </div>
            <h3 class="font-bold text-gray-900 text-lg"><?= esc($k['nama_kelas']) ?></h3>
            <p class="text-xs text-gray-400 mt-0.5"><?= esc($k['major_name']) ?> · Wali: <?= esc($k['wakel_name'] ?? '-') ?></p>

            <!-- Progress nilai -->
            <div class="mt-4">
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1.5">
                    <span>Progress Penilaian</span>
                    <span class="font-bold" style="color:<?= $barColor ?>"><?= $pct ?>%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all" style="width:<?= $pct ?>%;background:<?= $barColor ?>"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1"><?= $k['sudah_dinilai'] ?? 0 ?> / <?= $k['jumlah_siswa'] ?> siswa dinilai</p>
            </div>
        </div>
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
            <a href="<?= base_url('nilai/kelas/' . $k['id'] . '?tahun=' . $tahun . '&semester=' . $semester) ?>"
               class="flex-1 text-center btn-primary py-2 rounded-xl text-xs font-semibold">
                <i class="fas fa-table mr-1"></i> Lihat Nilai
            </a>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php $this->endSection() ?>