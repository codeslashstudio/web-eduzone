<!DOCTYPE html>
<html lang="id" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — EduZone</title>

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
        :root {
            --bg: #0a0a0f;
            --surface: #111118;
            --card: #16161e;
            --card-h: #1c1c28;
            --br: rgba(255, 255, 255, 0.08);
            --br-h: rgba(255, 255, 255, 0.18);
            --t1: #f0f0ff;
            --t2: #8888aa;
            --t3: #55556a;
            --ac: #6c63ff;
            --ag: rgba(108, 99, 255, 0.26);
            --pk: #ff6584;
            --gn: #43e97b;
            --sh: 0 4px 40px rgba(0, 0, 0, 0.5);
        }

        [data-theme="light"] {
            --bg: #f7f7fc;
            --surface: #ededf8;
            --card: #ffffff;
            --card-h: #f0f0fc;
            --br: rgba(0, 0, 0, 0.07);
            --br-h: rgba(108, 99, 255, 0.28);
            --t1: #0f0f1a;
            --t2: #5a5a7a;
            --t3: #9898b0;
            --ac: #5b54e8;
            --ag: rgba(91, 84, 232, 0.18);
            --pk: #e84393;
            --gn: #1db954;
            --sh: 0 4px 32px rgba(91, 84, 232, 0.1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--t1);
            min-height: 100svh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .35s, color .25s;
            overflow-x: hidden;
            padding: 1rem;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.025'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: .5;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(var(--br) 1px, transparent 1px), linear-gradient(90deg, var(--br) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 15%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 15%, transparent 100%);
            pointer-events: none;
            z-index: 0;
        }

        .blob {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .blob-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--ag) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pb 6s ease-in-out infinite;
        }

        .blob-2 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 101, 132, .1) 0%, transparent 70%);
            bottom: 5%;
            left: 5%;
            animation: pb 8s ease-in-out infinite reverse;
        }

        @keyframes pb {

            0%,
            100% {
                opacity: .5;
                scale: 1
            }

            50% {
                opacity: .9;
                scale: 1.12
            }
        }

        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 880px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--card);
            border: 1px solid var(--br);
            border-radius: 20px;
            box-shadow: var(--sh);
            overflow: hidden;
            opacity: 0;
            transform: translateY(16px);
            animation: fu .55s ease .05s forwards;
        }

        @keyframes fu {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .panel-l {
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(145deg, #181626 0%, #0e0d1a 100%);
            border-right: 1px solid var(--br);
            position: relative;
            overflow: hidden;
        }

        [data-theme="light"] .panel-l {
            background: linear-gradient(145deg, #eeeeff 0%, #e5e2ff 100%);
        }

        .panel-l::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(108, 99, 255, .18) 0%, transparent 70%);
        }

        .panel-l::after {
            content: '';
            position: absolute;
            bottom: 20px;
            left: -30px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 101, 132, .1) 0%, transparent 70%);
        }

        .fi {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: .7rem 0;
            border-bottom: 1px solid var(--br);
            opacity: 0;
            transform: translateX(-12px);
            animation: si .45s ease forwards;
        }

        .fi:last-child {
            border-bottom: none;
        }

        @keyframes si {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .field-label {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--t2);
            margin-bottom: .4rem;
        }

        .field-input {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--br);
            border-radius: 10px;
            padding: .78rem 2.6rem .78rem .9rem;
            font-size: .875rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--t1);
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
            -webkit-appearance: none;
        }

        .field-input::placeholder {
            color: var(--t3);
        }

        .field-input:focus {
            border-color: var(--ac);
            background: var(--card-h);
            box-shadow: 0 0 0 3px var(--ag);
        }

        .btn-sub {
            width: 100%;
            background: var(--ac);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .85rem;
            font-size: .88rem;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            letter-spacing: .02em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            position: relative;
            overflow: hidden;
            transition: background .2s, transform .2s, box-shadow .2s;
        }

        .btn-sub::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, .1) 0%, transparent 55%);
        }

        .btn-sub:hover:not(:disabled) {
            background: #7b74ff;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px var(--ag);
        }

        .btn-sub:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-sub:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .spin {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-top-color: white;
            border-radius: 50%;
            animation: sp .65s linear infinite;
            display: none;
            flex-shrink: 0;
        }

        @keyframes sp {
            to {
                transform: rotate(360deg);
            }
        }

        .theme-btn {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 100;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--card);
            border: 1px solid var(--br);
            color: var(--t2);
            font-size: .88rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color .2s, color .2s;
        }

        .theme-btn:hover {
            border-color: var(--br-h);
            color: var(--t1);
        }

        .alert {
            border-radius: 9px;
            padding: .72rem .9rem;
            font-size: .8rem;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            border: 1px solid;
            line-height: 1.5;
        }

        .alert-e {
            background: rgba(255, 101, 132, .07);
            border-color: rgba(255, 101, 132, .22);
            color: var(--pk);
        }

        .alert-s {
            background: rgba(67, 233, 123, .07);
            border-color: rgba(67, 233, 123, .22);
            color: var(--gn);
        }

        [data-theme="light"] .alert-e {
            background: #fff0f4;
            border-color: #ffb3c0;
            color: #c01828;
        }

        [data-theme="light"] .alert-s {
            background: #f0fff6;
            border-color: #8de0b0;
            color: #107830;
        }

        .dots {
            display: grid;
            grid-template-columns: repeat(5, 6px);
            gap: 5px;
            opacity: .18;
        }

        .dots span {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--ac);
            display: block;
        }

        @media (max-width:620px) {
            .card {
                grid-template-columns: 1fr;
            }

            .panel-l {
                display: none !important;
            }

            .panel-r {
                padding: 2rem 1.5rem !important;
            }
        }

        @media (min-width:621px) and (max-width:780px) {
            .card {
                max-width: 700px;
            }

            .panel-l {
                padding: 2rem;
            }

            .panel-r {
                padding: 2rem !important;
            }
        }

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
    </style>
</head>

<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <button class="theme-btn" id="themeToggle" title="Ganti tema">
        <i class="fas fa-sun" id="themeIcon"></i>
    </button>

    <div class="card">

        <!-- ═══ LEFT PANEL ═══ -->
        <div class="panel-l">
            <div class="relative z-10 flex flex-col h-full justify-between">

                <!-- Logo + headline + features -->
                <div>
                    <div class="flex items-center gap-2.5 mb-7">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                            style="background:var(--ac);">
                            <i class="fas fa-graduation-cap text-white text-sm"></i>
                        </div>
                        <span class="font-display font-extrabold text-lg tracking-tight" style="color:var(--t1);">EduZone</span>
                    </div>

                    <div class="mb-6">
                        <h2 class="font-display font-extrabold text-2xl leading-tight tracking-tight mb-2"
                            style="color:var(--t1);">
                            Platform sekolah<br>
                            <span style="background:linear-gradient(135deg,var(--ac) 0%,#a78bfa 55%,var(--pk) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                                masa depan
                            </span>
                        </h2>
                        <p class="text-xs leading-relaxed max-w-[220px]" style="color:var(--t2);">
                            Satu platform untuk administrasi, pembelajaran, dan komunikasi sekolah modern.
                        </p>
                    </div>

                    <div>
                        <?php
                        $features = [
                            ['fa-shield-alt', 'var(--ac)',  'rgba(108,99,255,.12)', 'Data aman & terenkripsi',    '.15s'],
                            ['fa-bolt',       '#f7a440',    'rgba(247,164,64,.12)', 'Sinkronisasi real-time',      '.25s'],
                            ['fa-users',      'var(--gn)',  'rgba(67,233,123,.12)', 'Multi-role access',           '.35s'],
                            ['fa-chart-line', 'var(--pk)',  'rgba(255,101,132,.12)', 'Analitik & laporan mendalam', '.45s'],
                        ];
                        foreach ($features as [$icon, $color, $bg, $label, $delay]): ?>
                            <div class="fi" style="animation-delay:<?= $delay ?>">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-xs"
                                    style="background:<?= $bg ?>;color:<?= $color ?>;">
                                    <i class="fas <?= $icon ?>"></i>
                                </div>
                                <span class="text-xs font-semibold" style="color:var(--t1);"><?= $label ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Stats + dots -->
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <?php
                        $stats = [['500+', 'Sekolah', 'var(--ac)'], ['50K+', 'Pengguna', 'var(--ac)'], ['4.8★', 'Rating', 'var(--gn)']];
                        foreach ($stats as $i => [$v, $l, $c]): ?>
                            <?php if ($i > 0): ?><div class="w-px h-7 flex-shrink-0" style="background:var(--br);"></div><?php endif; ?>
                            <div>
                                <div class="font-display font-extrabold text-base leading-none" style="color:<?= $c ?>;"><?= $v ?></div>
                                <div class="text-[10px] uppercase tracking-widest mt-0.5" style="color:var(--t3);"><?= $l ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="dots">
                        <?php for ($i = 0; $i < 25; $i++): ?><span></span><?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ RIGHT PANEL ═══ -->
        <div class="panel-r relative z-10 flex flex-col justify-between p-8" style="background:var(--card);">

            <!-- Back -->
            <div class="mb-5">
                <a href="<?= base_url('/') ?>"
                    class="inline-flex items-center gap-1.5 text-xs font-medium transition-colors"
                    style="color:var(--t3);text-decoration:none;"
                    onmouseover="this.style.color='var(--t2)'"
                    onmouseout="this.style.color='var(--t3)'">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Heading -->
            <div class="mb-5">
                <h1 class="font-display font-extrabold tracking-tight leading-tight mb-1.5"
                    style="font-size:clamp(1.4rem,4vw,1.65rem); color:var(--t1);">
                    Selamat datang<br>kembali 👋
                </h1>
                <p class="text-xs leading-relaxed" style="color:var(--t2);">
                    Masukkan kredensial Anda untuk mengakses dashboard sekolah.
                </p>
            </div>

            <!-- Alerts -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-e mb-4">
                    <i class="fas fa-circle-exclamation mt-px flex-shrink-0 text-xs"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-s mb-4">
                    <i class="fas fa-circle-check mt-px flex-shrink-0 text-xs"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?= base_url('auth/doLogin') ?>" method="POST" id="loginForm" class="flex flex-col gap-4 flex-1">
                <?= csrf_field() ?>

                <!-- Username -->
                <div>
                    <label class="field-label" for="username">Username</label>
                    <div class="relative">
                        <input type="text" id="username" name="username" required
                            value="<?= old('username') ?>" autocomplete="username"
                            class="field-input" placeholder="Masukkan username Anda">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-xs"
                            style="color:var(--t3);"><i class="fas fa-at"></i></span>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="field-label" for="password">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            autocomplete="current-password"
                            class="field-input" placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" tabindex="-1"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-xs transition-colors"
                            style="color:var(--t3);background:none;border:none;cursor:pointer;padding:4px;"
                            onmouseover="this.style.color='var(--t2)'"
                            onmouseout="this.style.color='var(--t3)'">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                            class="w-3.5 h-3.5 rounded cursor-pointer"
                            style="accent-color:var(--ac);">
                        <span class="text-xs" style="color:var(--t2);">Ingat saya</span>
                    </label>
                    <a href="#" class="text-xs font-semibold transition-opacity"
                        style="color:var(--ac);text-decoration:none;"
                        onmouseover="this.style.opacity='.7'"
                        onmouseout="this.style.opacity='1'">Lupa password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-sub mt-1" id="submitBtn">
                    <div class="spin" id="spinner"></div>
                    <i class="fas fa-arrow-right-to-bracket text-sm" id="btnIcon"></i>
                    <span id="btnText">Masuk ke Dashboard</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-5 pt-5 text-center" style="border-top:1px solid var(--br);">
                <p class="text-xs" style="color:var(--t3);">
                    Butuh bantuan?&nbsp;
                    <a href="mailto:support@eduzone.com" class="font-semibold transition-opacity"
                        style="color:var(--ac);text-decoration:none;"
                        onmouseover="this.style.opacity='.7'"
                        onmouseout="this.style.opacity='1'">support@eduzone.com</a>
                </p>
            </div>
        </div>
    </div>

    <script>
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

        function togglePassword() {
            const inp = document.getElementById('password');
            const ico = document.getElementById('toggleIcon');
            const show = inp.type === 'password';
            inp.type = show ? 'text' : 'password';
            ico.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            document.getElementById('spinner').style.display = 'block';
            document.getElementById('btnIcon').style.display = 'none';
            document.getElementById('btnText').textContent = 'Memproses…';
        });
    </script>
</body>

</html>