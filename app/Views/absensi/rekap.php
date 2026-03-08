<?php $this->extend('layout/main') ?>

<?php $this->section('content') ?>

<?php
$namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$kelasList = $kelasList ?? [];
$rekap     = $rekap     ?? [];
$kelasInfo = $kelasInfo ?? [];
$class_id  = $class_id  ?? '';
$bulan     = $bulan     ?? date('m');
$tahun     = $tahun     ?? date('Y');
$kelasNama = $kelasInfo['nama_kelas'] ?? '';
?>

<!-- Filter -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6" data-aos="fade-up">
    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <i class="fas fa-filter" style="color:var(--color-primary)"></i>
        Filter Rekap
    </h2>
    <form method="get" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas</label>
            <select name="class_id" class="px-4 py-2 rounded-xl border-2 border-gray-200 focus:outline-none text-sm min-w-40">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($kelasList as $k): ?>
                <option value="<?= $k['class_id'] ?>" <?= $class_id == $k['class_id'] ? 'selected' : '' ?>>
                    <?= esc($k['nama_kelas']) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
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
        <button type="submit" class="btn-primary px-5 py-2 rounded-xl font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-search"></i><span>Tampilkan</span>
        </button>
    </form>
</div>

<?php if ($class_id && !empty($rekap)): ?>

<!-- Stat summary -->
<?php
$totalH   = array_sum(array_column($rekap, 'hadir'));
$totalS   = array_sum(array_column($rekap, 'sakit'));
$totalI   = array_sum(array_column($rekap, 'izin'));
$totalA   = array_sum(array_column($rekap, 'alpa'));
$totalAll = $totalH + $totalS + $totalI + $totalA;
$pct      = $totalAll > 0 ? round($totalH / $totalAll * 100, 1) : 0;
?>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
        <p class="text-2xl font-bold"><?= $totalH ?></p>
        <p class="text-white/80 text-sm">Total Hadir (<?= $pct ?>%)</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl p-4 text-white shadow-lg">
        <p class="text-2xl font-bold"><?= $totalS ?></p>
        <p class="text-white/80 text-sm">Total Sakit</p>
    </div>
    <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl p-4 text-white shadow-lg">
        <p class="text-2xl font-bold"><?= $totalI ?></p>
        <p class="text-white/80 text-sm">Total Izin</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-4 text-white shadow-lg">
        <p class="text-2xl font-bold"><?= $totalA ?></p>
        <p class="text-white/80 text-sm">Total Alpa</p>
    </div>
</div>

<!-- Tabel Rekap -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">
                Rekap Absensi — <?= esc($kelasNama) ?>
            </h2>
            <p class="text-sm text-gray-400"><?= $namaBulan[(int)$bulan] ?> <?= $tahun ?> • <?= count($rekap) ?> siswa</p>
        </div>
        <button onclick="exportExcel()" class="btn-primary px-5 py-2 rounded-xl font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-file-excel"></i>Export Excel
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="rekapTable">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" class="text-white">
                    <th class="px-4 py-3 text-left text-sm font-bold">No</th>
                    <th class="px-4 py-3 text-left text-sm font-bold">NIS</th>
                    <th class="px-4 py-3 text-left text-sm font-bold">Nama Siswa</th>
                    <th class="px-4 py-3 text-center text-sm font-bold">Hadir</th>
                    <th class="px-4 py-3 text-center text-sm font-bold">Sakit</th>
                    <th class="px-4 py-3 text-center text-sm font-bold">Izin</th>
                    <th class="px-4 py-3 text-center text-sm font-bold">Alpa</th>
                    <th class="px-4 py-3 text-center text-sm font-bold">Total Hari</th>
                    <th class="px-4 py-3 text-center text-sm font-bold">% Hadir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($rekap as $i => $r):
                    $total  = $r['hadir'] + $r['sakit'] + $r['izin'] + $r['alpa'];
                    $pctRow = $total > 0 ? round($r['hadir'] / $total * 100, 1) : 0;
                    $pctColor = $pctRow >= 75 ? 'text-emerald-600' : ($pctRow >= 50 ? 'text-yellow-600' : 'text-red-600');
                ?>
                <tr class="hover:bg-gray-50 transition-colors <?= $r['alpa'] > 3 ? 'bg-red-50/30' : '' ?>">
                    <td class="px-4 py-3 text-sm text-gray-500"><?= $i + 1 ?></td>
                    <td class="px-4 py-3 text-sm text-gray-600"><?= esc($r['nis']) ?></td>
                    <td class="px-4 py-3 font-semibold text-gray-800 text-sm"><?= esc($r['full_name']) ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold"><?= $r['hadir'] ?></span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-sm font-bold"><?= $r['sakit'] ?></span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-sm font-bold"><?= $r['izin'] ?></span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-full text-sm font-bold <?= $r['alpa'] > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500' ?>">
                            <?= $r['alpa'] ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-sm font-semibold text-gray-700"><?= $total ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm font-bold <?= $pctColor ?>"><?= $pctRow ?>%</span>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php elseif ($class_id && empty($rekap)): ?>
<div class="bg-white rounded-2xl shadow-lg p-12 text-center" data-aos="fade-up">
    <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
    <h3 class="text-lg font-bold text-gray-700 mb-2">Belum ada data absensi</h3>
    <p class="text-gray-400 text-sm">Belum ada absensi yang diinput untuk kelas ini pada bulan yang dipilih</p>
</div>

<?php else: ?>
<div class="bg-white rounded-2xl shadow-lg p-12 text-center" data-aos="fade-up">
    <i class="fas fa-table text-6xl mb-4" style="color: var(--color-primary); opacity: 0.3"></i>
    <h3 class="text-lg font-bold text-gray-700 mb-2">Pilih Kelas & Bulan</h3>
    <p class="text-gray-400 text-sm">Pilih kelas, bulan, dan tahun untuk melihat rekap absensi</p>
</div>
<?php endif ?>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportExcel() {
    const table = document.getElementById('rekapTable');
    if (!table) return;
    const wb       = XLSX.utils.table_to_book(table, { sheet: 'Rekap Absensi' });
    const kelasNama = '<?= esc($kelasNama) ?>'.replace(/\s+/g, '_');
    const bulan     = '<?= $namaBulan[(int)$bulan] ?? "" ?>';
    const tahun     = '<?= $tahun ?>';
    XLSX.writeFile(wb, `Rekap_Absensi_${kelasNama}_${bulan}_${tahun}.xlsx`);
}
</script>
<?php $this->endSection() ?>