<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<?php $role = session()->get('role') ?>
<a href="<?= base_url('dashboard/' . $role) ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<?php if (in_array($role, ['kepsek', 'tu', 'superadmin'])): ?>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
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
    .form-input:focus {
        border-color: var(--color-primary);
    }
    .image-preview { display: none; }
    .image-preview.show { display: block; }
</style>

<!-- Back button di header sudah ada di main.php, ini tambahan breadcrumb -->
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="<?= base_url('siswa') ?>" class="hover:text-accent transition-colors">Data Siswa</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-accent font-semibold"><?= $mode === 'add' ? 'Tambah Siswa' : 'Edit Siswa' ?></span>
    </div>
    <a href="<?= base_url('siswa') ?>"
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

<div class="bg-white rounded-2xl shadow-lg p-8" data-aos="fade-up">

    <form action="<?= $mode === 'add' ? base_url('siswa/store') : base_url('siswa/update/' . $siswa['id']) ?>"
          method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- ============================================================
             DATA PRIBADI
             ============================================================ -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-white text-sm" style="background: var(--color-primary)">
                    <i class="fas fa-user"></i>
                </span>
                Data Pribadi Siswa
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" class="form-input"
                        value="<?= old('full_name', $siswa['full_name'] ?? '') ?>"
                        placeholder="Masukkan nama lengkap" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NIS</label>
                    <input type="text" name="nis" class="form-input"
                        value="<?= old('nis', $siswa['nis'] ?? '') ?>"
                        placeholder="Nomor Induk Siswa">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NISN <span class="text-red-500">*</span></label>
                    <input type="text" id="nisn" name="nisn" class="form-input"
                        value="<?= old('nisn', $siswa['nisn'] ?? '') ?>"
                        placeholder="10 digit NISN" maxlength="10" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" class="form-input" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" <?= old('gender', $siswa['gender'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= old('gender', $siswa['gender'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tempat Lahir</label>
                    <input type="text" name="birth_place" class="form-input"
                        value="<?= old('birth_place', $siswa['birth_place'] ?? '') ?>"
                        placeholder="Kota kelahiran">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="birth_date" class="form-input"
                        value="<?= old('birth_date', $siswa['birth_date'] ?? '') ?>" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Agama <span class="text-red-500">*</span></label>
                    <select name="religion" class="form-input" required>
                        <option value="">Pilih Agama</option>
                        <?php foreach (['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag): ?>
                            <option value="<?= $ag ?>" <?= old('religion', $siswa['religion'] ?? '') === $ag ? 'selected' : '' ?>><?= $ag ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. HP</label>
                    <input type="text" name="phone" class="form-input"
                        value="<?= old('phone', $siswa['phone'] ?? '') ?>"
                        placeholder="08xxxxxxxxxx">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="3" class="form-input"
                        placeholder="Masukkan alamat lengkap" required><?= old('address', $siswa['address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================
             DATA AKADEMIK
             ============================================================ -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-white text-sm" style="background: var(--color-secondary)">
                    <i class="fas fa-graduation-cap"></i>
                </span>
                Data Akademik
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select name="grade" class="form-input" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach (['X','XI','XII'] as $kelas): ?>
                            <option value="<?= $kelas ?>" <?= old('grade', $siswa['grade'] ?? '') === $kelas ? 'selected' : '' ?>><?= $kelas ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jurusan <span class="text-red-500">*</span></label>
                    <select name="major_id" class="form-input" required>
                        <option value="">Pilih Jurusan</option>
                        <?php foreach ($jurusan as $j): ?>
                            <option value="<?= $j['id'] ?>" <?= old('major_id', $siswa['major_id'] ?? '') == $j['id'] ? 'selected' : '' ?>>
                                <?= esc($j['abbreviation']) ?> - <?= esc($j['name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Rombongan Belajar</label>
                    <select name="class_group" class="form-input">
                        <option value="">Pilih Rombel</option>
                        <?php foreach (['1','2','3','4'] as $rb): ?>
                            <option value="<?= $rb ?>" <?= old('class_group', $siswa['class_group'] ?? '') === $rb ? 'selected' : '' ?>><?= $rb ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif"  <?= old('status', $siswa['status'] ?? 'aktif') === 'aktif'  ? 'selected' : '' ?>>Aktif</option>
                        <option value="keluar" <?= old('status', $siswa['status'] ?? '') === 'keluar' ? 'selected' : '' ?>>Keluar</option>
                        <option value="lulus"  <?= old('status', $siswa['status'] ?? '') === 'lulus'  ? 'selected' : '' ?>>Lulus</option>
                        <option value="pindah" <?= old('status', $siswa['status'] ?? '') === 'pindah' ? 'selected' : '' ?>>Pindah</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Masuk</label>
                    <input type="date" name="joined_date" class="form-input"
                        value="<?= old('joined_date', $siswa['joined_date'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================
             DATA ORANG TUA
             ============================================================ -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-white text-sm bg-emerald-500">
                    <i class="fas fa-users"></i>
                </span>
                Data Orang Tua
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ayah <span class="text-red-500">*</span></label>
                    <input type="text" name="father_name" class="form-input"
                        value="<?= old('father_name', $siswa['father_name'] ?? '') ?>"
                        placeholder="Nama ayah" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ibu <span class="text-red-500">*</span></label>
                    <input type="text" name="mother_name" class="form-input"
                        value="<?= old('mother_name', $siswa['mother_name'] ?? '') ?>"
                        placeholder="Nama ibu" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pekerjaan Ayah</label>
                    <input type="text" name="father_job" class="form-input"
                        value="<?= old('father_job', $siswa['father_job'] ?? '') ?>"
                        placeholder="Pekerjaan ayah">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pekerjaan Ibu</label>
                    <input type="text" name="mother_job" class="form-input"
                        value="<?= old('mother_job', $siswa['mother_job'] ?? '') ?>"
                        placeholder="Pekerjaan ibu">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. HP Orang Tua</label>
                    <input type="text" name="parent_phone" class="form-input"
                        value="<?= old('parent_phone', $siswa['parent_phone'] ?? '') ?>"
                        placeholder="08xxxxxxxxxx">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Orang Tua</label>
                    <textarea name="parent_address" rows="2" class="form-input"
                        placeholder="Alamat orang tua (jika berbeda)"><?= old('parent_address', $siswa['parent_address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================
             FOTO
             ============================================================ -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-white text-sm bg-orange-500">
                    <i class="fas fa-camera"></i>
                </span>
                Foto Siswa
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <label class="block text-sm font-bold text-gray-700 mb-2">Preview Foto</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 flex items-center justify-center h-48 bg-gray-50">
                        <?php if ($mode === 'edit' && !empty($siswa['photo'])): ?>
                            <img id="imagePreview" src="<?= base_url('uploads/siswa/' . $siswa['photo']) ?>"
                                class="image-preview show max-h-full max-w-full object-contain rounded-lg" alt="Preview">
                            <div id="previewPlaceholder" style="display:none" class="text-center text-gray-400">
                        <?php else: ?>
                            <img id="imagePreview" class="image-preview max-h-full max-w-full object-contain rounded-lg" alt="Preview">
                            <div id="previewPlaceholder" class="text-center text-gray-400">
                        <?php endif ?>
                                <i class="fas fa-image text-5xl mb-2 block opacity-30"></i>
                                <p class="text-sm">Preview foto akan muncul di sini</p>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
             TOMBOL AKSI
             ============================================================ -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
            <a href="<?= base_url('siswa') ?>"
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold">
                <i class="fas fa-save mr-2"></i><?= $mode === 'add' ? 'Simpan Data' : 'Update Data' ?>
            </button>
        </div>

    </form>
</div>

<?php $this->endSection() ?>


<?php $this->section('scripts') ?>
<script>
    // Preview foto
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const preview     = document.getElementById('imagePreview');
        const placeholder = document.getElementById('previewPlaceholder');
        const reader      = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.add('show');
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    // NISN hanya angka
    document.getElementById('nisn')?.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Auto hide alert
    setTimeout(() => {
        document.querySelectorAll('[role="alert"]').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>
<?php $this->endSection() ?>