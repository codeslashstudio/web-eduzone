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
    @media print {
        #sidebar, .no-print { display: none !important; }
        #main-content { margin-left: 0 !important; }
    }
</style>

<!-- Breadcrumb + Action Buttons -->
<div class="flex items-center justify-between mb-6 no-print">
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="<?= base_url('siswa') ?>" class="hover:text-accent transition-colors">Data Siswa</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="font-semibold" style="color: var(--color-primary)">Detail Siswa</span>
    </div>
    <div class="flex items-center space-x-3">
        <button onclick="window.print()"
            class="flex items-center space-x-2 px-4 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all font-semibold text-sm">
            <i class="fas fa-print"></i>
            <span>Cetak</span>
        </button>
        <?php if ($canEdit): ?>
        <a href="<?= base_url('siswa/edit/' . $siswa['id']) ?>"
            class="flex items-center space-x-2 px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-all font-semibold text-sm">
            <i class="fas fa-edit"></i>
            <span>Edit</span>
        </a>
        <button onclick="document.getElementById('deleteModal').classList.remove('hidden')"
            class="flex items-center space-x-2 px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all font-semibold text-sm">
            <i class="fas fa-trash"></i>
            <span>Hapus</span>
        </button>
        <?php endif ?>
        <a href="<?= base_url('siswa') ?>"
            class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold text-sm">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<!-- Profile Card -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6" data-aos="fade-up">
    <!-- Banner pakai CSS variable -->
    <div class="h-32" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))"></div>
    <div class="px-8 pb-8">
        <div class="flex flex-col md:flex-row items-start md:items-end -mt-16 space-y-4 md:space-y-0 md:space-x-6">
            <!-- Foto -->
            <div class="flex-shrink-0">
                <?php if (!empty($siswa['photo'])): ?>
                    <img src="<?= base_url('uploads/siswa/' . $siswa['photo']) ?>"
                        alt="<?= esc($siswa['full_name']) ?>"
                        class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-xl">
                <?php else: ?>
                    <div class="w-32 h-32 rounded-2xl flex items-center justify-center text-white font-bold text-5xl border-4 border-white shadow-xl"
                         style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
                        <?= strtoupper(substr($siswa['full_name'], 0, 1)) ?>
                    </div>
                <?php endif ?>
            </div>

            <!-- Info -->
            <div class="flex-1 pt-4">
                <h2 class="text-3xl font-bold text-gray-900 mb-2"><?= esc($siswa['full_name']) ?></h2>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center">
                        <i class="fas fa-id-card mr-2" style="color: var(--color-primary)"></i>
                        NISN: <strong class="ml-1"><?= esc($siswa['nisn'] ?? '-') ?></strong>
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-id-badge mr-2 text-emerald-600"></i>
                        NIS: <strong class="ml-1"><?= esc($siswa['nis'] ?? '-') ?></strong>
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>
                        Kelas <?= esc($siswa['grade']) ?>
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        <?= ($siswa['status'] ?? '') === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                        <?= esc(ucfirst($siswa['status'] ?? '-')) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Informasi Pribadi -->
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
        <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-white text-sm" style="background: var(--color-primary)">
                <i class="fas fa-user"></i>
            </span>
            Informasi Pribadi
        </h3>
        <div class="space-y-3">
            <?php
            $pribadi = [
                'Nama Lengkap'  => $siswa['full_name'],
                'NIS'           => $siswa['nis'] ?? '-',
                'NISN'          => $siswa['nisn'] ?? '-',
                'Jenis Kelamin' => ($siswa['gender'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan',
                'Tempat Lahir'  => $siswa['birth_place'] ?? '-',
                'Tanggal Lahir' => !empty($siswa['birth_date']) ? date('d F Y', strtotime($siswa['birth_date'])) : '-',
                'Agama'         => $siswa['religion'] ?? '-',
                'No. HP'        => $siswa['phone'] ?? '-',
                'Alamat'        => $siswa['address'] ?? '-',
            ];
            foreach ($pribadi as $label => $value): ?>
            <div class="flex items-start text-sm">
                <div class="w-36 font-semibold text-gray-500 flex-shrink-0"><?= $label ?></div>
                <div class="flex-1 text-gray-900">: <?= esc($value) ?></div>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- Informasi Akademik -->
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
        <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-white text-sm" style="background: var(--color-secondary)">
                <i class="fas fa-graduation-cap"></i>
            </span>
            Informasi Akademik
        </h3>
        <div class="space-y-3">
            <?php
            $akademik = [
                'Kelas'             => $siswa['grade'] ?? '-',
                'Jurusan'           => $siswa['major_name'] ?? '-',
                'Rombongan Belajar' => $siswa['class_group'] ?? '-',
                'Tanggal Masuk'     => !empty($siswa['joined_date']) ? date('d F Y', strtotime($siswa['joined_date'])) : '-',
                'Status'            => ucfirst($siswa['status'] ?? '-'),
            ];
            foreach ($akademik as $label => $value): ?>
            <div class="flex items-start text-sm">
                <div class="w-36 font-semibold text-gray-500 flex-shrink-0"><?= $label ?></div>
                <div class="flex-1 text-gray-900">: <?= esc($value) ?></div>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- Informasi Orang Tua -->
    <div class="bg-white rounded-2xl shadow-lg p-6 lg:col-span-2" data-aos="fade-up" data-aos-delay="300">
        <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-white text-sm bg-emerald-500">
                <i class="fas fa-users"></i>
            </span>
            Informasi Orang Tua
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <h4 class="font-bold text-gray-700 text-sm border-b pb-2">Data Ayah</h4>
                <div class="flex text-sm"><div class="w-32 font-semibold text-gray-500">Nama</div><div class="text-gray-900">: <?= esc($siswa['father_name'] ?? '-') ?></div></div>
                <div class="flex text-sm"><div class="w-32 font-semibold text-gray-500">Pekerjaan</div><div class="text-gray-900">: <?= esc($siswa['father_job'] ?? '-') ?></div></div>
            </div>
            <div class="space-y-3">
                <h4 class="font-bold text-gray-700 text-sm border-b pb-2">Data Ibu</h4>
                <div class="flex text-sm"><div class="w-32 font-semibold text-gray-500">Nama</div><div class="text-gray-900">: <?= esc($siswa['mother_name'] ?? '-') ?></div></div>
                <div class="flex text-sm"><div class="w-32 font-semibold text-gray-500">Pekerjaan</div><div class="text-gray-900">: <?= esc($siswa['mother_job'] ?? '-') ?></div></div>
                <div class="flex text-sm"><div class="w-32 font-semibold text-gray-500">No. HP</div><div class="text-gray-900">: <?= esc($siswa['parent_phone'] ?? '-') ?></div></div>
            </div>
        </div>
    </div>

    <!-- Informasi Sistem -->
    <div class="bg-white rounded-2xl shadow-lg p-6 lg:col-span-2" data-aos="fade-up" data-aos-delay="400">
        <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-white text-sm bg-orange-500">
                <i class="fas fa-info-circle"></i>
            </span>
            Informasi Sistem
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="flex"><div class="w-40 font-semibold text-gray-500">Tanggal Dibuat</div><div class="text-gray-900">: <?= date('d F Y H:i', strtotime($siswa['created_at'])) ?></div></div>
            <div class="flex"><div class="w-40 font-semibold text-gray-500">Terakhir Diupdate</div><div class="text-gray-900">: <?= date('d F Y H:i', strtotime($siswa['updated_at'])) ?></div></div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<?php if ($canEdit): ?>
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center no-print">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus data siswa
                <strong><?= esc($siswa['full_name']) ?></strong>?
                Data tidak dapat dikembalikan.
            </p>
            <div class="flex space-x-4">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                    Batal
                </button>
                <form action="<?= base_url('siswa/delete/' . $siswa['id']) ?>" method="POST" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 font-semibold transition-all">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php $this->endSection() ?>