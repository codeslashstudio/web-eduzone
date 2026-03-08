<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('guru/add') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-user-plus w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Tambah Guru</span>
</a>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$mode   = $mode   ?? 'add';
$guru   = $guru   ?? [];
$isEdit = $mode === 'edit';
$action = $isEdit
    ? base_url('guru/update/' . ($guru['id'] ?? ''))
    : base_url('guru/store');
?>

<!-- Flash errors -->
<?php if (session()->getFlashdata('errors')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <ul class="text-sm space-y-0.5">
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

<form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Kolom kiri: Foto -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-5" data-aos="fade-up">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-image text-sm" style="color:var(--color-primary)"></i>
                    Foto Guru
                </h3>
                <!-- Preview -->
                <div class="flex flex-col items-center">
                    <div id="photoPreview"
                         class="w-32 h-32 rounded-2xl overflow-hidden bg-gray-100 flex items-center justify-center mb-4 border-2 border-dashed border-gray-200">
                        <?php if ($isEdit && !empty($guru['photo'])): ?>
                        <img src="<?= base_url('uploads/guru/' . $guru['photo']) ?>"
                             id="previewImg" class="w-full h-full object-cover" alt="">
                        <?php else: ?>
                        <div id="previewPlaceholder" class="text-center">
                            <i class="fas fa-user text-4xl text-gray-300 mb-1"></i>
                            <p class="text-xs text-gray-400">Belum ada foto</p>
                        </div>
                        <?php endif ?>
                    </div>
                    <label class="cursor-pointer btn-primary px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-camera text-xs"></i> Pilih Foto
                        <input type="file" name="photo" accept="image/jpeg,image/png" class="hidden"
                               onchange="previewPhoto(this)">
                    </label>
                    <p class="text-xs text-gray-400 mt-2 text-center">JPG/PNG, maks. 2MB</p>
                </div>

                <!-- Status aktif (edit only) -->
                <?php if ($isEdit): ?>
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Status</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1"
                                   <?= ($guru['is_active'] ?? 1) == 1 ? 'checked' : '' ?>
                                   style="accent-color:var(--color-primary)">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0"
                                   <?= ($guru['is_active'] ?? 1) == 0 ? 'checked' : '' ?>
                                   style="accent-color:var(--color-primary)">
                            <span class="text-sm text-gray-700">Nonaktif</span>
                        </label>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>

        <!-- Kolom kanan: Form -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Data Pribadi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fas fa-id-card text-sm" style="color:var(--color-primary)"></i>
                    <h3 class="font-bold text-gray-900">Data Pribadi</h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <!-- Nama Lengkap -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="full_name"
                               value="<?= esc(old('full_name', $guru['full_name'] ?? '')) ?>"
                               placeholder="Nama lengkap sesuai ijazah"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">NIP</label>
                        <input type="text" name="nip"
                               value="<?= esc(old('nip', $guru['nip'] ?? '')) ?>"
                               placeholder="18 digit NIP"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all font-mono">
                    </div>

                    <!-- NUPTK -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">NUPTK</label>
                        <input type="text" name="nuptk"
                               value="<?= esc(old('nuptk', $guru['nuptk'] ?? '')) ?>"
                               placeholder="16 digit NUPTK"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all font-mono">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Jenis Kelamin <span class="text-red-400">*</span>
                        </label>
                        <div class="flex gap-4 pt-1">
                            <?php foreach (['L' => 'Laki-laki', 'P' => 'Perempuan'] as $val => $lbl): ?>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="<?= $val ?>"
                                       <?= old('gender', $guru['gender'] ?? '') === $val ? 'checked' : '' ?>
                                       style="accent-color:var(--color-primary)">
                                <span class="text-sm text-gray-700"><?= $lbl ?></span>
                            </label>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <!-- Agama -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Agama <span class="text-red-400">*</span>
                        </label>
                        <select name="religion" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all bg-white">
                            <option value="">-- Pilih Agama --</option>
                            <?php foreach (['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $r): ?>
                            <option value="<?= $r ?>" <?= old('religion', $guru['religion'] ?? '') === $r ? 'selected' : '' ?>><?= $r ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tempat Lahir</label>
                        <input type="text" name="birth_place"
                               value="<?= esc(old('birth_place', $guru['birth_place'] ?? '')) ?>"
                               placeholder="Kota kelahiran"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="birth_date"
                               value="<?= esc(old('birth_date', $guru['birth_date'] ?? '')) ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>

                    <!-- Alamat -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat</label>
                        <textarea name="address" rows="2"
                                  placeholder="Alamat lengkap"
                                  class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all resize-none"><?= esc(old('address', $guru['address'] ?? '')) ?></textarea>
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">No. HP</label>
                        <input type="tel" name="phone"
                               value="<?= esc(old('phone', $guru['phone'] ?? '')) ?>"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                        <input type="email" name="email"
                               value="<?= esc(old('email', $guru['email'] ?? '')) ?>"
                               placeholder="email@sekolah.sch.id"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>
                </div>
            </div>

            <!-- Data Kepegawaian -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fas fa-briefcase text-sm" style="color:var(--color-primary)"></i>
                    <h3 class="font-bold text-gray-900">Kepegawaian & Pendidikan</h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <!-- Status Kepegawaian -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Status Kepegawaian <span class="text-red-400">*</span>
                        </label>
                        <select name="employment_status" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all bg-white">
                            <option value="">-- Pilih Status --</option>
                            <?php foreach (['PNS','PPPK','Honorer','GTY','GTT'] as $st): ?>
                            <option value="<?= $st ?>" <?= old('employment_status', $guru['employment_status'] ?? '') === $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Tanggal Bergabung -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Bergabung</label>
                        <input type="date" name="joined_date"
                               value="<?= esc(old('joined_date', $guru['joined_date'] ?? '')) ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>

                    <!-- Pendidikan Terakhir -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Pendidikan Terakhir <span class="text-red-400">*</span>
                        </label>
                        <select name="last_education" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all bg-white">
                            <option value="">-- Pilih Pendidikan --</option>
                            <?php foreach (['S3','S2','S1','D4','D3','D2','D1','SMA/SMK/MA'] as $edu): ?>
                            <option value="<?= $edu ?>" <?= old('last_education', $guru['last_education'] ?? '') === $edu ? 'selected' : '' ?>><?= $edu ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Jurusan Pendidikan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jurusan Pendidikan</label>
                        <input type="text" name="education_major"
                               value="<?= esc(old('education_major', $guru['education_major'] ?? '')) ?>"
                               placeholder="Contoh: Pendidikan Matematika"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between gap-3" data-aos="fade-up">
                <a href="<?= base_url('guru') ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus' ?> text-xs"></i>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Guru' ?>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function previewPhoto(input) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('photoPreview');
        preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="">`;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
<?php $this->endSection() ?>