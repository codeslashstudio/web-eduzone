<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$siswa    = $siswa    ?? [];
$grades   = $grades   ?? [];
$sikap    = $sikap    ?? [];
$config   = $config   ?? [];
$rataRata = $rataRata ?? null;
$tahun    = $tahun    ?? '2025/2026';
$semester = $semester ?? 'Ganjil';
$canInput = $canInput ?? false;

$sikapLabel = ['SB'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','K'=>'Kurang'];
$predColors = [
    'A' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
    'B' => 'bg-blue-100 text-blue-700 border-blue-200',
    'C' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
    'D' => 'bg-red-100 text-red-600 border-red-200',
];
?>

<!-- Print button -->
<div class="flex items-center justify-between mb-5 print:hidden" data-aos="fade-down">
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="<?= base_url('nilai') ?>" class="hover:text-gray-600">Nilai</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-700 font-semibold">Rapor <?= esc($siswa['full_name']) ?></span>
    </div>
    <div class="flex gap-2">
        <?php if ($canInput && !($config['is_finalized'] ?? false)): ?>
        <a href="<?= base_url('nilai/kelas/' . $siswa['class_id'] . '?tahun=' . $tahun . '&semester=' . $semester) ?>"
           class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
            <i class="fas fa-edit mr-1 text-xs"></i> Edit Nilai
        </a>
        <?php endif ?>
        <button onclick="window.print()"
                class="btn-primary px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fas fa-print text-xs"></i> Cetak Rapor
        </button>
    </div>
</div>

<!-- RAPOR CARD -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-0" id="raporPrint" data-aos="fade-up">

    <!-- Header Rapor -->
    <div class="p-6 border-b-2 border-gray-100" style="background: linear-gradient(135deg, var(--color-primary)10, var(--color-secondary)10)">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                <i class="fas fa-graduation-cap text-3xl" style="color:var(--color-primary)"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Laporan Hasil Belajar</p>
                <h1 class="text-2xl font-extrabold text-gray-900">RAPOR SISWA</h1>
                <p class="text-sm text-gray-500 mt-0.5">Semester <?= $semester ?> — Tahun Ajaran <?= $tahun ?></p>
            </div>
            <div class="ml-auto text-right">
                <?php if ($config['is_finalized'] ?? false): ?>
                <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-xl">
                    <i class="fas fa-check-circle"></i> Nilai Final
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1.5 bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1.5 rounded-xl">
                    <i class="fas fa-clock"></i> Draft
                </span>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Info Siswa -->
    <div class="p-6 border-b border-gray-100">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Nama Siswa</p>
                <p class="font-bold text-gray-900"><?= esc($siswa['full_name']) ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">NIS / NISN</p>
                <p class="font-semibold text-gray-700"><?= esc($siswa['nis']) ?> / <?= esc($siswa['nisn'] ?? '-') ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Kelas</p>
                <p class="font-semibold text-gray-700"><?= esc($siswa['nama_kelas'] ?? '-') ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Wali Kelas</p>
                <p class="font-semibold text-gray-700"><?= esc($siswa['wakel_name'] ?? '-') ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Jurusan</p>
                <p class="font-semibold text-gray-700"><?= esc($siswa['major_name'] ?? '-') ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Kurikulum</p>
                <p class="font-semibold text-gray-700"><?= $config['kurikulum'] ?? 'Merdeka' ?>
                    <?php if (($config['kurikulum'] ?? '') === 'K13'): ?>
                    <span class="text-xs text-gray-400">(KKM: <?= $config['kkm'] ?>)</span>
                    <?php endif ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Tabel Nilai Mapel -->
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-book text-indigo-500"></i> Nilai Mata Pelajaran
        </h3>
        <?php if (!empty($grades)): ?>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b-2 border-gray-100 text-xs font-semibold text-gray-500">
                    <th class="text-left py-2 w-8">#</th>
                    <th class="text-left py-2">Mata Pelajaran</th>
                    <th class="text-center py-2 w-28">Nilai Harian</th>
                    <th class="text-center py-2 w-28">Nilai Tugas</th>
                    <th class="text-center py-2 w-28">Nilai Akhir</th>
                    <th class="text-center py-2 w-24">Predikat</th>
                    <th class="text-left py-2">Catatan Guru</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($grades as $i => $g): ?>
                <tr>
                    <td class="py-2.5 text-gray-400 text-xs"><?= $i+1 ?></td>
                    <td class="py-2.5 font-semibold text-gray-800"><?= esc($g['subject']) ?></td>
                    <td class="py-2.5 text-center text-gray-600"><?= $g['nilai_harian'] !== null ? number_format($g['nilai_harian'],1) : '—' ?></td>
                    <td class="py-2.5 text-center text-gray-600"><?= $g['nilai_tugas']  !== null ? number_format($g['nilai_tugas'],1)  : '—' ?></td>
                    <td class="py-2.5 text-center">
                        <?php if ($g['nilai_akhir'] !== null): ?>
                        <span class="font-extrabold text-base <?= (float)$g['nilai_akhir'] >= 75 ? 'text-emerald-700' : 'text-red-600' ?>">
                            <?= number_format($g['nilai_akhir'],1) ?>
                        </span>
                        <?php else: ?>—<?php endif ?>
                    </td>
                    <td class="py-2.5 text-center">
                        <?php $p = $g['predikat'] ?? ''; ?>
                        <?php if ($p): ?>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg border <?= $predColors[$p] ?? 'bg-gray-100 text-gray-500' ?>">
                            <?= $p ?>
                        </span>
                        <?php else: ?>—<?php endif ?>
                    </td>
                    <td class="py-2.5 text-xs text-gray-500 italic"><?= esc($g['catatan'] ?? '') ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-200 bg-gray-50">
                    <td colspan="4" class="py-3 px-0 text-right font-bold text-gray-600 text-sm">Rata-rata Nilai Akhir:</td>
                    <td class="py-3 text-center">
                        <span class="font-extrabold text-lg <?= $rataRata >= 75 ? 'text-emerald-700' : 'text-red-600' ?>">
                            <?= $rataRata !== null ? number_format($rataRata,2) : '—' ?>
                        </span>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
        <p class="text-gray-400 text-sm py-4 text-center">Belum ada nilai yang diinput</p>
        <?php endif ?>
    </div>

    <!-- Sikap & Kehadiran -->
    <div class="p-6 border-b border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Sikap -->
            <div>
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-heart text-amber-500"></i> Penilaian Sikap
                </h3>
                <?php if (!empty($sikap)): ?>
                <div class="space-y-2">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-600">Sikap Spiritual</span>
                        <span class="font-bold text-sm text-indigo-700">
                            <?= $sikap['sikap_spiritual'] ?> — <?= $sikapLabel[$sikap['sikap_spiritual']] ?? '' ?>
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-600">Sikap Sosial</span>
                        <span class="font-bold text-sm text-indigo-700">
                            <?= $sikap['sikap_sosial'] ?> — <?= $sikapLabel[$sikap['sikap_sosial']] ?? '' ?>
                        </span>
                    </div>
                    <?php if (!empty($sikap['catatan_sikap'])): ?>
                    <p class="text-xs text-gray-400 italic pt-1"><?= esc($sikap['catatan_sikap']) ?></p>
                    <?php endif ?>
                </div>
                <?php else: ?>
                <p class="text-gray-400 text-sm">Belum diisi</p>
                <?php endif ?>
            </div>

            <!-- Kehadiran -->
            <div>
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-teal-500"></i> Rekap Kehadiran
                </h3>
                <?php if (!empty($sikap)): ?>
                <div class="grid grid-cols-3 gap-3">
                    <div class="text-center bg-blue-50 rounded-xl py-3">
                        <p class="text-2xl font-extrabold text-blue-700"><?= $sikap['ketidakhadiran_sakit'] ?? 0 ?></p>
                        <p class="text-xs text-blue-500 font-semibold mt-0.5">Sakit</p>
                    </div>
                    <div class="text-center bg-yellow-50 rounded-xl py-3">
                        <p class="text-2xl font-extrabold text-yellow-700"><?= $sikap['ketidakhadiran_izin'] ?? 0 ?></p>
                        <p class="text-xs text-yellow-500 font-semibold mt-0.5">Izin</p>
                    </div>
                    <div class="text-center bg-red-50 rounded-xl py-3">
                        <p class="text-2xl font-extrabold text-red-600"><?= $sikap['ketidakhadiran_alpa'] ?? 0 ?></p>
                        <p class="text-xs text-red-400 font-semibold mt-0.5">Alpa</p>
                    </div>
                </div>
                <?php else: ?>
                <p class="text-gray-400 text-sm">Belum diisi</p>
                <?php endif ?>
            </div>
        </div>

        <!-- Catatan Wali Kelas -->
        <?php if (!empty($sikap['catatan_wakel'])): ?>
        <div class="mt-4 bg-amber-50 rounded-xl p-4 border border-amber-100">
            <p class="text-xs font-bold text-amber-700 mb-1"><i class="fas fa-comment-dots mr-1"></i>Catatan Wali Kelas</p>
            <p class="text-sm text-gray-700 italic">"<?= esc($sikap['catatan_wakel']) ?>"</p>
        </div>
        <?php endif ?>
    </div>

    <!-- Footer Rapor -->
    <div class="p-6 bg-gray-50 flex items-center justify-between text-xs text-gray-400">
        <p>Dicetak: <?= date('d F Y, H:i') ?></p>
        <p>EduZone — Sistem Informasi Manajemen Sekolah</p>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #raporPrint, #raporPrint * { visibility: visible; }
    #raporPrint { position: absolute; left: 0; top: 0; width: 100%; }
    .print\:hidden { display: none !important; }
}
</style>
<?php $this->endSection() ?>