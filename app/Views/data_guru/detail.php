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
    @media print {
        #sidebar, .no-print { display: none !important; }
        #main-content { margin-left: 0 !important; }
    }
    .info-card { transition: all 0.3s ease; }
    .info-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
</style>

<!-- Breadcrumb + Action Buttons -->
<div class="flex items-center justify-between mb-6 no-print">
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="<?= base_url('guru') ?>" class="hover:text-accent transition-colors">Data Guru</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="font-semibold" style="color: var(--color-primary)">Detail Guru</span>
    </div>
    <div class="flex items-center space-x-3">
        <button onclick="window.print()"
            class="flex items-center space-x-2 px-4 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all font-semibold text-sm">
            <i class="fas fa-print"></i><span>Cetak</span>
        </button>
        <?php if ($canEdit): ?>
        <a href="<?= base_url('guru/edit/' . $guru['id']) ?>"
            class="flex items-center space-x-2 px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-all font-semibold text-sm">
            <i class="fas fa-edit"></i><span>Edit</span>
        </a>
        <button onclick="document.getElementById('deleteModal').classList.remove('hidden')"
            class="flex items-center space-x-2 px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all font-semibold text-sm">
            <i class="fas fa-trash"></i><span>Hapus</span>
        </button>
        <?php endif ?>
        <a href="<?= base_url('guru') ?>"
            class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold text-sm">
            <i class="fas fa-arrow-left"></i><span>Kembali</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Profile Card (Left) -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-right">
            <!-- Banner -->
            <div class="p-6 text-center" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
                <?php if (!empty($guru['photo'])): ?>
                    <img src="<?= base_url('uploads/guru/' . $guru['photo']) ?>"
                        alt="<?= esc($guru['full_name']) ?>"
                        class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                <?php else: ?>
                    <div class="w-32 h-32 bg-white rounded-full mx-auto border-4 border-white shadow-lg flex items-center justify-center">
                        <span class="text-5xl font-bold" style="color: var(--color-primary)">
                            <?= strtoupper(substr($guru['full_name'], 0, 1)) ?>
                        </span>
                    </div>
                <?php endif ?>
                <h2 class="text-xl font-bold text-white mt-4"><?= esc($guru['full_name']) ?></h2>
                <p class="text-white/70 mt-1 text-sm"><?= esc($guru['education_major'] ?? '-') ?></p>
            </div>

            <!-- Info Singkat -->
            <div class="p-5 space-y-3">
                <?php
                $statusColors = [
                    'PNS'     => 'bg-green-100 text-green-800',
                    'PPPK'    => 'bg-blue-100 text-blue-800',
                    'Honorer' => 'bg-yellow-100 text-yellow-800',
                    'GTY'     => 'bg-purple-100 text-purple-800',
                    'GTT'     => 'bg-orange-100 text-orange-800',
                ];
                $statusClass = $statusColors[$guru['employment_status'] ?? ''] ?? 'bg-gray-100 text-gray-800';
                ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="text-sm font-semibold text-gray-500">Status</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                        <?= esc($guru['employment_status'] ?? '-') ?>
                    </span>
                </div>

                <?php
                $infoItems = [
                    ['icon' => 'fa-id-card',       'color' => 'var(--color-primary)', 'label' => 'NIP',        'value' => $guru['nip'] ?? '-'],
                    ['icon' => 'fa-id-badge',       'color' => '#6366f1',             'label' => 'NUPTK',      'value' => $guru['nuptk'] ?? '-'],
                    ['icon' => 'fa-graduation-cap', 'color' => '#3b82f6',             'label' => 'Pendidikan', 'value' => $guru['last_education'] ?? '-'],
                ];
                foreach ($infoItems as $item): ?>
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background: <?= $item['color'] ?>20">
                        <i class="fas <?= $item['icon'] ?> text-sm" style="color: <?= $item['color'] ?>"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400"><?= $item['label'] ?></p>
                        <p class="font-semibold text-gray-900 text-sm"><?= esc($item['value']) ?></p>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <!-- Detail (Right) -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Data Pribadi -->
        <div class="info-card bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs" style="background: var(--color-primary)">
                    <i class="fas fa-user"></i>
                </span>
                Data Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php
                $pribadi = [
                    'Jenis Kelamin'        => ($guru['gender'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan',
                    'Agama'                => $guru['religion'] ?? '-',
                    'Tempat Lahir'         => $guru['birth_place'] ?? '-',
                    'Tanggal Lahir'        => !empty($guru['birth_date']) ? date('d F Y', strtotime($guru['birth_date'])) : '-',
                    'Tgl Mulai Mengajar'   => !empty($guru['joined_date']) ? date('d F Y', strtotime($guru['joined_date'])) : '-',
                    'Jurusan/Prodi'        => $guru['education_major'] ?? '-',
                ];
                foreach ($pribadi as $label => $value): ?>
                <div>
                    <p class="text-xs text-gray-400 mb-1"><?= $label ?></p>
                    <p class="font-semibold text-gray-900 text-sm"><?= esc($value) ?></p>
                </div>
                <?php endforeach ?>
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-400 mb-1">Alamat</p>
                    <p class="font-semibold text-gray-900 text-sm"><?= esc($guru['address'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <!-- Kontak -->
        <div class="info-card bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs" style="background: var(--color-secondary)">
                    <i class="fas fa-address-book"></i>
                </span>
                Informasi Kontak
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3 p-4 bg-emerald-50 rounded-xl">
                    <div class="w-11 h-11 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-phone text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">No. HP/WA</p>
                        <p class="font-semibold text-gray-900 text-sm"><?= esc($guru['phone'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-xl">
                    <div class="w-11 h-11 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Email</p>
                        <p class="font-semibold text-gray-900 text-sm break-all"><?= esc($guru['email'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Sistem -->
        <div class="info-card bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-orange-500">
                    <i class="fas fa-info-circle"></i>
                </span>
                Informasi Sistem
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Terdaftar Sejak</p>
                    <p class="font-semibold text-gray-900">
                        <?= !empty($guru['created_at']) ? date('d F Y, H:i', strtotime($guru['created_at'])) . ' WIB' : '-' ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Terakhir Diupdate</p>
                    <p class="font-semibold text-gray-900">
                        <?= !empty($guru['updated_at']) ? date('d F Y, H:i', strtotime($guru['updated_at'])) . ' WIB' : '-' ?>
                    </p>
                </div>
            </div>
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
            <p class="text-gray-600 mb-2">Apakah Anda yakin ingin menghapus data guru</p>
            <p class="font-bold text-gray-900 mb-6"><?= esc($guru['full_name']) ?></p>
            <div class="flex space-x-4">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                    Batal
                </button>
                <form action="<?= base_url('guru/delete/' . $guru['id']) ?>" method="POST" class="flex-1">
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