<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<?php $role = session()->get('role') ?>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru_mapel') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<div>
    <button onclick="toggleAbsensi()" class="menu-item active w-full flex items-center justify-between px-4 py-3 rounded-xl">
        <div class="flex items-center space-x-3">
            <i class="fas fa-clipboard-check w-5"></i>
            <span class="sidebar-text font-semibold text-sm">Absensi</span>
        </div>
        <i class="fas fa-chevron-down sidebar-text text-xs transition-transform" id="absensiIcon"></i>
    </button>
    <div class="ml-4 mt-1 space-y-1 sidebar-text" id="absensiSubmenu">
        <a href="<?= base_url('absensi') ?>" class="menu-item active flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-chart-pie w-4"></i><span>Dashboard</span>
        </a>
        <?php if (in_array(session()->get('role'), ['wali_kelas','tu','superadmin'])): ?>
        <a href="<?= base_url('absensi/harian') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-calendar-check w-4"></i><span>Absensi Harian</span>
        </a>
        <?php endif ?>
        <?php if (in_array(session()->get('role'), ['guru_mapel','tu','superadmin'])): ?>
        <a href="<?= base_url('absensi/mapel') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-book-open w-4"></i><span>Absensi Mapel</span>
        </a>
        <?php endif ?>
        <a href="<?= base_url('absensi/rekap') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-table w-4"></i><span>Rekap Absensi</span>
        </a>
    </div>
</div>
<a href="<?= base_url('laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chart-line w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-key w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<?php
$stat       = $statistik ?? [];
$totalHadir = $stat['total_hadir'] ?? 0;
$totalSakit = $stat['total_sakit'] ?? 0;
$totalIzin  = $stat['total_izin']  ?? 0;
$totalAlpa  = $stat['total_alpa']  ?? 0;
$totalRec   = $stat['total_records'] ?? 0;
$pctHadir   = $totalRec > 0 ? round($totalHadir / $totalRec * 100, 1) : 0;

$namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>

<!-- Filter -->
<div class="bg-white rounded-2xl shadow-lg p-5 mb-6" data-aos="fade-up">
    <form method="get" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bulan</label>
            <select name="bulan" class="px-4 py-2 rounded-xl border-2 border-gray-200 focus:outline-none text-sm">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= $bulan == $m ? 'selected' : '' ?>><?= $namaBulan[$m] ?></option>
                <?php endfor ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun</label>
            <select name="tahun" class="px-4 py-2 rounded-xl border-2 border-gray-200 focus:outline-none text-sm">
                <?php foreach ([2024, 2025, 2026] as $y): ?>
                <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <button type="submit" class="btn-primary px-5 py-2 rounded-xl font-semibold text-sm flex items-center space-x-2">
            <i class="fas fa-filter"></i><span>Filter</span>
        </button>
    </form>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up"
         style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-check-circle text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalHadir) ?></h3>
        <p class="text-white/80 text-sm">Total Hadir</p>
        <p class="text-white/60 text-xs mt-1"><?= $pctHadir ?>% kehadiran</p>
    </div>

    <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-file-medical text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalSakit) ?></h3>
        <p class="text-white/80 text-sm">Sakit</p>
    </div>

    <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-envelope-open-text text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalIzin) ?></h3>
        <p class="text-white/80 text-sm">Izin</p>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-times-circle text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalAlpa) ?></h3>
        <p class="text-white/80 text-sm">Alpa</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <?php if ($canInputHarian): ?>
    <a href="<?= base_url('absensi/harian?date=' . date('Y-m-d')) ?>"
       class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform"
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
            <i class="fas fa-calendar-check text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Absensi Harian</p>
        <p class="text-gray-400 text-xs mt-1">Input hari ini</p>
    </a>
    <?php endif ?>

    <?php if ($canInputMapel): ?>
    <a href="<?= base_url('absensi/mapel?date=' . date('Y-m-d')) ?>"
       class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="50">
        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-book-open text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Absensi Mapel</p>
        <p class="text-gray-400 text-xs mt-1">Per jam pelajaran</p>
    </a>
    <?php endif ?>

    <a href="<?= base_url('absensi/rekap') ?>"
       class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="100">
        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-table text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Rekap Bulanan</p>
        <p class="text-gray-400 text-xs mt-1">Lihat rekap</p>
    </a>

    <a href="<?= base_url('absensi/rekap') ?>"
       class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="150">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-file-excel text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Export Excel</p>
        <p class="text-gray-400 text-xs mt-1">Download rekap</p>
    </a>
</div>

<!-- Chart + Alpa Terbanyak -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Trend Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line" style="color:var(--color-primary)"></i>
            Trend Kehadiran — <?= $namaBulan[(int)$bulan] ?> <?= $tahun ?>
        </h3>
        <canvas id="trendChart" height="100"></canvas>
    </div>

    <!-- Alpa terbanyak -->
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle text-red-500"></i>
            Alpa Terbanyak
        </h3>
        <?php if (empty($alpaTerbanyak)): ?>
        <div class="text-center py-8 text-gray-400">
            <i class="fas fa-check-circle text-4xl mb-2 text-emerald-300"></i>
            <p class="text-sm">Tidak ada data alpa</p>
        </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($alpaTerbanyak as $i => $s): ?>
            <div class="flex items-center justify-between p-3 rounded-xl bg-red-50">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center font-bold"><?= $i+1 ?></span>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?= esc($s['full_name']) ?></p>
                        <p class="text-gray-400 text-xs"><?= $s['grade'] ?> <?= $s['major_name'] ?> <?= $s['class_group'] ?></p>
                    </div>
                </div>
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full"><?= $s['total_alpa'] ?>x</span>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Kehadiran per Kelas -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Daftar Kelas</h3>
        <p class="text-sm text-gray-400 mt-0.5">Klik kelas untuk lihat rekap absensi</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" class="text-white">
                    <th class="px-5 py-3 text-left text-sm font-bold">Kelas</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Jumlah Siswa</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($kelasList)): ?>
                <tr><td colspan="3" class="text-center py-8 text-gray-400">Belum ada data kelas</td></tr>
                <?php else: ?>
                <?php foreach ($kelasList as $kelas): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-bold text-gray-800"><?= esc($kelas['nama_kelas']) ?></td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold"><?= $kelas['jumlah_siswa'] ?> siswa</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="<?= base_url("absensi/rekap?grade={$kelas['grade']}&major_id={$kelas['major_id']}&class_group={$kelas['class_group']}&bulan=$bulan&tahun=$tahun") ?>"
                           class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                           style="background: rgba(var(--color-primary-rgb, 102,126,234), 0.1); color: var(--color-primary)">
                            <i class="fas fa-eye mr-1.5"></i>Lihat Rekap
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection() ?>


<?php $this->section('scripts') ?>
<script>
    function toggleAbsensi() {
        document.getElementById('absensiSubmenu').classList.toggle('hidden');
        document.getElementById('absensiIcon').classList.toggle('rotate-180');
    }

    // Trend Chart
    const trendData = <?= json_encode($trend ?? []) ?>;
    const labels  = trendData.map(d => d.hari);
    const hadir   = trendData.map(d => parseInt(d.hadir));
    const alpa    = trendData.map(d => parseInt(d.alpa));

    const rootStyle   = getComputedStyle(document.documentElement);
    const colorPrimary = rootStyle.getPropertyValue('--color-primary').trim() || '#667eea';

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: hadir,
                    borderColor: colorPrimary,
                    backgroundColor: colorPrimary + '22',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                },
                {
                    label: 'Alpa',
                    data: alpa,
                    borderColor: '#ef4444',
                    backgroundColor: '#ef444422',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
<?php $this->endSection() ?>