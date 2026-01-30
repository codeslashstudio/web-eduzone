<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Keuangan - EduZone TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .submenu.active {
            max-height: 500px;
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
                <a href="<?= base_url('tu/dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-home text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Dashboard</span>
                </a>

                <a href="<?= base_url('tu/siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-user-graduate text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Data Siswa</span>
                </a>

                <a href="<?= base_url('tu/guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-chalkboard-teacher text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Data Guru</span>
                </a>

                <!-- Keuangan Menu with Submenu -->
                <div>
                    <button onclick="toggleSubmenu()" class="menu-item active w-full flex items-center justify-between px-4 py-3 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-money-bill-wave text-xl w-6"></i>
                            <span class="sidebar-text font-semibold">Keuangan</span>
                        </div>
                        <i class="fas fa-chevron-down sidebar-text transition-transform" id="submenuIcon"></i>
                    </button>
                    <div class="submenu active ml-4 mt-2 space-y-1 sidebar-text" id="keuanganSubmenu">
                        <a href="<?= base_url('tu/keuangan') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-white bg-white/20 hover:bg-white/30">
                            <i class="fas fa-chart-pie text-sm w-5"></i>
                            <span class="text-sm">Dashboard</span>
                        </a>
                        <a href="<?= base_url('tu/keuangan/pemasukan') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-arrow-down text-sm w-5"></i>
                            <span class="text-sm">Pemasukan</span>
                        </a>
                        <a href="<?= base_url('tu/keuangan/pengeluaran') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-arrow-up text-sm w-5"></i>
                            <span class="text-sm">Pengeluaran</span>
                        </a>
                        <a href="<?= base_url('tu/keuangan/bos') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-file-invoice-dollar text-sm w-5"></i>
                            <span class="text-sm">BOS/BOP</span>
                        </a>
                        <a href="<?= base_url('tu/keuangan/persetujuan') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-check-circle text-sm w-5"></i>
                            <span class="text-sm">Persetujuan</span>
                        </a>
                        <a href="<?= base_url('tu/keuangan/audit') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-history text-sm w-5"></i>
                            <span class="text-sm">Audit</span>
                        </a>
                    </div>
                </div>

                <a href="<?= base_url('tu/laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-chart-line text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Laporan Akademik</span>
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
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Keuangan</h1>
                        <p class="text-sm text-gray-500">Monitoring Keuangan Sekolah Real-time</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <i class="fas fa-bell text-gray-600 text-xl"></i>
                        <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">5</span>
                    </button>

                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold text-gray-900" id="current-date"></p>
                        <p class="text-xs text-gray-500" id="current-time"></p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-8">
            <!-- Period Filter -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Periode</label>
                            <select id="periodeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="bulan-ini">Bulan Ini</option>
                                <option value="bulan-lalu">Bulan Lalu</option>
                                <option value="triwulan">Triwulan</option>
                                <option value="semester">Semester</option>
                                <option value="tahun">Tahun Ini</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran</label>
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option>2025/2026</option>
                                <option>2024/2025</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-end space-x-3">
                        <button onclick="refreshData()" class="flex items-center space-x-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            <i class="fas fa-sync-alt"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Pemasukan -->
                <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-down text-3xl"></i>
                        </div>
                        <span class="text-xs bg-white/20 px-3 py-1 rounded-full">+12.5%</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">Rp 450.5 Jt</h3>
                    <p class="text-green-100 text-sm font-medium">Total Pemasukan</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <a href="<?= base_url('tu/keuangan/pemasukan') ?>" class="text-sm hover:underline flex items-center">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Total Pengeluaran -->
                <div class="stat-card bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-up text-3xl"></i>
                        </div>
                        <span class="text-xs bg-white/20 px-3 py-1 rounded-full">+8.3%</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">Rp 320.2 Jt</h3>
                    <p class="text-red-100 text-sm font-medium">Total Pengeluaran</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <a href="<?= base_url('tu/keuangan/pengeluaran') ?>" class="text-sm hover:underline flex items-center">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Saldo -->
                <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-3xl"></i>
                        </div>
                        <span class="text-xs bg-green-400 px-3 py-1 rounded-full">Positif</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">Rp 130.3 Jt</h3>
                    <p class="text-blue-100 text-sm font-medium">Saldo Kas</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <p class="text-xs">Pemasukan - Pengeluaran</p>
                    </div>
                </div>

                <!-- Dana BOS -->
                <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-hand-holding-usd text-3xl"></i>
                        </div>
                        <span class="text-xs bg-white/20 px-3 py-1 rounded-full">75%</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">Rp 225 Jt</h3>
                    <p class="text-purple-100 text-sm font-medium">Dana BOS/BOP</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <a href="<?= base_url('tu/keuangan/bos') ?>" class="text-sm hover:underline flex items-center">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Cash Flow Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-chart-line text-green-500 mr-3"></i>
                            Arus Kas Bulanan
                        </h3>
                        <select class="text-sm border rounded px-2 py-1">
                            <option>6 Bulan Terakhir</option>
                            <option>12 Bulan Terakhir</option>
                        </select>
                    </div>
                    <canvas id="cashFlowChart"></canvas>
                </div>

                <!-- Expense Category Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                        Kategori Pengeluaran
                    </h3>
                    <canvas id="expenseCategoryChart"></canvas>
                </div>
            </div>

            <!-- Recent Transactions & Pending Approvals -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Recent Transactions -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-receipt text-teal-500 mr-3"></i>
                            Transaksi Terbaru
                        </h3>
                        <a href="<?= base_url('tu/keuangan/audit') ?>" class="text-sm text-green-600 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="space-y-3">
                        <!-- Transaction Item -->
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-down text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">SPP Januari 2026</p>
                                    <p class="text-xs text-gray-500">30 Jan 2026, 14:30</p>
                                </div>
                            </div>
                            <span class="font-bold text-green-600">+Rp 45.5 Jt</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-up text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Pembelian ATK</p>
                                    <p class="text-xs text-gray-500">29 Jan 2026, 10:15</p>
                                </div>
                            </div>
                            <span class="font-bold text-red-600">-Rp 3.2 Jt</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-down text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Dana BOS Triwulan 1</p>
                                    <p class="text-xs text-gray-500">28 Jan 2026, 09:00</p>
                                </div>
                            </div>
                            <span class="font-bold text-green-600">+Rp 75 Jt</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-up text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Gaji Guru Honorer</p>
                                    <p class="text-xs text-gray-500">25 Jan 2026, 15:45</p>
                                </div>
                            </div>
                            <span class="font-bold text-red-600">-Rp 12 Jt</span>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-clock text-orange-500 mr-3"></i>
                            Menunggu Persetujuan
                        </h3>
                        <a href="<?= base_url('tu/keuangan/persetujuan') ?>" class="text-sm text-green-600 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="space-y-3">
                        <!-- Approval Item -->
                        <div class="p-4 border-l-4 border-orange-500 bg-orange-50 rounded-lg">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">Renovasi Gedung B</p>
                                    <p class="text-xs text-gray-500 mt-1">Diajukan: 29 Jan 2026</p>
                                </div>
                                <span class="px-2 py-1 bg-orange-500 text-white text-xs rounded-full">Pending</span>
                            </div>
                            <p class="text-2xl font-bold text-orange-600 mb-2">Rp 50 Jt</p>
                            <div class="flex space-x-2">
                                <button class="flex-1 px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600">
                                    <i class="fas fa-check mr-1"></i> Setujui
                                </button>
                                <button class="flex-1 px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            </div>
                        </div>

                        <div class="p-4 border-l-4 border-orange-500 bg-orange-50 rounded-lg">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">Pembelian Laptop</p>
                                    <p class="text-xs text-gray-500 mt-1">Diajukan: 28 Jan 2026</p>
                                </div>
                                <span class="px-2 py-1 bg-orange-500 text-white text-xs rounded-full">Pending</span>
                            </div>
                            <p class="text-2xl font-bold text-orange-600 mb-2">Rp 25 Jt</p>
                            <div class="flex space-x-2">
                                <button class="flex-1 px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600">
                                    <i class="fas fa-check mr-1"></i> Setujui
                                </button>
                                <button class="flex-1 px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-3"></i>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <a href="<?= base_url('tu/keuangan/pemasukan/add') ?>" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition group">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                            <i class="fas fa-plus text-white text-xl"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 text-center">Tambah Pemasukan</span>
                    </a>

                    <a href="<?= base_url('tu/keuangan/pengeluaran/add') ?>" class="flex flex-col items-center justify-center p-4 bg-red-50 rounded-xl hover:bg-red-100 transition group">
                        <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                            <i class="fas fa-minus text-white text-xl"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 text-center">Tambah Pengeluaran</span>
                    </a>

                    <a href="<?= base_url('tu/keuangan/bos') ?>" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition group">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                            <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 text-center">Dana BOS</span>
                    </a>

                    <a href="<?= base_url('tu/keuangan/persetujuan') ?>" class="flex flex-col items-center justify-center p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition group">
                        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 text-center">Persetujuan</span>
                    </a>

                    <a href="<?= base_url('tu/keuangan/audit') ?>" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition group">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                            <i class="fas fa-history text-white text-xl"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 text-center">Riwayat</span>
                    </a>

                    <button onclick="cetakLaporan()" class="flex flex-col items-center justify-center p-4 bg-teal-50 rounded-xl hover:bg-teal-100 transition group">
                        <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                            <i class="fas fa-print text-white text-xl"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 text-center">Cetak Laporan</span>
                    </button>
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

        function toggleSubmenu() {
            const submenu = document.getElementById('keuanganSubmenu');
            const icon = document.getElementById('submenuIcon');
            submenu.classList.toggle('active');
            icon.classList.toggle('rotate-180');
        }

        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
            document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Cash Flow Chart
        const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
        new Chart(cashFlowCtx, {
            type: 'line',
            data: {
                labels: ['Agu', 'Sep', 'Okt', 'Nov', 'Des', 'Jan'],
                datasets: [{
                    label: 'Pemasukan',
                    data: [380, 420, 390, 450, 410, 450.5],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Pengeluaran',
                    data: [280, 310, 295, 340, 305, 320.2],
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value + ' Jt';
                            }
                        }
                    }
                }
            }
        });

        // Expense Category Chart
        const expenseCtx = document.getElementById('expenseCategoryChart').getContext('2d');
        new Chart(expenseCtx, {
            type: 'doughnut',
            data: {
                labels: ['Gaji', 'Operasional', 'Pemeliharaan', 'ATK', 'Lainnya'],
                datasets: [{
                    data: [45, 25, 15, 10, 5],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        function refreshData() {
            location.reload();
        }

        function cetakLaporan() {
            window.open('<?= base_url("tu/keuangan/cetak") ?>', '_blank');
        }
    </script>
</body>

</html>