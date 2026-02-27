<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<?php $role = session()->get('role') ?>
<a href="<?= base_url('dashboard/' . $role) ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<div>
    <button onclick="toggleSubmenu()" class="menu-item active w-full flex items-center justify-between px-4 py-3 rounded-xl">
        <div class="flex items-center space-x-3">
            <i class="fas fa-money-bill-wave w-5"></i>
            <span class="sidebar-text font-semibold text-sm">Keuangan</span>
        </div>
        <i class="fas fa-chevron-down sidebar-text text-xs" id="submenuIcon"></i>
    </button>
            <div class="ml-4 mt-1 space-y-1 sidebar-text" id="keuanganSubmenu">
        <a href="<?= base_url('keuangan') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-chart-pie w-4"></i><span>Dashboard</span>
        </a>
        <a href="<?= base_url('keuangan/pemasukan') ?>" class="menu-item active flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-arrow-down w-4"></i><span>Pemasukan</span>
        </a>
        <a href="<?= base_url('keuangan/pengeluaran') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-arrow-up w-4"></i><span>Pengeluaran</span>
        </a>
    </div>
</div>
<a href="<?= base_url('laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chart-line w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-key w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up"
         style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-arrow-down text-2xl"></i>
        </div>
        <h3 class="text-2xl font-bold mb-1">
            Rp <?= number_format(array_sum(array_column($pemasukan ?? [], 'jumlah')) / 1000000, 1) ?> Jt
        </h3>
        <p class="text-white/80 text-sm">Total Pemasukan</p>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-receipt text-2xl"></i>
        </div>
        <h3 class="text-2xl font-bold mb-1"><?= count($pemasukan ?? []) ?></h3>
        <p class="text-white/80 text-sm">Total Transaksi</p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-calendar-check text-2xl"></i>
        </div>
        <h3 class="text-2xl font-bold mb-1">
            <?php
            $bulanIni = array_filter($pemasukan ?? [], fn($p) => date('Y-m', strtotime($p['tanggal_transaksi'])) === date('Y-m'));
            echo count($bulanIni);
            ?>
        </h3>
        <p class="text-white/80 text-sm">Transaksi Bulan Ini</p>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">

    <!-- Table Header -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Daftar Pemasukan</h2>
                <p class="text-sm text-gray-400 mt-1">Riwayat semua transaksi pemasukan</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <!-- Filter bulan -->
                <input type="month" id="filterBulan" value="<?= date('Y-m') ?>"
                    class="px-3 py-2 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-accent">
                <?php if ($canEdit): ?>
                <a href="<?= base_url('keuangan/pemasukan/add') ?>" class="btn-primary flex items-center space-x-2 px-5 py-2.5 rounded-xl font-semibold text-sm">
                    <i class="fas fa-plus"></i><span>Tambah</span>
                </a>
                <?php endif ?>
                <button onclick="window.print()" class="flex items-center space-x-2 bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-all font-semibold text-sm">
                    <i class="fas fa-print"></i><span>Cetak</span>
                </button>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari pemasukan..."
                        class="pl-9 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-accent w-48 text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" class="text-white">
                    <th class="px-6 py-4 text-left text-sm font-bold">No</th>
                    <th class="px-6 py-4 text-left text-sm font-bold">Tanggal</th>
                    <th class="px-6 py-4 text-left text-sm font-bold">Kategori</th>
                    <th class="px-6 py-4 text-left text-sm font-bold">Keterangan</th>
                    <th class="px-6 py-4 text-left text-sm font-bold">Sumber</th>
                    <th class="px-6 py-4 text-left text-sm font-bold">Metode</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">Jumlah</th>
                    <?php if ($canEdit): ?>
                    <th class="px-6 py-4 text-left text-sm font-bold">Aksi</th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (empty($pemasukan)): ?>
                <tr>
                    <td colspan="<?= $canEdit ? 8 : 7 ?>" class="px-6 py-16 text-center text-gray-400">
                        <i class="fas fa-inbox text-6xl mb-4 block opacity-20"></i>
                        <p class="text-lg font-semibold">Belum ada data pemasukan</p>
                        <?php if ($canEdit): ?>
                        <p class="text-sm mt-1">Klik "Tambah" untuk mencatat pemasukan baru</p>
                        <?php endif ?>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($pemasukan as $i => $p): ?>
                <tr class="hover:bg-gray-50 transition-colors" data-tanggal="<?= date('Y-m', strtotime($p['tanggal_transaksi'])) ?>">
                    <td class="px-6 py-4 text-sm text-gray-500"><?= $i + 1 ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold whitespace-nowrap">
                        <?= date('d M Y', strtotime($p['tanggal_transaksi'])) ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(var(--color-primary-rgb), 0.1); color: var(--color-primary)">
                            <?= esc($p['nama_kategori'] ?? '-') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate"><?= esc($p['keterangan']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= esc($p['sumber'] ?? '-') ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold">
                            <?= esc($p['metode_pembayaran'] ?? '-') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-sm font-bold text-emerald-600">
                            +Rp <?= number_format($p['jumlah'], 0, ',', '.') ?>
                        </span>
                    </td>
                    <?php if ($canEdit): ?>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="<?= base_url('keuangan/pemasukan/edit/' . $p['id']) ?>"
                               class="p-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-all" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="confirmDelete(<?= $p['id'] ?>, '<?= esc($p['keterangan']) ?>')"
                               class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all" title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                    <?php endif ?>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Total <span class="font-bold text-gray-900"><?= count($pemasukan ?? []) ?></span> transaksi
        </p>
        <p class="text-sm font-bold text-emerald-600">
            Total: Rp <?= number_format(array_sum(array_column($pemasukan ?? [], 'jumlah')), 0, ',', '.') ?>
        </p>
    </div>
</div>

<!-- Modal Hapus -->
<?php if ($canEdit): ?>
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-1">Hapus transaksi pemasukan:</p>
            <p class="font-bold text-gray-900 mb-6" id="deleteKeterangan"></p>
            <div class="flex space-x-4">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 font-semibold transition-all">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php $this->endSection() ?>


<?php $this->section('scripts') ?>
<script>
    function toggleSubmenu() {
        const el = document.getElementById('keuanganSubmenu');
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
        document.getElementById('submenuIcon').classList.toggle('rotate-180');
    }

    // Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#tableBody tr[data-tanggal]').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // Filter bulan
    document.getElementById('filterBulan').addEventListener('change', function() {
        const val = this.value; // format: YYYY-MM
        document.querySelectorAll('#tableBody tr[data-tanggal]').forEach(row => {
            row.style.display = (!val || row.dataset.tanggal === val) ? '' : 'none';
        });
    });

    <?php if ($canEdit): ?>
    function confirmDelete(id, keterangan) {
        document.getElementById('deleteKeterangan').textContent = keterangan;
        document.getElementById('deleteForm').action = '<?= base_url('keuangan/pemasukan/delete/') ?>' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    <?php endif ?>
</script>
<?php $this->endSection() ?>