<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Guru - EduZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar-collapsed {
            width: 80px;
        }

        .sidebar-expanded {
            width: 280px;
        }

        .menu-item {
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(8px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .info-card {
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Sidebar (sama seperti sebelumnya) -->
    <aside id="sidebar" class="sidebar sidebar-expanded fixed left-0 top-0 h-screen bg-white shadow-2xl z-50 overflow-y-auto">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div class="sidebar-text">
                        <h2 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">EduZone</h2>
                        <p class="text-xs text-gray-500">Kepala Sekolah</p>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        <?= strtoupper(substr($username, 0, 1)) ?>
                    </div>
                    <div class="sidebar-text overflow-hidden">
                        <p class="font-bold text-gray-900 truncate"><?= esc($username) ?></p>
                        <p class="text-xs text-gray-500">Kepala Sekolah</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="<?= base_url('kepsek/dashboard') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
                    <i class="fas fa-home text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Dashboard</span>
                </a>

                <a href="<?= base_url('kepsek/siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-user-graduate text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Data Siswa</span>
                </a>

                <a href="<?= base_url('kepsek/guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-chalkboard-teacher text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Data Guru</span>
                </a>

                <a href="<?= base_url('kepsek/keuangan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-money-bill-wave text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Keuangan</span>
                </a>

                <a href="<?= base_url('kepsek/laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-chart-line text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Laporan Akademik</span>
                </a>

                <a href="<?= base_url('kepsek/users') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-users-cog text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Manajemen User</span>
                </a>

                <a href="<?= base_url('kepsek/password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-key text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Ubah Password</span>
                </a>

                <a href="<?= base_url('kepsek/pengaturan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-cog text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Pengaturan</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-gray-200">
                <a href="<?= base_url('logout') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all">
                    <i class="fas fa-sign-out-alt text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div id="main-content" class="ml-[280px] transition-all duration-300">
        <!-- Top Navbar -->
        <header class="bg-white shadow-md sticky top-0 z-40">
            <div class="flex items-center justify-between px-8 py-4">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <i class="fas fa-bars text-gray-600 text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Guru</h1>
                        <p class="text-sm text-gray-500">Informasi lengkap data guru</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="<?= base_url('kepsek/guru') ?>" class="inline-flex items-center space-x-2 text-gray-600 hover:text-purple-600 transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span class="font-semibold">Kembali ke Data Guru</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-right">
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-6 text-center">
                            <?php if (!empty($guru['foto'])): ?>
                                <img src="<?= base_url('uploads/guru/' . $guru['foto']) ?>" alt="Foto Guru"
                                    class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                            <?php else: ?>
                                <div class="w-32 h-32 bg-white rounded-full mx-auto border-4 border-white shadow-lg flex items-center justify-center">
                                    <span class="text-5xl font-bold text-purple-600">
                                        <?= strtoupper(substr($guru['nama'], 0, 1)) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <h2 class="text-2xl font-bold text-white mt-4"><?= esc($guru['nama']) ?></h2>
                            <p class="text-purple-100 mt-1"><?= esc($guru['jabatan']) ?></p>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Status -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-semibold text-gray-600">Status Kepegawaian</span>
                                <?php
                                $statusColors = [
                                    'PNS' => 'bg-green-100 text-green-800',
                                    'PPPK' => 'bg-blue-100 text-blue-800',
                                    'Honorer' => 'bg-yellow-100 text-yellow-800',
                                    'Kontrak' => 'bg-purple-100 text-purple-800'
                                ];
                                $colorClass = $statusColors[$guru['status_kepegawaian']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $colorClass ?>">
                                    <?= esc($guru['status_kepegawaian']) ?>
                                </span>
                            </div>

                            <!-- NIP -->
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-id-card text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">NIP</p>
                                    <p class="font-semibold text-gray-900"><?= esc($guru['nip']) ?></p>
                                </div>
                            </div>

                            <!-- Pendidikan -->
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Pendidikan Terakhir</p>
                                    <p class="font-semibold text-gray-900"><?= esc($guru['pendidikan_terakhir']) ?></p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <!-- <div class="pt-4 space-y-2">
                                <a href="<?= base_url('kepsek/guru/edit/' . $guru['idguru']) ?>"
                                    class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit Data</span>
                                </a>
                                <form action="<?= base_url('kepsek/guru/delete/' . $guru['idguru']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru <?= esc($guru['nama']) ?>?\n\nData akan dinonaktifkan dari sistem.')">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                        class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-all">
                                        <i class="fas fa-trash"></i>
                                        <span>Hapus Data</span>
                                    </button>
                                </form>
                            </div> -->
                        </div>
                    </div>
                </div>

                <!-- Detailed Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="info-card bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-purple-200 flex items-center">
                            <i class="fas fa-user text-purple-600 mr-3"></i>
                            Data Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Jenis Kelamin</p>
                                <p class="font-semibold text-gray-900">
                                    <?= $guru['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Agama</p>
                                <p class="font-semibold text-gray-900"><?= esc($guru['agama']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tempat Lahir</p>
                                <p class="font-semibold text-gray-900">
                                    <?= !empty($guru['tempat_lahir']) ? esc($guru['tempat_lahir']) : '-' ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Lahir</p>
                                <p class="font-semibold text-gray-900">
                                    <?php if (!empty($guru['tanggal_lahir'])): ?>
                                        <?= date('d F Y', strtotime($guru['tanggal_lahir'])) ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 mb-1">Alamat</p>
                                <p class="font-semibold text-gray-900">
                                    <?= !empty($guru['alamat']) ? esc($guru['alamat']) : '-' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="info-card bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-purple-200 flex items-center">
                            <i class="fas fa-address-book text-purple-600 mr-3"></i>
                            Informasi Kontak
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">No. HP/WA</p>
                                    <p class="font-semibold text-gray-900"><?= esc($guru['no_hp']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-lg">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-900 text-sm break-all">
                                        <?= !empty($guru['email']) ? esc($guru['email']) : '-' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="info-card bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-purple-200 flex items-center">
                            <i class="fas fa-info-circle text-purple-600 mr-3"></i>
                            Informasi Tambahan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Terdaftar Sejak</p>
                                <p class="font-semibold text-gray-900">
                                    <?= date('d F Y, H:i', strtotime($guru['created_at'])) ?> WIB
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Terakhir Diupdate</p>
                                <p class="font-semibold text-gray-900">
                                    <?= date('d F Y, H:i', strtotime($guru['updated_at'])) ?> WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-6 mt-12">
            <div class="px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <p class="text-gray-600 text-sm">© <?= date('Y') ?> EduZone. All rights reserved.</p>
                    <p class="text-gray-500 text-sm">Version 1.0.0</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');

            if (sidebar.classList.contains('sidebar-expanded')) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('ml-[280px]');
                mainContent.classList.add('ml-[80px]');
                sidebarTexts.forEach(text => text.style.display = 'none');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                mainContent.classList.remove('ml-[80px]');
                mainContent.classList.add('ml-[280px]');
                sidebarTexts.forEach(text => text.style.display = 'block');
            }
        }

        function confirmDelete(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus guru ${nama}?`)) {
                window.location.href = `<?= base_url('kepsek/guru/delete/') ?>${id}`;
            }
        }
    </script>
</body>

</html>