<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru - EduZone</title>
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

        .form-control {
            transition: all 0.3s ease;
        }

        .form-control:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        #imagePreview {
            display: none;
        }

        #imagePreview img {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
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
                        <h1 class="text-2xl font-bold text-gray-900">Tambah Guru</h1>
                        <p class="text-sm text-gray-500">Tambahkan data guru baru</p>
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
            <!-- Back Button -->
            <div class="mb-6">
                <a href="<?= base_url('kepsek/guru') ?>" class="inline-flex items-center space-x-2 text-gray-600 hover:text-purple-600 transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span class="font-semibold">Kembali ke Data Guru</span>
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-user-plus mr-3"></i>
                        Form Tambah Guru
                    </h2>
                    <p class="text-purple-100 mt-2">Lengkapi semua data dengan benar</p>
                </div>

                <form action="<?= base_url('kepsek/guru/store') ?>" method="POST" enctype="multipart/form-data" class="p-8">
                    <?= csrf_field() ?>

                    <!-- Data Pribadi -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-purple-200 flex items-center">
                            <i class="fas fa-user text-purple-600 mr-2"></i>
                            Data Pribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIP -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    NIP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nip" required
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Masukkan NIP">
                            </div>

                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" required
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Masukkan nama lengkap">
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_kelamin" required
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <!-- Agama -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Agama <span class="text-red-500">*</span>
                                </label>
                                <select name="agama" required
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tempat Lahir
                                </label>
                                <input type="text" name="tempat_lahir"
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Masukkan tempat lahir">
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <input type="date" name="tanggal_lahir"
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Alamat
                                </label>
                                <textarea name="alamat" rows="3"
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Masukkan alamat lengkap"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Data Kontak -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-purple-200 flex items-center">
                            <i class="fas fa-address-book text-purple-600 mr-2"></i>
                            Data Kontak
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- No HP -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    No. HP/WA <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="no_hp" required
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" name="email"
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="email@example.com">
                            </div>
                        </div>
                    </div>

                    <!-- Data Kepegawaian -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-purple-200 flex items-center">
                            <i class="fas fa-briefcase text-purple-600 mr-2"></i>
                            Data Kepegawaian
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pendidikan Terakhir -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Pendidikan Terakhir <span class="text-red-500">*</span>
                                </label>
                                <select name="pendidikan_terakhir" required
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>

                            <!-- Jabatan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jabatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="jabatan" required
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Contoh: Guru Matematika">
                            </div>

                            <!-- Status Kepegawaian -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Kepegawaian <span class="text-red-500">*</span>
                                </label>
                                <select name="status_kepegawaian" required
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Pilih Status</option>
                                    <option value="PNS">PNS</option>
                                    <option value="PPPK">PPPK</option>
                                    <option value="Honorer">Honorer</option>
                                    <option value="Kontrak">Kontrak</option>
                                </select>
                            </div>

                            <!-- Foto -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Foto Profil
                                </label>
                                <input type="file" name="foto" accept="image/*" onchange="previewImage(event)"
                                    class="form-control w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maksimal 2MB</p>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-4">
                                    <img id="preview" class="rounded-lg border-2 border-purple-200" alt="Preview">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="<?= base_url('kepsek/guru') ?>"
                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="reset"
                            class="px-6 py-3 bg-yellow-500 text-white rounded-lg font-semibold hover:bg-yellow-600 transition-all">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>
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

        // Preview Image
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('preview');
                const previewContainer = document.getElementById('imagePreview');
                preview.src = reader.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Form Validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const nip = document.querySelector('input[name="nip"]').value;
            const nama = document.querySelector('input[name="nama"]').value;
            const noHp = document.querySelector('input[name="no_hp"]').value;

            if (!nip || !nama || !noHp) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi!');
                return false;
            }

            // Validate phone number
            if (!/^[0-9]{10,13}$/.test(noHp)) {
                e.preventDefault();
                alert('Format nomor HP tidak valid! Gunakan 10-13 digit angka.');
                return false;
            }

            return true;
        });
    </script>
</body>

</html>