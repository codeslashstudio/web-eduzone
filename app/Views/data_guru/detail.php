<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('guru') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<?php if ($canEdit ?? false): ?>
<a href="<?= base_url('guru/edit/' . ($guru['id'] ?? '')) ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-pencil w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Edit Guru</span>
</a>
<?php endif ?>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$guru    = $guru    ?? [];
$canEdit = $canEdit ?? false;
$db      = \Config\Database::connect();

// Jadwal guru
$jadwal = $db->query("
    SELECT sc.*, c.nama_kelas
    FROM schedules sc
    LEFT JOIN classes c ON c.id = sc.class_id
    WHERE sc.teacher_id = ? AND sc.is_active = 1
    ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), sc.start_time
", [$guru['id'] ?? 0])->getResultArray();

// Absensi bulan ini
$absensiStat = $db->query("
    SELECT
        SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) AS hadir,
        SUM(CASE WHEN status='Sakit' THEN 1 ELSE 0 END) AS sakit,
        SUM(CASE WHEN status='Izin'  THEN 1 ELSE 0 END) AS izin,
        SUM(CASE WHEN status='Alpa'  THEN 1 ELSE 0 END) AS alpa,
        COUNT(*) AS total
    FROM teacher_attendance
    WHERE teacher_id = ? AND MONTH(date) = ? AND YEAR(date) = ?
", [$guru['id'] ?? 0, date('m'), date('Y')])->getRowArray();

$hariUrut = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$jadwalPerHari = [];
foreach ($hariUrut as $h) {
    $jadwalPerHari[$h] = array_values(array_filter($jadwal, fn($j) => $j['day'] === $h));
}
$totalJam = 0;
foreach ($jadwal as $j) {
    $totalJam += (strtotime($j['end_time']) - strtotime($j['start_time'])) / 3600;
}
?>

<!-- Flash -->
<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-check-circle mt-0.5"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <!-- Profil Card -->
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <!-- Header gradient -->
            <div class="h-20" style="background:linear-gradient(135deg,var(--color-primary),var(--color-secondary))"></div>
            <!-- Foto -->
            <div class="px-5 pb-5 -mt-10">
                <div class="w-20 h-20 rounded-2xl border-4 border-white overflow-hidden shadow-lg mx-auto mb-3">
                    <?php if (!empty($guru['photo'])): ?>
                    <img src="<?= base_url('uploads/guru/' . $guru['photo']) ?>" class="w-full h-full object-cover" alt="">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-white text-2xl font-bold"
                         style="background:var(--color-primary)">
                        <?= strtoupper(substr($guru['full_name'] ?? 'G', 0, 1)) ?>
                    </div>
                    <?php endif ?>
                </div>
                <div class="text-center">
                    <h2 class="font-bold text-gray-900 text-lg leading-tight"><?= esc($guru['full_name'] ?? '-') ?></h2>
                    <p class="text-xs text-gray-400 mt-1">
                        <?= esc($guru['major_name'] ?? 'Guru') ?>
                    </p>
                    <?php if ($guru['kelas_wali'] ?? ''): ?>
                    <span class="inline-block mt-2 text-xs font-bold px-3 py-1 rounded-full bg-purple-100 text-purple-700">
                        <i class="fas fa-home mr-1"></i> Wali <?= esc($guru['kelas_wali']) ?>
                    </span>
                    <?php endif ?>
                </div>

                <!-- Stats mini -->
                <div class="grid grid-cols-3 gap-2 mt-4">
                    <div class="text-center bg-gray-50 rounded-xl py-2.5">
                        <p class="font-bold text-gray-900 text-lg"><?= count($jadwal) ?></p>
                        <p class="text-xs text-gray-400">Jadwal</p>
                    </div>
                    <div class="text-center bg-gray-50 rounded-xl py-2.5">
                        <p class="font-bold text-gray-900 text-lg"><?= round($totalJam, 1) ?></p>
                        <p class="text-xs text-gray-400">Jam/mgg</p>
                    </div>
                    <div class="text-center bg-gray-50 rounded-xl py-2.5">
                        <?php $pct = ($absensiStat['total'] ?? 0) > 0
                            ? round(($absensiStat['hadir'] ?? 0) / $absensiStat['total'] * 100)
                            : 0 ?>
                        <p class="font-bold text-gray-900 text-lg"><?= $pct ?>%</p>
                        <p class="text-xs text-gray-400">Hadir</p>
                    </div>
                </div>

                <!-- Aksi -->
                <?php if ($canEdit): ?>
                <div class="flex gap-2 mt-4">
                    <a href="<?= base_url('guru/edit/' . $guru['id']) ?>"
                       class="flex-1 btn-primary py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-pencil text-xs"></i> Edit
                    </a>
                    <form method="POST" action="<?= base_url('guru/delete/' . $guru['id']) ?>"
                          onsubmit="return confirm('Hapus data guru ini?')">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-red-50 text-red-500 hover:bg-red-100 transition-colors flex items-center gap-1.5">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
                <?php endif ?>
            </div>
        </div>

        <!-- Absensi bulan ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" data-aos="fade-up">
            <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                <i class="fas fa-calendar-check text-xs" style="color:var(--color-primary)"></i>
                Kehadiran Bulan Ini
            </h4>
            <div class="grid grid-cols-2 gap-2">
                <?php foreach ([
                    ['hadir', 'Hadir', 'emerald'],
                    ['sakit', 'Sakit', 'blue'],
                    ['izin',  'Izin',  'yellow'],
                    ['alpa',  'Alpa',  'red'],
                ] as [$key, $lbl, $c]): ?>
                <div class="bg-<?= $c ?>-50 rounded-xl p-3 text-center">
                    <p class="text-xl font-bold text-<?= $c ?>-600"><?= $absensiStat[$key] ?? 0 ?></p>
                    <p class="text-xs text-<?= $c ?>-500"><?= $lbl ?></p>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <!-- Detail & Jadwal -->
    <div class="lg:col-span-2 space-y-4">

        <!-- Data Pribadi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-id-card text-sm" style="color:var(--color-primary)"></i>
                    Data Pribadi
                </h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php
                $fields = [
                    ['NIP',                 $guru['nip']               ?? '-'],
                    ['NUPTK',               $guru['nuptk']             ?? '-'],
                    ['Jenis Kelamin',        $guru['gender'] === 'L' ? 'Laki-laki' : 'Perempuan'],
                    ['Agama',               $guru['religion']          ?? '-'],
                    ['Tempat Lahir',        $guru['birth_place']       ?? '-'],
                    ['Tanggal Lahir',       $guru['birth_date'] ? date('d F Y', strtotime($guru['birth_date'])) : '-'],
                    ['No. HP',              $guru['phone']             ?? '-'],
                    ['Email',               $guru['email']             ?? '-'],
                ];
                foreach ($fields as [$lbl, $val]):
                ?>
                <div>
                    <p class="text-xs font-semibold text-gray-400 mb-0.5"><?= $lbl ?></p>
                    <p class="text-sm font-semibold text-gray-800"><?= esc($val) ?></p>
                </div>
                <?php endforeach ?>
                <div class="sm:col-span-2">
                    <p class="text-xs font-semibold text-gray-400 mb-0.5">Alamat</p>
                    <p class="text-sm font-semibold text-gray-800"><?= esc($guru['address'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <!-- Kepegawaian -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-briefcase text-sm" style="color:var(--color-primary)"></i>
                    Kepegawaian
                </h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php
                $fields2 = [
                    ['Status Kepegawaian', $guru['employment_status'] ?? '-'],
                    ['Pendidikan Terakhir', ($guru['last_education'] ?? '-') . ' ' . ($guru['education_major'] ? '(' . $guru['education_major'] . ')' : '')],
                    ['Bidang Studi',       $guru['major_name']        ?? '-'],
                    ['Tanggal Bergabung',  $guru['joined_date'] ? date('d F Y', strtotime($guru['joined_date'])) : '-'],
                ];
                foreach ($fields2 as [$lbl, $val]):
                ?>
                <div>
                    <p class="text-xs font-semibold text-gray-400 mb-0.5"><?= $lbl ?></p>
                    <p class="text-sm font-semibold text-gray-800"><?= esc($val) ?></p>
                </div>
                <?php endforeach ?>
            </div>
        </div>

        <!-- Jadwal Mengajar -->
        <?php if (!empty($jadwal)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-sm" style="color:var(--color-primary)"></i>
                    Jadwal Mengajar
                </h3>
                <a href="<?= base_url('jadwal/guru/' . $guru['id']) ?>"
                   class="text-xs font-semibold flex items-center gap-1" style="color:var(--color-primary)">
                    Lihat semua <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                <?php foreach ($jadwal as $j): ?>
                <div class="px-5 py-3 flex items-center gap-4">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg text-white shrink-0"
                          style="background:var(--color-primary);min-width:52px;text-align:center">
                        <?= $j['day'] ?>
                    </span>
                    <span class="text-xs font-mono text-gray-500 shrink-0">
                        <?= substr($j['start_time'], 0, 5) ?>–<?= substr($j['end_time'], 0, 5) ?>
                    </span>
                    <span class="font-semibold text-gray-800 text-sm flex-1"><?= esc($j['subject']) ?></span>
                    <span class="text-xs text-gray-400"><?= esc($j['nama_kelas'] ?? '-') ?></span>
                </div>
                <?php endforeach ?>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>

<?php $this->endSection() ?>