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
$siswa          = $siswa          ?? [];
$absensiStat    = $absensiStat    ?? [];
$jadwalHariIni  = $jadwalHariIni  ?? [];
$jadwalMinggu   = $jadwalMinggu   ?? [];
$absensiTerbaru = $absensiTerbaru ?? [];
$prestasi       = $prestasi       ?? [];
$pengumuman     = $pengumuman     ?? [];
$hariIni        = $hariIni        ?? '';
$hadir = $absensiStat['hadir'] ?? 0;
$sakit = $absensiStat['sakit'] ?? 0;
$izin  = $absensiStat['izin']  ?? 0;
$alpa  = $absensiStat['alpa']  ?? 0;
$total = $absensiStat['total'] ?? 0;
$pct   = $total > 0 ? round($hadir / $total * 100, 1) : 0;
$namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>

<!-- Banner -->
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden" data-aos="fade-up"
     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
    <div class="relative z-10">
        <p class="text-white/70 text-sm mb-1">Halo,</p>
        <h1 class="text-2xl font-bold mb-1"><?= esc($siswa['full_name'] ?? session()->get('username')) ?></h1>
        <p class="text-white/80 text-sm flex items-center gap-2">
            <i class="fas fa-graduation-cap"></i>
            Kelas <?= ($siswa['grade'] ?? '') . ' ' . ($siswa['major_name'] ?? '') . ' ' . ($siswa['class_group'] ?? '') ?>
            • NIS: <?= esc($siswa['nis'] ?? '-') ?>
        </p>
        <p class="text-white/60 text-xs mt-1"><?= date('l, d F Y') ?></p>
    </div>
    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute -right-4 top-10 w-24 h-24 bg-white/10 rounded-full"></div>
</div>

<!-- Stats Absensi -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="rounded-2xl p-5 text-white shadow-xl" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" data-aos="fade-up">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-check-circle text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $pct ?>%</h3>
        <p class="text-white/80 text-sm">Kehadiran Bulan Ini</p>
        <div class="mt-2 h-1.5 bg-white/20 rounded-full"><div class="h-full bg-white rounded-full" style="width:<?= $pct ?>%"></div></div>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-user-check text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $hadir ?></h3>
        <p class="text-white/80 text-sm">Hari Hadir</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-file-medical text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $sakit + $izin ?></h3>
        <p class="text-white/80 text-sm">Sakit + Izin</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-5 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-times-circle text-xl"></i></div>
        <h3 class="text-2xl font-bold"><?= $alpa ?></h3>
        <p class="text-white/80 text-sm">Alpa</p>
    </div>
</div>

<!-- Jadwal Hari Ini + Pengumuman -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-clock" style="color:var(--color-primary)"></i>Jadwal Hari Ini — <?= $hariIni ?>
            </h3>
        </div>
        <?php if (empty($jadwalHariIni)): ?>
        <div class="p-10 text-center text-gray-400">
            <i class="fas fa-sun text-4xl mb-2 text-yellow-200"></i>
            <p class="text-sm">Tidak ada pelajaran hari ini</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($jadwalHariIni as $j): ?>
            <div class="px-5 py-4 flex items-center gap-3 hover:bg-gray-50">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-white font-bold text-sm"
                     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
                    <?= strtoupper(substr($j['subject'], 0, 2)) ?>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-800 text-sm"><?= esc($j['subject']) ?></p>
                    <p class="text-gray-400 text-xs"><?= esc($j['nama_guru'] ?? '') ?> • <?= esc($j['room'] ?? '') ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold" style="color:var(--color-primary)"><?= date('H:i', strtotime($j['start_time'])) ?></p>
                    <p class="text-gray-400 text-xs"><?= date('H:i', strtotime($j['end_time'])) ?></p>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-bullhorn" style="color:var(--color-primary)"></i>Pengumuman
            </h3>
        </div>
        <?php if (empty($pengumuman)): ?>
        <div class="p-8 text-center text-gray-400"><p class="text-sm">Tidak ada pengumuman</p></div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($pengumuman as $pg): ?>
            <div class="px-5 py-4 hover:bg-gray-50">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 <?= $pg['is_important'] ? 'bg-red-100' : 'bg-blue-100' ?>">
                        <i class="fas fa-<?= $pg['is_important'] ? 'exclamation text-red-500' : 'info text-blue-500' ?> text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm"><?= esc($pg['title']) ?></p>
                        <p class="text-gray-400 text-xs mt-0.5"><?= date('d M Y', strtotime($pg['published_at'])) ?></p>
                    </div>
                    <?php if ($pg['is_important']): ?>
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-semibold">Penting</span>
                    <?php endif ?>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Riwayat Absensi + Prestasi -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-calendar-check" style="color:var(--color-primary)"></i>Riwayat Absensi Terbaru
            </h3>
        </div>
        <?php if (empty($absensiTerbaru)): ?>
        <div class="p-8 text-center text-gray-400"><p class="text-sm">Belum ada data absensi</p></div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php
            $statusColor = ['Hadir'=>'bg-emerald-100 text-emerald-700','Sakit'=>'bg-yellow-100 text-yellow-700','Izin'=>'bg-blue-100 text-blue-700','Alpa'=>'bg-red-100 text-red-700'];
            foreach ($absensiTerbaru as $a):
                $sc = $statusColor[$a['status']] ?? 'bg-gray-100 text-gray-600';
            ?>
            <div class="px-5 py-3.5 flex items-center justify-between hover:bg-gray-50">
                <p class="text-sm text-gray-700 font-medium"><?= date('l, d M Y', strtotime($a['date'])) ?></p>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $sc ?>"><?= $a['status'] ?></span>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i>Prestasi Saya
            </h3>
        </div>
        <?php if (empty($prestasi)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-trophy text-4xl mb-2 text-gray-200"></i>
            <p class="text-sm">Belum ada prestasi tercatat</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($prestasi as $p): ?>
            <div class="px-5 py-4 flex items-start gap-3 hover:bg-gray-50">
                <div class="w-9 h-9 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-medal text-yellow-500"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($p['title']) ?></p>
                    <p class="text-gray-400 text-xs mt-0.5"><?= $p['level'] ?> • <?= $p['year'] ?></p>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>
<?php $this->endSection() ?>