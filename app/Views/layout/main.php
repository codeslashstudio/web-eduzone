<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'EduZone') ?> - EduZone</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php
    // ============================================================
    // Ambil dari session SEKALI di sini — tidak perlu di view lain
    // ============================================================
    $colorPrimary   = session()->get('color_primary')   ?? '#3B82F6';
    $colorSecondary = session()->get('color_secondary')  ?? '#2563EB';
    $roleIcon       = session()->get('role_icon')        ?? 'fa-user';
    $roleName       = session()->get('role_name')        ?? 'User';
    $username       = session()->get('username')         ?? '';
    $role           = session()->get('role')             ?? '';
    ?>

    <style>
        /* ============================================================
           CSS VARIABLES — set sekali, berlaku di seluruh halaman
           ============================================================ */
        :root {
            --color-primary:   <?= $colorPrimary ?>;
            --color-secondary: <?= $colorSecondary ?>;
            --color-primary-rgb: <?= implode(',', sscanf($colorPrimary, '#%02x%02x%02x')) ?>;
        }

        * {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* ============================================================
           SIDEBAR — putih seperti tampilan lama
           ============================================================ */
        .sidebar {
            width: 280px;
            transition: width 0.3s ease;
            background: white;
            box-shadow: 4px 0 24px rgba(0,0,0,0.08);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar.collapsed .sidebar-logo-text {
            display: none;
        }

        /* ============================================================
           MENU ITEMS — warna dari CSS variable
           ============================================================ */
        .menu-item {
            transition: all 0.3s ease;
            color: #374151;
            border-radius: 0.75rem;
        }

        .menu-item:hover {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            transform: translateX(8px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(var(--color-primary-rgb), 0.3);
        }

        /* Logo & user profile border */
        .sidebar-divider {
            border-color: #e5e7eb;
        }

        /* User avatar di sidebar */
        .sidebar-avatar {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        }

        /* Logo icon */
        .sidebar-logo-icon {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        }

        /* ============================================================
           MAIN CONTENT
           ============================================================ */
        #main-content {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        #main-content.expanded {
            margin-left: 80px;
        }

        /* ============================================================
           HEADER
           ============================================================ */
        .top-header {
            background: white;
            border-bottom: 2px solid rgba(var(--color-primary-rgb), 0.1);
        }

        /* ============================================================
           ACCENT ELEMENTS — pakai var di seluruh halaman
           ============================================================ */
        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(var(--color-primary-rgb), 0.4);
        }

        .text-accent    { color: var(--color-primary); }
        .border-accent  { border-color: var(--color-primary); }
        .bg-accent-soft { background: rgba(var(--color-primary-rgb), 0.08); }

        .badge-role {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
        }

        .stat-icon-bg {
            background: rgba(var(--color-primary-rgb), 0.1);
            color: var(--color-primary);
        }

        .card-border-accent {
            border-left: 4px solid var(--color-primary);
        }

        /* ============================================================
           SCROLLBAR
           ============================================================ */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 4px; }

        /* ============================================================
           FLASH MESSAGES
           ============================================================ */
        .flash-success { background: #f0fdf4; border-left: 4px solid #22c55e; color: #15803d; }
        .flash-error   { background: #fef2f2; border-left: 4px solid #ef4444; color: #b91c1c; }
    </style>
</head>

<body class="bg-gray-50">

    <!-- ============================================================
         SIDEBAR
         ============================================================ -->
    <aside id="sidebar" class="sidebar fixed left-0 top-0 h-screen z-50 overflow-y-auto flex flex-col">

        <!-- Logo -->
        <div class="p-6 border-b sidebar-divider flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="sidebar-logo-icon w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-graduation-cap text-white text-lg"></i>
                </div>
                <div class="sidebar-logo-text">
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">EduZone</h2>
                    <p class="text-xs text-gray-500"><?= esc($roleName) ?></p>
                </div>
            </div>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-b sidebar-divider flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="sidebar-avatar w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                    <?= strtoupper(substr($username, 0, 1)) ?>
                </div>
                <div class="sidebar-text overflow-hidden">
                    <p class="font-bold text-gray-900 text-sm truncate"><?= esc($username) ?></p>
                    <div class="flex items-center space-x-1 mt-0.5">
                        <i class="fas <?= esc($roleIcon) ?> text-xs" style="color: var(--color-primary)"></i>
                        <p class="text-xs text-gray-500 truncate"><?= esc($roleName) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <?= $this->renderSection('sidebar_menu') ?>
        </nav>

        <!-- Logout -->
        <div class="p-3 border-t sidebar-divider flex-shrink-0">
            <a href="<?= base_url('auth/logout') ?>"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all">
                <i class="fas fa-sign-out-alt text-base w-5 flex-shrink-0"></i>
                <span class="sidebar-text font-semibold text-sm">Logout</span>
            </a>
        </div>
    </aside>

    <!-- ============================================================
         MAIN CONTENT
         ============================================================ -->
    <div id="main-content">

        <!-- Top Header -->
        <header class="top-header sticky top-0 z-40 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Kiri: Toggle + Judul -->
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()"
                            class="w-10 h-10 rounded-xl hover:bg-gray-100 transition-all flex items-center justify-center">
                        <i class="fas fa-bars text-gray-500 text-lg"></i>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900"><?= esc($title ?? 'Dashboard') ?></h1>
                        <p class="text-xs text-gray-400"><?= esc($subtitle ?? '') ?></p>
                    </div>
                </div>

                <!-- Kanan: Notif + Waktu + Role Badge -->
                <div class="flex items-center space-x-3">
                    <div class="hidden md:block text-right">
                        <p class="text-xs font-semibold text-gray-700" id="current-date"></p>
                        <p class="text-xs text-gray-400" id="current-time"></p>
                    </div>

                    <button class="relative w-10 h-10 rounded-xl hover:bg-gray-100 transition-all flex items-center justify-center">
                        <i class="fas fa-bell text-gray-500"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="badge-role px-3 py-1.5 rounded-xl text-xs font-bold flex items-center space-x-1.5">
                        <i class="fas <?= esc($roleIcon) ?>"></i>
                        <span class="sidebar-text"><?= esc($roleName) ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash-success p-4 rounded-xl mb-4 flex items-center space-x-3">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-sm font-semibold"><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash-error p-4 rounded-xl mb-4 flex items-center space-x-3">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span class="text-sm font-semibold"><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Page Content -->
        <main class="p-6">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-100 py-4 px-6 mt-4">
            <div class="flex items-center justify-between text-xs text-gray-400">
                <p>© <?= date('Y') ?> EduZone. All rights reserved.</p>
                <p>Version 1.0.0</p>
            </div>
        </footer>
    </div>

    <!-- ============================================================
         SCRIPTS
         ============================================================ -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 600, once: true, offset: 50 });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar     = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Simpan state ke localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Restore sidebar state
        document.addEventListener('DOMContentLoaded', () => {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('main-content').classList.add('expanded');
            }
        });

        // Date & Time
        function updateDateTime() {
            const now     = new Date();
            const dateEl  = document.getElementById('current-date');
            const timeEl  = document.getElementById('current-time');
            if (!dateEl || !timeEl) return;

            dateEl.textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
            timeEl.textContent = now.toLocaleTimeString('id-ID');
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>