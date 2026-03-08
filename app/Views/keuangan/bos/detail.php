<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$detail  = $detail  ?? [];
$canEdit = $canEdit ?? false;
$bos     = $detail['bos']      ?? [];
$rel     = $detail['realisasi'] ?? [];

$totalRealisasi = array_sum(array_column($rel, 'jumlah'));
$sisa = ($bos['jumlah_diterima'] ?? 0) - $totalRealisasi;
$pct  = $bos['jumlah_diterima'] > 0 ? min(100, round($totalRealisasi / $bos['jumlah_diterima'] * 100)) : 0;
$triwulanLabels = ['1'=>'Triwulan I','2'=>'Triwulan II','3'=>'Triwulan III','4'=>'Triwulan IV'];
?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('keuangan/bos') ?>" class="hover:text-gray-600">Dana BOS</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold">Detail Realisasi</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Info BOS -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" data-aos="fade-up">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4" style="background:var(--color-primary)22">
                <i class="fas fa-university text-xl" style="color:var(--color-primary)"></i>
            </div>
            <div class="space-y-3">
                <?php foreach ([
                    ['Tahun Ajaran', $bos['tahun_ajaran']  ?? '-'],
                    ['Semester',     $bos['semester']      ?? '-'],
                    ['Triwulan',     $triwulanLabels[$bos['triwulan'] ?? ''] ?? '-'],
                    ['Tgl Terima',   $bos['tanggal_terima'] ? date('d F Y', strtotime($bos['tanggal_terima'])) : '-'],
                ] as [$lbl,$val]): ?>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400"><?= $lbl ?></span>
                    <span class="text-sm font-semibold text-gray-800"><?= esc($val) ?></span>
                </div>
                <?php endforeach ?>
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Dana Diterima</p>
                    <p class="text-xl font-bold text-gray-900">Rp <?= number_format($bos['jumlah_diterima'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Progress realisasi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" data-aos="fade-up">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Realisasi Penggunaan</p>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Terpakai</span>
                    <span class="font-bold text-gray-900">Rp <?= number_format($totalRealisasi, 0, ',', '.') ?></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-500"
                         style="width:<?= $pct ?>%; background:var(--color-primary)"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400">
                    <span><?= $pct ?>% terpakai</span>
                    <span>Sisa: Rp <?= number_format($sisa, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel realisasi -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Rincian Pengeluaran BOS</h3>
            </div>
            <?php if (empty($rel)): ?>
            <div class="py-12 text-center text-gray-400">
                <i class="fas fa-receipt text-4xl text-gray-200 mb-3"></i>
                <p class="font-semibold text-sm">Belum ada realisasi</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">No. Transaksi</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Tanggal</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Keterangan</th>
                            <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($rel as $r): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 font-mono text-xs text-gray-600"><?= esc($r['no_transaksi'] ?? '-') ?></td>
                            <td class="py-3 px-4 text-xs text-gray-600"><?= date('d/m/Y', strtotime($r['tanggal_transaksi'])) ?></td>
                            <td class="py-3 px-4 text-sm text-gray-700"><?= esc($r['keterangan']) ?></td>
                            <td class="py-3 px-4 text-right font-semibold text-red-600">
                                Rp <?= number_format($r['jumlah'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot class="border-t-2 border-gray-200">
                        <tr class="bg-gray-50">
                            <td colspan="3" class="py-3 px-4 text-sm font-bold text-gray-700">Total</td>
                            <td class="py-3 px-4 text-right font-bold text-red-600">
                                Rp <?= number_format($totalRealisasi, 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>
<?php $this->endSection() ?>