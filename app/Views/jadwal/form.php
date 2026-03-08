<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('jadwal') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<a href="<?= base_url('jadwal/add') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-plus-circle w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Tambah Jadwal</span>
</a>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$mode      = $mode      ?? 'add';
$jadwal    = $jadwal    ?? null;
$guruList  = $guruList  ?? [];
$kelasList = $kelasList ?? [];
$hariList  = $hariList  ?? ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$mapelList = $mapelList ?? [];
$isEdit    = $mode === 'edit';
$actionUrl = $isEdit
    ? base_url('jadwal/update/' . ($jadwal['id'] ?? ''))
    : base_url('jadwal/store');
?>

<!-- Flash errors -->
<?php if (session()->getFlashdata('errors')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <ul class="text-sm space-y-1">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
        <li><?= esc($err) ?></li>
        <?php endforeach ?>
    </ul>
</div>
<?php endif ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="stat-icon-bg w-10 h-10 rounded-xl flex items-center justify-center">
                <i class="fas <?= $isEdit ? 'fa-pencil' : 'fa-plus' ?> text-sm"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-900"><?= $isEdit ? 'Edit Jadwal' : 'Tambah Jadwal Baru' ?></h2>
                <p class="text-xs text-gray-400"><?= $isEdit ? esc($jadwal['subject'] ?? '') : 'Isi form di bawah dengan lengkap' ?></p>
            </div>
        </div>

        <form method="POST" action="<?= $actionUrl ?>">
            <?= csrf_field() ?>
            <div class="p-6 space-y-5">

                <!-- Mata Pelajaran -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Mata Pelajaran <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="subject"
                            value="<?= esc(old('subject', $jadwal['subject'] ?? '')) ?>"
                            list="mapelSuggestions"
                            placeholder="Contoh: Matematika, Bahasa Indonesia..."
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                        <datalist id="mapelSuggestions">
                            <?php foreach ($mapelList as $m): ?>
                            <option value="<?= esc($m) ?>">
                            <?php endforeach ?>
                        </datalist>
                    </div>
                </div>

                <!-- Guru -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Guru Pengampu <span class="text-red-400">*</span>
                    </label>
                    <select name="teacher_id" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all bg-white">
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($guruList as $g): ?>
                        <option value="<?= $g['id'] ?>"
                            <?= old('teacher_id', $jadwal['teacher_id'] ?? '') == $g['id'] ? 'selected' : '' ?>>
                            <?= esc($g['full_name']) ?> <?= $g['nip'] ? '(' . esc($g['nip']) . ')' : '' ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Kelas <span class="text-red-400">*</span>
                    </label>
                    <select name="class_id" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all bg-white">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['class_id'] ?>"
                            <?= old('class_id', $jadwal['class_id'] ?? '') == $k['class_id'] ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Hari + Ruang -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Hari <span class="text-red-400">*</span>
                        </label>
                        <select name="day" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all bg-white">
                            <option value="">-- Pilih Hari --</option>
                            <?php foreach ($hariList as $h): ?>
                            <option value="<?= $h ?>"
                                <?= old('day', $jadwal['day'] ?? '') === $h ? 'selected' : '' ?>>
                                <?= $h ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ruang</label>
                        <input type="text" name="room"
                            value="<?= esc(old('room', $jadwal['room'] ?? '')) ?>"
                            placeholder="Contoh: Kelas XII IPA 1, Lab Komputer"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>
                </div>

                <!-- Jam Mulai + Selesai -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Jam Mulai <span class="text-red-400">*</span>
                        </label>
                        <input type="time" name="start_time"
                            value="<?= old('start_time', isset($jadwal['start_time']) ? substr($jadwal['start_time'],0,5) : '') ?>"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Jam Selesai <span class="text-red-400">*</span>
                        </label>
                        <input type="time" name="end_time"
                            value="<?= old('end_time', isset($jadwal['end_time']) ? substr($jadwal['end_time'],0,5) : '') ?>"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>
                </div>

                <!-- Durasi preview -->
                <div id="durasiPreview" class="hidden text-xs text-gray-500 bg-gray-50 rounded-xl px-4 py-2.5 flex items-center gap-2">
                    <i class="fas fa-clock text-gray-400"></i>
                    <span id="durasiText"></span>
                </div>

                <!-- Status (edit only) -->
                <?php if ($isEdit): ?>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1"
                                <?= ($jadwal['is_active'] ?? 1) == 1 ? 'checked' : '' ?>
                                class="w-4 h-4" style="accent-color:var(--color-primary)">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0"
                                <?= ($jadwal['is_active'] ?? 1) == 0 ? 'checked' : '' ?>
                                class="w-4 h-4" style="accent-color:var(--color-primary)">
                            <span class="text-sm text-gray-700">Nonaktif</span>
                        </label>
                    </div>
                </div>
                <?php endif ?>

            </div>

            <!-- Footer buttons -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
                <a href="<?= base_url('jadwal') ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus' ?> text-xs"></i>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Jadwal' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview durasi
const startEl = document.querySelector('[name="start_time"]');
const endEl   = document.querySelector('[name="end_time"]');
const preview = document.getElementById('durasiPreview');
const durasiTxt = document.getElementById('durasiText');

function updateDurasi() {
    if (!startEl.value || !endEl.value) { preview.classList.add('hidden'); return; }
    const [sh, sm] = startEl.value.split(':').map(Number);
    const [eh, em] = endEl.value.split(':').map(Number);
    const menit = (eh * 60 + em) - (sh * 60 + sm);
    if (menit <= 0) { preview.classList.add('hidden'); return; }
    const jam = Math.floor(menit / 60);
    const sisa = menit % 60;
    durasiTxt.textContent = `Durasi: ${jam > 0 ? jam + ' jam ' : ''}${sisa > 0 ? sisa + ' menit' : ''}`;
    preview.classList.remove('hidden');
}
startEl.addEventListener('change', updateDurasi);
endEl.addEventListener('change', updateDurasi);
updateDurasi();
</script>
<?php $this->endSection() ?>