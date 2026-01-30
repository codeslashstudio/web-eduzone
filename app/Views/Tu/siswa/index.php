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
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
            transform: translateX(8px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
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

                <a href="<?= base_url('tu/siswa') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
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
                        <h1 class="text-2xl font-bold text-gray-900"><?= esc($title) ?></h1>
                        <p class="text-sm text-gray-500">Kelola data siswa sekolah</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <i class="fas fa-bell text-gray-600 text-xl"></i>
                        <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-8">
            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-2xl mr-3"></i>
                        <div>
                            <p class="font-bold">Berhasil!</p>
                            <p><?= session()->getFlashdata('success') ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                        <div>
                            <p class="font-bold">Error!</p>
                            <p><?= session()->getFlashdata('error') ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Bar -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <a href="<?= base_url('tu/siswa/add') ?>" class="flex items-center space-x-2 bg-gradient-to-r from-green-600 to-teal-600 text-white px-6 py-3 rounded-xl hover:shadow-xl transition-all transform hover:scale-105">
                            <i class="fas fa-plus"></i>
                            <span class="font-semibold">Tambah Siswa</span>
                        </a>
                        <button onclick="window.print()" class="flex items-center space-x-2 bg-green-500 text-white px-6 py-3 rounded-xl hover:shadow-xl transition-all transform hover:scale-105">
                            <i class="fas fa-print"></i>
                            <span class="font-semibold">Cetak</span>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Cari siswa..." class="pl-10 pr-4 py-2 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none w-64">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                        <select id="filterJurusan" class="px-4 py-2 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none">
                            <option value="">Semua Jurusan</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-users text-4xl opacity-80"></i>
                        <div class="text-right">
                            <p class="text-3xl font-bold"><?= count($siswa) ?></p>
                            <p class="text-sm opacity-80">Total Siswa</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-male text-4xl opacity-80"></i>
                        <div class="text-right">
                            <p class="text-3xl font-bold">0</p>
                            <p class="text-sm opacity-80">Laki-laki</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-female text-4xl opacity-80"></i>
                        <div class="text-right">
                            <p class="text-3xl font-bold">0</p>
                            <p class="text-sm opacity-80">Perempuan</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-user-plus text-4xl opacity-80"></i>
                        <div class="text-right">
                            <p class="text-3xl font-bold">0</p>
                            <p class="text-sm opacity-80">Siswa Baru</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full" id="siswaTable">
                        <thead class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase">No</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase">Foto</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase">NISN</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase">Nama</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase">Jurusan</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase">Agama</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase">Tanggal Lahir</th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($siswa)): ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-5xl mb-4 opacity-50"></i>
                                        <p class="text-lg font-semibold">Belum ada data siswa</p>
                                        <p class="text-sm">Klik tombol "Tambah Siswa" untuk menambahkan data siswa baru</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($siswa as $index => $s): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-900"><?= $index + 1 ?></td>
                                        <td class="px-6 py-4">
                                            <?php if ($s['foto']): ?>
                                                <img src="<?= base_url('uploads/siswa/' . $s['foto']) ?>" alt="Foto <?= esc($s['nama']) ?>" class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">
                                            <?php else: ?>
                                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-teal-500 flex items-center justify-center text-white font-bold">
                                                    <?= strtoupper(substr($s['nama'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-semibold"><?= esc($s['nis']) ?></td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-semibold text-gray-900"><?= esc($s['nama']) ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <?= esc($s['nama_jurusan']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?= esc($s['agama']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?= date('d/m/Y', strtotime($s['tanggal_lahir'])) ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="<?= base_url('tu/siswa/detail/' . $s['idsiswa']) ?>" class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all transform hover:scale-110" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('tu/siswa/edit/' . $s['idsiswa']) ?>" class="p-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all transform hover:scale-110" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmDelete(<?= $s['idsiswa'] ?>, '<?= esc($s['nama']) ?>')" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all transform hover:scale-110" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data siswa <span id="deleteName" class="font-bold"></span>?</p>
                <div class="flex space-x-4">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all font-semibold">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
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

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#siswaTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Delete confirmation
        function confirmDelete(id, name) {
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteForm').action = '<?= base_url('tu/siswa/delete/') ?>' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Auto hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>

</html>