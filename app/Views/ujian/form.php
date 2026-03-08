<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode      = $mode      ?? 'add';
$exam      = $exam      ?? [];
$kelasList = $kelasList ?? [];
$guruList  = $guruList  ?? [];
$teacherId = $teacherId ?? null;
$isEdit    = $mode === 'edit';
$action    = $isEdit ? base_url('ujian/update/' . $exam['id']) : base_url('ujian/store');
?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('ujian') ?>" class="hover:text-gray-600">Ujian</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= $isEdit ? 'Edit' : 'Buat' ?> Ujian</span>
</div>

<div class="max-w-xl">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-file-alt text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900"><?= $isEdit ? 'Edit' : 'Buat' ?> Ujian</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Ujian <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required
                           value="<?= esc(old('name', $exam['name'] ?? '')) ?>"
                           placeholder="contoh: UTS Matematika Semester Ganjil 2025"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Mata Pelajaran <span class="text-red-400">*</span></label>
                        <input type="text" name="subject" required
                               value="<?= esc(old('subject', $exam['subject'] ?? '')) ?>"
                               placeholder="Matematika"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kelas</label>
                        <select name="class_id" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                            <option value="">Semua Kelas</option>
                            <?php foreach ($kelasList as $k): ?>
                            <option value="<?= $k['id'] ?>"
                                <?= old('class_id', $exam['class_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                                <?= esc($k['nama_kelas']) ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tingkat</label>
                        <select name="grade" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                            <?php foreach (['X','XI','XII'] as $g): ?>
                            <option value="<?= $g ?>" <?= old('grade', $exam['grade'] ?? 'X') === $g ? 'selected' : '' ?>><?= $g ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jurusan</label>
                        <input type="text" name="major"
                               value="<?= esc(old('major', $exam['major'] ?? '')) ?>"
                               placeholder="IPA, IPS, dll"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                </div>
                <!-- Tanggal & Jam -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal <span class="text-red-400">*</span></label>
                    <input type="date" name="date" required
                           value="<?= esc(old('date', $exam['date'] ?? '')) ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Mulai</label>
                        <input type="time" name="start_time"
                               value="<?= esc(old('start_time', $exam['start_time'] ?? '07:30')) ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Selesai</label>
                        <input type="time" name="end_time"
                               value="<?= esc(old('end_time', $exam['end_time'] ?? '09:30')) ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                </div>
                <!-- Pengawas -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pengawas</label>
                    <select name="supervisor_id" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                        <option value="">-- Pilih Pengawas --</option>
                        <?php foreach ($guruList as $g): ?>
                        <option value="<?= $g['id'] ?>"
                            <?= old('supervisor_id', $exam['supervisor_id'] ?? $teacherId) == $g['id'] ? 'selected' : '' ?>>
                            <?= esc($g['full_name']) ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="<?= base_url('ujian') ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus' ?> text-xs"></i>
                    <?= $isEdit ? 'Simpan' : 'Buat Ujian' ?>
                </button>
            </div>
        </div>
    </form>
</div>
<?php $this->endSection() ?>