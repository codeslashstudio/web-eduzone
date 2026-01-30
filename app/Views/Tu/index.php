<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tata Usaha - EduZone</title>
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
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
            transform: translateX(8px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar sidebar-expanded fixed left-0 top-0 h-screen bg-white shadow-2xl z-50 overflow-y-auto">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-600 to-teal-600 rounded-xl">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div class="sidebar-text">
                        <h2 class="text-xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">EduZone</h2>
                        <p class="text-xs text-gray-500">Tata Usaha</p>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-500 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        <?= strtoupper(substr($username, 0, 1)) ?>
                    </div>
                    <div class="sidebar-text overflow-hidden">
                        <p class="font-bold text-gray-900 truncate"><?= esc($username) ?></p>
                        <p class="text-xs text-gray-500">Tata Usaha</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="<?= base_url('tu/dashboard') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-home text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Dashboard</span>
                </a>

                <a href="<?= base_url('tu/siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
                    <i class="fas fa-user-graduate text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Data Siswa</span>
                </a>

                <a href="<?= base_url('tu/guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-chalkboard-teacher text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Data Guru</span>
                </a>

                <a href="<?= base_url('tu/keuangan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-money-bill-wave text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Keuangan</span>
                </a>

                <a href="<?= base_url('tu/laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-chart-line text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Laporan Akademik</span>
                </a>

                <a href="<?= base_url('tu/users') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-users-cog text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Manajemen User</span>
                </a>

                <a href="<?= base_url('tu/password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-key text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Ubah Password</span>
                </a>

                <a href="<?= base_url('tu/pengaturan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
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
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Tata Usaha</h1>
                        <p class="text-sm text-gray-500">Selamat datang kembali, <?= esc($username) ?>!</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <i class="fas fa-bell text-gray-600 text-xl"></i>
                        <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>

                    <!-- Current Date & Time -->
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold text-gray-900" id="current-date"></p>
                        <p class="text-xs text-gray-500" id="current-time"></p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Siswa -->
                <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-graduate text-3xl"></i>
                        </div>
                        <span class="text-sm bg-white/20 px-3 py-1 rounded-full">+12%</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">1,234</h3>
                    <p class="text-green-100 text-sm font-medium">Total Siswa</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <a href="<?= base_url('tu/siswa') ?>" class="text-sm hover:underline flex items-center">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Total Guru -->
                <div class="stat-card bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-3xl"></i>
                        </div>
                        <span class="text-sm bg-white/20 px-3 py-1 rounded-full">+5%</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">87</h3>
                    <p class="text-teal-100 text-sm font-medium">Total Guru</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <a href="<?= base_url('tu/guru') ?>" class="text-sm hover:underline flex items-center">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Surat Masuk -->
                <div class="stat-card bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope text-3xl"></i>
                        </div>
                        <span class="text-sm bg-white/20 px-3 py-1 rounded-full">Baru</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">25</h3>
                    <p class="text-cyan-100 text-sm font-medium">Surat Masuk</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <a href="<?= base_url('tu/surat') ?>" class="text-sm hover:underline flex items-center">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Dokumen -->
                <div class="stat-card bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-alt text-3xl"></i>
                        </div>
                        <span class="text-sm bg-white/20 px-3 py-1 rounded-full">+18</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">342</h3>
                    <p class="text-emerald-100 text-sm font-medium">Total Dokumen</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <a href="<?= base_url('tu/administrasi') ?>" class="text-sm hover:underline flex items-center">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Quick Actions & Recent Activity -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bolt text-yellow-500 mr-3"></i>
                            Quick Actions
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="<?= base_url('tu/siswa/add') ?>" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-all group">
                                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-plus text-white text-xl"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 text-center">Tambah Siswa</span>
                            </a>

                            <a href="<?= base_url('tu/guru/add') ?>" class="flex flex-col items-center justify-center p-4 bg-teal-50 rounded-xl hover:bg-teal-100 transition-all group">
                                <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-tie text-white text-xl"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 text-center">Tambah Guru</span>
                            </a>

                            <a href="<?= base_url('tu/surat/create') ?>" class="flex flex-col items-center justify-center p-4 bg-cyan-50 rounded-xl hover:bg-cyan-100 transition-all group">
                                <div class="w-12 h-12 bg-cyan-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-envelope-open-text text-white text-xl"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 text-center">Buat Surat</span>
                            </a>

                            <a href="<?= base_url('tu/laporan') ?>" class="flex flex-col items-center justify-center p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-all group">
                                <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-chart-bar text-white text-xl"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 text-center">Laporan</span>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-history text-green-500 mr-3"></i>
                            Aktivitas Terkini
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user-plus text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">Siswa Baru Terdaftar</p>
                                    <p class="text-sm text-gray-600">Ahmad Fauzi berhasil terdaftar di kelas X IPA 1</p>
                                    <p class="text-xs text-gray-400 mt-1">2 jam yang lalu</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all">
                                <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">Surat Masuk Baru</p>
                                    <p class="text-sm text-gray-600">Surat undangan rapat dari Dinas Pendidikan</p>
                                    <p class="text-xs text-gray-400 mt-1">4 jam yang lalu</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all">
                                <div class="w-10 h-10 bg-cyan-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">Dokumen Diupload</p>
                                    <p class="text-sm text-gray-600">Laporan kehadiran siswa bulan Januari telah diupload</p>
                                    <p class="text-xs text-gray-400 mt-1">1 hari yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Tasks & Calendar -->
                <div class="space-y-8">
                    <!-- Pending Tasks -->
                    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-tasks text-teal-500 mr-3"></i>
                            Tugas Pending
                        </h2>
                        <div class="space-y-4">
                            <div class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border-l-4 border-green-500">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-bold text-gray-900">Verifikasi Data Siswa</h3>
                                    <span class="text-xs bg-green-500 text-white px-2 py-1 rounded-full">Urgent</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">15 data siswa baru perlu diverifikasi</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>Deadline: Hari ini</span>
                                </div>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl border-l-4 border-teal-500">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-bold text-gray-900">Update Absensi</h3>
                                    <span class="text-xs bg-teal-500 text-white px-2 py-1 rounded-full">Normal</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Input absensi minggu ini</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>Deadline: 2 hari lagi</span>
                                </div>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-xl border-l-4 border-cyan-500">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-bold text-gray-900">Arsip Dokumen</h3>
                                    <span class="text-xs bg-cyan-500 text-white px-2 py-1 rounded-full">Low</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Mengarsipkan dokumen semester lalu</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>Deadline: Minggu depan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-br from-green-600 to-teal-600 rounded-2xl shadow-lg p-6 text-white" data-aos="fade-up" data-aos-delay="300">
                        <h2 class="text-xl font-bold mb-4 flex items-center">
                            <i class="fas fa-chart-pie mr-3"></i>
                            Statistik Cepat
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                                <span class="text-sm">Kehadiran Hari Ini</span>
                                <span class="font-bold text-lg">94.5%</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                                <span class="text-sm">Dokumen Bulan Ini</span>
                                <span class="font-bold text-lg">156</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur">
                                <span class="text-sm">Surat Terproses</span>
                                <span class="font-bold text-lg">89%</span>
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
                    <p class="text-gray-500 text-sm">Version 1.0.0 - Tata Usaha</p>
                </div>
            </div>
        </footer>
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

        // Update Date and Time
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = now.toLocaleTimeString('id-ID');

            document.getElementById('current-date').textContent = dateStr;
            document.getElementById('current-time').textContent = timeStr;
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>

</html>