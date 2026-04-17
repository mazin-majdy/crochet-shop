<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="120"> {{-- تحديث تلقائي كل دقيقتين --}}
    <title>لمسة خيط – تحت الصيانة</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg">
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --maroon: #7b1113;
            --brown: #422018;
            --gold: #d4af37;
            --cream: #faf7f2;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--cream);
            color: var(--brown);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 24px;
        }

        /* ── Embroidery background pattern ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='2' fill='none' stroke='%23d4af37' stroke-width='0.6' opacity='0.18'/%3E%3Cpath d='M0 30 Q15 15 30 30 Q45 45 60 30' fill='none' stroke='%237b1113' stroke-width='0.5' opacity='0.08'/%3E%3Cpath d='M30 0 Q45 15 30 30 Q15 45 30 60' fill='none' stroke='%23d4af37' stroke-width='0.5' opacity='0.08'/%3E%3C/svg%3E");
            background-size: 60px 60px;
            animation: pattern-shift 30s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes pattern-shift {
            to {
                background-position: 60px 60px;
            }
        }

        /* ── Glow orbs ── */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
            animation: orb-float 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: rgba(123, 17, 19, 0.06);
            top: -150px;
            right: -100px;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: rgba(212, 175, 55, 0.07);
            bottom: -100px;
            left: -80px;
            animation-delay: -4s;
        }

        @keyframes orb-float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-30px) scale(1.05);
            }
        }

        /* ── Card ── */
        .card {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 32px;
            padding: 56px 52px;
            max-width: 540px;
            width: 100%;
            text-align: center;
            box-shadow: 0 32px 80px rgba(66, 32, 24, 0.12);
            animation: card-in 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        @keyframes card-in {
            from {
                opacity: 0;
                transform: translateY(32px) scale(0.95);
            }
        }

        /* Dashed stitching border */
        .card::before {
            content: '';
            position: absolute;
            inset: 14px;
            border: 1.5px dashed rgba(212, 175, 55, 0.25);
            border-radius: 22px;
            pointer-events: none;
        }

        /* ── Logo ── */
        .logo-wrap {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            overflow: hidden;
            margin: 0 auto 24px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            box-shadow: 0 8px 24px rgba(123, 17, 19, 0.15);
            animation: logo-spin 0s;
            /* no spin — just nice entry */
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Animated needle ── */
        .needle-wrap {
            font-size: 3rem;
            margin-bottom: 20px;
            animation: needle-swing 3s ease-in-out infinite;
            display: inline-block;
            transform-origin: center bottom;
        }

        @keyframes needle-swing {

            0%,
            100% {
                transform: rotate(-12deg);
            }

            50% {
                transform: rotate(12deg);
            }
        }

        /* ── Title ── */
        h1 {
            font-family: 'Amiri', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--brown);
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .subtitle {
            font-size: 1rem;
            color: #8a7060;
            line-height: 1.8;
            margin-bottom: 32px;
        }

        /* ── Thread separator ── */
        .thread-line {
            height: 2px;
            background: repeating-linear-gradient(to left,
                    var(--gold) 0, var(--gold) 8px,
                    transparent 8px, transparent 16px);
            margin: 28px 0;
            border-radius: 2px;
            opacity: 0.5;
        }

        /* ── Progress loader ── */
        .loader-wrap {
            margin-bottom: 28px;
        }

        .loader-label {
            font-size: 0.8rem;
            color: #aaa;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .loader-bar {
            height: 6px;
            background: #f0e8e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .loader-fill {
            height: 100%;
            background: linear-gradient(to left, var(--gold), var(--maroon));
            border-radius: 10px;
            animation: loader-pulse 2.4s ease-in-out infinite;
            width: 0%;
        }

        @keyframes loader-pulse {
            0% {
                width: 0%;
                opacity: 1;
            }

            70% {
                width: 85%;
                opacity: 1;
            }

            85% {
                width: 85%;
                opacity: 0.6;
            }

            100% {
                width: 0%;
                opacity: 0;
            }
        }

        /* ── Info items ── */
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 28px;
            text-align: right;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #faf4ef;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: var(--brown);
        }

        .info-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .info-text {
            font-weight: 600;
        }

        .info-sub {
            font-size: 0.78rem;
            color: #aaa;
        }

        /* ── WhatsApp button ── */
        .wa-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #25D366;
            color: #fff;
            padding: 14px 30px;
            border-radius: 14px;
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 0.95rem;
            text-decoration: none;
            margin-top: 4px;
            transition: all 0.25s;
            width: 100%;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .wa-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.18), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .wa-btn:hover {
            background: #1db954;
            color: #fff;
            box-shadow: 0 8px 28px rgba(37, 211, 102, 0.4);
            transform: translateY(-2px);
        }

        .wa-btn:hover::before {
            opacity: 1;
        }

        /* ── Admin login link ── */
        .admin-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #bbb;
            font-size: 0.8rem;
            text-decoration: none;
            margin-top: 20px;
            font-weight: 600;
            transition: color 0.2s;
        }

        .admin-link:hover {
            color: var(--maroon);
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .card {
                padding: 36px 28px;
            }

            h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

    <!-- Background elements -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Main Card -->
    <div class="card">

        <!-- Logo -->
        <div class="logo-wrap">
            <img src="/images/logo.svg" alt="لمسة خيط">
        </div>

        <!-- Animated Needle -->
        <div class="needle-wrap">🪡</div>

        <!-- Title -->
        <h1>نعمل على تحسين موقعنا</h1>
        <p class="subtitle">
            لمسة خيط تخضع حالياً لأعمال صيانة وتطوير.<br>
            نعود قريباً بمزيد من الجمال والمنتجات الرائعة ✨
        </p>

        <!-- Thread divider -->
        <div class="thread-line"></div>

        <!-- Progress loader -->
        <div class="loader-wrap">
            <div class="loader-label">جارٍ التحضير...</div>
            <div class="loader-bar">
                <div class="loader-fill"></div>
            </div>
        </div>

        <!-- Info -->
        <div class="info-list">
            <div class="info-item">
                <div class="info-icon" style="background:rgba(212,175,55,0.12);color:#9a7d0a">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <div class="info-text">نعمل على تحسينات جوهرية</div>
                    <div class="info-sub">سنعود في أقرب وقت ممكن</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon" style="background:rgba(37,211,102,0.1);color:#25D366">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <div>
                    <div class="info-text">يمكنك التواصل معنا مباشرةً</div>
                    <div class="info-sub">واتساب متاح طوال فترة الصيانة</div>
                </div>
            </div>
        </div>

        <!-- WhatsApp CTA -->
        <a href="https://wa.me/{{ config('app.whatsapp_number', setting('contact.whatsapp', '970591234567')) }}"
            target="_blank" class="wa-btn">
            <i class="bi bi-whatsapp" style="font-size:1.2rem"></i>
            تواصل معنا على واتساب
        </a>

        <!-- Thread divider -->
        <div class="thread-line" style="margin-top:28px;margin-bottom:16px"></div>

        

    </div>

    <script>
        // Countdown or status message (optional — shows time since page loaded)
        let seconds = 0;
        const label = document.querySelector('.loader-label');

        setInterval(() => {
            seconds++;
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            if (label && seconds > 5) {
                label.textContent = `وقت الانتظار: ${m ? m+'د ' : ''}${s}ث`;
            }
        }, 1000);
    </script>

</body>

</html>
