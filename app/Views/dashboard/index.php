<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EduZone Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .gradient-animated {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .role-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .role-card:hover {
            transform: translateY(-12px);
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 1.5rem;
            padding: 2px;
            background: linear-gradient(135deg, transparent, transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .role-card:hover::before {
            opacity: 1;
        }

        .btn-masuk {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-masuk::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-masuk:hover::before {
            width: 300px;
            height: 300px;
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body class="antialiased bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-lg shadow-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-2xl shadow-lg transform hover:rotate-12 transition-transform">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-extrabold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">EduZone</span>
                        <p class="text-xs text-gray-500 font-medium">Platform Manajemen Sekolah</p>
                    </div>
                </div>

                <!-- User Info & Logout -->
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden md:block">
                        <p class="text-xs text-gray-500 font-medium">Selamat Datang,</p>
                        <p class="font-bold text-gray-900 text-lg"><?= esc($username) ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg ring-4 ring-purple-100">
                        <?= strtoupper(substr($username, 0, 1)) ?>
                    </div>
                    <a href="<?= base_url('logout') ?>" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2.5 rounded-xl hover:from-red-600 hover:to-red-700 transition-all font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Welcome Section -->
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center justify-center w-28 h-28 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-3xl shadow-2xl mb-8 floating">
                    <i class="fas fa-hand-sparkles text-white text-5xl"></i>
                </div>

                <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
                    <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Selamat Datang di EduZone!
                    </span>
                </h1>

                <div class="max-w-2xl mx-auto mb-6">
                    <p class="text-2xl font-bold text-gray-800 mb-3">
                        Halo, <?= esc($username) ?>! 👋
                    </p>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Terima kasih telah bergabung dengan platform manajemen sekolah digital terpadu yang memudahkan pengelolaan administrasi sekolah secara efisien dan modern.
                    </p>
                </div>

                <!-- Info Box -->
                <div class="max-w-4xl mx-auto mt-10" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-white/70 backdrop-blur-lg border-2 border-blue-200 rounded-3xl p-8 shadow-xl">
                        <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg pulse-slow">
                                    <i class="fas fa-info-circle text-white text-3xl"></i>
                                </div>
                            </div>
                            <div class="flex-1 text-left">
                                <h3 class="text-2xl font-bold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-rocket text-blue-600 mr-2"></i>
                                    Pilih Role Anda
                                </h3>
                                <p class="text-gray-700 leading-relaxed text-base">
                                    Untuk melanjutkan, silakan <span class="font-bold text-blue-600">pilih role yang sesuai</span> dengan posisi Anda di sekolah.
                                    Setiap role memiliki akses dashboard dan fitur yang berbeda, disesuaikan dengan kebutuhan dan tanggung jawab masing-masing pengguna.
                                </p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>Dashboard Khusus
                                    </span>
                                    <span class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>Akses Terbatas
                                    </span>
                                    <span class="px-4 py-2 bg-pink-100 text-pink-700 rounded-full text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>Fitur Lengkap
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Selection Title -->
            <div class="text-center mb-12" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Pilih Role Anda
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Klik pada salah satu role di bawah ini untuk mengakses dashboard yang sesuai dengan posisi Anda
                </p>
            </div>

            <!-- Role Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16">

                <!-- Kepala Sekolah -->
                <div class="role-card group" data-aos="zoom-in" data-aos-delay="100">
                    <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl border-2 border-gray-100 hover:border-blue-400 relative overflow-hidden h-full">
                        <!-- Decorative Circle -->
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <!-- Icon -->
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-tie text-white text-3xl"></i>
                            </div>

                            <!-- Content -->
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                                Kepala Sekolah
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Kelola dan monitor seluruh aktivitas sekolah dengan akses penuh ke semua fitur manajemen
                            </p>

                            <!-- Features List -->
                            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-blue-500 mr-2"></i>
                                    Dashboard Lengkap
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-blue-500 mr-2"></i>
                                    Laporan & Analitik
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-blue-500 mr-2"></i>
                                    Manajemen Staff
                                </li>
                            </ul>

                            <!-- Button -->
                            <a href="<?= base_url('dashboard/select/kepsek') ?>" class="btn-masuk block w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3.5 px-6 rounded-xl font-semibold text-center hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk sebagai Kepala Sekolah
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tata Usaha -->
                <div class="role-card group" data-aos="zoom-in" data-aos-delay="200">
                    <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl border-2 border-gray-100 hover:border-green-400 relative overflow-hidden h-full">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-green-100 to-green-200 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-alt text-white text-3xl"></i>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">
                                Tata Usaha
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Kelola administrasi dan dokumentasi sekolah dengan sistem yang terorganisir
                            </p>

                            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Manajemen Dokumen
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Surat Menyurat
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Arsip Digital
                                </li>
                            </ul>

                            <a href="<?= base_url('dashboard/select/tu') ?>" class="btn-masuk block w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3.5 px-6 rounded-xl font-semibold text-center hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk sebagai Tata Usaha
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Wali Kelas -->
                <div class="role-card group" data-aos="zoom-in" data-aos-delay="300">
                    <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl border-2 border-gray-100 hover:border-purple-400 relative overflow-hidden h-full">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-purple-100 to-purple-200 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-chalkboard-teacher text-white text-3xl"></i>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                                Wali Kelas
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Kelola data siswa, absensi, dan monitoring perkembangan kelas Anda
                            </p>

                            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-purple-500 mr-2"></i>
                                    Data Siswa Kelas
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-purple-500 mr-2"></i>
                                    Absensi & Presensi
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-purple-500 mr-2"></i>
                                    Laporan Siswa
                                </li>
                            </ul>

                            <a href="<?= base_url('dashboard/select/wakel') ?>" class="btn-masuk block w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-3.5 px-6 rounded-xl font-semibold text-center hover:from-purple-600 hover:to-purple-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk sebagai Wali Kelas
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bimbingan Konseling -->
                <div class="role-card group" data-aos="zoom-in" data-aos-delay="400">
                    <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl border-2 border-gray-100 hover:border-pink-400 relative overflow-hidden h-full">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-pink-100 to-pink-200 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-friends text-white text-3xl"></i>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-pink-600 transition-colors">
                                Bimbingan Konseling
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Berikan bimbingan dan konseling untuk mendukung perkembangan siswa
                            </p>

                            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-pink-500 mr-2"></i>
                                    Konseling Siswa
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-pink-500 mr-2"></i>
                                    Catatan Bimbingan
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-pink-500 mr-2"></i>
                                    Monitoring Perilaku
                                </li>
                            </ul>

                            <a href="<?= base_url('dashboard/select/bk') ?>" class="btn-masuk block w-full bg-gradient-to-r from-pink-500 to-pink-600 text-white py-3.5 px-6 rounded-xl font-semibold text-center hover:from-pink-600 hover:to-pink-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk sebagai BK
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kurikulum -->
                <div class="role-card group" data-aos="zoom-in" data-aos-delay="500">
                    <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl border-2 border-gray-100 hover:border-indigo-400 relative overflow-hidden h-full">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-book-open text-white text-3xl"></i>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">
                                Kurikulum
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Kelola kurikulum, silabus, dan program pembelajaran sekolah
                            </p>

                            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-indigo-500 mr-2"></i>
                                    Manajemen Kurikulum
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-indigo-500 mr-2"></i>
                                    Silabus & RPP
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-indigo-500 mr-2"></i>
                                    Program Pembelajaran
                                </li>
                            </ul>

                            <a href="<?= base_url('dashboard/select/kurikulum') ?>" class="btn-masuk block w-full bg-gradient-to-r from-indigo-500 to-indigo-600 text-white py-3.5 px-6 rounded-xl font-semibold text-center hover:from-indigo-600 hover:to-indigo-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk sebagai Kurikulum
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Guru -->
                <div class="role-card group" data-aos="zoom-in" data-aos-delay="600">
                    <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl border-2 border-gray-100 hover:border-orange-400 relative overflow-hidden h-full">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-graduate text-white text-3xl"></i>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors">
                                Guru
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Kelola mata pelajaran, nilai siswa, dan materi pembelajaran
                            </p>

                            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-orange-500 mr-2"></i>
                                    Input Nilai Siswa
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-orange-500 mr-2"></i>
                                    Materi Pelajaran
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-orange-500 mr-2"></i>
                                    Jadwal Mengajar
                                </li>
                            </ul>

                            <a href="<?= base_url('dashboard/select/guru') ?>" class="btn-masuk block w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3.5 px-6 rounded-xl font-semibold text-center hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk sebagai Guru
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Additional Features Section -->
            <div class="max-w-6xl mx-auto" data-aos="fade-up" data-aos-delay="700">
                <div class="bg-white/70 backdrop-blur-lg rounded-3xl p-10 shadow-2xl border-2 border-gray-200">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-4 shadow-lg">
                            <i class="fas fa-rocket text-white text-3xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-3">Fitur Unggulan EduZone</h3>
                        <p class="text-gray-600 text-lg">Platform lengkap untuk manajemen sekolah modern</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border-2 border-blue-200 hover:border-blue-400 transition-all hover:shadow-lg">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-4 shadow-md">
                                <i class="fas fa-shield-alt text-white text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg mb-2">Keamanan Terjamin</h4>
                            <p class="text-gray-600 text-sm">Data terenkripsi dengan sistem keamanan tingkat enterprise</p>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 border-2 border-purple-200 hover:border-purple-400 transition-all hover:shadow-lg">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 shadow-md">
                                <i class="fas fa-bolt text-white text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg mb-2">Akses Super Cepat</h4>
                            <p class="text-gray-600 text-sm">Performa tinggi dengan teknologi cloud terkini</p>
                        </div>

                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl p-6 border-2 border-pink-200 hover:border-pink-400 transition-all hover:shadow-lg">
                            <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-4 shadow-md">
                                <i class="fas fa-headset text-white text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg mb-2">Support 24/7</h4>
                            <p class="text-gray-600 text-sm">Tim support siap membantu Anda kapan saja</p>
                        </div>
                    </div>

                    <!-- Info Credentials -->
                    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- <div class="items-center">
                                <h5 class="font-bold text-lg mb-3 flex items-center">
                                    <i class="fas fa-key mr-2"></i>
                                    Demo Credentials
                                </h5>
                                <div class="space-y-2 text-sm bg-white/10 backdrop-blur rounded-xl p-4">
                                    <p class="flex items-center">
                                        <i class="fas fa-user w-6"></i>
                                        <span class="font-mono bg-white/20 px-3 py-1.5 rounded-lg">kepsek</span>
                                    </p>
                                    <p class="flex items-center">
                                        <i class="fas fa-lock w-6"></i>
                                        <span class="font-mono bg-white/20 px-3 py-1.5 rounded-lg">belajar123</span>
                                    </p>
                                </div>
                            </div> -->
                            <div>
                                <h5 class="font-bold text-lg mb-3 flex items-center">
                                    <i class="fas fa-users mr-2"></i>
                                    Role Tersedia
                                </h5>
                                <div class="space-y-2 text-sm bg-white/10 backdrop-blur rounded-xl p-4">
                                    <p><i class="fas fa-check-circle mr-2"></i>Kepala Sekolah & Tata Usaha</p>
                                    <p><i class="fas fa-check-circle mr-2"></i>Wali Kelas & Guru</p>
                                    <p><i class="fas fa-check-circle mr-2"></i>BK & Kurikulum</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white/80 backdrop-blur-lg border-t border-gray-200 mt-20 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-600 mb-2">
                    © <?= date('Y') ?> <span class="font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">EduZone</span> - Platform Manajemen Sekolah Digital
                </p>
                <p class="text-gray-500 text-sm">Dibuat dengan <i class="fas fa-heart text-red-500"></i> untuk pendidikan Indonesia</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS Animation
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            easing: 'ease-in-out'
        });

        // Add blob animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>