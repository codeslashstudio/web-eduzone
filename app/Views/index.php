<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduZone - Platform Manajemen Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #4c6ef5 50%, #3b5bdb 100%);
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Navbar -->
    <nav class="fixed w-full top-0 z-50 bg-white/10 backdrop-blur-lg border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <span class="text-3xl font-bold text-white">EduZone</span>
                </div>

                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-white hover:text-blue-200 transition font-medium">Home</a>
                    <a href="#services" class="text-white hover:text-blue-200 transition font-medium">Services</a>
                    <a href="#about" class="text-white hover:text-blue-200 transition font-medium">About</a>
                    <a href="#pricing" class="text-white hover:text-blue-200 transition font-medium">Pricing</a>
                    <a href="#newsletter" class="text-white hover:text-blue-200 transition font-medium">Newsletter</a>
                    <a href="<?= base_url('login') ?>" class="bg-white text-blue-600 px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-50 transition btn-hover">Masuk</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-white focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/10 backdrop-blur-lg">
            <div class="px-4 pt-2 pb-4 space-y-3">
                <a href="#home" class="block text-white hover:text-blue-200 transition py-2">Home</a>
                <a href="#services" class="block text-white hover:text-blue-200 transition py-2">Services</a>
                <a href="#about" class="block text-white hover:text-blue-200 transition py-2">About</a>
                <a href="#pricing" class="block text-white hover:text-blue-200 transition py-2">Pricing</a>
                <a href="#newsletter" class="block text-white hover:text-blue-200 transition py-2">Newsletter</a>
                <a href="<?= base_url('login') ?>" class="block bg-white text-blue-600 px-6 py-2.5 rounded-lg font-semibold text-center">Masuk</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="gradient-bg min-h-screen flex items-center justify-center pt-20 relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-white space-y-8">
                    <div class="space-y-4">
                        <h1 class="text-5xl md:text-6xl font-bold leading-tight">
                            Get The Latest App<br />
                            From App Stores
                        </h1>
                        <p class="text-lg md:text-xl text-blue-100 leading-relaxed">
                            Platform manajemen sekolah terpadu berbasis digital untuk guru,
                            siswa, toolman, dan BK dalam satu sistem yang mudah digunakan.
                        </p>
                    </div>

                    <!-- Download Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a href="#" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold flex items-center space-x-3 hover:bg-blue-50 transition transform hover:scale-105 btn-hover shadow-lg">
                            <i class="fab fa-apple text-2xl"></i>
                            <span>App Store</span>
                        </a>
                        <a href="#" class="bg-white/20 backdrop-blur text-white px-8 py-4 rounded-xl font-semibold flex items-center space-x-3 hover:bg-white/30 transition transform hover:scale-105 btn-hover shadow-lg border border-white/30">
                            <i class="fab fa-google-play text-2xl"></i>
                            <span>Google Play</span>
                        </a>
                    </div>

                    <!-- Features -->
                    <div class="grid grid-cols-3 gap-6 pt-8">
                        <div class="text-center">
                            <div class="text-4xl font-bold">500+</div>
                            <div class="text-blue-100 text-sm mt-1">Sekolah</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold">50K+</div>
                            <div class="text-blue-100 text-sm mt-1">Pengguna</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold">4.8</div>
                            <div class="text-blue-100 text-sm mt-1">Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Phone Mockup -->
                <div class="hidden md:flex justify-center items-center">
                    <div class="relative">
                        <!-- Phone Frame -->
                        <div class="w-80 h-[600px] bg-white rounded-[3rem] shadow-2xl p-4 transform rotate-6 hover:rotate-0 transition-transform duration-500">
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 rounded-[2.5rem] flex items-center justify-center">
                                <div class="text-center space-y-4 p-8">
                                    <i class="fas fa-graduation-cap text-white text-6xl"></i>
                                    <h3 class="text-white text-2xl font-bold">EduZone App</h3>
                                    <p class="text-white/80">Kelola sekolah dengan mudah</p>
                                </div>
                            </div>
                        </div>
                        <!-- Floating Elements -->
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg animate-bounce">
                            <i class="fas fa-star text-white text-3xl"></i>
                        </div>
                        <div class="absolute -bottom-10 -left-10 w-20 h-20 bg-pink-400 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                            <i class="fas fa-heart text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-gradient-to-br from-blue-200 to-purple-200 rounded-full filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-200 to-pink-200 rounded-full filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-20" data-aos="fade-up">
                <div class="inline-block mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-full text-sm font-semibold tracking-wide uppercase shadow-lg">Layanan Kami</span>
                </div>
                <h2 class="text-5xl md:text-6xl font-extrabold mb-6">
                    <span class="bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">
                        Fitur Unggulan
                    </span>
                    <br />
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        EduZone Platform
                    </span>
                </h2>
                <p class="text-gray-600 text-xl max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="200">Solusi lengkap berbasis AI untuk transformasi digital pendidikan Anda</p>
            </div>

            <!-- Service Cards -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1: App Maintenance -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 hover:bg-white transition-all duration-500 transform hover:-translate-y-2 shadow-lg hover:shadow-2xl border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>

                    <div class="relative">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 w-20 h-20 rounded-2xl flex items-center justify-center mb-6 shadow-xl group-hover:scale-110 transition-transform duration-500 group-hover:rotate-6">
                            <i class="fas fa-mobile-alt text-white text-3xl"></i>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">App Maintenance</h3>
                            <p class="text-gray-600 leading-relaxed">Sistem maintenance otomatis untuk performa optimal</p>
                        </div>

                        <div class="flex items-center text-blue-600 font-semibold group-hover:gap-3 transition-all cursor-pointer">
                            <span>Pelajari Lebih Lanjut</span>
                            <i class="fas fa-arrow-right transform group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Rocket Speed -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 hover:bg-white transition-all duration-500 transform hover:-translate-y-2 shadow-lg hover:shadow-2xl border border-gray-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>

                    <div class="relative">
                        <div class="bg-gradient-to-br from-purple-500 to-pink-600 w-20 h-20 rounded-2xl flex items-center justify-center mb-6 shadow-xl group-hover:scale-110 transition-transform duration-500 group-hover:rotate-6">
                            <i class="fas fa-rocket text-white text-3xl"></i>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">Rocket Speed</h3>
                            <p class="text-gray-600 leading-relaxed">Loading super cepat dengan teknologi cloud terkini</p>
                        </div>

                        <div class="flex items-center text-purple-600 font-semibold group-hover:gap-3 transition-all cursor-pointer">
                            <span>Pelajari Lebih Lanjut</span>
                            <i class="fas fa-arrow-right transform group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Multi Workflow -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 hover:bg-white transition-all duration-500 transform hover:-translate-y-2 shadow-lg hover:shadow-2xl border border-gray-100" data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>

                    <div class="relative">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 w-20 h-20 rounded-2xl flex items-center justify-center mb-6 shadow-xl group-hover:scale-110 transition-transform duration-500 group-hover:rotate-6">
                            <i class="fas fa-tasks text-white text-3xl"></i>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition-colors">Multi Workflow</h3>
                            <p class="text-gray-600 leading-relaxed">Workflow terintegrasi untuk semua role sekolah</p>
                        </div>

                        <div class="flex items-center text-emerald-600 font-semibold group-hover:gap-3 transition-all cursor-pointer">
                            <span>Pelajari Lebih Lanjut</span>
                            <i class="fas fa-arrow-right transform group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 4: 24/7 Support -->
                <div class="group relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 hover:bg-white transition-all duration-500 transform hover:-translate-y-2 shadow-lg hover:shadow-2xl border border-gray-100" data-aos="fade-up" data-aos-delay="400">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>

                    <div class="relative">
                        <div class="bg-gradient-to-br from-orange-500 to-red-600 w-20 h-20 rounded-2xl flex items-center justify-center mb-6 shadow-xl group-hover:scale-110 transition-transform duration-500 group-hover:rotate-6">
                            <i class="fas fa-headset text-white text-3xl"></i>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors">24/7 Support</h3>
                            <p class="text-gray-600 leading-relaxed">Tim support siap membantu kapan saja</p>
                        </div>

                        <div class="flex items-center text-orange-600 font-semibold group-hover:gap-3 transition-all cursor-pointer">
                            <span>Pelajari Lebih Lanjut</span>
                            <i class="fas fa-arrow-right transform group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-gradient-to-b from-white to-gray-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-start">
                <!-- Left Content -->
                <div class="space-y-8" data-aos="fade-right">
                    <div>
                        <div class="inline-flex items-center bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                            <i class="fas fa-info-circle mr-2"></i>
                            Tentang Kami
                        </div>
                        <h2 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
                            <span class="text-gray-900">Kenapa Memilih</span>
                            <br />
                            <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">EduZone?</span>
                        </h2>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            EduZone adalah platform manajemen sekolah digital yang dirancang untuk mempermudah operasional sekolah modern dengan teknologi terdepan dan inovasi berkelanjutan.
                        </p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-6">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl">
                            <div class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">98%</div>
                            <div class="text-sm text-gray-600 mt-1">Kepuasan</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl">
                            <div class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">5+</div>
                            <div class="text-sm text-gray-600 mt-1">Tahun</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl">
                            <div class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">24/7</div>
                            <div class="text-sm text-gray-600 mt-1">Support</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Feature Cards -->
                <div class="space-y-6" data-aos="fade-left">
                    <!-- Card 1 -->
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 overflow-hidden" data-aos="zoom-in" data-aos-delay="100">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500">
                                    <i class="fas fa-shield-alt text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Keamanan Data Terjamin</h3>
                                <p class="text-gray-600 leading-relaxed">Sistem enkripsi tingkat enterprise untuk melindungi data sekolah Anda</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 overflow-hidden" data-aos="zoom-in" data-aos-delay="200">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Kolaborasi Tim Optimal</h3>
                                <p class="text-gray-600 leading-relaxed">Hubungkan guru, siswa, dan orang tua dalam satu platform terintegrasi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 overflow-hidden" data-aos="zoom-in" data-aos-delay="300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500">
                                    <i class="fas fa-chart-line text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">Analitik & Laporan Real-time</h3>
                                <p class="text-gray-600 leading-relaxed">Dashboard interaktif dengan insight mendalam untuk keputusan strategis</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 overflow-hidden" data-aos="zoom-in" data-aos-delay="400">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100 to-red-100 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500">
                                    <i class="fas fa-cogs text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">Kustomisasi Fleksibel</h3>
                                <p class="text-gray-600 leading-relaxed">Sesuaikan platform dengan kebutuhan unik sekolah Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section id="testimonial" class="py-24 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-64 h-64 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-5xl md:text-6xl font-extrabold text-white mb-4">
                    Check What <span class="text-yellow-300">The Clients Say</span> About Our App Dev
                </h2>
                <p class="text-blue-100 text-lg" data-aos="fade-up" data-aos-delay="100">Testimoni dari pengguna EduZone di berbagai sekolah</p>
            </div>

            <!-- Testimonial Cards -->
            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <!-- Card 1 -->
                <div class="bg-white rounded-3xl p-8 shadow-2xl transform hover:-translate-y-2 transition-all duration-500 hover:shadow-blue-500/50" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4 shadow-lg">
                            DM
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">David Martino, Co.</h4>
                            <p class="text-gray-500 text-sm">Manager Apps</p>
                        </div>
                    </div>
                    <div class="flex gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 leading-relaxed">Platform yang sangat membantu dalam mengelola administrasi sekolah. Sangat direkomendasikan!</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-3xl p-8 shadow-2xl transform hover:-translate-y-2 transition-all duration-500 hover:shadow-indigo-500/50" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4 shadow-lg">
                            JT
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">Joko Tomo Nyo</h4>
                            <p class="text-gray-500 text-sm">Mobile Developer</p>
                        </div>
                    </div>
                    <div class="flex gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 leading-relaxed">Interface yang user-friendly dan fitur yang lengkap. Tim support juga sangat responsif.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-3xl p-8 shadow-2xl transform hover:-translate-y-2 transition-all duration-500 hover:shadow-purple-500/50" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4 shadow-lg">
                            MC
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">Muhammad Alif Sya'bani</h4>
                            <p class="text-gray-500 text-sm">Business & Economics</p>
                        </div>
                    </div>
                    <div class="flex gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 leading-relaxed">Sistem yang sangat efisien untuk manajemen data siswa dan guru. ROI yang sangat baik!</p>
                </div>
            </div>

            <!-- CEO Testimonial -->
            <div class="max-w-4xl mx-auto" data-aos="zoom-in" data-aos-delay="400">
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl p-10 shadow-2xl">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-quote-left text-blue-600 text-3xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-lg md:text-xl leading-relaxed mb-6 italic">
                                "Sejak menggunakan EduZone, proses administrasi sekolah kami menjadi lebih efisien. Semua data terintegrasi dengan baik dan mudah diakses. Sistem absensi digital sangat membantu dalam monitoring kehadiran siswa dan guru secara real-time. Highly recommended!"
                            </p>
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mr-4 shadow-lg">
                                    <span class="text-blue-600 font-bold text-xl">F</span>
                                </div>
                                <div>
                                    <h4 class="text-white font-bold text-lg">Fawa</h4>
                                    <p class="text-blue-200">CEO of EduZone</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-5xl md:text-6xl font-extrabold mb-4">
                    <span class="text-gray-900">We Have The Best Pre-Order</span>
                    <br />
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Prices</span>
                    <span class="text-gray-900"> You Can Get</span>
                </h2>
                <p class="text-gray-600 text-lg" data-aos="fade-up" data-aos-delay="100">Pilih paket yang sesuai dengan kebutuhan sekolah Anda</p>
            </div>

            <!-- Pricing Cards -->
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Standard Plan -->
                <div class="bg-white rounded-3xl p-10 shadow-lg hover:shadow-2xl transition-all duration-500 border-2 border-gray-100 hover:border-blue-500 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Standard Plan App</h3>
                        <div class="mb-6">
                            <span class="text-6xl font-extrabold text-blue-600">$12</span>
                            <span class="text-gray-500 text-lg">/month</span>
                        </div>
                        <p class="text-gray-500">Lorem Ipsum Dolores</p>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-blue-500 mr-3"></i>
                            <span>50 TB Storage</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-blue-500 mr-3"></i>
                            <span>Unlimited Users</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-blue-500 mr-3"></i>
                            <span>All Features</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-blue-500 mr-3"></i>
                            <span>Lifetime Updates</span>
                        </li>
                    </ul>

                    <button class="w-full bg-blue-600 text-white py-4 rounded-xl font-semibold hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-blue-500/50">
                        Available Your Plan Now
                    </button>
                </div>

                <!-- Business Plan (Popular) -->
                <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-3xl p-10 shadow-2xl transform scale-105 hover:scale-110 transition-all duration-500 relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute top-6 right-6 bg-yellow-400 text-gray-900 px-4 py-2 rounded-full text-sm font-bold shadow-lg transform rotate-12">
                        BEST
                    </div>

                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-white mb-4">Business Plan App</h3>
                        <div class="mb-6">
                            <span class="text-6xl font-extrabold text-white">$25</span>
                            <span class="text-blue-100 text-lg">/month</span>
                        </div>
                        <p class="text-blue-100">Lorem Ipsum Dolores</p>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-white">
                            <i class="fas fa-check-circle text-yellow-300 mr-3"></i>
                            <span>100 TB Storage</span>
                        </li>
                        <li class="flex items-center text-white">
                            <i class="fas fa-check-circle text-yellow-300 mr-3"></i>
                            <span>Unlimited Users</span>
                        </li>
                        <li class="flex items-center text-white">
                            <i class="fas fa-check-circle text-yellow-300 mr-3"></i>
                            <span>Premium Features</span>
                        </li>
                        <li class="flex items-center text-white">
                            <i class="fas fa-check-circle text-yellow-300 mr-3"></i>
                            <span>Lifetime Updates</span>
                        </li>
                        <li class="flex items-center text-white">
                            <i class="fas fa-check-circle text-yellow-300 mr-3"></i>
                            <span>Maintenance Full Time</span>
                        </li>
                    </ul>

                    <button class="w-full bg-white text-blue-600 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg">
                        Available Your Plan Now
                    </button>
                </div>

                <!-- Premium Plan -->
                <div class="bg-white rounded-3xl p-10 shadow-lg hover:shadow-2xl transition-all duration-500 border-2 border-gray-100 hover:border-purple-500 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Premium Plan App</h3>
                        <div class="mb-6">
                            <span class="text-6xl font-extrabold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">$94</span>
                            <span class="text-gray-500 text-lg">/month</span>
                        </div>
                        <p class="text-gray-500">Lorem Ipsum Dolores</p>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-purple-500 mr-3"></i>
                            <span>Unlimited Storage</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-purple-500 mr-3"></i>
                            <span>Unlimited Users</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-purple-500 mr-3"></i>
                            <span>All Premium Features</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-purple-500 mr-3"></i>
                            <span>Lifetime Updates</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-purple-500 mr-3"></i>
                            <span>24/7 Support</span>
                        </li>
                    </ul>

                    <button class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 rounded-xl font-semibold hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-purple-500/50">
                        Available Your Plan Now
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section id="newsletter" class="py-24 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4">
                    Join Our Mailing List To Receive The News & Latest Trends
                </h2>
                <p class="text-blue-100 text-lg mb-10">Dapatkan update terbaru tentang fitur dan tips penggunaan EduZone</p>
            </div>

            <!-- Newsletter Form -->
            <form action="<?= base_url('newsletter/subscribe') ?>" method="POST" class="flex flex-col md:flex-row gap-4 justify-center items-center max-w-3xl mx-auto" data-aos="zoom-in" data-aos-delay="200">
                <?= csrf_field() ?>
                <div class="w-full md:flex-1">
                    <input
                        type="email"
                        name="email"
                        required
                        placeholder="Enter your email address"
                        class="w-full px-6 py-5 rounded-full border-0 focus:outline-none focus:ring-4 focus:ring-white/30 text-gray-700 placeholder-gray-400 shadow-lg text-lg">
                </div>
                <button
                    type="submit"
                    class="w-full md:w-auto bg-white text-blue-600 px-10 py-5 rounded-full font-bold text-lg hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg hover:shadow-2xl">
                    Subscribe Now
                </button>
            </form>

            <!-- Success Message (Hidden by default) -->
            <div id="successMessage" class="hidden mt-6 bg-green-500/20 backdrop-blur-sm border border-green-400 text-white px-6 py-4 rounded-2xl max-w-md mx-auto">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Terima kasih! Anda telah berlangganan newsletter kami.</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <!-- Company Info -->
                <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-2xl font-bold">EduZone</h3>
                    <p class="text-gray-400">Platform manajemen sekolah terpadu berbasis digital</p>
                    <div class="space-y-2">
                        <p class="text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i>Jl. Pendidikan No. 123</p>
                        <p class="text-gray-400">Jakarta, Indonesia</p>
                        <p class="text-gray-400"><i class="fas fa-envelope mr-2"></i>info@eduzone.com</p>
                        <p class="text-gray-400"><i class="fas fa-phone mr-2"></i>+62 21 1234 5678</p>
                    </div>
                </div>

                <!-- About Us -->
                <div data-aos="fade-up" data-aos-delay="200">
                    <h4 class="font-semibold mb-4 text-lg">About Us</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Company</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Portfolio</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Useful Links -->
                <div data-aos="fade-up" data-aos-delay="300">
                    <h4 class="font-semibold mb-4 text-lg">Useful Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#services" class="text-gray-400 hover:text-white transition">Our Services</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Terms of Use</a></li>
                    </ul>
                </div>

                <!-- About Our Company -->
                <div data-aos="fade-up" data-aos-delay="400">
                    <h4 class="font-semibold mb-4 text-lg">About Our Company</h4>
                    <p class="text-gray-400 mb-6 leading-relaxed">EduZone adalah platform manajemen sekolah digital terpercaya yang telah membantu ratusan sekolah di Indonesia dalam digitalisasi sistem administrasi.</p>

                    <!-- Social Media -->
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition transform hover:scale-110">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-400 transition transform hover:scale-110">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition transform hover:scale-110">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-700 transition transform hover:scale-110">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400">&copy; 2025 EduZone. All rights reserved. Made with <i class="fas fa-heart text-red-500"></i> in Indonesia</p>
            </div>
        </div>
    </footer>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>

    <!-- JavaScript for Mobile Menu and Newsletter -->
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    </script>
</body>

</html>