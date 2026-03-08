<!DOCTYPE html>
<html lang="id" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — EduZone</title>

    <script>
        (function() {
            var t = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Outfit', 'sans-serif'],
                        body: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ?? CSS Variables (selaras index.php & login.php) ?? */
        :root {
            --bg: #0a0a0f;
            --sur: #111118;
            --card: #16161e;
            --card-h: #1c1c28;
            --br: rgba(255, 255, 255, 0.08);
            --br-h: rgba(255, 255, 255, 0.18);
            --t1: #f0f0ff;
            --t2: #8888aa;
            --t3: #55556a;
            --ac: #6c63ff;
            --ag: rgba(108, 99, 255, 0.25);
            --pk: #ff6584;
            --gn: #43e97b;
            --am: #f7a440;
            --nav: rgba(10, 10, 15, 0.88);
            --sh: 0 4px 40px rgba(0, 0, 0, 0.45);
        }

        [data-theme="light"] {
            --bg: #f7f7fc;
            --sur: #ededf8;
            --card: #ffffff;
            --card-h: #f0f0fc;
            --br: rgba(0, 0, 0, 0.07);
            --br-h: rgba(108, 99, 255, 0.28);
            --t1: #0f0f1a;
            --t2: #5a5a7a;
            --t3: #9898b0;
            --ac: #5b54e8;
            --ag: rgba(91, 84, 232, 0.15);
            --pk: #e84393;
            --gn: #1db954;
            --am: #d97706;
            --nav: rgba(247, 247, 252, 0.9);
            --sh: 0 4px 32px rgba(91, 84, 232, 0.09);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--t1);
            min-height: 100vh;
            transition: background .35s, color .25s;
            overflow-x: hidden;
        }

        /* Noise */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.025'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: .45;
        }

        /* Grid lines */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(var(--br) 1px, transparent 1px), linear-gradient(90deg, var(--br) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse 100% 100% at 50% 0%, black 30%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 100% 100% at 50% 0%, black 30%, transparent 100%);
            pointer-events: none;
            z-index: 0;
        }

        /* ?? Navbar ?? */
        nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--nav);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--br);
            transition: background .35s, border-color .25s;
        }

        /* ?? Role cards ?? */
        .role-card {
            background: var(--card);
            border: 1px solid var(--br);
            border-radius: 18px;
            padding: 1.6rem;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            transition: border-color .3s, transform .3s cubic-bezier(.4, 0, .2, 1), box-shadow .3s;
            position: relative;
            overflow: hidden;
            cursor: default;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--ac), transparent);
            opacity: 0;
            transition: opacity .3s;
        }

        .role-card:hover {
            border-color: var(--br-h);
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.22);
        }

        .role-card:hover::before {
            opacity: 1;
        }

        .role-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            transition: transform .3s, box-shadow .3s;
        }

        .role-card:hover .role-icon-wrap {
            transform: scale(1.08) rotate(4deg);
        }

        /* ?? Btn link inside card ?? */
        .btn-role {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            padding: .7rem 1rem;
            border-radius: 9px;
            font-size: .82rem;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            letter-spacing: .02em;
            text-decoration: none;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s, filter .2s;
        }

        .btn-role::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, .12) 0%, transparent 55%);
        }

        .btn-role:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }

        .btn-role:active {
            transform: translateY(0);
            filter: brightness(0.95);
        }

        /* ?? Info box ?? */
        .info-box {
            background: var(--card);
            border: 1px solid var(--br);
            border-radius: 16px;
            padding: 1.4rem 1.75rem;
            transition: border-color .3s;
        }

        .info-box:hover {
            border-color: var(--br-h);
        }

        /* ?? Badge pill ?? */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .04em;
            padding: .3rem .75rem;
            border-radius: 100px;
            border: 1px solid;
        }

        /* ?? Feature mini card ?? */
        .feat-card {
            background: var(--sur);
            border: 1px solid var(--br);
            border-radius: 14px;
            padding: 1.25rem;
            transition: border-color .25s, transform .25s;
        }

        .feat-card:hover {
            border-color: var(--br-h);
            transform: translateY(-2px);
        }

        /* ?? Avatar initial ?? */
        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: .95rem;
            color: #fff;
            flex-shrink: 0;
        }

        /* ?? Theme toggle ?? */
        .theme-btn {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--card);
            border: 1px solid var(--br);
            color: var(--t2);
            font-size: .82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color .2s, color .2s;
            flex-shrink: 0;
        }

        .theme-btn:hover {
            border-color: var(--br-h);
            color: var(--t1);
        }

        /* ?? Section label ?? */
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ac);
        }

        .section-label::before {
            content: '';
            width: 18px;
            height: 1px;
            background: var(--ac);
        }

        /* ?? Divider ?? */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--br), transparent);
        }

        /* ?? Glow blob ?? */
        .blob {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--ag) 0%, transparent 70%);
            top: 30%;
            left: 60%;
            transform: translate(-50%, -50%);
            animation: pb 7s ease-in-out infinite;
        }

        @keyframes pb {

            0%,
            100% {
                opacity: .4;
                scale: 1
            }

            50% {
                opacity: .7;
                scale: 1.1
            }
        }

        /* ?? Scroll reveal ?? */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .65s ease, transform .65s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .rv1 {
            transition-delay: .05s
        }

        .rv2 {
            transition-delay: .1s
        }

        .rv3 {
            transition-delay: .15s
        }

        .rv4 {
            transition-delay: .2s
        }

        .rv5 {
            transition-delay: .25s
        }

        .rv6 {
            transition-delay: .3s
        }

        .rv7 {
            transition-delay: .35s
        }

        .rv8 {
            transition-delay: .4s
        }

        .rv9 {
            transition-delay: .45s
        }

        /* ?? Dot decoration ?? */
        .dots {
            display: grid;
            grid-template-columns: repeat(6, 6px);
            gap: 5px;
            opacity: .14;
        }

        .dots span {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--ac);
            display: block;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--ac);
            border-radius: 2px;
        }

        /* Navbar logout btn */
        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1rem;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            background: rgba(255, 101, 132, .1);
            border: 1px solid rgba(255, 101, 132, .22);
            color: var(--pk);
            text-decoration: none;
            transition: all .2s;
        }

        .btn-logout:hover {
            background: rgba(255, 101, 132, .18);
            border-color: rgba(255, 101, 132, .4);
        }

        [data-theme="light"] .btn-logout {
            background: #fff0f4;
            border-color: #ffb3c0;
            color: #c01828;
        }

        [data-theme="light"] .btn-logout:hover {
            background: #ffe0e8;
        }
    </style>
</head>

<body>
    <div class="blob blob-1"></div>

    <!-- ???????????????????????????????????
         NAVBAR
    ??????????????????????????????????? -->
    <nav>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <!-- Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2.5 flex-shrink-0" style="text-decoration:none;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:var(--ac);">
                        <i class="fas fa-graduation-cap text-white text-sm"></i>
                    </div>
                    <div class="hidden sm:block">
                        <div class="font-display font-extrabold text-base tracking-tight leading-none" style="color:var(--t1);">EduZone</div>
                        <div class="text-[10px] leading-none mt-0.5" style="color:var(--t3);">Platform Manajemen Sekolah</div>
                    </div>
                </a>

                <!-- Right: user info + theme + logout -->
                <div class="flex items-center gap-3">
                    <!-- User info (desktop) -->
                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-xs font-semibold leading-tight" style="color:var(--t1);"><?= esc($username) ?></span>
                        <span class="text-[10px] uppercase tracking-widest leading-tight mt-0.5" style="color:var(--ac);"><?= esc($role) ?></span>
                    </div>
                    <!-- Avatar -->
                    <div class="avatar" style="background:var(--ac);">
                        <?= strtoupper(substr(esc($username), 0, 1)) ?>
                    </div>
                    <!-- Theme toggle -->
                    <button class="theme-btn" id="themeToggle" title="Ganti tema">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                    <!-- Logout -->
                    <a href="<?= base_url('auth/logout') ?>" class="btn-logout">
                        <i class="fas fa-arrow-right-from-bracket text-xs"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ???????????????????????????????????
         MAIN CONTENT
    ??????????????????????????????????? -->
    <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-20">

        <!-- ?? Welcome header ?? -->
        <div class="mb-10 reveal">
            <!-- Section label -->
            <div class="section-label mb-3">Dashboard Utama</div>

            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h1 class="font-display font-extrabold tracking-tight leading-tight mb-1"
                        style="font-size:clamp(1.7rem,4vw,2.4rem); color:var(--t1);">
                        Halo, <?= esc($username) ?> ?
                    </h1>
                    <p class="text-sm leading-relaxed" style="color:var(--t2); max-width:480px;">
                        Anda login sebagai
                        <span class="font-semibold" style="color:var(--ac);"><?= esc($role) ?></span>.
                        Pilih dashboard role yang ingin Anda akses di bawah ini.
                    </p>
                </div>

                <!-- Access badges -->
                <div class="flex flex-wrap gap-2 flex-shrink-0">
                    <span class="badge" style="background:rgba(108,99,255,.1);border-color:rgba(108,99,255,.25);color:var(--ac);">
                        <i class="fas fa-check text-[9px]"></i> Akses Penuh
                    </span>
                    <span class="badge" style="background:rgba(67,233,123,.08);border-color:rgba(67,233,123,.2);color:var(--gn);">
                        <i class="fas fa-check text-[9px]"></i> Semua Role
                    </span>
                    <span class="badge" style="background:rgba(247,164,64,.08);border-color:rgba(247,164,64,.2);color:var(--am);">
                        <i class="fas fa-check text-[9px]"></i> Super Admin
                    </span>
                </div>
            </div>
        </div>

        <!-- ?? Info box ?? -->
        <div class="info-box mb-10 reveal rv1">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 text-sm"
                    style="background:rgba(108,99,255,.12);color:var(--ac);">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-display font-bold text-sm mb-0.5" style="color:var(--t1);">
                        Akses Super Admin Aktif
                    </div>
                    <p class="text-xs leading-relaxed" style="color:var(--t2);">
                        Sebagai <span class="font-semibold" style="color:var(--ac);">Super Admin</span>, Anda dapat mengakses seluruh dashboard role yang tersedia.
                        Semua aktivitas direkam dalam audit log sistem.
                    </p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:var(--gn);box-shadow:0 0 6px var(--gn);"></span>
                    <span class="text-xs font-semibold" style="color:var(--gn);">Online</span>
                </div>
            </div>
        </div>

        <!-- ?? Role title ?? -->
        <div class="mb-6 reveal rv2">
            <div class="section-label mb-2">Pilih Role</div>
            <h2 class="font-display font-bold tracking-tight" style="font-size:clamp(1.2rem,3vw,1.6rem); color:var(--t1);">
                Dashboard yang Tersedia
            </h2>
        </div>

        <!-- ??????????????????????????????
             ROLE CARDS GRID
        ?????????????????????????????? -->
        <?php
        $roles = [
            [
                'label'   => 'Kepala Sekolah',
                'icon'    => 'fa-user-tie',
                'color'   => '#6c63ff',
                'bg'      => 'rgba(108,99,255,.12)',
                'url'     => 'dashboard/kepsek',
                'desc'    => 'Monitor & kelola seluruh aktivitas sekolah dengan akses penuh ke semua fitur manajemen.',
                'features' => ['Dashboard Lengkap', 'Laporan & Analitik', 'Manajemen Staff'],
                'delay'   => 'rv1',
            ],
            [
                'label'   => 'Tata Usaha',
                'icon'    => 'fa-file-lines',
                'color'   => '#43e97b',
                'bg'      => 'rgba(67,233,123,.1)',
                'url'     => 'dashboard/tu',
                'desc'    => 'Kelola administrasi dan dokumentasi sekolah dengan sistem yang terorganisir.',
                'features' => ['Manajemen Dokumen', 'Surat Menyurat', 'Arsip Digital'],
                'delay'   => 'rv2',
            ],
            [
                'label'   => 'Wali Kelas',
                'icon'    => 'fa-chalkboard-user',
                'color'   => '#a78bfa',
                'bg'      => 'rgba(167,139,250,.12)',
                'url'     => 'dashboard/wakel',
                'desc'    => 'Kelola data siswa, absensi, dan monitoring perkembangan kelas Anda.',
                'features' => ['Data Siswa Kelas', 'Absensi & Presensi', 'Laporan Siswa'],
                'delay'   => 'rv3',
            ],
            [
                'label'   => 'Bimbingan Konseling',
                'icon'    => 'fa-heart-pulse',
                'color'   => '#ff6584',
                'bg'      => 'rgba(255,101,132,.1)',
                'url'     => 'dashboard/bk',
                'desc'    => 'Berikan bimbingan dan konseling untuk mendukung perkembangan siswa.',
                'features' => ['Konseling Siswa', 'Catatan Bimbingan', 'Monitoring Perilaku'],
                'delay'   => 'rv4',
            ],
            [
                'label'   => 'Kurikulum',
                'icon'    => 'fa-book-open-reader',
                'color'   => '#60a5fa',
                'bg'      => 'rgba(96,165,250,.1)',
                'url'     => 'dashboard/kurikulum',
                'desc'    => 'Kelola kurikulum, silabus, dan program pembelajaran sekolah.',
                'features' => ['Manajemen Kurikulum', 'Silabus & RPP', 'Program Belajar'],
                'delay'   => 'rv5',
            ],
            [
                'label'   => 'Guru',
                'icon'    => 'fa-person-chalkboard',
                'color'   => '#f7a440',
                'bg'      => 'rgba(247,164,64,.1)',
                'url'     => 'dashboard/guru',
                'desc'    => 'Kelola mata pelajaran, nilai siswa, dan materi pembelajaran.',
                'features' => ['Input Nilai Siswa', 'Materi Pelajaran', 'Jadwal Mengajar'],
                'delay'   => 'rv6',
            ],
            [
                'label'   => 'Kesiswaan',
                'icon'    => 'fa-users-rectangle',
                'color'   => '#2dd4bf',
                'bg'      => 'rgba(45,212,191,.1)',
                'url'     => 'dashboard/kesiswaan',
                'desc'    => 'Kelola data kesiswaan, ekstrakurikuler, dan kegiatan siswa di sekolah.',
                'features' => ['Data Kesiswaan', 'Ekstrakurikuler', 'Prestasi Siswa'],
                'delay'   => 'rv7',
            ],
            [
                'label'   => 'Teknisi Lab',
                'icon'    => 'fa-screwdriver-wrench',
                'color'   => '#fbbf24',
                'bg'      => 'rgba(251,191,36,.1)',
                'url'     => 'dashboard/toolman',
                'desc'    => 'Kelola inventaris, perawatan peralatan, dan laporan kegiatan laboratorium.',
                'features' => ['Inventaris Lab', 'Booking Lab', 'Laporan Harian'],
                'delay'   => 'rv8',
            ],
            [
                'label'   => 'Siswa',
                'icon'    => 'fa-user-graduate',
                'color'   => '#34d399',
                'bg'      => 'rgba(52,211,153,.1)',
                'url'     => 'dashboard/siswa',
                'desc'    => 'Lihat jadwal, nilai, absensi, dan informasi sekolah secara personal.',
                'features' => ['Jadwal Pelajaran', 'Nilai & Rapor', 'Absensi Pribadi'],
                'delay'   => 'rv9',
            ],
        ];
        ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-12">
            <?php foreach ($roles as $r): ?>
                <div class="role-card reveal <?= $r['delay'] ?>">
                    <!-- Header row -->
                    <div class="flex items-start gap-3">
                        <div class="role-icon-wrap" style="background:<?= $r['bg'] ?>; color:<?= $r['color'] ?>;">
                            <i class="fas <?= $r['icon'] ?>"></i>
                        </div>
                        <div class="flex-1 min-w-0 pt-0.5">
                            <h3 class="font-display font-bold text-base leading-tight truncate"
                                style="color:var(--t1);"><?= $r['label'] ?></h3>
                            <p class="text-xs mt-1 leading-relaxed line-clamp-2" style="color:var(--t2);"><?= $r['desc'] ?></p>
                        </div>
                    </div>

                    <!-- Feature list -->
                    <div class="flex flex-col gap-1.5">
                        <?php foreach ($r['features'] as $f): ?>
                            <div class="flex items-center gap-2 text-xs" style="color:var(--t2);">
                                <i class="fas fa-check text-[9px] flex-shrink-0" style="color:<?= $r['color'] ?>;"></i>
                                <?= $f ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- CTA -->
                    <a href="<?= base_url($r['url']) ?>"
                        class="btn-role mt-auto"
                        style="background:<?= $r['color'] ?>;">
                        <i class="fas fa-arrow-right text-xs"></i>
                        Masuk sebagai <?= $r['label'] ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ??????????????????????????????
             FEATURES SECTION
        ?????????????????????????????? -->
        <div class="divider mb-10"></div>

        <div class="reveal rv1 mb-6">
            <div class="section-label mb-2">Platform</div>
            <h2 class="font-display font-bold tracking-tight" style="font-size:clamp(1.2rem,3vw,1.5rem); color:var(--t1);">
                Fitur Unggulan EduZone
            </h2>
        </div>

        <!-- 3 feature cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 reveal rv2">
            <?php
            $feats = [
                ['fa-shield-halved', 'var(--ac)',  'rgba(108,99,255,.1)',  'Keamanan Enterprise',    'Enkripsi AES-256 dengan audit log lengkap untuk setiap aksi.'],
                ['fa-bolt',          'var(--am)',  'rgba(247,164,64,.1)',  'Performa Tinggi',         'Infrastruktur cloud terdistribusi, latensi sangat rendah.'],
                ['fa-headset',       'var(--gn)',  'rgba(67,233,123,.08)', 'Support 24/7',            'Tim dedicasi siap membantu via live chat dan telepon kapanpun.'],
            ];
            foreach ($feats as [$icon, $color, $bg, $title, $desc]): ?>
                <div class="feat-card">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 text-sm"
                        style="background:<?= $bg ?>;color:<?= $color ?>;">
                        <i class="fas <?= $icon ?>"></i>
                    </div>
                    <div class="font-display font-bold text-sm mb-1" style="color:var(--t1);"><?= $title ?></div>
                    <p class="text-xs leading-relaxed" style="color:var(--t2);"><?= $desc ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Role list box -->
        <div class="info-box reveal rv3">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs flex-shrink-0"
                    style="background:rgba(108,99,255,.12);color:var(--ac);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="font-display font-bold text-sm" style="color:var(--t1);">Role Tersedia di Sistem</div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-2">
                <?php
                $allRoles = ['Kepala Sekolah', 'Tata Usaha', 'Kurikulum', 'Wali Kelas', 'Guru Mapel', 'Kesiswaan', 'Bimbingan Konseling', 'Teknisi Lab', 'Siswa'];
                foreach ($allRoles as $rn): ?>
                    <div class="flex items-center gap-2 text-xs" style="color:var(--t2);">
                        <i class="fas fa-check text-[9px] flex-shrink-0" style="color:var(--gn);"></i>
                        <?= $rn ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>

    <!-- ???????????????????????????????????
         FOOTER
    ??????????????????????????????????? -->
    <footer class="relative z-10" style="background:var(--sur);border-top:1px solid var(--br);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center text-white text-xs" style="background:var(--ac);">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="font-display font-bold text-sm" style="color:var(--t1);">EduZone</span>
                </div>
                <p class="text-xs text-center" style="color:var(--t3);">
                    © <?= date('Y') ?> EduZone — Platform Manajemen Sekolah Digital.
                    Dibuat dengan <i class="fas fa-heart text-xs" style="color:var(--pk);"></i> untuk pendidikan Indonesia.
                </p>
                <div class="dots">
                    <?php for ($i = 0; $i < 18; $i++): ?><span></span><?php endfor; ?>
                </div>
            </div>
        </div>
    </footer>

    <!-- ???????????????????????????????????
         SCRIPTS
    ??????????????????????????????????? -->
    <script>
        // ?? Theme ??
        const html = document.documentElement;

        function applyTheme(t) {
            html.setAttribute('data-theme', t);
            document.getElementById('themeIcon').className = t === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        applyTheme(localStorage.getItem('theme') || 'dark');

        document.getElementById('themeToggle').addEventListener('click', () => {
            const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', next);
            applyTheme(next);
        });

        // ?? Scroll reveal ??
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.08
        });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // ?? Navbar shadow on scroll ??
        window.addEventListener('scroll', () => {
            document.querySelector('nav').style.boxShadow = window.scrollY > 10 ? '0 4px 24px rgba(0,0,0,0.25)' : 'none';
        });
    </script>
</body>

</html>