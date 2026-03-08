<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$rekapList  = $rekapList  ?? [];
$kelasList  = $kelasList  ?? [];
$jadwalList = $jadwalList ?? [];
$bulan      = $bulan      ?? date('m');
$tahun      = $tahun      ?? date('Y');
$classId    = $classId    ?? '';
$scheduleId = $scheduleId ?? '';
$namaBulan  = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('absensi-mapel') ?>" class="hover:text-gray-600">Absensi Mapel</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold">Rekap</span>
</div>

<!-- Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Bulan</label>
            <select name="bulan" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <?php for ($m=1; $m<=12; $m++): ?>
                <option value="<?= str_pad($m,2,'0',STR_PAD_LEFT) ?>" <?= (int)$bulan===$m?'selected':'' ?>>
                    <?= $namaBulan[$m] ?>
                </option>
                <?php endfor ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Tahun</label>
            <select name="tahun" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <?php foreach ([2025,2026,2027] as $y): ?>
                <option value="<?= $y ?>" <?= (int)$tahun===$y?'selected':'' ?>><?= $y ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Mata Pelajaran</label>
            <select name="schedule_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none min-w-48">
                <option value="">— Semua Mapel —</option>
                <?php foreach ($jadwalList as $j): ?>
                <option value="<?= $j['id'] ?>" <?= $scheduleId==$j['id']?'selected':'' ?>>
                    <?= esc($j['subject']) ?> — <?= esc($j['nama_kelas']) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Kelas</label>
            <select name="class_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">— Semua Kelas —</option>
                <?php foreach ($kelasList as $k): ?>
                <option value="<?= $k['id'] ?>" <?= $classId==$k['id']?'selected':'' ?>>
                    <?= esc($k['nama_kelas']) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <button type="submit" class="btn-primary px-4 py-2 rounded-xl text-sm font-semibold">
            <i class="fas fa-filter mr-1 text-xs"></i> Tampilkan
        </button>
    </form>
</div>

<!-- Tabel Rekap -->
<?php if (empty($rekapList) && ($scheduleId || $classId)): ?>
<div class="bg-white rounded-2xl py-12 text-center text-gray-400 shadow-sm border border-gray-100">
    <i class="fas fa-clipboard text-5xl text-gray-200 mb-4"></i>
    <p class="font-semibold">Belum ada data absensi untuk filter ini</p>
</div>
<?php elseif (empty($rekapList)): ?>
<div class="bg-white rounded-2xl py-12 text-center text-gray-400 shadow-sm border border-gray-100">
    <i class="fas fa-filter text-5xl text-gray-200 mb-4"></i>
    <p class="font-semibold">Pilih mata pelajaran atau kelas untuk melihat rekap</p>
</div>
<?php else: ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <div class="p-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Rekap Absensi — <?= $namaBulan[(int)$bulan] ?> <?= $tahun ?></h3>
        <p class="text-xs text-gray-400 mt-0.5"><?= count($rekapList) ?> siswa</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500">
                    <th class="text-left py-3 px-4">#</th>
                    <th class="text-left py-3 px-4">Nama Siswa</th>
                    <th class="text-left py-3 px-4">Mata Pelajaran</th>
                    <th class="text-left py-3 px-4">Kelas</th>
                    <th class="text-center py-3 px-4">Pertemuan</th>
                    <th class="text-center py-3 px-4 text-emerald-600">Hadir</th>
                    <th class="text-center py-3 px-4 text-red-500">Alpa</th>
                    <th class="text-center py-3 px-4">% Hadir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($rekapList as $i => $r): ?>
                <?php
                $total = max(1, (int)$r['total_pertemuan']);
                $pct   = $total > 0 ? round(($r['hadir'] / $total) * 100) : 0;
                $pctColor = $pct >= 80 ? 'text-emerald-600' : ($pct >= 60 ? 'text-yellow-600' : 'text-red-600');
                $barColor = $pct >= 80 ? '#10b981' : ($pct >= 60 ? '#f59e0b' : '#ef4444');
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4 text-gray-400 text-xs"><?= $i+1 ?></td>
                    <td class="py-3 px-4">
                        <p class="font-semibold text-gray-800"><?= esc($r['full_name']) ?></p>
                        <p class="text-xs text-gray-400"><?= esc($r['nis']) ?></p>
                    </td>
                    <td class="py-3 px-4 font-medium text-gray-700"><?= esc($r['subject']) ?></td>
                    <td class="py-3 px-4 text-gray-500"><?= esc($r['nama_kelas']) ?></td>
                    <td class="py-3 px-4 text-center font-semibold text-gray-700"><?= $r['total_pertemuan'] ?></td>
                    <td class="py-3 px-4 text-center font-bold text-emerald-600"><?= $r['hadir'] ?></td>
                    <td class="py-3 px-4 text-center font-bold <?= $r['alpa'] > 0 ? 'text-red-500' : 'text-gray-300' ?>"><?= $r['alpa'] ?></td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center gap-2 justify-center">
                            <div class="w-16 bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full" style="width:<?= $pct ?>%;background:<?= $barColor ?>"></div>
                            </div>
                            <span class="font-bold text-xs <?= $pctColor ?>"><?= $pct ?>%</span>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif ?>

<?php $this->endSection() ?>