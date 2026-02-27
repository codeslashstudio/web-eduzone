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
<a href="<?= base_url('absensi') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-clipboard-check w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Absensi</span>
</a>
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
$totalSiswa      = $totalSiswa      ?? 0;
$totalAktif      = $totalAktif      ?? 0;
$totalLulus      = $totalLulus      ?? 0;
$totalAlpa       = $totalAlpa       ?? 0;
$prestasi        = $prestasi        ?? [];
$absensiTerbaru  = $absensiTerbaru  ?? [];
$pengumuman      = $pengumuman      ?? [];
$siswaPerKelas   = $siswaPerKelas   ?? [];
$distribusiGender= $distribusiGender ?? ['L' => 0, 'P' => 0];
?>

<!-- Welcome Banner -->
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden" data-aos="fade-up"
     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
    <div class="relative z-10">
        <p class="text-white/70 text-sm font-medium mb-1">Selamat datang,</p>
        <h1 class="text-2xl font-bold mb-1"><?= esc(session()->get('username')) ?></h1>
        <p class="text-white/80 text-sm flex items-center gap-2">
            <i class="fas fa-star"></i> Kesiswaan — <?= date('l, d F Y') ?>
        </p>
    </div>
    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute -right-4 top-10 w-24 h-24 bg-white/10 rounded-full"></div>
    <div class="absolute right-20 -bottom-6 w-20 h-20 bg-white/10 rounded-full"></div>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up"
         style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-users text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalSiswa) ?></h3>
        <p class="text-white/80 text-sm">Total Siswa</p>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-user-check text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalAktif) ?></h3>
        <p class="text-white/80 text-sm">Siswa Aktif</p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-trophy text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= count($prestasi) ?></h3>
        <p class="text-white/80 text-sm">Prestasi Tahun Ini</p>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-times-circle text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalAlpa) ?></h3>
        <p class="text-white/80 text-sm">Alpa Bulan Ini</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <a href="<?= base_url('siswa') ?>" class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform"
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
            <i class="fas fa-user-graduate text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Data Siswa</p>
        <p class="text-gray-400 text-xs mt-1">Kelola data siswa</p>
    </a>

    <a href="<?= base_url('absensi/rekap') ?>" class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="50">
        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-table text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Rekap Absensi</p>
        <p class="text-gray-400 text-xs mt-1">Semua kelas</p>
    </a>

    <a href="#" class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="100">
        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-trophy text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Prestasi Siswa</p>
        <p class="text-gray-400 text-xs mt-1">Data pencapaian</p>
    </a>

    <a href="#" class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="150">
        <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-bullhorn text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Pengumuman</p>
        <p class="text-gray-400 text-xs mt-1">Buat & kelola</p>
    </a>
</div>

<!-- Charts + Prestasi -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Siswa Per Kelas Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
        <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fas fa-chart-bar" style="color:var(--color-primary)"></i>
            Jumlah Siswa Per Kelas
        </h3>
        <p class="text-gray-400 text-xs mb-4">Distribusi siswa aktif</p>
        <canvas id="kelasChart" height="120"></canvas>
    </div>

    <!-- Gender Distribution -->
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-venus-mars" style="color:var(--color-primary)"></i>
            Distribusi Gender
        </h3>
        <canvas id="genderChart" height="180"></canvas>
        <div class="flex justify-center gap-6 mt-4 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                <span class="text-gray-600">Laki-laki: <strong><?= $distribusiGender['L'] ?? 0 ?></strong></span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-pink-500"></div>
                <span class="text-gray-600">Perempuan: <strong><?= $distribusiGender['P'] ?? 0 ?></strong></span>
            </div>
        </div>
    </div>
</div>

<!-- Prestasi Terbaru + Absensi -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Prestasi Terbaru -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i>
                Prestasi Terbaru
            </h3>
            <a href="#" class="text-xs font-semibold" style="color:var(--color-primary)">Lihat Semua →</a>
        </div>
        <?php if (empty($prestasi)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-trophy text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Belum ada data prestasi</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php
            $levelColors = [
                'Internasional' => 'bg-purple-100 text-purple-700',
                'Nasional'      => 'bg-red-100 text-red-700',
                'Provinsi'      => 'bg-orange-100 text-orange-700',
                'Kabupaten'     => 'bg-blue-100 text-blue-700',
                'Kecamatan'     => 'bg-teal-100 text-teal-700',
                'Sekolah'       => 'bg-gray-100 text-gray-700',
            ];
            foreach ($prestasi as $p):
                $lc = $levelColors[$p['level']] ?? 'bg-gray-100 text-gray-600';
            ?>
            <div class="px-5 py-4 flex items-start gap-3 hover:bg-gray-50 transition-colors">
                <div class="w-9 h-9 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-medal text-yellow-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($p['title']) ?></p>
                    <p class="text-gray-400 text-xs mt-0.5"><?= esc($p['nama_siswa'] ?? '') ?> • <?= $p['year'] ?></p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold flex-shrink-0 <?= $lc ?>">
                    <?= $p['level'] ?>
                </span>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>

    <!-- Pengumuman Aktif -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-bullhorn" style="color:var(--color-primary)"></i>
                Pengumuman Aktif
            </h3>
            <a href="#" class="text-xs font-semibold" style="color:var(--color-primary)">Kelola →</a>
        </div>
        <?php if (empty($pengumuman)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-bullhorn text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Belum ada pengumuman aktif</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($pengumuman as $pg): ?>
            <div class="px-5 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start gap-3">
                    <?php if ($pg['is_important']): ?>
                    <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-red-500"></i>
                    </div>
                    <?php else: ?>
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-info text-blue-500"></i>
                    </div>
                    <?php endif ?>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm"><?= esc($pg['title']) ?></p>
                        <p class="text-gray-400 text-xs mt-0.5 line-clamp-2"><?= esc(substr($pg['content'], 0, 80)) ?>...</p>
                        <p class="text-gray-300 text-xs mt-1"><?= date('d M Y', strtotime($pg['published_at'])) ?></p>
                    </div>
                    <?php if ($pg['is_important']): ?>
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">Penting</span>
                    <?php endif ?>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Rekap Absensi Per Kelas -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-clipboard-list" style="color:var(--color-primary)"></i>
            Rekap Absensi Bulan Ini — Semua Kelas
        </h3>
        <p class="text-gray-400 text-xs mt-0.5"><?= date('F Y') ?></p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" class="text-white">
                    <th class="px-5 py-3 text-left text-sm font-bold">Kelas</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Siswa</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Hadir</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Sakit</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Izin</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Alpa</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">% Hadir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($rekapAbsensi)): ?>
                <tr><td colspan="7" class="text-center py-8 text-gray-400">Belum ada data absensi bulan ini</td></tr>
                <?php else: ?>
                <?php foreach (($rekapAbsensi ?? []) as $r):
                    $total = ($r['hadir'] + $r['sakit'] + $r['izin'] + $r['alpa']);
                    $pct   = $total > 0 ? round($r['hadir'] / $total * 100, 1) : 0;
                    $pctColor = $pct >= 75 ? 'text-emerald-600' : ($pct >= 50 ? 'text-yellow-600' : 'text-red-600');
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-bold text-gray-800 text-sm"><?= esc($r['nama_kelas'] ?? '') ?></td>
                    <td class="px-5 py-3 text-center text-sm text-gray-600"><?= $r['jumlah_siswa'] ?? 0 ?></td>
                    <td class="px-5 py-3 text-center"><span class="text-sm font-bold text-emerald-600"><?= $r['hadir'] ?? 0 ?></span></td>
                    <td class="px-5 py-3 text-center"><span class="text-sm font-bold text-yellow-600"><?= $r['sakit'] ?? 0 ?></span></td>
                    <td class="px-5 py-3 text-center"><span class="text-sm font-bold text-blue-600"><?= $r['izin'] ?? 0 ?></span></td>
                    <td class="px-5 py-3 text-center"><span class="text-sm font-bold text-red-600"><?= $r['alpa'] ?? 0 ?></span></td>
                    <td class="px-5 py-3 text-center"><span class="text-sm font-bold <?= $pctColor ?>"><?= $pct ?>%</span></td>
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
    const rootStyle    = getComputedStyle(document.documentElement);
    const colorPrimary = rootStyle.getPropertyValue('--color-primary').trim()   || '#14B8A6';
    const colorSecond  = rootStyle.getPropertyValue('--color-secondary').trim() || '#0D9488';

    // Siswa Per Kelas Chart
    const kelasData  = <?= json_encode(array_values($siswaPerKelas ?? [])) ?>;
    const kelasLabel = kelasData.map(k => k.nama_kelas ?? '');
    const kelasJml   = kelasData.map(k => parseInt(k.jumlah_siswa ?? 0));

    new Chart(document.getElementById('kelasChart'), {
        type: 'bar',
        data: {
            labels: kelasLabel,
            datasets: [{
                label: 'Jumlah Siswa',
                data: kelasJml,
                backgroundColor: colorPrimary + '99',
                borderColor: colorPrimary,
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Gender Doughnut
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [<?= $distribusiGender['L'] ?? 0 ?>, <?= $distribusiGender['P'] ?? 0 ?>],
                backgroundColor: ['#60a5facc', '#f472b6cc'],
                borderColor: ['#60a5fa', '#f472b6'],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: { legend: { display: false } }
        }
    });
</script>
<?php $this->endSection() ?>