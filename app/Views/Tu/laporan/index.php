<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akademik - EduZone TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: #f3f4f6;
            transform: scale(1.01);
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


                <a href="<?= base_url('tu/keuangan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-money-bill-wave text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Keuangan</span>
                </a>

                <a href="<?= base_url('tu/laporan') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
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
                        <h1 class="text-2xl font-bold text-gray-900">Laporan Akademik</h1>
                        <p class="text-sm text-gray-500">Monitoring Prestasi Akademik Per Kelas</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <i class="fas fa-bell text-gray-600 text-xl"></i>
                        <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
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
            <!-- Filter & Export Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran</label>
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option>2025/2026</option>
                                <option>2024/2025</option>
                                <option>2023/2024</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option>Ganjil</option>
                                <option>Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-end space-x-3">
                        <button onclick="exportToExcel()" class="flex items-center space-x-2 px-6 py-2 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-file-excel"></i>
                            <span class="font-semibold">Export Excel</span>
                        </button>
                        <button onclick="printReport()" class="flex items-center space-x-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                            <i class="fas fa-print"></i>
                            <span class="font-semibold">Print</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-school text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">12</h3>
                    <p class="text-green-100 text-sm font-medium">Total Kelas</p>
                </div>

                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">82.5</h3>
                    <p class="text-teal-100 text-sm font-medium">Rata-rata Keseluruhan</p>
                </div>

                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-trophy text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">95.8</h3>
                    <p class="text-cyan-100 text-sm font-medium">Nilai Tertinggi</p>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">1,234</h3>
                    <p class="text-emerald-100 text-sm font-medium">Total Siswa</p>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8" data-aos="fade-up">
                <!-- Table Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Laporan Per Kelas</h2>
                            <p class="text-sm text-gray-500 mt-1">Data prestasi akademik per kelas</p>
                        </div>
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Cari kelas..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full table-hover" id="laporanTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jurusan</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Siswa</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Rata-rata Nilai</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Nilai Tertinggi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Nilai Terendah</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                            <?php
                            // Sample data - replace with actual data from database
                            $laporan = [
                                ['kelas' => 'X', 'jurusan' => 'IPA 1', 'jumlah_siswa' => 36, 'rata_rata' => 85.5, 'tertinggi' => 95.8, 'terendah' => 72.3],
                                ['kelas' => 'X', 'jurusan' => 'IPA 2', 'jumlah_siswa' => 34, 'rata_rata' => 83.2, 'tertinggi' => 93.5, 'terendah' => 70.5],
                                ['kelas' => 'X', 'jurusan' => 'IPS 1', 'jumlah_siswa' => 35, 'rata_rata' => 81.8, 'tertinggi' => 91.2, 'terendah' => 68.9],
                                ['kelas' => 'X', 'jurusan' => 'IPS 2', 'jumlah_siswa' => 33, 'rata_rata' => 80.5, 'tertinggi' => 89.7, 'terendah' => 67.3],
                                ['kelas' => 'XI', 'jurusan' => 'IPA 1', 'jumlah_siswa' => 32, 'rata_rata' => 84.3, 'tertinggi' => 94.2, 'terendah' => 71.8],
                                ['kelas' => 'XI', 'jurusan' => 'IPA 2', 'jumlah_siswa' => 31, 'rata_rata' => 82.7, 'tertinggi' => 92.8, 'terendah' => 69.5],
                                ['kelas' => 'XI', 'jurusan' => 'IPS 1', 'jumlah_siswa' => 34, 'rata_rata' => 79.9, 'tertinggi' => 88.5, 'terendah' => 66.7],
                                ['kelas' => 'XI', 'jurusan' => 'IPS 2', 'jumlah_siswa' => 30, 'rata_rata' => 78.6, 'tertinggi' => 87.3, 'terendah' => 65.2],
                                ['kelas' => 'XII', 'jurusan' => 'IPA 1', 'jumlah_siswa' => 35, 'rata_rata' => 86.2, 'tertinggi' => 95.5, 'terendah' => 73.5],
                                ['kelas' => 'XII', 'jurusan' => 'IPA 2', 'jumlah_siswa' => 33, 'rata_rata' => 84.8, 'tertinggi' => 93.8, 'terendah' => 72.1],
                                ['kelas' => 'XII', 'jurusan' => 'IPS 1', 'jumlah_siswa' => 32, 'rata_rata' => 82.4, 'tertinggi' => 90.6, 'terendah' => 70.3],
                                ['kelas' => 'XII', 'jurusan' => 'IPS 2', 'jumlah_siswa' => 29, 'rata_rata' => 81.1, 'tertinggi' => 89.2, 'terendah' => 68.8],
                            ];

                            foreach ($laporan as $index => $l):
                                // Determine status based on average
                                if ($l['rata_rata'] >= 85) {
                                    $status = 'Sangat Baik';
                                    $statusColor = 'bg-green-100 text-green-800';
                                } elseif ($l['rata_rata'] >= 75) {
                                    $status = 'Baik';
                                    $statusColor = 'bg-blue-100 text-blue-800';
                                } elseif ($l['rata_rata'] >= 65) {
                                    $status = 'Cukup';
                                    $statusColor = 'bg-yellow-100 text-yellow-800';
                                } else {
                                    $status = 'Perlu Perbaikan';
                                    $statusColor = 'bg-red-100 text-red-800';
                                }
                            ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-900"><?= $l['kelas'] ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900"><?= $l['jurusan'] ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 text-sm font-semibold text-gray-900 bg-gray-100 rounded-full">
                                            <?= $l['jumlah_siswa'] ?> Siswa
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-bold text-teal-600"><?= number_format($l['rata_rata'], 1) ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-bold text-green-600"><?= number_format($l['tertinggi'], 1) ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-bold text-orange-600"><?= number_format($l['terendah'], 1) ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusColor ?>">
                                            <?= $status ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="<?= base_url('tu/laporan/detail/' . $l['kelas'] . '-' . str_replace(' ', '-', $l['jurusan'])) ?>"
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span class="text-xs font-semibold">Detail</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Bar Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-green-500 mr-3"></i>
                        Perbandingan Rata-rata Nilai Per Kelas
                    </h3>
                    <canvas id="barChart"></canvas>
                </div>

                <!-- Line Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-line text-teal-500 mr-3"></i>
                        Trend Nilai Tertinggi & Terendah
                    </h3>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-cyan-500 mr-3"></i>
                    Distribusi Status Kelas
                </h3>
                <div class="max-w-md mx-auto">
                    <canvas id="pieChart"></canvas>
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

        // Search Function
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#tableBody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });

        // Chart Data
        const labels = ['X IPA 1', 'X IPA 2', 'X IPS 1', 'X IPS 2', 'XI IPA 1', 'XI IPA 2', 'XI IPS 1', 'XI IPS 2', 'XII IPA 1', 'XII IPA 2', 'XII IPS 1', 'XII IPS 2'];
        const rataRata = [85.5, 83.2, 81.8, 80.5, 84.3, 82.7, 79.9, 78.6, 86.2, 84.8, 82.4, 81.1];
        const tertinggi = [95.8, 93.5, 91.2, 89.7, 94.2, 92.8, 88.5, 87.3, 95.5, 93.8, 90.6, 89.2];
        const terendah = [72.3, 70.5, 68.9, 67.3, 71.8, 69.5, 66.7, 65.2, 73.5, 72.1, 70.3, 68.8];

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: rataRata,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Nilai Tertinggi',
                        data: tertinggi,
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Nilai Terendah',
                        data: terendah,
                        borderColor: 'rgba(251, 146, 60, 1)',
                        backgroundColor: 'rgba(251, 146, 60, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Pie Chart - Status Distribution
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Sangat Baik', 'Baik', 'Cukup', 'Perlu Perbaikan'],
                datasets: [{
                    data: [4, 6, 2, 0],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });

        // Export to Excel
        function exportToExcel() {
            const table = document.getElementById('laporanTable');
            const wb = XLSX.utils.table_to_book(table, {
                sheet: "Laporan Akademik"
            });
            XLSX.writeFile(wb, 'Laporan_Akademik_' + new Date().toISOString().slice(0, 10) + '.xlsx');
        }

        // Print Report
        function printReport() {
            window.print();
        }
    </script>

    <!-- Print Styles -->
    <style media="print">
        .sidebar,
        header,
        footer,
        button {
            display: none !important;
        }

        #main-content {
            margin-left: 0 !important;
        }
    </style>
</body>

</html>