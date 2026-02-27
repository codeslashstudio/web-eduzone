<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            animation: gradient 15s ease infinite;
            background-size: 400% 400%;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .input-focus:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>

<body class="antialiased">
    <!-- Main Container -->
    <div class="gradient-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full filter blur-3xl floating"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-300/20 rounded-full filter blur-3xl floating" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-pink-300/10 rounded-full filter blur-3xl floating" style="animation-delay: 2s;"></div>
        </div>

        <!-- Login Card -->
        <div class="glass-strong rounded-3xl shadow-2xl w-full max-w-md p-8 md:p-12 relative z-10 transition-shadow duration-300 hover:shadow-3xl">
            <!-- Logo & Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-xl mb-4 transform hover:rotate-12 transition-transform">
                    <i class="fas fa-graduation-cap text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    EduZone
                </h1>
                <p class="text-gray-600 text-sm">Selamat datang kembali! Silakan login untuk melanjutkan.</p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <i class="fas fa-circle-exclamation flex-shrink-0"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    <i class="fas fa-circle-check flex-shrink-0"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= base_url('auth/doLogin') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>

                <!-- Username Input -->
                <div class="space-y-2">
                    <label for="username" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Username
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="username"
                            name="username"
                            required
                            value="<?= old('username') ?>"
                            autocomplete="username"
                            class="input-focus w-full px-4 py-3 bg-white/80 border-2 border-gray-200 rounded-xl focus:outline-none transition-all text-gray-700 placeholder-gray-400"
                            placeholder="Masukkan username Anda">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-at text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="input-focus w-full px-4 py-3 bg-white/80 border-2 border-gray-200 rounded-xl focus:outline-none transition-all text-gray-700 placeholder-gray-400"
                            placeholder="••••••••">
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                        <span class="ml-2 text-gray-600 group-hover:text-gray-900 transition-colors">Ingat saya</span>
                    </label>
                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold py-4 rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-2xl">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Dashboard
                </button>
            </form>

            <!-- Back to Home -->
            <div class="mt-8 text-center">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

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
    </script>
</body>

</html>