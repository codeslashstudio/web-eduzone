<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode      = $mode      ?? 'add';
$item      = $item      ?? [];
$majorList = $majorList ?? [];
$isEdit    = $mode === 'edit';
$action    = $isEdit ? base_url('master/kelas/update/' . $item['id']) : base_url('master/kelas/store');
?>

<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('master/kelas') ?>" class="hover:text-gray-600">Manajemen Kelas</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= $isEdit ? 'Edit' : 'Tambah' ?> Kelas</span>
</div>

<div class="max-w-lg">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-door-open text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900"><?= $isEdit ? 'Edit' : 'Tambah' ?> Kelas</h3>
            </div>
            <div class="p-5 space-y-4">

                <div class="grid grid-cols-3 gap-3">
                    <!-- Tingkat -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tingkat <span class="text-red-400">*</span></label>
                        <select name="grade" required
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white"
                                onchange="updateNamaKelas()">
                            <?php foreach (['X','XI','XII'] as $g): ?>
                            <option value="<?= $g ?>" <?= old('grade', $item['grade'] ?? 'X') === $g ? 'selected' : '' ?>><?= $g ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <!-- Jurusan -->
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jurusan <span class="text-red-400">*</span></label>
                        <select name="major_id" id="majorSelect" required
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white"
                                onchange="updateNamaKelas()">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($majorList as $m): ?>
                            <option value="<?= $m['id'] ?>" data-abbr="<?= esc($m['abbreviation']) ?>"
                                <?= old('major_id', $item['major_id'] ?? '') == $m['id'] ? 'selected' : '' ?>>
                                <?= esc($m['name']) ?> (<?= esc($m['abbreviation']) ?>)
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Rombel -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Rombel <span class="text-red-400">*</span></label>
                        <input type="text" name="class_group" required
                               value="<?= esc(old('class_group', $item['class_group'] ?? '')) ?>"
                               placeholder="1, 2, 3..."
                               maxlength="10"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400"
                               oninput="updateNamaKelas()">
                    </div>
                    <!-- Kapasitas -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kapasitas</label>
                        <input type="number" name="kapasitas" min="1" max="60"
                               value="<?= esc(old('kapasitas', $item['kapasitas'] ?? 36)) ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tahun Ajaran <span class="text-red-400">*</span></label>
                    <select name="academic_year" required
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                        <?php foreach (['2025/2026','2024/2025','2026/2027','2023/2024'] as $ty): ?>
                        <option value="<?= $ty ?>" <?= old('academic_year', $item['academic_year'] ?? '2025/2026') === $ty ? 'selected' : '' ?>><?= $ty ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Nama kelas (auto-generated tapi bisa diubah) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Nama Kelas
                        <span class="font-normal text-gray-400 ml-1">— otomatis, bisa diubah</span>
                    </label>
                    <input type="text" name="nama_kelas" id="namaKelas"
                           value="<?= esc(old('nama_kelas', $item['nama_kelas'] ?? '')) ?>"
                           placeholder="contoh: X IPA 1"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>

            </div>
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="<?= base_url('master/kelas') ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus' ?> text-xs"></i>
                    <?= $isEdit ? 'Simpan' : 'Tambah Kelas' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
function updateNamaKelas() {
    const grade   = document.querySelector('[name="grade"]')?.value ?? '';
    const sel     = document.getElementById('majorSelect');
    const abbr    = sel?.options[sel.selectedIndex]?.dataset?.abbr ?? '';
    const group   = document.querySelector('[name="class_group"]')?.value ?? '';
    const field   = document.getElementById('namaKelas');
    if (grade && abbr && group) {
        field.value = `${grade} ${abbr} ${group}`;
    }
}
</script>
<?php $this->endSection() ?>