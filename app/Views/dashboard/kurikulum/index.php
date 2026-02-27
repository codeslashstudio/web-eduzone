<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('dashboard') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
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
$totalGuru       = $totalGuru       ?? 0;
$totalMapel      = $totalMapel      ?? 0;
$totalJadwal     = $totalJadwal     ?? 0;
$totalUjian      = $totalUjian      ?? 0;
$ujianMendatang  = $ujianMendatang  ?? [];
$jurnalTerbaru   = $jurnalTerbaru   ?? [];
$jadwalHariIni   = $jadwalHariIni   ?? [];
$kehadiranGuru   = $kehadiranGuru   ?? [];
$mapelList       = $mapelList       ?? [];
?>

<!-- Welcome Banner -->
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden" data-aos="fade-up"
     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
    <div class="relative z-10">
        <p class="text-white/70 text-sm font-medium mb-1">Selamat datang,</p>
        <h1 class="text-2xl font-bold mb-1"><?= esc(session()->get('username')) ?></h1>
        <p class="text-white/80 text-sm flex items-center gap-2">
            <i class="fas fa-book-open"></i> Kurikulum — <?= date('l, d F Y') ?>
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
            <i class="fas fa-chalkboard-teacher text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= $totalGuru ?></h3>
        <p class="text-white/80 text-sm">Total Guru</p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-book text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= $totalMapel ?></h3>
        <p class="text-white/80 text-sm">Mata Pelajaran</p>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-calendar-alt text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= $totalJadwal ?></h3>
        <p class="text-white/80 text-sm">Total Jadwal Aktif</p>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-file-alt text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold"><?= $totalUjian ?></h3>
        <p class="text-white/80 text-sm">Ujian Terjadwal</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <a href="<?= base_url('guru') ?>" class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform"
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
            <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Data Guru</p>
        <p class="text-gray-400 text-xs mt-1">Kelola guru</p>
    </a>

    <a href="#" class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="50">
        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-calendar-week text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Jadwal Pelajaran</p>
        <p class="text-gray-400 text-xs mt-1">Atur jadwal</p>
    </a>

    <a href="#" class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="100">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-file-alt text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Jadwal Ujian</p>
        <p class="text-gray-400 text-xs mt-1">UTS / UAS</p>
    </a>

    <a href="<?= base_url('laporan') ?>" class="bg-white rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all text-center group" data-aos="fade-up" data-aos-delay="150">
        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
            <i class="fas fa-chart-line text-white text-xl"></i>
        </div>
        <p class="font-bold text-gray-800 text-sm">Laporan</p>
        <p class="text-gray-400 text-xs mt-1">Akademik</p>
    </a>
</div>

<!-- Jadwal Hari Ini + Ujian Mendatang -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Jadwal Hari Ini -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-clock" style="color:var(--color-primary)"></i>
                Jadwal Pelajaran Hari Ini
            </h3>
            <p class="text-gray-400 text-xs mt-0.5"><?= date('l, d F Y') ?></p>
        </div>
        <?php if (empty($jadwalHariIni)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-calendar-times text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Tidak ada jadwal hari ini</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
            <?php foreach ($jadwalHariIni as $j): ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: linear-gradient(135deg, var(--color-primary)22, var(--color-secondary)22)">
                    <i class="fas fa-book text-sm" style="color:var(--color-primary)"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-800 text-sm"><?= esc($j['subject']) ?></p>
                    <p class="text-gray-400 text-xs"><?= esc($j['nama_guru'] ?? '') ?> • <?= $j['grade'] ?> <?= $j['major'] ?> <?= $j['class_group'] ?></p>
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

    <!-- Ujian Mendatang -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-file-alt text-orange-500"></i>
                Ujian Mendatang
            </h3>
            <p class="text-gray-400 text-xs mt-0.5">30 hari ke depan</p>
        </div>
        <?php if (empty($ujianMendatang)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-check-circle text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Tidak ada ujian terjadwal</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
            <?php foreach ($ujianMendatang as $u): ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-orange-500 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-800 text-sm"><?= esc($u['name']) ?></p>
                    <p class="text-gray-400 text-xs"><?= $u['grade'] ?> <?= $u['major'] ?> • <?= esc($u['subject']) ?></p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs font-bold text-orange-500"><?= date('d M', strtotime($u['date'])) ?></p>
                    <p class="text-gray-400 text-xs"><?= date('H:i', strtotime($u['start_time'])) ?></p>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Chart Mapel + Jurnal Terbaru -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Distribusi Mapel Per Guru (chart) -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
        <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fas fa-chart-bar" style="color:var(--color-primary)"></i>
            Jadwal Per Hari
        </h3>
        <p class="text-gray-400 text-xs mb-4">Jumlah sesi mengajar per hari</p>
        <canvas id="jadwalChart" height="120"></canvas>
    </div>

    <!-- Jurnal Terbaru -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-book-open" style="color:var(--color-primary)"></i>
                Jurnal Terbaru
            </h3>
        </div>
        <?php if (empty($jurnalTerbaru)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-book text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Belum ada jurnal</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($jurnalTerbaru as $j): ?>
            <div class="px-5 py-3.5 hover:bg-gray-50 transition-colors">
                <p class="font-semibold text-gray-800 text-sm"><?= esc($j['subject']) ?> — Kelas <?= $j['grade'] ?></p>
                <p class="text-gray-500 text-xs mt-0.5 line-clamp-1"><?= esc($j['topic'] ?? '-') ?></p>
                <p class="text-gray-300 text-xs mt-1 flex items-center gap-1">
                    <i class="fas fa-user"></i> <?= esc($j['nama_guru'] ?? '') ?>
                    <span class="mx-1">•</span>
                    <?= date('d M Y', strtotime($j['date'])) ?>
                </p>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Daftar Guru + Jadwal Mengajar -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-chalkboard-teacher" style="color:var(--color-primary)"></i>
            Daftar Guru & Kehadiran Bulan Ini
        </h3>
        <a href="<?= base_url('guru') ?>" class="text-xs font-semibold" style="color:var(--color-primary)">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" class="text-white">
                    <th class="px-5 py-3 text-left text-sm font-bold">Guru</th>
                    <th class="px-5 py-3 text-left text-sm font-bold">Mapel</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Jml Jadwal</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Hadir</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">Tidak Hadir</th>
                    <th class="px-5 py-3 text-center text-sm font-bold">% Kehadiran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($kehadiranGuru)): ?>
                <tr><td colspan="6" class="text-center py-8 text-gray-400">Belum ada data</td></tr>
                <?php else: ?>
                <?php foreach ($kehadiranGuru as $g):
                    $totalH = ($g['hadir'] ?? 0) + ($g['tidak_hadir'] ?? 0);
                    $pct    = $totalH > 0 ? round(($g['hadir'] ?? 0) / $totalH * 100, 1) : 0;
                    $pctColor = $pct >= 80 ? 'text-emerald-600' : ($pct >= 60 ? 'text-yellow-600' : 'text-red-600');
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 style="background: var(--color-primary)">
                                <?= strtoupper(substr($g['full_name'], 0, 1)) ?>
                            </div>
                            <span class="font-semibold text-gray-800 text-sm"><?= esc($g['full_name']) ?></span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600"><?= esc($g['subject'] ?? '-') ?></td>
                    <td class="px-5 py-3 text-center text-sm font-semibold text-gray-700"><?= $g['jml_jadwal'] ?? 0 ?></td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-sm font-bold text-emerald-600"><?= $g['hadir'] ?? 0 ?></span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-sm font-bold text-red-500"><?= $g['tidak_hadir'] ?? 0 ?></span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-sm font-bold <?= $pctColor ?>"><?= $pct ?>%</span>
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
    const rootStyle    = getComputedStyle(document.documentElement);
    const colorPrimary = rootStyle.getPropertyValue('--color-primary').trim()   || '#6366F1';
    const colorSecond  = rootStyle.getPropertyValue('--color-secondary').trim() || '#4F46E5';

    // Jadwal per hari chart
    const jadwalPerHari = <?= json_encode($jadwalPerHari ?? []) ?>;
    const hariLabels = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const hariData   = hariLabels.map(h => {
        const found = jadwalPerHari.find(j => j.day === h);
        return found ? parseInt(found.total) : 0;
    });

    new Chart(document.getElementById('jadwalChart'), {
        type: 'bar',
        data: {
            labels: hariLabels,
            datasets: [{
                label: 'Sesi Mengajar',
                data: hariData,
                backgroundColor: colorPrimary + '99',
                borderColor: colorPrimary,
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
<?php $this->endSection() ?>