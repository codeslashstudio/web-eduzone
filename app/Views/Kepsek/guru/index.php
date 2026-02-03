<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru - EduZone</title>
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

                <a href="<?= base_url('kepsek/siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700">
                    <i class="fas fa-user-graduate text-xl w-6"></i>
                    <span class="sidebar-text font-semibold">Data Siswa</span>
                </a>

                <a href="<?= base_url('kepsek/guru') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
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
                        <h1 class="text-2xl font-bold text-gray-900">Data Guru</h1>
                        <p class="text-sm text-gray-500">Manajemen Data Guru Sekolah</p>
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
            <!-- Success/Error Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg" data-aos="fade-down">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <p class="text-green-700 font-semibold"><?= session()->getFlashdata('success') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg" data-aos="fade-down">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                        <p class="text-red-700 font-semibold"><?= session()->getFlashdata('error') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1"><?= count($guru) ?></h3>
                    <p class="text-purple-100 text-sm font-medium">Total Guru</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-check text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">
                        <?= count(array_filter($guru, function ($g) {
                            return $g['status_kepegawaian'] === 'PNS';
                        })) ?>
                    </h3>
                    <p class="text-blue-100 text-sm font-medium">Guru PNS</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-certificate text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">
                        <?= count(array_filter($guru, function ($g) {
                            return $g['status_kepegawaian'] === 'PPPK';
                        })) ?>
                    </h3>
                    <p class="text-green-100 text-sm font-medium">Guru PPPK</p>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-tie text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">
                        <?= count(array_filter($guru, function ($g) {
                            return $g['status_kepegawaian'] === 'Honorer';
                        })) ?>
                    </h3>
                    <p class="text-orange-100 text-sm font-medium">Guru Honorer</p>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
                <!-- Table Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Daftar Guru</h2>
                            <p class="text-sm text-gray-500 mt-1">Kelola data guru sekolah</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Search -->
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="Cari guru..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <!-- Add Button -->
                            <!-- <a href="<?= base_url('kepsek/guru/add') ?>" 
                                class="flex items-center space-x-2 px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all">
                                <i class="fas fa-plus"></i>
                                <span class="font-semibold">Tambah Guru</span>
                            </a> -->
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full table-hover">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Foto</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIP</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">JK</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jabatan</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontak</th>
                                <!-- <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                            <?php if (empty($guru)): ?>
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                                        <p class="text-gray-500 font-semibold">Belum ada data guru</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($guru as $index => $g): ?>
                                    <tr
                                        class="hover:bg-blue-50 transition-colors cursor-pointer"
                                        data-url="<?= base_url('kepsek/guru/detail/' . $g['idguru']) ?>">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if (!empty($g['foto'])): ?>
                                                <img
                                                    src="<?= base_url('uploads/guru/' . $g['foto']) ?>"
                                                    alt="<?= esc($g['nama']) ?>"
                                                    class="w-10 h-10 rounded-full object-cover">    
                                            <?php else: ?>
                                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold text-sm">
                                                    <?= strtoupper(substr($g['nama'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-gray-900"><?= esc($g['nip']) ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900"><?= esc($g['nama']) ?></div>
                                            <div class="text-xs text-gray-500"><?= esc($g['email']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $g['jenis_kelamin'] == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' ?>">
                                                <?= $g['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= esc($g['jabatan']) ?></div>
                                            <div class="text-xs text-gray-500"><?= esc($g['pendidikan_terakhir']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $statusColors = [
                                                'PNS' => 'bg-green-100 text-green-800',
                                                'PPPK' => 'bg-blue-100 text-blue-800',
                                                'Honorer' => 'bg-yellow-100 text-yellow-800',
                                                'Kontrak' => 'bg-purple-100 text-purple-800'
                                            ];
                                            $colorClass = $statusColors[$g['status_kepegawaian']] ?? 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $colorClass ?>">
                                                <?= esc($g['status_kepegawaian']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center space-x-1">
                                                <i class="fas fa-phone text-gray-400 text-xs"></i>
                                                <span><?= esc($g['no_hp']) ?></span>
                                            </div>
                                        </td>
                                        <!-- <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="<?= base_url('kepsek/guru/detail/' . $g['idguru']) ?>"
                                                    class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all"
                                                    title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('kepsek/guru/edit/' . $g['idguru']) ?>" 
                                                    class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-all" 
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmDelete(<?= $g['idguru'] ?>, '<?= esc($g['nama']) ?>')" 
                                                    class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all" 
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold"><?= count($guru) ?></span> data guru
                        </p>
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

        // Confirm Delete
        function confirmDelete(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus guru ${nama}?`)) {
                window.location.href = `<?= base_url('kepsek/guru/delete/') ?>${id}`;
            }
        }

        document.querySelectorAll('tbody tr[data-url]').forEach(row => {
            row.addEventListener('click', () => {
                window.location.href = row.dataset.url;
            });
        });
    </script>
</body>

</html>