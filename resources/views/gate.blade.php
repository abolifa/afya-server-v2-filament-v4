<!doctype html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>الهيئة الوطنية لأمراض الكلى - البوابة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Apply Filament theme from localStorage BEFORE paint -->
    <script>
        (function () {
            const KEY = 'theme';
            const mm = window.matchMedia('(prefers-color-scheme: dark)');

            function currentTheme() {
                const stored = localStorage.getItem(KEY);
                if (stored === 'light' || stored === 'dark') return stored;
                // treat anything else (null / 'system') as system
                return mm.matches ? 'dark' : 'light';
            }

            function apply(theme) {
                document.documentElement.setAttribute('data-theme', theme);
            }

            // initial
            apply(currentTheme());

            // react to storage changes (switching inside Filament panel)
            window.addEventListener('storage', (e) => {
                if (e.key === KEY) apply(currentTheme());
            });

            // react to OS scheme if user chose "system"
            mm.addEventListener?.('change', () => apply(currentTheme()));
        })();
    </script>

    <style>
        /* ---------- THEME TOKENS ---------- */
        :root[data-theme="dark"] {
            --bg-start: #1e1e20;
            --bg-end: #121212;
            --text: #eaeaea;
            --muted: #bdbdbd;
            --heading: #4ade80;
            --accent: #4ade80;
            --card-bg: #1b1b1e;
            --card-grad: #252529;
            --border: #2f2f2f;
            --shadow: rgba(0, 0, 0, .45);
            --glow: rgba(74, 222, 128, .5);
            --hover-shadow: rgba(74, 222, 128, .5);
        }

        :root[data-theme="light"] {
            --bg-start: #f7fafc;
            --bg-end: #eef2f7;
            --text: #111827;
            --muted: #6b7280;
            --heading: #166534; /* darker green for contrast */
            --accent: #16a34a;
            --card-bg: #ffffff;
            --card-grad: #f9fafb;
            --border: #e5e7eb;
            --shadow: rgba(0, 0, 0, .08);
            --glow: rgba(22, 163, 74, .35);
            --hover-shadow: rgba(22, 163, 74, .25);
        }

        /* ---------- RESET & BASE ---------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif
        }

        body {
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            overflow-x: hidden;
            background: radial-gradient(circle at top, var(--bg-start), var(--bg-end));
        }

        /* ---------- HERO ---------- */
        header {
            width: 100%;
            padding: 60px 20px 40px;
            text-align: center;
            animation: fadeInDown 1s ease-out;
        }

        header img {
            width: 140px;
            height: auto;
            margin-bottom: 20px;
            transition: transform .3s ease;
        }

        header img:hover {
            transform: scale(1.05);
        }

        header h1 {
            font-size: 2.4rem;
            font-weight: 800;
        }

        header p {
            margin-top: 10px;
            font-size: 1.2rem;
            color: var(--muted);
            font-weight: 500;
        }

        /* ---------- CARDS GRID ---------- */
        .container {
            display: grid;
            grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
            width: 100%;
            max-width: 1200px;
            padding: 50px 20px;
            animation: fadeInUp 1.2s ease-out;
        }

        .card-link {
            text-decoration: none;
            display: block;
            transition: transform .3s ease;
        }

        .card {
            background: linear-gradient(145deg, var(--card-bg), var(--card-grad));
            border: 1px solid gray;
            border-radius: 20px;
            padding: 50px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 18px;
            font-size: 1.3rem;
            color: var(--text);
            text-align: center;
            box-shadow: 0 6px 25px var(--shadow);
            transition: transform .3s, box-shadow .3s, border .3s, color .3s;
        }

        .card:hover {
            border: 1px solid var(--accent);
            box-shadow: 0 8px 35px var(--hover-shadow);
            transform: translateY(-8px) scale(1.05);
            color: var(--accent);
        }

        .card i {
            font-size: 60px;
            color: var(--accent);
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }

            header p {
                font-size: 1rem;
            }

            .card {
                font-size: 1.1rem;
                padding: 40px 25px;
            }

            .card i {
                font-size: 50px;
            }
        }

        @media (max-width: 480px) {
            header img {
                width: 110px;
            }

            .card {
                font-size: 1rem;
                padding: 35px 20px;
            }

            .card i {
                font-size: 44px;
            }
        }

        /* ---------- ANIMATIONS ---------- */
        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-30px)
            }
            100% {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px)
            }
            100% {
                opacity: 1;
                transform: translateY(0)
            }
        }
    </style>
</head>
<body>

<!-- Hero Branding -->
<header>
    <img src="/logo.png" alt="شعار الهيئة الوطنية لأمراض الكلى" width="140"
         height="140">
    <h1>الهيئة الوطنية لأمراض الكلى</h1>
    <p>بوابة الخدمات الإلكترونية</p>
</header>

<!-- App Cards -->
<div class="container">
    <a href="/admin" class="card-link">
        <div class="card">
            <i class="fa-solid fa-users"></i>
            نظام عافية الصحي
        </div>
    </a>

    <a href="/site" class="card-link">
        <div class="card">
            <i class="fa-solid fa-code"></i>
            إدارة الموقع الإلكتروني
        </div>
    </a>

    <a href="/archive" class="card-link">
        <div class="card">
            <i class="fa-solid fa-box-archive"></i>
            الأرشيف والمراسلة
        </div>
    </a>
</div>

</body>
</html>
