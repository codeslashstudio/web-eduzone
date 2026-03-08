<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$kelas     = $kelas     ?? [];
$mapelList = $mapelList ?? [];
$siswaList = $siswaList ?? [];
$subject   = $subject   ?? '';
$tahun     = $tahun     ?? '2025/2026';
$semester  = $semester  ?? 'Ganjil';
?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('nilai') ?>" class="hover:text-gray-600">Nilai</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <a href="<?= base_url('nilai/kelas/' . $kelas['id'] . '?tahun=' . $tahun . '&semester=' . $semester) ?>" class="hover:text-gray-600"><?= esc($kelas['nama_kelas']) ?></a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold">Input Nilai</span>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Mata Pelajaran</label>
            <select name="subject" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none min-w-48">
                <?php foreach ($mapelList as $m): ?>
                <option value="<?= esc($m['subject']) ?>" <?= $subject === $m['subject'] ? 'selected' : '' ?>>
                    <?= esc($m['subject']) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <input type="hidden" name="tahun" value="<?= $tahun ?>">
        <input type="hidden" name="semester" value="<?= $semester ?>">
        <div class="ml-auto flex items-end gap-2 flex-wrap">
            <span class="text-xs bg-indigo-50 text-indigo-600 font-semibold px-3 py-2 rounded-xl">
                <?= $kelas['kurikulum'] ?? 'Merdeka' ?> · Formula: NH×40% + NT×60%
            </span>
        </div>
    </form>
</div>

<form method="POST" action="<?= base_url('nilai/store/' . $kelas['id']) ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="tahun" value="<?= $tahun ?>">
    <input type="hidden" name="semester" value="<?= $semester ?>">
    <input type="hidden" name="subject" value="<?= esc($subject) ?>">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800"><?= esc($subject) ?></h3>
                <p class="text-xs text-gray-400 mt-0.5"><?= $kelas['nama_kelas'] ?> · <?= $semester ?> <?= $tahun ?> · <?= count($siswaList) ?> siswa</p>
            </div>
            <div class="text-xs text-gray-400 bg-gray-50 rounded-xl px-3 py-2">
                <i class="fas fa-info-circle mr-1"></i>
                Nilai 0–100
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 w-8">#</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500">Nama Siswa</th>
                        <th class="py-3 px-4 font-semibold text-gray-500 text-center w-36">
                            Nilai Harian (NH)
                            <div class="font-normal text-gray-400 text-xs">bobot 40%</div>
                        </th>
                        <th class="py-3 px-4 font-semibold text-gray-500 text-center w-36">
                            Nilai Tugas (NT)
                            <div class="font-normal text-gray-400 text-xs">bobot 60%</div>
                        </th>
                        <th class="py-3 px-4 font-semibold text-gray-500 text-center w-28">Nilai Akhir</th>
                        <th class="py-3 px-4 font-semibold text-gray-500 text-center w-24">Predikat</th>
                        <th class="py-3 px-4 font-semibold text-gray-500 text-left w-48">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="nilaiTable">
                    <?php foreach ($siswaList as $i => $s): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-gray-400 text-xs"><?= $i+1 ?></td>
                        <td class="py-3 px-4">
                            <input type="hidden" name="student_id[]" value="<?= $s['id'] ?>">
                            <p class="font-semibold text-gray-800"><?= esc($s['full_name']) ?></p>
                            <p class="text-xs text-gray-400"><?= esc($s['nis']) ?></p>
                        </td>
                        <td class="py-3 px-4">
                            <input type="number" name="nilai_harian[]"
                                   value="<?= $s['nilai_harian'] ?? '' ?>"
                                   min="0" max="100" step="0.01"
                                   class="nh-input w-full text-center border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-400 text-sm font-semibold"
                                   placeholder="0–100">
                        </td>
                        <td class="py-3 px-4">
                            <input type="number" name="nilai_tugas[]"
                                   value="<?= $s['nilai_tugas'] ?? '' ?>"
                                   min="0" max="100" step="0.01"
                                   class="nt-input w-full text-center border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-400 text-sm font-semibold"
                                   placeholder="0–100">
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="na-display font-bold text-gray-700 text-base">
                                <?= $s['nilai_akhir'] ? number_format($s['nilai_akhir'],1) : '—' ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="predikat-display text-xs font-bold px-2 py-1 rounded-lg
                                <?php
                                $p = $s['predikat'] ?? '';
                                echo $p === 'A' ? 'bg-emerald-100 text-emerald-700' :
                                    ($p === 'B' ? 'bg-blue-100 text-blue-700' :
                                    ($p === 'C' ? 'bg-yellow-100 text-yellow-700' :
                                    ($p === 'D' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400')));
                                ?>">
                                <?= $p ?: '—' ?>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <input type="text" name="catatan[]"
                                   value="<?= esc($s['catatan'] ?? '') ?>"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-400 text-xs"
                                   placeholder="Catatan (opsional)">
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 flex justify-between items-center">
            <a href="<?= base_url('nilai/kelas/' . $kelas['id'] . '?tahun=' . $tahun . '&semester=' . $semester) ?>"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-1 text-xs"></i> Kembali
            </a>
            <button type="submit"
                    class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> Simpan Nilai
            </button>
        </div>
    </div>
</form>

<script>
// Auto-hitung nilai akhir (NH*40% + NT*60%) + predikat live
const kurikulum = '<?= $kelas['kurikulum'] ?? 'Merdeka' ?>';
const kkm = <?= $kelas['kkm'] ?? 75 ?>;

function hitungPredikat(na) {
    if (na >= 90) return ['A', 'bg-emerald-100 text-emerald-700'];
    if (na >= 80) return ['B', 'bg-blue-100 text-blue-700'];
    if (kurikulum === 'K13') {
        if (na >= kkm) return ['C', 'bg-yellow-100 text-yellow-700'];
    } else {
        if (na >= 70) return ['C', 'bg-yellow-100 text-yellow-700'];
    }
    return ['D', 'bg-red-100 text-red-600'];
}

document.querySelectorAll('#nilaiTable tr').forEach(row => {
    const nh = row.querySelector('.nh-input');
    const nt = row.querySelector('.nt-input');
    const naDisplay = row.querySelector('.na-display');
    const pdDisplay = row.querySelector('.predikat-display');
    if (!nh || !nt) return;

    function update() {
        const nhVal = parseFloat(nh.value);
        const ntVal = parseFloat(nt.value);
        if (isNaN(nhVal) && isNaN(ntVal)) { naDisplay.textContent = '—'; pdDisplay.textContent = '—'; return; }
        const na = ((isNaN(nhVal) ? 0 : nhVal) * 0.4 + (isNaN(ntVal) ? 0 : ntVal) * 0.6);
        const naRound = Math.round(na * 10) / 10;
        naDisplay.textContent = naRound.toFixed(1);
        const [pred, cls] = hitungPredikat(naRound);
        pdDisplay.textContent = pred;
        pdDisplay.className = 'predikat-display text-xs font-bold px-2 py-1 rounded-lg ' + cls;
        // Warna NA
        naDisplay.className = 'na-display font-bold text-base ' + (naRound >= 75 ? 'text-emerald-600' : 'text-red-600');
    }

    nh.addEventListener('input', update);
    nt.addEventListener('input', update);
});
</script>
<?php $this->endSection() ?>