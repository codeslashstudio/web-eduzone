<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<?php $role = session()->get('role') ?>
<a href="<?= base_url('dashboard/' . $role) ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<?php if (in_array($role, ['kepsek', 'tu', 'superadmin'])): ?>
<a href="<?= base_url('keuangan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-money-bill-wave w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Keuangan</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chart-line w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<?php endif ?>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-key w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<style>
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-input:focus { border-color: var(--color-primary); }
</style>

<!-- Breadcrumb + Back -->
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="<?= base_url('guru') ?>" class="hover:text-accent transition-colors">Data Guru</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="font-semibold" style="color: var(--color-primary)">
            <?= $mode === 'add' ? 'Tambah Guru' : 'Edit Guru' ?>
        </span>
    </div>
    <a href="<?= base_url('guru') ?>"
       class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold text-sm">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
    </a>
</div>

<!-- Error Alert -->
<?php if (session()->getFlashdata('errors')): ?>
<div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl" role="alert">
    <div class="flex items-start">
        <i class="fas fa-exclamation-circle text-xl mr-3 mt-0.5"></i>
        <div>
            <p class="font-bold mb-2">Terdapat kesalahan pada form:</p>
            <ul class="list-disc list-inside space-y-1 text-sm">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>
<?php endif ?>

<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">

    <!-- Form Header -->
    <div class="p-6" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
        <h2 class="text-xl font-bold text-white flex items-center">
            <i class="fas <?= $mode === 'add' ? 'fa-user-plus' : 'fa-user-edit' ?> mr-3"></i>
            <?= $mode === 'add' ? 'Form Tambah Guru' : 'Form Edit Guru: ' . esc($guru['full_name'] ?? '') ?>
        </h2>
        <p class="text-white/70 mt-1 text-sm">
            <?= $mode === 'add' ? 'Lengkapi semua data dengan benar' : 'Perbarui data yang diperlukan' ?>
        </p>
    </div>

    <form action="<?= $mode === 'add' ? base_url('guru/store') : base_url('guru/update/' . $guru['id']) ?>"
          method="POST" enctype="multipart/form-data" class="p-8">
        <?= csrf_field() ?>

        <!-- ============================================================
             DATA PRIBADI
             ============================================================ -->
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs" style="background: var(--color-primary)">
                    <i class="fas fa-user"></i>
                </span>
                Data Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NIP</label>
                    <input type="text" name="nip" class="form-input"
                        value="<?= old('nip', $guru['nip'] ?? '') ?>"
                        placeholder="Masukkan NIP (opsional)">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NUPTK</label>
                    <input type="text" name="nuptk" class="form-input"
                        value="<?= old('nuptk', $guru['nuptk'] ?? '') ?>"
                        placeholder="Masukkan NUPTK (opsional)">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" class="form-input" required
                        value="<?= old('full_name', $guru['full_name'] ?? '') ?>"
                        placeholder="Masukkan nama lengkap">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" class="form-input" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" <?= old('gender', $guru['gender'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= old('gender', $guru['gender'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Agama <span class="text-red-500">*</span></label>
                    <select name="religion" class="form-input" required>
                        <option value="">Pilih Agama</option>
                        <?php foreach (['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag): ?>
                            <option value="<?= $ag ?>" <?= old('religion', $guru['religion'] ?? '') === $ag ? 'selected' : '' ?>><?= $ag ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tempat Lahir</label>
                    <input type="text" name="birth_place" class="form-input"
                        value="<?= old('birth_place', $guru['birth_place'] ?? '') ?>"
                        placeholder="Kota kelahiran">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="form-input"
                        value="<?= old('birth_date', $guru['birth_date'] ?? '') ?>">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat</label>
                    <textarea name="address" rows="3" class="form-input"
                        placeholder="Masukkan alamat lengkap"><?= old('address', $guru['address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================
             DATA KONTAK
             ============================================================ -->
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs" style="background: var(--color-secondary)">
                    <i class="fas fa-address-book"></i>
                </span>
                Data Kontak
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. HP/WA</label>
                    <input type="text" name="phone" class="form-input"
                        value="<?= old('phone', $guru['phone'] ?? '') ?>"
                        placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="form-input"
                        value="<?= old('email', $guru['email'] ?? '') ?>"
                        placeholder="email@example.com">
                </div>
            </div>
        </div>

        <!-- ============================================================
             DATA KEPEGAWAIAN
             ============================================================ -->
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-emerald-500">
                    <i class="fas fa-briefcase"></i>
                </span>
                Data Kepegawaian
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                    <select name="last_education" class="form-input" required>
                        <option value="">Pilih Pendidikan</option>
                        <?php foreach (['D3','S1','S2','S3'] as $edu): ?>
                            <option value="<?= $edu ?>" <?= old('last_education', $guru['last_education'] ?? '') === $edu ? 'selected' : '' ?>><?= $edu ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jurusan/Prodi</label>
                    <input type="text" name="education_major" class="form-input"
                        value="<?= old('education_major', $guru['education_major'] ?? '') ?>"
                        placeholder="Contoh: Pendidikan Matematika">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Kepegawaian <span class="text-red-500">*</span></label>
                    <select name="employment_status" class="form-input" required>
                        <option value="">Pilih Status</option>
                        <?php foreach (['PNS','PPPK','Honorer','GTY','GTT'] as $status): ?>
                            <option value="<?= $status ?>" <?= old('employment_status', $guru['employment_status'] ?? '') === $status ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Mulai Mengajar</label>
                    <input type="date" name="joined_date" class="form-input"
                        value="<?= old('joined_date', $guru['joined_date'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================
             FOTO
             ============================================================ -->
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-orange-500">
                    <i class="fas fa-camera"></i>
                </span>
                Foto Profil
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <?= $mode === 'add' ? 'Upload Foto' : 'Upload Foto Baru' ?>
                    </label>
                    <input type="file" name="photo" accept="image/*" class="form-input"
                        onchange="previewImage(event)">
                    <p class="text-xs text-gray-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Format: JPG, PNG. Maks 2MB.
                        <?= $mode === 'edit' ? 'Kosongkan jika tidak ingin mengubah foto.' : '' ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Preview</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 flex items-center justify-center h-48 bg-gray-50">
                        <?php if ($mode === 'edit' && !empty($guru['photo'])): ?>
                            <img id="imagePreview" src="<?= base_url('uploads/guru/' . $guru['photo']) ?>"
                                class="max-h-full max-w-full object-contain rounded-lg" alt="Preview">
                            <div id="previewPlaceholder" style="display:none" class="text-center text-gray-400">
                        <?php else: ?>
                            <img id="imagePreview" class="max-h-full max-w-full object-contain rounded-lg hidden" alt="Preview">
                            <div id="previewPlaceholder" class="text-center text-gray-400">
                        <?php endif ?>
                                <i class="fas fa-image text-5xl mb-2 block opacity-30"></i>
                                <p class="text-sm">Preview foto akan muncul di sini</p>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
            <a href="<?= base_url('guru') ?>"
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <?php if ($mode === 'edit'): ?>
            <a href="<?= base_url('guru/detail/' . $guru['id']) ?>"
               class="px-6 py-3 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition-all font-semibold">
                <i class="fas fa-eye mr-2"></i>Lihat Detail
            </a>
            <?php endif ?>
            <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold">
                <i class="fas fa-save mr-2"></i><?= $mode === 'add' ? 'Simpan Data' : 'Update Data' ?>
            </button>
        </div>

    </form>
</div>

<?php $this->endSection() ?>


<?php $this->section('scripts') ?>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const preview     = document.getElementById('imagePreview');
        const placeholder = document.getElementById('previewPlaceholder');
        const reader      = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
</script>
<?php $this->endSection() ?>