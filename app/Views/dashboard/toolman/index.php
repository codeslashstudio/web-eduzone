<?php $this->extend('layout/main') ?>
<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('dashboard') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-key w-5"></i><span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$invStat        = $invStat        ?? [];
$inventaris     = $inventaris     ?? [];
$laporanTerbaru = $laporanTerbaru ?? [];
$labBookings    = $labBookings    ?? [];
$labVisits      = $labVisits      ?? [];
$itemRusak      = $itemRusak      ?? [];
$totalItem    = $invStat['total_item']   ?? 0;
$totalUnit    = $invStat['total_unit']   ?? 0;
$kondisiBaik  = $invStat['kondisi_baik'] ?? 0;
$rusakRingan  = $invStat['rusak_ringan'] ?? 0;
$rusakBerat   = $invStat['rusak_berat']  ?? 0;
?>

<!-- Banner -->
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden" data-aos="fade-up"
     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
    <div class="relative z-10">
        <p class="text-white/70 text-sm mb-1">Selamat datang,</p>
        <h1 class="text-2xl font-bold mb-1"><?= esc(session()->get('username')) ?></h1>
        <p class="text-white/80 text-sm"><i class="fas fa-tools mr-2"></i>Toolman / Laboran — <?= date('l, d F Y') ?></p>
    </div>
    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute -right-4 top-10 w-24 h-24 bg-white/10 rounded-full"></div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="rounded-2xl p-5 text-white shadow-xl" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" data-aos="fade-up">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-boxes text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $totalItem ?></h3>
        <p class="text-white/80 text-sm">Jenis Alat</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-check-circle text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $kondisiBaik ?></h3>
        <p class="text-white/80 text-sm">Kondisi Baik</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-exclamation-circle text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $rusakRingan ?></h3>
        <p class="text-white/80 text-sm">Rusak Ringan</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-times-circle text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $rusakBerat ?></h3>
        <p class="text-white/80 text-sm">Rusak Berat</p>
    </div>
</div>

<!-- Lab Booking Hari Ini + Item Rusak -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-flask" style="color:var(--color-primary)"></i>Peminjaman Lab Hari Ini
            </h3>
        </div>
        <?php if (empty($labBookings)): ?>
        <div class="p-8 text-center text-gray-400"><i class="fas fa-calendar-times text-4xl mb-2 text-gray-200"></i><p class="text-sm">Tidak ada peminjaman hari ini</p></div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($labBookings as $b): ?>
            <div class="px-5 py-4 flex items-center gap-3 hover:bg-gray-50">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-flask text-blue-500 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($b['lab_name'] ?? $b['purpose'] ?? '-') ?></p>
                    <p class="text-gray-400 text-xs"><?= esc($b['nama_guru'] ?? '') ?></p>
                </div>
                <span class="text-xs font-bold" style="color:var(--color-primary)">
                    <?= date('H:i', strtotime($b['start_time'] ?? '00:00')) ?>
                </span>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-tools text-red-500"></i>Alat Perlu Perbaikan
            </h3>
        </div>
        <?php if (empty($itemRusak)): ?>
        <div class="p-8 text-center text-gray-400"><i class="fas fa-smile text-4xl mb-2 text-gray-200"></i><p class="text-sm">Semua alat dalam kondisi baik</p></div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($itemRusak as $item): ?>
            <?php $condColor = $item['condition'] === 'Rusak Berat' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'; ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($item['item_name']) ?></p>
                    <p class="text-gray-400 text-xs"><?= esc($item['location'] ?? '') ?> • <?= $item['quantity'] ?> unit</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full <?= $condColor ?>"><?= $item['condition'] ?></span>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Inventaris Lengkap -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-list" style="color:var(--color-primary)"></i>Daftar Inventaris
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" class="text-white">
                    <th class="px-5 py-3 text-left text-sm font-bold">Nama Alat</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Jumlah</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Kondisi</th>
                    <th class="px-5 py-3 text-left text-sm font-bold">Lokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($inventaris as $item):
                    $cc = match($item['condition']) {
                        'Baik'         => 'bg-emerald-100 text-emerald-700',
                        'Rusak Ringan' => 'bg-yellow-100 text-yellow-700',
                        'Rusak Berat'  => 'bg-red-100 text-red-700',
                        default        => 'bg-gray-100 text-gray-600'
                    };
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-semibold text-gray-800 text-sm"><?= esc($item['item_name']) ?></td>
                    <td class="px-5 py-3 text-center text-sm font-bold text-gray-700"><?= $item['quantity'] ?></td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full <?= $cc ?>"><?= $item['condition'] ?></span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-500"><?= esc($item['location'] ?? '-') ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->endSection() ?>