<!DOCTYPE html>
<html lang="id" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduZone — Platform Manajemen Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #111118;
            --bg-card: #16161e;
            --bg-card-hover: #1c1c28;
            --border: rgba(255,255,255,0.08);
            --border-hover: rgba(255,255,255,0.18);
            --text-primary: #f0f0ff;
            --text-secondary: #8888aa;
            --text-muted: #55556a;
            --accent: #6c63ff;
            --accent-glow: rgba(108,99,255,0.3);
            --accent-2: #ff6584;
            --accent-3: #43e97b;
            --nav-bg: rgba(10,10,15,0.85);
            --card-shadow: 0 4px 40px rgba(0,0,0,0.4);
        }
        [data-theme="light"] {
            --bg-primary: #f7f7fc;
            --bg-secondary: #ededf8;
            --bg-card: #ffffff;
            --bg-card-hover: #f0f0fc;
            --border: rgba(0,0,0,0.07);
            --border-hover: rgba(108,99,255,0.3);
            --text-primary: #0f0f1a;
            --text-secondary: #5a5a7a;
            --text-muted: #9898b0;
            --accent: #5b54e8;
            --accent-glow: rgba(91,84,232,0.18);
            --accent-2: #e84393;
            --accent-3: #1db954;
            --nav-bg: rgba(247,247,252,0.88);
            --card-shadow: 0 4px 32px rgba(91,84,232,0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: background 0.4s ease, color 0.3s ease;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Noise texture overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 2px; }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            background: var(--nav-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .nav-link {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.02em;
            transition: color 0.2s;
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent);
            transition: width 0.3s ease;
        }
        .nav-link:hover { color: var(--text-primary); }
        .nav-link:hover::after { width: 100%; }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            font-family:'Outfit',sans-serif;
            letter-spacing: 0.01em;
            transition: all 0.25s ease;
            box-shadow: 0 0 0 0 var(--accent-glow);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px var(--accent-glow);
            background: #7b74ff;
        }

        .btn-ghost {
            color: var(--text-primary);
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            font-family:'Outfit',sans-serif;
            transition: all 0.25s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-ghost:hover {
            background: var(--bg-card-hover);
            border-color: var(--border-hover);
        }

        /* Theme toggle */
        .theme-toggle {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--text-secondary);
            font-size: 0.95rem;
        }
        .theme-toggle:hover {
            background: var(--bg-card-hover);
            border-color: var(--border-hover);
            color: var(--text-primary);
        }

        /* Hero */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-grid-bg {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 30%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 30%, transparent 100%);
        }

        .hero-glow {
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            animation: pulse-glow 4s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 0.35rem 0.9rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        .badge-pill .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--accent-3);
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .hero-title {
            font-size: clamp(3rem, 6vw, 5.5rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.03em;
            color: var(--text-primary);
        }
        .hero-title .accent-text {
            background: linear-gradient(135deg, var(--accent) 0%, #a78bfa 60%, var(--accent-2) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            border-color: var(--border-hover);
            background: var(--bg-card-hover);
            transform: translateY(-2px);
        }
        .stat-number {
            font-family:'Outfit',sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
        }
        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Floating UI card */
        .hero-visual {
            position: relative;
        }
        .ui-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: border-color 0.3s;
        }
        .ui-card-floating {
            position: absolute;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.9rem 1.2rem;
            box-shadow: var(--card-shadow);
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Section styles */
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 1rem;
        }
        .section-label::before {
            content: '';
            width: 20px;
            height: 1px;
            background: var(--accent);
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.1;
            color: var(--text-primary);
        }

        /* Feature cards */
        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: default;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .feature-card:hover {
            border-color: var(--border-hover);
            background: var(--bg-card-hover);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(108,99,255,0.1);
        }
        .feature-card:hover::before { opacity: 1; }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.1rem;
        }

        /* Testimonial */
        .testimonial-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
        }
        .testimonial-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-3px);
            box-shadow: var(--card-shadow);
        }

        /* Pricing */
        .pricing-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            transition: all 0.35s ease;
            position: relative;
        }
        .pricing-card.popular {
            background: var(--accent);
            border-color: var(--accent);
        }
        .pricing-card:not(.popular):hover {
            border-color: var(--accent);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px var(--accent-glow);
        }

        /* Divider line with gradient */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 0;
        }

        /* Marquee */
        .marquee-wrapper {
            overflow: hidden;
            position: relative;
        }
        .marquee-track {
            display: flex;
            gap: 2rem;
            animation: marquee 25s linear infinite;
            width: max-content;
        }
        .marquee-wrapper:hover .marquee-track { animation-play-state: paused; }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .marquee-item {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 0.6rem 1.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 500;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Newsletter */
        .newsletter-input {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.85rem 1.2rem;
            color: var(--text-primary);
            font-size: 0.9rem;
            font-family:'Plus Jakarta Sans',sans-serif;
            width: 100%;
            transition: border-color 0.2s;
            outline: none;
        }
        .newsletter-input:focus { border-color: var(--accent); }
        .newsletter-input::placeholder { color: var(--text-muted); }

        /* Footer */
        footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
        }

        /* Scroll animations */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* Mobile menu */
        #mobile-menu {
            background: var(--nav-bg);
            border-top: 1px solid var(--border);
        }

        /* Avatar group */
        .avatar-group { display: flex; }
        .avatar-group .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            border: 2px solid var(--bg-primary);
            margin-left: -8px;
            font-size: 0.7rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .avatar-group .avatar:first-child { margin-left: 0; }

        /* Glow dot decorations */
        .glow-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 12px var(--accent);
        }

        /* Progress bars */
        .progress-bar {
            height: 4px;
            border-radius: 2px;
            background: var(--border);
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 2px;
            background: linear-gradient(90deg, var(--accent), #a78bfa);
            animation: fill 2s ease forwards;
        }
        @keyframes fill {
            from { width: 0; }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav>
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2.5" style="text-decoration:none;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--accent);">
                        <i class="fas fa-graduation-cap text-white text-sm"></i>
                    </div>
                    <span style="font-family:'Outfit',sans-serif; font-weight:800; font-size:1.1rem; color:var(--text-primary); letter-spacing:-0.02em;">EduZone</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-7">
                    <a href="#home" class="nav-link">Home</a>
                    <a href="#services" class="nav-link">Layanan</a>
                    <a href="#about" class="nav-link">Tentang</a>
                    <a href="#pricing" class="nav-link">Harga</a>
                    <a href="#newsletter" class="nav-link">Newsletter</a>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" id="themeToggle" title="Toggle dark/light mode">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                    <a href="<?= base_url('login') ?>" class="btn-ghost" style="text-decoration:none;">
                        Masuk <i class="fas fa-arrow-right" style="font-size:0.75rem;"></i>
                    </a>
                    <a href="<?= base_url('register') ?>" class="btn-primary" style="text-decoration:none;">
                        Mulai Gratis
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center gap-2">
                    <button class="theme-toggle" id="themeToggleMobile">
                        <i class="fas fa-sun" id="themeIconMobile"></i>
                    </button>
                    <button id="mobile-menu-btn" class="theme-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="px-5 py-4 flex flex-col gap-3">
                <a href="#home" class="nav-link py-1.5" style="font-size:0.9rem;">Home</a>
                <a href="#services" class="nav-link py-1.5" style="font-size:0.9rem;">Layanan</a>
                <a href="#about" class="nav-link py-1.5" style="font-size:0.9rem;">Tentang</a>
                <a href="#pricing" class="nav-link py-1.5" style="font-size:0.9rem;">Harga</a>
                <div class="flex gap-2 pt-2">
                    <a href="<?= base_url('login') ?>" class="btn-ghost flex-1 justify-center" style="text-decoration:none;">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="btn-primary flex-1 justify-center" style="text-decoration:none;">Mulai Gratis</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===================== HERO ===================== -->
    <section id="home" class="hero-section">
        <div class="hero-grid-bg"></div>
        <div class="hero-glow"></div>

        <div class="max-w-7xl mx-auto px-5 lg:px-8 w-full py-16 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Left -->
                <div class="reveal">
                    <div class="badge-pill">
                        <span class="dot"></span>
                        Platform #1 Manajemen Sekolah Digital
                    </div>
                    <h1 class="hero-title mb-5">
                        Kelola Sekolah<br>
                        <span class="accent-text">Lebih Cerdas,</span><br>
                        Lebih Mudah
                    </h1>
                    <p style="color:var(--text-secondary); font-size:1.05rem; line-height:1.7; max-width:460px; margin-bottom:2.5rem;">
                        Platform terpadu untuk guru, siswa, orang tua, dan admin sekolah. Dari absensi digital hingga analitik performa — semua dalam satu sistem yang elegan.
                    </p>

                    <div class="flex flex-wrap gap-3 mb-10">
                        <a href="<?= base_url('register') ?>" class="btn-primary" style="padding:0.85rem 2rem; font-size:0.95rem; text-decoration:none;">
                            Coba Gratis 14 Hari
                            <i class="fas fa-arrow-right" style="font-size:0.8rem;"></i>
                        </a>
                        <a href="#services" class="btn-ghost" style="padding:0.85rem 2rem; font-size:0.95rem; text-decoration:none;">
                            <i class="fas fa-play-circle" style="color:var(--accent);"></i>
                            Lihat Demo
                        </a>
                    </div>

                    <!-- Social proof -->
                    <div class="flex items-center gap-4">
                        <div class="avatar-group">
                            <div class="avatar" style="background:#6c63ff;">RK</div>
                            <div class="avatar" style="background:#ff6584;">AS</div>
                            <div class="avatar" style="background:#43e97b; color:#0f0f1a;">DM</div>
                            <div class="avatar" style="background:#f7a440;">JT</div>
                        </div>
                        <div>
                            <div style="font-family:'Outfit',sans-serif; font-weight:700; font-size:0.9rem; color:var(--text-primary);">50,000+ pengguna aktif</div>
                            <div style="font-size:0.78rem; color:var(--text-muted);">dari 500+ sekolah di Indonesia</div>
                        </div>
                    </div>
                </div>

                <!-- Right — UI Mockup -->
                <div class="hero-visual hidden lg:block reveal reveal-delay-2">
                    <div style="position:relative; padding: 2rem 1rem;">

                        <!-- Main dashboard card -->
                        <div class="ui-card" style="max-width:440px; margin:auto;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                                <div>
                                    <div style="font-family:'Outfit',sans-serif; font-weight:700; color:var(--text-primary);">Dashboard Sekolah</div>
                                    <div style="font-size:0.78rem; color:var(--text-muted); margin-top:2px;">Senin, 1 Mar 2026</div>
                                </div>
                                <div class="glow-dot"></div>
                            </div>

                            <!-- Stats row -->
                            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; margin-bottom:1.5rem;">
                                <div style="background:var(--bg-secondary); border-radius:12px; padding:0.9rem; text-align:center;">
                                    <div style="font-family:'Outfit',sans-serif; font-size:1.4rem; font-weight:800; color:var(--accent);">96%</div>
                                    <div style="font-size:0.7rem; color:var(--text-muted); margin-top:2px;">Hadir</div>
                                </div>
                                <div style="background:var(--bg-secondary); border-radius:12px; padding:0.9rem; text-align:center;">
                                    <div style="font-family:'Outfit',sans-serif; font-size:1.4rem; font-weight:800; color:var(--accent-3);">142</div>
                                    <div style="font-size:0.7rem; color:var(--text-muted); margin-top:2px;">Siswa Aktif</div>
                                </div>
                                <div style="background:var(--bg-secondary); border-radius:12px; padding:0.9rem; text-align:center;">
                                    <div style="font-family:'Outfit',sans-serif; font-size:1.4rem; font-weight:800; color:var(--accent-2);">18</div>
                                    <div style="font-size:0.7rem; color:var(--text-muted); margin-top:2px;">Guru</div>
                                </div>
                            </div>

                            <!-- Progress bars -->
                            <div style="display:flex; flex-direction:column; gap:1rem;">
                                <div>
                                    <div style="display:flex; justify-content:space-between; margin-bottom:0.4rem;">
                                        <span style="font-size:0.78rem; color:var(--text-secondary);">Matematika</span>
                                        <span style="font-size:0.78rem; color:var(--accent); font-weight:600;">87%</span>
                                    </div>
                                    <div class="progress-bar"><div class="progress-fill" style="width:87%;"></div></div>
                                </div>
                                <div>
                                    <div style="display:flex; justify-content:space-between; margin-bottom:0.4rem;">
                                        <span style="font-size:0.78rem; color:var(--text-secondary);">Bahasa Indonesia</span>
                                        <span style="font-size:0.78rem; color:var(--accent); font-weight:600;">92%</span>
                                    </div>
                                    <div class="progress-bar"><div class="progress-fill" style="width:92%;"></div></div>
                                </div>
                                <div>
                                    <div style="display:flex; justify-content:space-between; margin-bottom:0.4rem;">
                                        <span style="font-size:0.78rem; color:var(--text-secondary);">IPA</span>
                                        <span style="font-size:0.78rem; color:var(--accent); font-weight:600;">78%</span>
                                    </div>
                                    <div class="progress-bar"><div class="progress-fill" style="width:78%;"></div></div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating notification -->
                        <div class="ui-card-floating" style="top:-20px; right:-20px; color:var(--text-primary);">
                            <div style="display:flex; align-items:center; gap:0.6rem;">
                                <div style="width:32px; height:32px; background:var(--accent); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.85rem; color:white; flex-shrink:0;"><i class="fas fa-bell"></i></div>
                                <div>
                                    <div style="font-size:0.78rem; font-weight:600;">Jadwal Diperbarui</div>
                                    <div style="font-size:0.7rem; color:var(--text-muted);">2 menit lalu</div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating online badge -->
                        <div class="ui-card-floating" style="bottom:-15px; left:10px; color:var(--text-primary);">
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <div style="width:8px; height:8px; border-radius:50%; background:var(--accent-3); box-shadow: 0 0 8px var(--accent-3);"></div>
                                <span style="font-size:0.78rem; font-weight:600;">Sinkronisasi Real-time</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-20 reveal reveal-delay-2">
                <div class="stat-card">
                    <div class="stat-number">500<span style="color:var(--accent);">+</span></div>
                    <div class="stat-label">Sekolah Terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50K<span style="color:var(--accent);">+</span></div>
                    <div class="stat-label">Pengguna Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">4.8<span style="color:var(--accent);">★</span></div>
                    <div class="stat-label">Rating Pengguna</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">99<span style="color:var(--accent);">%</span></div>
                    <div class="stat-label">Uptime Terjamin</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Marquee Bar -->
    <div style="background:var(--bg-secondary); border-top:1px solid var(--border); border-bottom:1px solid var(--border); padding:1rem 0; overflow:hidden;">
        <div class="marquee-wrapper">
            <div class="marquee-track">
                <?php $items = ['Absensi Digital','Rapor Online','Manajemen Kelas','Analitik Real-time','Notifikasi Orang Tua','Jadwal Otomatis','BK Terintegrasi','E-Learning','Pembayaran SPP','Multi Role Access','Data Aman & Terenkripsi','Laporan Cepat']; foreach(array_merge($items,$items,$items) as $item): ?>
                <div class="marquee-item">
                    <i class="fas fa-check" style="color:var(--accent); font-size:0.7rem;"></i>
                    <?= $item ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ===================== SERVICES ===================== -->
    <section id="services" style="padding:7rem 0; position:relative;">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="text-center mb-16 reveal">
                <div class="section-label">Fitur Platform</div>
                <h2 class="section-title mb-4">Semua yang dibutuhkan<br><span style="color:var(--text-secondary); font-weight:400;">sekolah modern</span></h2>
                <p style="color:var(--text-secondary); max-width:480px; margin:auto; font-size:0.95rem; line-height:1.7;">Dirancang bersama ratusan kepala sekolah, guru, dan administrator untuk menciptakan solusi yang benar-benar efektif</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="feature-card reveal reveal-delay-1">
                    <div class="icon-box" style="background: rgba(108,99,255,0.12);">
                        <i class="fas fa-mobile-alt" style="color:var(--accent);"></i>
                    </div>
                    <h3 style="font-family:'Outfit',sans-serif; font-weight:700; font-size:1.05rem; margin-bottom:0.6rem; color:var(--text-primary);">App Mobile</h3>
                    <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6;">Akses penuh dari iOS dan Android. Notifikasi push real-time untuk semua stakeholder.</p>
                    <div style="margin-top:1.25rem; display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:var(--accent); font-weight:600;">
                        Pelajari <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i>
                    </div>
                </div>

                <div class="feature-card reveal reveal-delay-2">
                    <div class="icon-box" style="background: rgba(255,101,132,0.12);">
                        <i class="fas fa-rocket" style="color:var(--accent-2);"></i>
                    </div>
                    <h3 style="font-family:'Outfit',sans-serif; font-weight:700; font-size:1.05rem; margin-bottom:0.6rem; color:var(--text-primary);">Performa Tinggi</h3>
                    <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6;">Infrastruktur cloud terdistribusi. Load balancing otomatis untuk ribuan pengguna simultan.</p>
                    <div style="margin-top:1.25rem; display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:var(--accent-2); font-weight:600;">
                        Pelajari <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i>
                    </div>
                </div>

                <div class="feature-card reveal reveal-delay-3">
                    <div class="icon-box" style="background: rgba(67,233,123,0.12);">
                        <i class="fas fa-sitemap" style="color:var(--accent-3);"></i>
                    </div>
                    <h3 style="font-family:'Outfit',sans-serif; font-weight:700; font-size:1.05rem; margin-bottom:0.6rem; color:var(--text-primary);">Multi Role & Alur</h3>
                    <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6;">Hak akses granular untuk admin, guru, siswa, orang tua, BK, dan toolman.</p>
                    <div style="margin-top:1.25rem; display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:var(--accent-3); font-weight:600;">
                        Pelajari <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i>
                    </div>
                </div>

                <div class="feature-card reveal reveal-delay-4">
                    <div class="icon-box" style="background: rgba(247,164,64,0.12);">
                        <i class="fas fa-headset" style="color:#f7a440;"></i>
                    </div>
                    <h3 style="font-family:'Outfit',sans-serif; font-weight:700; font-size:1.05rem; margin-bottom:0.6rem; color:var(--text-primary);">Support 24/7</h3>
                    <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6;">Tim dedikasi siap membantu via live chat, email, dan telepon kapanpun dibutuhkan.</p>
                    <div style="margin-top:1.25rem; display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:#f7a440; font-weight:600;">
                        Pelajari <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i>
                    </div>
                </div>
            </div>

            <!-- Second row — 3 bigger cards -->
            <div class="grid md:grid-cols-3 gap-5 mt-5">
                <div class="feature-card reveal" style="border-color:rgba(108,99,255,0.2); background: linear-gradient(135deg, var(--bg-card) 0%, rgba(108,99,255,0.05) 100%);">
                    <div class="icon-box" style="background:rgba(108,99,255,0.15); width:52px; height:52px;">
                        <i class="fas fa-shield-alt" style="color:var(--accent); font-size:1.2rem;"></i>
                    </div>
                    <h3 style="font-family:'Outfit',sans-serif; font-weight:700; font-size:1.15rem; margin-bottom:0.6rem; color:var(--text-primary);">Keamanan Enterprise</h3>
                    <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.7;">AES-256 encryption, 2FA authentication, audit log lengkap, dan backup otomatis setiap jam untuk keamanan data sekolah Anda.</p>
                </div>

                <div class="feature-card reveal reveal-delay-2" style="border-color:rgba(67,233,123,0.2); background: linear-gradient(135deg, var(--bg-card) 0%, rgba(67,233,123,0.04) 100%);">
                    <div class="icon-box" style="background:rgba(67,233,123,0.12); width:52px; height:52px;">
                        <i class="fas fa-chart-bar" style="color:var(--accent-3); font-size:1.2rem;"></i>
                    </div>
                    <h3 style="font-family:'Outfit',sans-serif; font-weight:700; font-size:1.15rem; margin-bottom:0.6rem; color:var(--text-primary);">Analitik & Laporan</h3>
                    <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.7;">Dashboard interaktif, laporan PDF satu klik, dan insight AI untuk membantu pengambilan keputusan strategis kepala sekolah.</p>
                </div>

                <div class="feature-card reveal reveal-delay-3" style="border-color:rgba(255,101,132,0.2); background: linear-gradient(135deg, var(--bg-card) 0%, rgba(255,101,132,0.04) 100%);">
                    <div class="icon-box" style="background:rgba(255,101,132,0.12); width:52px; height:52px;">
                        <i class="fas fa-plug" style="color:var(--accent-2); font-size:1.2rem;"></i>
                    </div>
                    <h3 style="font-family:'Outfit',sans-serif; font-weight:700; font-size:1.15rem; margin-bottom:0.6rem; color:var(--text-primary);">Integrasi Mudah</h3>
                    <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.7;">Terhubung dengan Dapodik, Google Workspace, WhatsApp API, dan sistem pembayaran populer di Indonesia.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- ===================== ABOUT ===================== -->
    <section id="about" style="padding:7rem 0;">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <!-- Left -->
                <div class="reveal">
                    <div class="section-label">Tentang EduZone</div>
                    <h2 class="section-title mb-5">Mengapa ribuan sekolah memilih kami?</h2>
                    <p style="color:var(--text-secondary); font-size:0.95rem; line-height:1.75; margin-bottom:2rem;">
                        EduZone dibangun dengan satu misi: menyederhanakan kompleksitas administrasi sekolah agar guru bisa fokus mengajar dan kepala sekolah bisa fokus memimpin.
                    </p>

                    <div style="display:flex; flex-direction:column; gap:1rem;">
                        <?php
                        $points = [
                            ['icon'=>'fa-check','color'=>'var(--accent-3)','title'=>'Implementasi dalam 1 hari','desc'=>'Onboarding cepat dengan panduan migrasi data lengkap'],
                            ['icon'=>'fa-check','color'=>'var(--accent-3)','title'=>'Tidak perlu pelatihan teknis','desc'=>'Interface intuitif yang bisa langsung digunakan oleh semua staf'],
                            ['icon'=>'fa-check','color'=>'var(--accent-3)','title'=>'Kustomisasi penuh','desc'=>'Sesuaikan alur kerja, template, dan laporan dengan kebutuhan sekolah'],
                        ];
                        foreach($points as $p): ?>
                        <div style="display:flex; gap:0.9rem; align-items:flex-start;">
                            <div style="width:24px; height:24px; border-radius:50%; background:rgba(67,233,123,0.15); display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                                <i class="fas <?= $p['icon'] ?>" style="color:<?= $p['color'] ?>; font-size:0.65rem;"></i>
                            </div>
                            <div>
                                <div style="font-family:'Outfit',sans-serif; font-weight:600; font-size:0.9rem; color:var(--text-primary); margin-bottom:2px;"><?= $p['title'] ?></div>
                                <div style="font-size:0.83rem; color:var(--text-secondary);"><?= $p['desc'] ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <div style="text-align:center; padding:1rem 1.5rem; background:var(--bg-card); border:1px solid var(--border); border-radius:14px;">
                            <div style="font-family:'Outfit',sans-serif; font-size:1.8rem; font-weight:800; color:var(--text-primary);">98%</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Kepuasan</div>
                        </div>
                        <div style="text-align:center; padding:1rem 1.5rem; background:var(--bg-card); border:1px solid var(--border); border-radius:14px;">
                            <div style="font-family:'Outfit',sans-serif; font-size:1.8rem; font-weight:800; color:var(--text-primary);">5+</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Tahun Berpengalaman</div>
                        </div>
                        <div style="text-align:center; padding:1rem 1.5rem; background:var(--bg-card); border:1px solid var(--border); border-radius:14px;">
                            <div style="font-family:'Outfit',sans-serif; font-size:1.8rem; font-weight:800; color:var(--text-primary);">24/7</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Support Aktif</div>
                        </div>
                    </div>
                </div>

                <!-- Right — testimonial-style quote card -->
                <div class="reveal reveal-delay-2">
                    <div class="ui-card" style="margin-bottom:1.25rem;">
                        <div style="font-size:2.5rem; line-height:1; color:var(--accent); font-family:'Outfit',sans-serif; margin-bottom:0.75rem; opacity:0.5;">"</div>
                        <p style="color:var(--text-primary); font-size:1rem; line-height:1.75; font-style:italic; margin-bottom:1.5rem;">
                            Sejak menggunakan EduZone, waktu yang kami habiskan untuk administrasi berkurang 70%. Laporan yang dulu butuh 2 hari kini selesai dalam 5 menit. Tim guru lebih fokus, orang tua lebih terlibat.
                        </p>
                        <div style="display:flex; align-items:center; gap:0.9rem;">
                            <div style="width:42px; height:42px; border-radius:50%; background:var(--accent); display:flex; align-items:center; justify-content:center; font-family:'Outfit',sans-serif; font-weight:800; color:white; font-size:0.9rem;">F</div>
                            <div>
                                <div style="font-family:'Outfit',sans-serif; font-weight:700; font-size:0.9rem; color:var(--text-primary);">Fawa</div>
                                <div style="font-size:0.78rem; color:var(--text-muted);">CEO, EduZone</div>
                            </div>
                            <div style="margin-left:auto; display:flex; gap:2px;">
                                <?php for($i=0;$i<5;$i++): ?><i class="fas fa-star" style="color:#f7a440; font-size:0.75rem;"></i><?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quick metrics -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div class="ui-card">
                            <div style="font-family:'Outfit',sans-serif; font-size:1.6rem; font-weight:800; color:var(--accent-3);">↓70%</div>
                            <div style="font-size:0.78rem; color:var(--text-secondary); margin-top:4px;">Pengurangan waktu administrasi</div>
                        </div>
                        <div class="ui-card">
                            <div style="font-family:'Outfit',sans-serif; font-size:1.6rem; font-weight:800; color:var(--accent);">↑3.2×</div>
                            <div style="font-size:0.78rem; color:var(--text-secondary); margin-top:4px;">Keterlibatan orang tua</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- ===================== TESTIMONIALS ===================== -->
    <section id="testimonial" style="padding:7rem 0; background:var(--bg-secondary);">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="text-center mb-14 reveal">
                <div class="section-label">Testimoni</div>
                <h2 class="section-title mb-3">Apa kata mereka?</h2>
                <p style="color:var(--text-secondary); font-size:0.9rem;">Pengguna nyata, hasil nyata</p>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                <?php
                $testimonials = [
                    ['init'=>'DM','name'=>'David Martino','role'=>'Kepala Sekolah SMA N 1','color'=>'#6c63ff','text'=>'Interface yang intuitif membuat seluruh guru langsung bisa menggunakan tanpa pelatihan panjang. Sistem absensi digitalnya luar biasa akurat.','delay'=>'reveal-delay-1'],
                    ['init'=>'JT','name'=>'Joko Tomonyo','role'=>'Koordinator IT Sekolah','color'=>'#43e97b','text'=>'Integrasi dengan Dapodik berjalan mulus. Tim developer-nya responsif dan update fitur terus mengalir. Platform terbaik yang pernah kami gunakan.','delay'=>'reveal-delay-2'],
                    ['init'=>'AS','name'=>'Muhammad Alif S.','role'=>'Wali Murid & Pengusaha','color'=>'#ff6584','text'=>'Sebagai orang tua, saya bisa pantau perkembangan anak secara real-time. Notifikasi langsung ke HP saya kalau ada pengumuman penting.','delay'=>'reveal-delay-3'],
                ];
                foreach($testimonials as $t): ?>
                <div class="testimonial-card reveal <?= $t['delay'] ?>">
                    <div style="display:flex; gap:1px; margin-bottom:1.25rem;">
                        <?php for($i=0;$i<5;$i++): ?><i class="fas fa-star" style="color:#f7a440; font-size:0.8rem;"></i><?php endfor; ?>
                    </div>
                    <p style="color:var(--text-secondary); font-size:0.88rem; line-height:1.7; margin-bottom:1.5rem; font-style:italic;">"<?= $t['text'] ?>"</p>
                    <div style="display:flex; align-items:center; gap:0.75rem; padding-top:1.25rem; border-top:1px solid var(--border);">
                        <div style="width:38px; height:38px; border-radius:50%; background:<?= $t['color'] ?>22; border:1px solid <?= $t['color'] ?>44; display:flex; align-items:center; justify-content:center; font-family:'Outfit',sans-serif; font-weight:800; font-size:0.8rem; color:<?= $t['color'] ?>;">
                            <?= $t['init'] ?>
                        </div>
                        <div>
                            <div style="font-family:'Outfit',sans-serif; font-weight:700; font-size:0.88rem; color:var(--text-primary);"><?= $t['name'] ?></div>
                            <div style="font-size:0.75rem; color:var(--text-muted);"><?= $t['role'] ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- ===================== PRICING ===================== -->
    <section id="pricing" style="padding:7rem 0;">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="text-center mb-14 reveal">
                <div class="section-label">Harga</div>
                <h2 class="section-title mb-3">Transparan, tanpa biaya tersembunyi</h2>
                <p style="color:var(--text-secondary); font-size:0.9rem; max-width:400px; margin:auto;">Mulai gratis, upgrade kapanpun sesuai kebutuhan sekolah Anda</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <!-- Starter -->
                <div class="pricing-card reveal reveal-delay-1">
                    <div style="font-family:'Outfit',sans-serif; font-size:0.78rem; font-weight:600; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-muted); margin-bottom:1rem;">Starter</div>
                    <div style="display:flex; align-items:baseline; gap:0.3rem; margin-bottom:0.5rem;">
                        <span style="font-family:'Outfit',sans-serif; font-size:3rem; font-weight:800; color:var(--text-primary);">$12</span>
                        <span style="color:var(--text-muted); font-size:0.85rem;">/bulan</span>
                    </div>
                    <p style="font-size:0.83rem; color:var(--text-secondary); margin-bottom:2rem; line-height:1.6;">Untuk sekolah kecil hingga 500 siswa</p>
                    <div style="display:flex; flex-direction:column; gap:0.85rem; margin-bottom:2.5rem;">
                        <?php foreach(['50 GB Storage','Maks 500 Siswa','Laporan Dasar','Support Email','Update Gratis'] as $f): ?>
                        <div style="display:flex; gap:0.7rem; align-items:center; font-size:0.85rem; color:var(--text-secondary);">
                            <i class="fas fa-check" style="color:var(--accent-3); font-size:0.7rem; flex-shrink:0;"></i> <?= $f ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= base_url('register') ?>" class="btn-ghost" style="width:100%; justify-content:center; text-decoration:none; padding:0.85rem;">Mulai Gratis</a>
                </div>

                <!-- Business (popular) -->
                <div class="pricing-card popular reveal reveal-delay-2" style="transform:scale(1.03);">
                    <div style="position:absolute; top:1.25rem; right:1.25rem; background:rgba(255,255,255,0.2); backdrop-filter:blur(10px); padding:0.3rem 0.8rem; border-radius:100px; font-size:0.7rem; font-weight:700; color:white; letter-spacing:0.05em;">POPULER</div>
                    <div style="font-family:'Outfit',sans-serif; font-size:0.78rem; font-weight:600; text-transform:uppercase; letter-spacing:0.1em; color:rgba(255,255,255,0.6); margin-bottom:1rem;">Business</div>
                    <div style="display:flex; align-items:baseline; gap:0.3rem; margin-bottom:0.5rem;">
                        <span style="font-family:'Outfit',sans-serif; font-size:3rem; font-weight:800; color:white;">$25</span>
                        <span style="color:rgba(255,255,255,0.6); font-size:0.85rem;">/bulan</span>
                    </div>
                    <p style="font-size:0.83rem; color:rgba(255,255,255,0.7); margin-bottom:2rem; line-height:1.6;">Untuk sekolah menengah hingga 2000 siswa</p>
                    <div style="display:flex; flex-direction:column; gap:0.85rem; margin-bottom:2.5rem;">
                        <?php foreach(['100 GB Storage','Unlimited Siswa','Laporan Advanced','Support Prioritas 24/7','Integrasi Dapodik','Analitik AI'] as $f): ?>
                        <div style="display:flex; gap:0.7rem; align-items:center; font-size:0.85rem; color:rgba(255,255,255,0.85);">
                            <i class="fas fa-check" style="color:rgba(255,255,255,0.9); font-size:0.7rem; flex-shrink:0;"></i> <?= $f ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="btn-primary" style="width:100%; justify-content:center; background:white; color:var(--accent); padding:0.85rem;">Mulai Sekarang</button>
                </div>

                <!-- Enterprise -->
                <div class="pricing-card reveal reveal-delay-3">
                    <div style="font-family:'Outfit',sans-serif; font-size:0.78rem; font-weight:600; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-muted); margin-bottom:1rem;">Enterprise</div>
                    <div style="display:flex; align-items:baseline; gap:0.3rem; margin-bottom:0.5rem;">
                        <span style="font-family:'Outfit',sans-serif; font-size:3rem; font-weight:800; color:var(--text-primary);">$94</span>
                        <span style="color:var(--text-muted); font-size:0.85rem;">/bulan</span>
                    </div>
                    <p style="font-size:0.83rem; color:var(--text-secondary); margin-bottom:2rem; line-height:1.6;">Untuk yayasan dan multi-sekolah</p>
                    <div style="display:flex; flex-direction:column; gap:0.85rem; margin-bottom:2.5rem;">
                        <?php foreach(['Unlimited Storage','Multi-Sekolah','White Label','Dedicated Server','Custom Integrasi','SLA 99.9% Uptime'] as $f): ?>
                        <div style="display:flex; gap:0.7rem; align-items:center; font-size:0.85rem; color:var(--text-secondary);">
                            <i class="fas fa-check" style="color:var(--accent); font-size:0.7rem; flex-shrink:0;"></i> <?= $f ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="mailto:info@eduzone.com" class="btn-ghost" style="width:100%; justify-content:center; text-decoration:none; padding:0.85rem;">Hubungi Sales</a>
                </div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- ===================== NEWSLETTER ===================== -->
    <section id="newsletter" style="padding:6rem 0; background:var(--bg-secondary);">
        <div class="max-w-2xl mx-auto px-5 text-center">
            <div class="reveal">
                <div class="section-label" style="justify-content:center;">Newsletter</div>
                <h2 class="section-title mb-4">Tetap update dengan<br>fitur terbaru EduZone</h2>
                <p style="color:var(--text-secondary); font-size:0.9rem; margin-bottom:2.5rem; line-height:1.7;">Tips manajemen sekolah, update produk, dan insight industri pendidikan — dikirim tiap 2 minggu. Tidak ada spam, berhenti kapanpun.</p>
            </div>

            <form action="<?= base_url('newsletter/subscribe') ?>" method="POST" class="reveal reveal-delay-1" style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                <?= csrf_field() ?>
                <input type="email" name="email" required placeholder="email@sekolahanda.sch.id" class="newsletter-input" style="flex:1; min-width:220px;">
                <button type="submit" class="btn-primary" style="padding:0.85rem 1.8rem; font-size:0.9rem; white-space:nowrap;">
                    Subscribe <i class="fas fa-paper-plane" style="font-size:0.8rem;"></i>
                </button>
            </form>

            <div id="successMessage" class="hidden" style="margin-top:1rem; padding:0.9rem 1.5rem; background:rgba(67,233,123,0.1); border:1px solid rgba(67,233,123,0.3); border-radius:10px; font-size:0.85rem; color:var(--accent-3);">
                <i class="fas fa-check-circle"></i> Terima kasih! Anda akan menerima email konfirmasi segera.
            </div>

            <p style="font-size:0.75rem; color:var(--text-muted); margin-top:1rem;">
                <i class="fas fa-lock" style="margin-right:4px;"></i>
                Data Anda aman bersama kami. Tidak pernah dijual ke pihak ketiga.
            </p>
        </div>
    </section>

    <!-- ===================== FOOTER ===================== -->
    <footer style="padding:4rem 0 2rem;">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="grid md:grid-cols-4 gap-10 mb-10">
                <!-- Brand -->
                <div>
                    <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:1rem;">
                        <div style="width:32px; height:32px; border-radius:8px; background:var(--accent); display:flex; align-items:center; justify-content:center; font-size:0.9rem; color:white;">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span style="font-family:'Outfit',sans-serif; font-weight:800; color:var(--text-primary);">EduZone</span>
                    </div>
                    <p style="font-size:0.83rem; color:var(--text-secondary); line-height:1.7; margin-bottom:1.25rem;">Platform manajemen sekolah digital terpadu untuk Indonesia.</p>
                    <div style="display:flex; gap:0.5rem;">
                        <?php foreach([['fa-twitter','#1da1f2'],['fa-instagram','#e1306c'],['fa-linkedin-in','#0a66c2']] as $s): ?>
                        <a href="#" style="width:34px; height:34px; border-radius:8px; background:var(--bg-card); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; color:var(--text-secondary); font-size:0.8rem; text-decoration:none; transition:all 0.2s;"
                           onmouseover="this.style.borderColor='<?= $s[1] ?>'; this.style.color='<?= $s[1] ?>';"
                           onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)';">
                            <i class="fab <?= $s[0] ?>"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Links -->
                <?php
                $footerLinks = [
                    'Produk' => ['Fitur Lengkap','Integrasi','API Docs','Changelog'],
                    'Perusahaan' => ['Tentang Kami','Karir','Blog','Press Kit'],
                    'Dukungan' => ['Pusat Bantuan','Live Chat','Status Sistem','Kebijakan Privasi'],
                ];
                foreach($footerLinks as $col => $links): ?>
                <div>
                    <div style="font-family:'Outfit',sans-serif; font-weight:700; font-size:0.85rem; color:var(--text-primary); margin-bottom:1rem;"><?= $col ?></div>
                    <div style="display:flex; flex-direction:column; gap:0.6rem;">
                        <?php foreach($links as $l): ?>
                        <a href="#" style="font-size:0.83rem; color:var(--text-secondary); text-decoration:none; transition:color 0.2s;"
                           onmouseover="this.style.color='var(--text-primary)'"
                           onmouseout="this.style.color='var(--text-secondary)'"><?= $l ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="divider" style="margin-bottom:1.5rem;"></div>

            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                <p style="font-size:0.78rem; color:var(--text-muted);">© 2026 EduZone. Dibuat dengan <i class="fas fa-heart" style="color:var(--accent-2); font-size:0.7rem;"></i> di Indonesia.</p>
                <div style="display:flex; gap:1.5rem;">
                    <a href="#" style="font-size:0.78rem; color:var(--text-muted); text-decoration:none;">Privacy Policy</a>
                    <a href="#" style="font-size:0.78rem; color:var(--text-muted); text-decoration:none;">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // ── Dark / Light Mode ──
        const html = document.documentElement;
        const stored = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', stored);

        function updateIcons(theme) {
            const icon = theme === 'dark' ? 'fa-sun' : 'fa-moon';
            document.querySelectorAll('#themeIcon, #themeIconMobile').forEach(el => {
                el.className = `fas ${icon}`;
            });
        }
        updateIcons(stored);

        function toggleTheme() {
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateIcons(next);
        }
        document.getElementById('themeToggle').addEventListener('click', toggleTheme);
        document.getElementById('themeToggleMobile').addEventListener('click', toggleTheme);

        // ── Mobile Menu ──
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        document.querySelectorAll('#mobile-menu a').forEach(a => {
            a.addEventListener('click', () => menu.classList.add('hidden'));
        });

        // ── Scroll Reveal ──
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // ── Navbar scroll shadow ──
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 20) {
                nav.style.boxShadow = '0 4px 30px rgba(0,0,0,0.2)';
            } else {
                nav.style.boxShadow = 'none';
            }
        });

        // ── Newsletter success toast ──
        const form = document.querySelector('#newsletter form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // If you want client-side preview:
                // e.preventDefault();
                document.getElementById('successMessage').classList.remove('hidden');
            });
        }
    </script>
</body>
</html>