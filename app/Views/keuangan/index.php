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
        <i class="fas fa-chevron-down sidebar-text text-xs transition-transform" id="submenuIcon"></i>
    </button>
            <div class="ml-4 mt-1 space-y-1 sidebar-text" id="keuanganSubmenu">
        <a href="<?= base_url('keuangan') ?>" class="menu-item active flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-chart-pie w-4"></i><span>Dashboard</span>
        </a>
        <a href="<?= base_url('keuangan/pemasukan') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
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

<style>
    .submenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .submenu.active { max-height: 300px; }
    .stat-card { transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-6px); }
</style>

<!-- Filter Periode -->
<div class="bg-white rounded-2xl shadow-lg p-5 mb-6" data-aos="fade-up">
    <div class="flex flex-col md:flex-row md:items-end gap-4 justify-between">
        <div class="flex items-end gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Periode</label>
                <select class="px-4 py-2 border-2 border-gray-200 rounded-xl text-sm focus:outline-none" style="border-color: var(--color-primary) !important">
                    <option>Bulan Ini</option><option>Bulan Lalu</option>
                    <option>Triwulan</option><option>Semester</option><option>Tahun Ini</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Ajaran</label>
                <select class="px-4 py-2 border-2 border-gray-200 rounded-xl text-sm focus:outline-none">
                    <option>2025/2026</option><option>2024/2025</option>
                </select>
            </div>
        </div>
        <button onclick="location.reload()" class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold text-sm transition-all">
            <i class="fas fa-sync-alt"></i><span>Refresh</span>
        </button>
    </div>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center"><i class="fas fa-arrow-down text-3xl"></i></div>
            <span class="text-xs bg-white/20 px-3 py-1 rounded-full">+12.5%</span>
        </div>
        <h3 class="text-3xl font-bold mb-1">Rp <?= isset($totalPemasukan) ? number_format($totalPemasukan/1000000, 1) : '0' ?> Jt</h3>
        <p class="text-blue-100 text-sm font-medium">Total Pemasukan</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('keuangan/pemasukan') ?>" class="text-sm flex items-center hover:underline">Lihat Detail <i class="fas fa-arrow-right ml-2"></i></a>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center"><i class="fas fa-arrow-up text-3xl"></i></div>
            <span class="text-xs bg-white/20 px-3 py-1 rounded-full">+8.3%</span>
        </div>
        <h3 class="text-3xl font-bold mb-1">Rp <?= isset($totalPengeluaran) ? number_format($totalPengeluaran/1000000, 1) : '0' ?> Jt</h3>
        <p class="text-red-100 text-sm font-medium">Total Pengeluaran</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="<?= base_url('keuangan/pengeluaran') ?>" class="text-sm flex items-center hover:underline">Lihat Detail <i class="fas fa-arrow-right ml-2"></i></a>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center"><i class="fas fa-wallet text-3xl"></i></div>
            <span class="text-xs bg-blue-400 px-3 py-1 rounded-full">Positif</span>
        </div>
        <?php $saldo = ($totalPemasukan ?? 0) - ($totalPengeluaran ?? 0); ?>
        <h3 class="text-3xl font-bold mb-1">Rp <?= number_format($saldo/1000000, 1) ?> Jt</h3>
        <p class="text-indigo-100 text-sm font-medium">Saldo Kas</p>
        <div class="mt-4 pt-4 border-t border-white/20"><p class="text-xs">Pemasukan - Pengeluaran</p></div>
    </div>

    <div class="stat-card rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300"
         style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center"><i class="fas fa-hand-holding-usd text-3xl"></i></div>
            <span class="text-xs bg-white/20 px-3 py-1 rounded-full">75%</span>
        </div>
        <h3 class="text-3xl font-bold mb-1">Rp <?= isset($danaBos) ? number_format($danaBos/1000000, 0) : '0' ?> Jt</h3>
        <p class="text-white/80 text-sm font-medium">Dana BOS/BOP</p>
        <div class="mt-4 pt-4 border-t border-white/20"><p class="text-xs">Realisasi tahun ajaran ini</p></div>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-900 flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs" style="background: var(--color-primary)">
                    <i class="fas fa-chart-line"></i>
                </span>
                Arus Kas Bulanan
            </h3>
        </div>
        <canvas id="cashFlowChart"></canvas>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
        <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs" style="background: var(--color-secondary)">
                <i class="fas fa-chart-pie"></i>
            </span>
            Kategori Pengeluaran
        </h3>
        <canvas id="expenseCategoryChart"></canvas>
    </div>
</div>

<!-- Transaksi + Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
        <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-indigo-500">
                <i class="fas fa-receipt"></i>
            </span>
            Transaksi Terbaru
        </h3>
        <div class="space-y-3">
            <?php if (!empty($transaksiTerbaru)): ?>
                <?php foreach (array_slice($transaksiTerbaru, 0, 4) as $t):
                    $isIn = ($t['tipe'] ?? '') === 'pemasukan'; ?>
                <div class="flex items-center justify-between p-3 <?= $isIn ? 'bg-blue-50' : 'bg-red-50' ?> rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 <?= $isIn ? 'bg-blue-500' : 'bg-red-500' ?> rounded-full flex items-center justify-center">
                            <i class="fas <?= $isIn ? 'fa-arrow-down' : 'fa-arrow-up' ?> text-white text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm"><?= esc($t['keterangan']) ?></p>
                            <p class="text-xs text-gray-400"><?= date('d M Y', strtotime($t['tanggal_transaksi'])) ?></p>
                        </div>
                    </div>
                    <span class="font-bold text-sm <?= $isIn ? 'text-blue-600' : 'text-red-600' ?>">
                        <?= $isIn ? '+' : '-' ?>Rp <?= number_format($t['jumlah']) ?>
                    </span>
                </div>
                <?php endforeach ?>
            <?php else: ?>
                <p class="text-center text-gray-400 py-6 text-sm">Belum ada transaksi</p>
            <?php endif ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
        <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-yellow-500">
                <i class="fas fa-bolt"></i>
            </span>
            Quick Actions
        </h3>
        <div class="grid grid-cols-2 gap-3">
            <?php if ($canEdit ?? false): ?>
            <a href="<?= base_url('keuangan/pemasukan/add') ?>" class="flex flex-col items-center p-4 bg-blue-50 rounded-2xl hover:bg-blue-100 transition group">
                <div class="w-11 h-11 bg-blue-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-plus text-white"></i>
                </div>
                <span class="text-xs font-semibold text-gray-700 text-center">Tambah Pemasukan</span>
            </a>
            <a href="<?= base_url('keuangan/pengeluaran/add') ?>" class="flex flex-col items-center p-4 bg-red-50 rounded-2xl hover:bg-red-100 transition group">
                <div class="w-11 h-11 bg-red-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-minus text-white"></i>
                </div>
                <span class="text-xs font-semibold text-gray-700 text-center">Tambah Pengeluaran</span>
            </a>
            <?php endif ?>
            <a href="<?= base_url('keuangan/pemasukan') ?>" class="flex flex-col items-center p-4 bg-emerald-50 rounded-2xl hover:bg-emerald-100 transition group">
                <div class="w-11 h-11 bg-emerald-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-list text-white"></i>
                </div>
                <span class="text-xs font-semibold text-gray-700 text-center">Data Pemasukan</span>
            </a>
            <a href="<?= base_url('keuangan/pengeluaran') ?>" class="flex flex-col items-center p-4 bg-orange-50 rounded-2xl hover:bg-orange-100 transition group">
                <div class="w-11 h-11 bg-orange-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-list text-white"></i>
                </div>
                <span class="text-xs font-semibold text-gray-700 text-center">Data Pengeluaran</span>
            </a>
            <button onclick="window.print()" class="col-span-2 flex flex-col items-center p-4 bg-cyan-50 rounded-2xl hover:bg-cyan-100 transition group">
                <div class="w-11 h-11 bg-cyan-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-print text-white"></i>
                </div>
                <span class="text-xs font-semibold text-gray-700 text-center">Cetak Laporan</span>
            </button>
        </div>
    </div>
</div>

<?php $this->endSection() ?>


<?php $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleSubmenu() {
        document.getElementById('keuanganSubmenu').classList.toggle('active');
        document.getElementById('submenuIcon').classList.toggle('rotate-180');
    }

    // Cash Flow Chart
    new Chart(document.getElementById('cashFlowChart'), {
        type: 'line',
        data: {
            labels: ['Agu', 'Sep', 'Okt', 'Nov', 'Des', 'Jan'],
            datasets: [{
                label: 'Pemasukan',
                data: [380, 420, 390, 450, 410, <?= isset($totalPemasukan) ? round($totalPemasukan/1000000, 1) : 450.5 ?>],
                borderColor: 'rgb(59,130,246)', backgroundColor: 'rgba(59,130,246,0.1)', tension: 0.4, fill: true
            }, {
                label: 'Pengeluaran',
                data: [280, 310, 295, 340, 305, <?= isset($totalPengeluaran) ? round($totalPengeluaran/1000000, 1) : 320.2 ?>],
                borderColor: 'rgb(239,68,68)', backgroundColor: 'rgba(239,68,68,0.1)', tension: 0.4, fill: true
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v + ' Jt' } } } }
    });

    // Donut Chart
    new Chart(document.getElementById('expenseCategoryChart'), {
        type: 'doughnut',
        data: {
            labels: ['Gaji', 'Operasional', 'Pemeliharaan', 'ATK', 'Lainnya'],
            datasets: [{ data: [45, 25, 15, 10, 5], backgroundColor: ['rgba(59,130,246,0.8)','rgba(139,92,246,0.8)','rgba(251,146,60,0.8)','rgba(99,102,241,0.8)','rgba(156,163,175,0.8)'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>
<?php $this->endSection() ?>