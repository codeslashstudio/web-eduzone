<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EduZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 50%, #4facfe 100%);
            animation: gradient 15s ease infinite;
            background-size: 400% 400%;
        }

        @keyframes gradient {
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

        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-strong {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        .input-focus:focus {
            border-color: #f5576c;
            box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
        }

        .progress-bar {
            transition: width 0.3s ease;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Main Container -->
    <div class="gradient-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-20 w-64 h-64 bg-pink-300/20 rounded-full filter blur-3xl floating"></div>
            <div class="absolute bottom-32 right-20 w-80 h-80 bg-blue-300/20 rounded-full filter blur-3xl floating" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-purple-300/15 rounded-full filter blur-3xl floating" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-56 h-56 bg-yellow-300/10 rounded-full filter blur-3xl floating" style="animation-delay: 1.5s;"></div>
        </div>

        <!-- Register Card -->
        <div class="glass-strong rounded-3xl shadow-2xl w-full max-w-2xl p-8 md:p-12 relative z-10 transform hover:scale-105 transition-transform duration-500">
            <!-- Logo & Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-pink-500 to-blue-500 rounded-2xl shadow-xl mb-4 transform hover:rotate-12 transition-transform">
                    <i class="fas fa-user-plus text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-extrabold bg-gradient-to-r from-pink-500 via-purple-500 to-blue-500 bg-clip-text text-transparent mb-2">
                    Bergabung dengan EduZone
                </h1>
                <p class="text-gray-600 text-sm">Daftar sekarang dan mulai transformasi digital sekolah Anda!</p>
            </div>

            <!-- Register Form -->
            <form action="<?= base_url('auth/doRegister') ?>" method="POST" class="space-y-5" id="registerForm">
                <?= csrf_field() ?>

                <!-- Full Name & Email Row -->
                <div class="grid md:grid-cols-2 gap-5">
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label for="fullname" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-user mr-2 text-pink-500"></i>Nama Lengkap
                        </label>
                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            required
                            class="input-focus w-full px-4 py-3 bg-white/80 border-2 border-gray-200 rounded-xl focus:outline-none transition-all text-gray-700 placeholder-gray-400"
                            placeholder="John Doe">
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-envelope mr-2 text-pink-500"></i>Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            class="input-focus w-full px-4 py-3 bg-white/80 border-2 border-gray-200 rounded-xl focus:outline-none transition-all text-gray-700 placeholder-gray-400"
                            placeholder="nama@sekolah.com">
                    </div>
                </div>

                <!-- School Name -->
                <div class="space-y-2">
                    <label for="school" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-school mr-2 text-pink-500"></i>Nama Sekolah
                    </label>
                    <input
                        type="text"
                        id="school"
                        name="school"
                        required
                        class="input-focus w-full px-4 py-3 bg-white/80 border-2 border-gray-200 rounded-xl focus:outline-none transition-all text-gray-700 placeholder-gray-400"
                        placeholder="SMA Negeri 1">
                </div>

                <!-- Phone Number -->
                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-phone mr-2 text-pink-500"></i>Nomor Telepon
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        required
                        class="input-focus w-full px-4 py-3 bg-white/80 border-2 border-gray-200 rounded-xl focus:outline-none transition-all text-gray-700 placeholder-gray-400"
                        placeholder="08123456789">
                </div>

                <!-- Password & Confirm Password Row -->
                <div class="grid md:grid-cols-2 gap-5">
                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-lock mr-2 text-pink-500"></i>Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                minlength="8"
                                class="input-focus w-full px-4 py-3 bg-white/80 border-2 border-gray-200 rounded-xl focus:outline-none transition-all text-gray-700 placeholder-gray-400"
                                placeholder="••••••••"
                                oninput="checkPasswordStrength()">
                            <button
                                type="button"
                                onclick="togglePassword('password', 'toggleIcon1')"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <i id="toggleIcon1" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="strengthBar" class="progress-bar h-full bg-gray-300 rounded-full" style="width: 0%"></div>
                            </div>
                            <p id="strengthText" class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label for="password_confirm" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-lock mr-2 text-pink-500"></i>Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password_confirm"
                                name="password_confirm"
                                required
                                class="input-focus w-full px-4 py-3 bg-white/80 border-2 border-gray-200 rounded-xl focus:outline-none transition-all text-gray-700 placeholder-gray-400"
                                placeholder="••••••••"
                                oninput="checkPasswordMatch()">
                            <button
                                type="button"
                                onclick="togglePassword('password_confirm', 'toggleIcon2')"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <i id="toggleIcon2" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p id="matchText" class="text-xs text-gray-500 mt-1"></p>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start space-x-3">
                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        required
                        class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500 focus:ring-2 mt-0.5 cursor-pointer">
                    <label for="terms" class="text-sm text-gray-600 cursor-pointer">
                        Saya setuju dengan
                        <a href="#" class="text-pink-500 hover:text-pink-600 font-semibold">Syarat & Ketentuan</a>
                        dan
                        <a href="#" class="text-pink-500 hover:text-pink-600 font-semibold">Kebijakan Privasi</a>
                    </label>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-pink-500 via-purple-500 to-blue-500 text-white font-bold py-4 rounded-xl hover:from-pink-600 hover:via-purple-600 hover:to-blue-600 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-2xl">
                    <i class="fas fa-rocket mr-2"></i>Daftar Sekarang
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">atau daftar dengan</span>
                </div>
            </div>

            <!-- Social Register -->
            <div class="grid grid-cols-2 gap-4">
                <button class="flex items-center justify-center px-4 py-3 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-pink-500 transition-all group">
                    <i class="fab fa-google text-red-500 text-xl mr-2 group-hover:scale-110 transition-transform"></i>
                    <span class="font-semibold text-gray-700">Google</span>
                </button>
                <button class="flex items-center justify-center px-4 py-3 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-blue-600 transition-all group">
                    <i class="fab fa-microsoft text-blue-600 text-xl mr-2 group-hover:scale-110 transition-transform"></i>
                    <span class="font-semibold text-gray-700">Microsoft</span>
                </button>
            </div>

            <!-- Login Link -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Sudah punya akun?
                    <a href="<?= base_url('login') ?>" class="text-pink-500 hover:text-pink-600 font-bold transition-colors">
                        Login Sekarang
                    </a>
                </p>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            let color = 'bg-gray-300';
            let text = 'Sangat Lemah';

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;

            switch (strength) {
                case 0:
                case 1:
                    color = 'bg-red-500';
                    text = 'Sangat Lemah';
                    strengthBar.style.width = '20%';
                    break;
                case 2:
                    color = 'bg-orange-500';
                    text = 'Lemah';
                    strengthBar.style.width = '40%';
                    break;
                case 3:
                    color = 'bg-yellow-500';
                    text = 'Cukup';
                    strengthBar.style.width = '60%';
                    break;
                case 4:
                    color = 'bg-green-500';
                    text = 'Kuat';
                    strengthBar.style.width = '80%';
                    break;
                case 5:
                    color = 'bg-emerald-500';
                    text = 'Sangat Kuat';
                    strengthBar.style.width = '100%';
                    break;
            }

            strengthBar.className = `progress-bar h-full ${color} rounded-full`;
            strengthText.textContent = text;
            strengthText.className = `text-xs ${color.replace('bg-', 'text-')} mt-1 font-semibold`;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirm').value;
            const matchText = document.getElementById('matchText');

            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchText.textContent = '✓ Password cocok';
                    matchText.className = 'text-xs text-green-500 mt-1 font-semibold';
                } else {
                    matchText.textContent = '✗ Password tidak cocok';
                    matchText.className = 'text-xs text-red-500 mt-1 font-semibold';
                }
            } else {
                matchText.textContent = '';
            }
        }
    </script>
</body>

</html> 