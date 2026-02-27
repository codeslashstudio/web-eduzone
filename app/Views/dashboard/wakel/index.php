<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('dashboard') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<div>
    <button onclick="toggleAbsensi()" class="menu-item w-full flex items-center justify-between px-4 py-3 rounded-xl">
        <div class="flex items-center space-x-3">
            <i class="fas fa-clipboard-check w-5"></i>
            <span class="sidebar-text font-semibold text-sm">Absensi</span>
        </div>
        <i class="fas fa-chevron-down sidebar-text text-xs transition-transform" id="absensiIcon"></i>
    </button>
    <div class="ml-4 mt-1 space-y-1 sidebar-text hidden" id="absensiSubmenu">
        <a href="<?= base_url('absensi') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-chart-pie w-4"></i><span>Dashboard</span>
        </a>
        <a href="<?= base_url('absensi/harian') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-calendar-check w-4"></i><span>Absensi Harian</span>
        </a>
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
$kelasSaya       = $kelasSaya       ?? [];
$statistikAbsensi= $statistikAbsensi ?? [];
$absensiHariIni  = $absensiHariIni  ?? [];
$daftarSiswa     = $daftarSiswa     ?? [];
$siswaBermasalah = $siswaBermasalah ?? [];
$jadwalHariIni   = $jadwalHariIni   ?? [];

$stat        = $statistikAbsensi;
$totalHadir  = $stat['total_hadir'] ?? 0;
$totalSakit  = $stat['total_sakit'] ?? 0;
$totalIzin   = $stat['total_izin']  ?? 0;
$totalAlpa   = $stat['total_alpa']  ?? 0;
$totalSiswa  = $stat['total_siswa'] ?? 0;
$totalRec    = $totalHadir + $totalSakit + $totalIzin + $totalAlpa;
$pctHadir    = $totalRec > 0 ? round($totalHadir / $totalRec * 100, 1) : 0;
$kelasNama   = $kelasSaya['nama_kelas'] ?? 'Kelas Saya';
?>

<!-- Welcome Banner -->
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden" data-aos="fade-up"
     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
    <div class="relative z-10">
        <p class="text-white/70 text-sm font-medium mb-1">Selamat datang,</p>
        <h1 class="text-2xl font-bold mb-1"><?= esc(session()->get('username')) ?></h1>
        <p class="text-white/80 text-sm flex items-center gap-2">
            <i class="fas fa-users"></i>
            Wali Kelas — <strong><?= esc($kelasNama) ?></strong>
            <?php if (!empty($kelasSaya['jumlah_siswa'])): ?>
            • <?= $kelasSaya['jumlah_siswa'] ?> siswa
            <?php endif ?>
        </p>
        <p class="text-white/60 text-xs mt-2">
            <?= date('l, d F Y') ?>
        </p>
    </div>
    <!-- Decorative circles -->
    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute -right-4 top-10 w-24 h-24 bg-white/10 rounded-full"></div>
    <div class="absolute right-20 -bottom-6 w-20 h-20 bg-white/10 rounded-full"></div>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all" data-aos="fade-up">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-3"
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
            <i class="fas fa-users text-white"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><?= $totalSiswa ?></h3>
        <p class="text-gray-400 text-sm">Total Siswa</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-check-circle text-white"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><?= $pctHadir ?>%</h3>
        <p class="text-gray-400 text-sm">Tingkat Kehadiran</p>
        <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full bg-emerald-400" style="width: <?= $pctHadir ?>%"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-gradient-to-br from-red-400 to-red-500 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-times-circle text-white"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><?= $totalAlpa ?></h3>
        <p class="text-gray-400 text-sm">Total Alpa Bulan Ini</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-calendar-day text-white"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><?= $jadwalHariIni ? count($jadwalHariIni) : 0 ?></h3>
        <p class="text-gray-400 text-sm">Jadwal Hari Ini</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <a href="<?= base_url('absensi/harian?date=' . date('Y-m-d') . '&grade=' . ($kelasSaya['grade'] ?? '') . '&major_id=' . ($kelasSaya['major_id'] ?? '') . '&class_group=' . ($kelasSaya['class_group'] ?? '')) ?>"
       class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform"
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
            <i class="fas fa-calendar-check text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Input Absensi</p>
        <p class="text-gray-400 text-xs mt-1">Absensi hari ini</p>
    </a>

    <a href="<?= base_url('absensi/rekap?grade=' . ($kelasSaya['grade'] ?? '') . '&major_id=' . ($kelasSaya['major_id'] ?? '') . '&class_group=' . ($kelasSaya['class_group'] ?? '') . '&bulan=' . date('m') . '&tahun=' . date('Y')) ?>"
       class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="50">
        <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-table text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Rekap Absensi</p>
        <p class="text-gray-400 text-xs mt-1">Bulan ini</p>
    </a>

    <a href="<?= base_url('siswa') ?>"
       class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="100">
        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-user-graduate text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Data Siswa</p>
        <p class="text-gray-400 text-xs mt-1">Lihat daftar siswa</p>
    </a>
</div>

<!-- Konten Utama -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Absensi Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
        <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fas fa-chart-bar" style="color:var(--color-primary)"></i>
            Statistik Kehadiran Bulan Ini
        </h3>
        <p class="text-gray-400 text-xs mb-4"><?= esc($kelasNama) ?></p>
        <canvas id="absensiChart" height="120"></canvas>
    </div>

    <!-- Status Absensi Hari Ini -->
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-day" style="color:var(--color-primary)"></i>
            Absensi Hari Ini
        </h3>

        <?php if (empty($absensiHariIni)): ?>
        <div class="text-center py-6">
            <i class="fas fa-clipboard text-4xl text-gray-200 mb-3"></i>
            <p class="text-gray-400 text-sm mb-3">Belum ada absensi hari ini</p>
            <a href="<?= base_url('absensi/harian?date=' . date('Y-m-d') . '&grade=' . ($kelasSaya['grade'] ?? '') . '&major_id=' . ($kelasSaya['major_id'] ?? '') . '&class_group=' . ($kelasSaya['class_group'] ?? '')) ?>"
               class="btn-primary px-4 py-2 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
                <i class="fas fa-plus"></i>Input Sekarang
            </a>
        </div>
        <?php else: ?>
        <?php
        $hCountH = count(array_filter($absensiHariIni, fn($a) => ($a['status'] ?? '') === 'Hadir'));
        $hCountS = count(array_filter($absensiHariIni, fn($a) => ($a['status'] ?? '') === 'Sakit'));
        $hCountI = count(array_filter($absensiHariIni, fn($a) => ($a['status'] ?? '') === 'Izin'));
        $hCountA = count(array_filter($absensiHariIni, fn($a) => ($a['status'] ?? '') === 'Alpa'));
        ?>
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-emerald-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-emerald-600"><?= $hCountH ?></p>
                <p class="text-emerald-600 text-xs font-semibold">Hadir</p>
            </div>
            <div class="bg-red-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-red-600"><?= $hCountA ?></p>
                <p class="text-red-600 text-xs font-semibold">Alpa</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-yellow-600"><?= $hCountS ?></p>
                <p class="text-yellow-600 text-xs font-semibold">Sakit</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-blue-600"><?= $hCountI ?></p>
                <p class="text-blue-600 text-xs font-semibold">Izin</p>
            </div>
        </div>
        <a href="<?= base_url('absensi/harian?date=' . date('Y-m-d') . '&grade=' . ($kelasSaya['grade'] ?? '') . '&major_id=' . ($kelasSaya['major_id'] ?? '') . '&class_group=' . ($kelasSaya['class_group'] ?? '')) ?>"
           class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all border-2"
           style="border-color: var(--color-primary); color: var(--color-primary)">
            <i class="fas fa-edit"></i>Edit Absensi
        </a>
        <?php endif ?>
    </div>
</div>

<!-- Jadwal Hari Ini + Siswa Alpa Terbanyak -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Jadwal Hari Ini -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-clock" style="color:var(--color-primary)"></i>
                Jadwal Kelas Hari Ini — <?= date('l') ?>
            </h3>
        </div>
        <?php if (empty($jadwalHariIni)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-calendar-times text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Tidak ada jadwal hari ini</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($jadwalHariIni as $j): ?>
            <div class="px-5 py-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: linear-gradient(135deg, var(--color-primary)22, var(--color-secondary)22)">
                    <i class="fas fa-book" style="color:var(--color-primary)"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-800 text-sm"><?= esc($j['subject']) ?></p>
                    <p class="text-gray-400 text-xs"><?= esc($j['nama_guru'] ?? 'Guru') ?></p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs font-bold" style="color:var(--color-primary)">
                        <?= date('H:i', strtotime($j['start_time'])) ?> – <?= date('H:i', strtotime($j['end_time'])) ?>
                    </p>
                    <p class="text-gray-400 text-xs"><?= esc($j['room'] ?? '') ?></p>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>

    <!-- Siswa Alpa Terbanyak -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                Perlu Perhatian — Alpa Terbanyak
            </h3>
            <p class="text-gray-400 text-xs mt-0.5">Bulan <?= date('F Y') ?></p>
        </div>
        <?php if (empty($siswaBermasalah)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-smile text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Semua siswa memiliki kehadiran baik</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($siswaBermasalah as $i => $s): ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                <span class="w-6 h-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center font-bold flex-shrink-0"><?= $i+1 ?></span>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate"><?= esc($s['full_name']) ?></p>
                    <p class="text-gray-400 text-xs"><?= esc($s['nis']) ?></p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">
                        <?= $s['total_alpa'] ?>x Alpa
                    </span>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Daftar Siswa Kelas -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-900">Daftar Siswa — <?= esc($kelasNama) ?></h3>
            <p class="text-gray-400 text-xs mt-0.5"><?= count($daftarSiswa ?? []) ?> siswa aktif</p>
        </div>
        <a href="<?= base_url('siswa') ?>" class="text-xs font-semibold transition-all" style="color:var(--color-primary)">
            Lihat Semua →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs font-bold uppercase">
                    <th class="px-5 py-3 text-left">No</th>
                    <th class="px-5 py-3 text-left">Nama</th>
                    <th class="px-5 py-3 text-left">NIS</th>
                    <th class="px-5 py-3 text-center">Gender</th>
                    <th class="px-5 py-3 text-center">Hadir</th>
                    <th class="px-5 py-3 text-center">Alpa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($daftarSiswa)): ?>
                <tr><td colspan="6" class="text-center py-8 text-gray-400">Belum ada data siswa</td></tr>
                <?php else: ?>
                <?php foreach (array_slice($daftarSiswa, 0, 8) as $i => $s): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-sm text-gray-500"><?= $i+1 ?></td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 style="background: var(--color-primary)">
                                <?= strtoupper(substr($s['full_name'], 0, 1)) ?>
                            </div>
                            <span class="font-semibold text-gray-800 text-sm"><?= esc($s['full_name']) ?></span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-500"><?= esc($s['nis']) ?></td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold <?= $s['gender'] === 'P' ? 'bg-pink-100 text-pink-600' : 'bg-blue-100 text-blue-600' ?>">
                            <?= $s['gender'] === 'P' ? 'Perempuan' : 'Laki-laki' ?>
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-sm font-bold text-emerald-600"><?= $s['hadir'] ?? 0 ?></span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-sm font-bold <?= ($s['alpa'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-400' ?>">
                            <?= $s['alpa'] ?? 0 ?>
                        </span>
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

    const rootStyle    = getComputedStyle(document.documentElement);
    const colorPrimary = rootStyle.getPropertyValue('--color-primary').trim()   || '#A855F7';
    const colorSecond  = rootStyle.getPropertyValue('--color-secondary').trim() || '#9333EA';

    // Absensi Chart - doughnut
    new Chart(document.getElementById('absensiChart'), {
        type: 'bar',
        data: {
            labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
            datasets: [{
                label: 'Jumlah Hari',
                data: [<?= $totalHadir ?>, <?= $totalSakit ?>, <?= $totalIzin ?>, <?= $totalAlpa ?>],
                backgroundColor: [
                    colorPrimary + 'cc',
                    '#fbbf24cc',
                    '#60a5facc',
                    '#f87171cc',
                ],
                borderColor: [colorPrimary, '#fbbf24', '#60a5fa', '#f87171'],
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
<?php $this->endSection() ?>