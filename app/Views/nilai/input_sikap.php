<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$kelas     = $kelas     ?? [];
$siswaList = $siswaList ?? [];
$tahun     = $tahun     ?? '2025/2026';
$semester  = $semester  ?? 'Ganjil';
$sikapOpts = ['SB'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','K'=>'Kurang'];
?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('nilai') ?>" class="hover:text-gray-600">Nilai</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <a href="<?= base_url('nilai/kelas/' . $kelas['id'] . '?tahun=' . $tahun . '&semester=' . $semester) ?>" class="hover:text-gray-600"><?= esc($kelas['nama_kelas']) ?></a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold">Input Sikap & Kehadiran</span>
</div>

<form method="POST" action="<?= base_url('nilai/store-sikap/' . $kelas['id']) ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="tahun" value="<?= $tahun ?>">
    <input type="hidden" name="semester" value="<?= $semester ?>">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Sikap & Kehadiran</h3>
            <p class="text-xs text-gray-400 mt-0.5"><?= $kelas['nama_kelas'] ?> · <?= $semester ?> <?= $tahun ?></p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500">
                        <th class="text-left py-3 px-4">#</th>
                        <th class="text-left py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4 text-center">Sikap Spiritual</th>
                        <th class="py-3 px-4 text-center">Sikap Sosial</th>
                        <th class="py-3 px-4 text-center w-16">Sakit</th>
                        <th class="py-3 px-4 text-center w-16">Izin</th>
                        <th class="py-3 px-4 text-center w-16">Alpa</th>
                        <th class="py-3 px-4 text-left">Catatan Wali Kelas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($siswaList as $i => $s): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-gray-400 text-xs"><?= $i+1 ?></td>
                        <td class="py-3 px-4">
                            <input type="hidden" name="student_id[]" value="<?= $s['id'] ?>">
                            <p class="font-semibold text-gray-800"><?= esc($s['full_name']) ?></p>
                            <p class="text-xs text-gray-400"><?= esc($s['nis']) ?></p>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <select name="sikap_spiritual[]"
                                    class="text-xs border border-gray-200 rounded-xl px-2 py-1.5 bg-white focus:outline-none focus:border-indigo-400">
                                <?php foreach ($sikapOpts as $k => $v): ?>
                                <option value="<?= $k ?>" <?= ($s['sikap_spiritual'] ?? 'B') === $k ? 'selected' : '' ?>>
                                    <?= $k ?> — <?= $v ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <select name="sikap_sosial[]"
                                    class="text-xs border border-gray-200 rounded-xl px-2 py-1.5 bg-white focus:outline-none focus:border-indigo-400">
                                <?php foreach ($sikapOpts as $k => $v): ?>
                                <option value="<?= $k ?>" <?= ($s['sikap_sosial'] ?? 'B') === $k ? 'selected' : '' ?>>
                                    <?= $k ?> — <?= $v ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <input type="number" name="sakit[]" value="<?= $s['ketidakhadiran_sakit'] ?? 0 ?>"
                                   min="0" class="w-14 text-center border border-gray-200 rounded-xl px-2 py-1.5 focus:outline-none focus:border-indigo-400 text-xs">
                        </td>
                        <td class="py-3 px-4 text-center">
                            <input type="number" name="izin[]" value="<?= $s['ketidakhadiran_izin'] ?? 0 ?>"
                                   min="0" class="w-14 text-center border border-gray-200 rounded-xl px-2 py-1.5 focus:outline-none focus:border-indigo-400 text-xs">
                        </td>
                        <td class="py-3 px-4 text-center">
                            <input type="number" name="alpa[]" value="<?= $s['ketidakhadiran_alpa'] ?? 0 ?>"
                                   min="0" class="w-14 text-center border border-gray-200 rounded-xl px-2 py-1.5 focus:outline-none focus:border-red-400 text-xs">
                        </td>
                        <td class="py-3 px-4">
                            <input type="text" name="catatan_wakel[]"
                                   value="<?= esc($s['catatan_wakel'] ?? '') ?>"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-1.5 focus:outline-none focus:border-indigo-400 text-xs"
                                   placeholder="Catatan untuk rapor...">
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 flex justify-between">
            <a href="<?= base_url('nilai/kelas/' . $kelas['id'] . '?tahun=' . $tahun . '&semester=' . $semester) ?>"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-1 text-xs"></i> Kembali
            </a>
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> Simpan Sikap
            </button>
        </div>
    </div>
</form>
<?php $this->endSection() ?>