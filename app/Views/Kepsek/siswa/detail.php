<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - EduZone</title>
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

        @media print {
            .no-print {
                display: none !important;
            }

            #sidebar {
                display: none !important;
            }

            #main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar sidebar-expanded fixed left-0 top-0 h-screen bg-white shadow-2xl z-50 overflow-y-auto no-print">
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
                <a href="<?= base_url('kepsek/dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-home text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Dashboard</span>
                </a>

                <a href="<?= base_url('kepsek/siswa') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
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
        <header class="bg-white shadow-md sticky top-0 z-40 no-print">
            <div class="flex items-center justify-between px-8 py-4">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <i class="fas fa-bars text-gray-600 text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900"><?= esc($title) ?></h1>
                        <p class="text-sm text-gray-500">Informasi lengkap data siswa</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button onclick="window.print()" class="flex items-center space-x-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all">
                        <i class="fas fa-print"></i>
                        <span class="font-semibold">Cetak</span>
                    </button>
                    <a href="<?= base_url('kepsek/siswa') ?>" class="flex items-center space-x-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
                        <i class="fas fa-arrow-left"></i>
                        <span class="font-semibold">Kembali</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-8">
            <!-- Student Profile Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6" data-aos="fade-up">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-32"></div>
                <div class="px-8 pb-8">
                    <div class="flex flex-col md:flex-row items-start md:items-end -mt-16 space-y-4 md:space-y-0 md:space-x-6">
                        <!-- Photo -->
                        <div class="flex-shrink-0">
                            <?php if ($siswa['foto']): ?>
                                <img src="<?= base_url('uploads/siswa/' . $siswa['foto']) ?>"
                                    alt="Foto <?= esc($siswa['nama']) ?>"
                                    class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-xl">
                            <?php else: ?>
                                <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-5xl border-4 border-white shadow-xl">
                                    <?= strtoupper(substr($siswa['nama'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2"><?= esc($siswa['nama']) ?></h2>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-id-card mr-2 text-blue-600"></i>
                                    NISN: <strong class="ml-1"><?= esc($siswa['nis']) ?></strong>
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>
                                    <?= esc($siswa['nama_jurusan']) ?>
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-2 text-green-600"></i>
                                    Terdaftar: <?= date('d F Y', strtotime($siswa['created_at'])) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <!-- <div class="flex space-x-2 no-print">
                            <a href="<?= base_url('kepsek/siswa/edit/' . $siswa['idsiswa']) ?>"
                                class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all flex items-center space-x-2">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </a>
                            <button onclick="confirmDelete()"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all flex items-center space-x-2">
                                <i class="fas fa-trash"></i>
                                <span>Hapus</span>
                            </button>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Detail Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-3"></i>
                        Informasi Pribadi
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Nama Lengkap</div>
                            <div class="flex-1">: <?= esc($siswa['nama']) ?></div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">NISN</div>
                            <div class="flex-1">: <?= esc($siswa['nis']) ?></div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Tanggal Lahir</div>
                            <div class="flex-1">: <?= date('d F Y', strtotime($siswa['tanggal_lahir'])) ?></div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Agama</div>
                            <div class="flex-1">: <?= esc($siswa['agama']) ?></div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Alamat</div>
                            <div class="flex-1">: <?= esc($siswa['alamat']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-graduation-cap text-purple-600 mr-3"></i>
                        Informasi Akademik
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Jurusan</div>
                            <div class="flex-1">: <?= esc($siswa['nama_jurusan']) ?></div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Kode Jurusan</div>
                            <div class="flex-1">: <?= esc($siswa['kode_jurusan']) ?></div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Status</div>
                            <div class="flex-1">: <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Aktif</span></div>
                        </div>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6 lg:col-span-2" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-users text-green-600 mr-3"></i>
                        Informasi Orang Tua
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-800 text-lg mb-3">Data Ayah</h4>
                            <div class="flex items-start">
                                <div class="w-32 font-semibold text-gray-700">Nama Ayah</div>
                                <div class="flex-1">: <?= esc($siswa['nama_ayah']) ?></div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-800 text-lg mb-3">Data Ibu</h4>
                            <div class="flex items-start">
                                <div class="w-32 font-semibold text-gray-700">Nama Ibu</div>
                                <div class="flex-1">: <?= esc($siswa['nama_ibu']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6 lg:col-span-2" data-aos="fade-up" data-aos-delay="400">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-orange-600 mr-3"></i>
                        Informasi Sistem
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Tanggal Dibuat</div>
                            <div class="flex-1">: <?= date('d F Y H:i:s', strtotime($siswa['created_at'])) ?></div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-40 font-semibold text-gray-700">Terakhir Diupdate</div>
                            <div class="flex-1">: <?= date('d F Y H:i:s', strtotime($siswa['updated_at'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-6 mt-12 no-print">
            <div class="px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <p class="text-gray-600 text-sm">© <?= date('Y') ?> EduZone. All rights reserved.</p>
                    <p class="text-gray-500 text-sm">Version 1.0.0</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center no-print">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data siswa <strong><?= esc($siswa['nama']) ?></strong>? Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="flex space-x-4">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all font-semibold">
                        Batal
                    </button>
                    <form action="<?= base_url('kepsek/siswa/delete/' . $siswa['idsiswa']) ?>" method="POST" class="flex-1">
                        <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all font-semibold">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Toggle Sidebar
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

        // Delete confirmation
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</body>

</html>