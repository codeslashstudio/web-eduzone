<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$audit_log = $audit_log ?? [];

$aksiColors = [
    'CREATE'  => 'bg-emerald-100 text-emerald-700',
    'UPDATE'  => 'bg-blue-100 text-blue-700',
    'DELETE'  => 'bg-red-100 text-red-700',
    'APPROVE' => 'bg-purple-100 text-purple-700',
    'REJECT'  => 'bg-orange-100 text-orange-700',
];
$aksiIcons = [
    'CREATE'  => 'fa-plus-circle',
    'UPDATE'  => 'fa-edit',
    'DELETE'  => 'fa-trash',
    'APPROVE' => 'fa-check-circle',
    'REJECT'  => 'fa-times-circle',
];
?>

<!-- Header + Filter -->
<div class="flex items-center justify-between mb-5 gap-3">
    <h1 class="text-xl font-bold text-gray-900">Riwayat Audit Keuangan</h1>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <!-- Filter -->
    <div class="p-5 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <select name="tabel" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Tabel</option>
                <?php foreach (['transaksi_pemasukan','transaksi_pengeluaran','pengajuan_anggaran','dana_bos'] as $t): ?>
                <option value="<?= $t ?>" <?= ($tabel ?? '') === $t ? 'selected' : '' ?>><?= str_replace('_',' ', ucfirst($t)) ?></option>
                <?php endforeach ?>
            </select>
            <input type="date" name="start_date" value="<?= esc($start_date ?? '') ?>"
                   class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none">
            <input type="date" name="end_date" value="<?= esc($end_date ?? '') ?>"
                   class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none">
            <button type="submit" class="btn-primary px-3 py-2 rounded-xl text-sm">
                <i class="fas fa-filter text-xs"></i>
            </button>
            <a href="<?= base_url('keuangan/audit') ?>" class="px-3 py-2 text-sm text-gray-400 border border-gray-200 rounded-xl hover:bg-gray-50">
                <i class="fas fa-times text-xs"></i>
            </a>
        </form>
    </div>

    <?php if (empty($audit_log)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-history text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada riwayat audit</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Waktu</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Tabel</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">User</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($audit_log as $log): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-4">
                        <p class="text-xs font-semibold text-gray-800"><?= date('d/m/Y', strtotime($log['created_at'])) ?></p>
                        <p class="text-xs text-gray-400"><?= date('H:i:s', strtotime($log['created_at'])) ?></p>
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg <?= $aksiColors[$log['aksi']] ?? 'bg-gray-100 text-gray-600' ?>">
                            <i class="fas <?= $aksiIcons[$log['aksi']] ?? 'fa-circle' ?> text-xs"></i>
                            <?= $log['aksi'] ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-xs text-gray-600 font-mono"><?= esc($log['tabel']) ?></td>
                    <td class="py-3.5 px-4 text-xs text-gray-600"><?= esc($log['username'] ?? '-') ?></td>
                    <td class="py-3.5 px-4 text-xs text-gray-400 font-mono"><?= esc($log['ip_address'] ?? '-') ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>
<?php $this->endSection() ?>