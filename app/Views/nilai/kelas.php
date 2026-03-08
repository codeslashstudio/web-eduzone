<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$kelas      = $kelas      ?? [];
$mapelList  = $mapelList  ?? [];
$siswaList  = $siswaList  ?? [];
$grades     = $grades     ?? [];
$tahun      = $tahun      ?? '2025/2026';
$semester   = $semester   ?? 'Ganjil';
$canInput   = $canInput   ?? false;
$canFinalize= $canFinalize?? false;
$canSikap   = $canSikap   ?? false;
?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('nilai?tahun='.$tahun.'&semester='.$semester) ?>" class="hover:text-gray-600">Nilai</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= esc($kelas['nama_kelas'] ?? '') ?></span>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Header Info -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                 style="background:var(--color-primary)22">
                <i class="fas fa-graduation-cap text-xl" style="color:var(--color-primary)"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 text-lg"><?= esc($kelas['nama_kelas']) ?></h2>
                <div class="flex gap-2 mt-1 flex-wrap">
                    <span class="text-xs bg-indigo-100 text-indigo-700 font-bold px-2 py-0.5 rounded-lg"><?= $kelas['kurikulum'] ?? 'Merdeka' ?></span>
                    <?php if ($kelas['kurikulum'] === 'K13'): ?>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg">KKM: <?= $kelas['kkm'] ?></span>
                    <?php endif ?>
                    <span class="text-xs <?= $kelas['is_finalized'] ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' ?> font-bold px-2 py-0.5 rounded-lg">
                        <i class="fas <?= $kelas['is_finalized'] ? 'fa-lock' : 'fa-unlock' ?> mr-1"></i>
                        <?= $kelas['is_finalized'] ? 'Final' : 'Draft' ?>
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg"><?= $semester ?> <?= $tahun ?></span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php if ($canSikap && !$kelas['is_finalized']): ?>
            <a href="<?= base_url('nilai/sikap/' . $kelas['id'] . '?tahun=' . $tahun . '&semester=' . $semester) ?>"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-amber-100 hover:bg-amber-200 text-amber-700 transition-colors">
                <i class="fas fa-heart text-xs"></i> Input Sikap
            </a>
            <?php endif ?>
            <?php if ($canInput && !$kelas['is_finalized'] && !empty($mapelList)): ?>
            <a href="<?= base_url('nilai/input/' . $kelas['id'] . '?tahun=' . $tahun . '&semester=' . $semester . '&subject=' . urlencode($mapelList[0]['subject'] ?? '')) ?>"
               class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold">
                <i class="fas fa-edit text-xs"></i> Input Nilai
            </a>
            <?php endif ?>
            <?php if ($canFinalize && !$kelas['is_finalized']): ?>
            <form method="POST" action="<?= base_url('nilai/finalize/' . $kelas['id']) ?>"
                  onsubmit="return confirm('Kunci nilai? Setelah dikunci tidak bisa diubah.')">
                <?= csrf_field() ?>
                <input type="hidden" name="tahun" value="<?= $tahun ?>">
                <input type="hidden" name="semester" value="<?= $semester ?>">
                <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-emerald-500 hover:bg-emerald-600 text-white transition-colors">
                    <i class="fas fa-lock text-xs"></i> Finalisasi
                </button>
            </form>
            <?php endif ?>
        </div>
    </div>
</div>

<!-- Tab Mapel -->
<?php if (!empty($mapelList)): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5" data-aos="fade-up">
    <div class="p-4 border-b border-gray-100 flex flex-wrap gap-2 items-center">
        <span class="text-xs font-bold text-gray-400 uppercase mr-2">Input per Mapel:</span>
        <?php foreach ($mapelList as $m): ?>
        <a href="<?= base_url('nilai/input/' . $kelas['id'] . '?tahun=' . $tahun . '&semester=' . $semester . '&subject=' . urlencode($m['subject'])) ?>"
           class="text-xs font-semibold px-3 py-1.5 rounded-xl border transition-colors
           <?= $canInput && !$kelas['is_finalized'] ? 'border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100' : 'border-gray-200 bg-gray-50 text-gray-500 cursor-default' ?>">
            <?= esc($m['subject']) ?>
        </a>
        <?php endforeach ?>
    </div>

    <!-- Tabel Nilai -->
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left py-3 px-4 font-semibold text-gray-500 sticky left-0 bg-gray-50">Nama Siswa</th>
                    <?php foreach ($mapelList as $m): ?>
                    <th class="py-3 px-3 font-semibold text-gray-500 text-center min-w-24"><?= esc($m['subject']) ?></th>
                    <?php endforeach ?>
                    <th class="py-3 px-3 font-semibold text-gray-500 text-center">Sikap</th>
                    <th class="py-3 px-3 font-semibold text-gray-500 text-center">Rata-rata</th>
                    <th class="py-3 px-3 font-semibold text-gray-500 text-center">Rapor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($siswaList as $s): ?>
                <?php
                $nilaiList = [];
                foreach ($mapelList as $m) {
                    $g = $grades[$s['id']][$m['subject']] ?? null;
                    if ($g && $g['nilai_akhir'] !== null) $nilaiList[] = (float)$g['nilai_akhir'];
                }
                $avg = count($nilaiList) > 0 ? round(array_sum($nilaiList) / count($nilaiList), 1) : null;
                $avgColor = $avg === null ? 'text-gray-400' : ($avg >= 80 ? 'text-emerald-600' : ($avg >= 70 ? 'text-yellow-600' : 'text-red-600'));
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4 sticky left-0 bg-white">
                        <p class="font-semibold text-gray-800"><?= esc($s['full_name']) ?></p>
                        <p class="text-gray-400"><?= esc($s['nis']) ?></p>
                    </td>
                    <?php foreach ($mapelList as $m): ?>
                    <?php $g = $grades[$s['id']][$m['subject']] ?? null; ?>
                    <td class="py-3 px-3 text-center">
                        <?php if ($g && $g['nilai_akhir'] !== null): ?>
                        <div>
                            <span class="font-bold <?= (float)$g['nilai_akhir'] >= 75 ? 'text-emerald-700' : 'text-red-600' ?>">
                                <?= number_format($g['nilai_akhir'], 1) ?>
                            </span>
                            <span class="ml-1 text-xs font-bold px-1.5 py-0.5 rounded
                                <?= $g['predikat'] === 'A' ? 'bg-emerald-100 text-emerald-700' : ($g['predikat'] === 'B' ? 'bg-blue-100 text-blue-700' : ($g['predikat'] === 'C' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600')) ?>">
                                <?= $g['predikat'] ?>
                            </span>
                        </div>
                        <div class="text-gray-400 mt-0.5">
                            <?= $g['nilai_harian'] ?? '-' ?> / <?= $g['nilai_tugas'] ?? '-' ?>
                        </div>
                        <?php else: ?>
                        <span class="text-gray-300">—</span>
                        <?php endif ?>
                    </td>
                    <?php endforeach ?>
                    <td class="py-3 px-3 text-center">
                        <?php if ($s['sikap_spiritual']): ?>
                        <div class="text-xs">
                            <span class="font-semibold text-gray-700"><?= $s['sikap_spiritual'] ?></span>
                            <span class="text-gray-400">/<?= $s['sikap_sosial'] ?></span>
                        </div>
                        <?php else: ?>
                        <span class="text-gray-300">—</span>
                        <?php endif ?>
                    </td>
                    <td class="py-3 px-3 text-center">
                        <span class="font-bold text-sm <?= $avgColor ?>">
                            <?= $avg !== null ? $avg : '—' ?>
                        </span>
                    </td>
                    <td class="py-3 px-3 text-center">
                        <a href="<?= base_url('nilai/rapor/' . $s['id'] . '?tahun=' . $tahun . '&semester=' . $semester) ?>"
                           class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-indigo-100 hover:text-indigo-600 text-gray-500 transition-colors">
                            <i class="fas fa-file-alt text-xs"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="bg-white rounded-2xl py-12 text-center text-gray-400 shadow-sm border border-gray-100">
    <i class="fas fa-calendar-alt text-5xl text-gray-200 mb-4"></i>
    <p class="font-semibold">Belum ada jadwal pelajaran untuk kelas ini</p>
    <p class="text-sm mt-1">Tambahkan jadwal terlebih dahulu di menu Jadwal</p>
</div>
<?php endif ?>

<?php $this->endSection() ?>