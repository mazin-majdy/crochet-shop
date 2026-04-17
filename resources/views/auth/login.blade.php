<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول – {{ setting('site.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        .eye-toggle {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            background: none;
            border: none;
            font-size: 1rem;
        }

        .pass-wrap {
            position: relative;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ccc;
            font-size: 0.8rem;
            margin: 22px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .site-link {
            color: var(--maroon);
            font-weight: 700;
            text-decoration: none;
            font-size: 0.88rem;
        }

        .site-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">

        <div class="auth-card fade-up">
            <!-- Logo -->
            <div class="logo-wrap">
                <img src="{{ asset('images/logo.svg') }}" alt="{{ setting('site.name') }}">
            </div>

            <h2>مرحباً بك ✨</h2>
            <p class="sub">سجّل دخولك لإدارة {{ setting('site.name') }}</p>

            @if ($errors->any())
                <div
                    style="background:#fdf0f0;border-radius:12px;padding:12px 16px;margin-bottom:18px;color:#7b1113;font-size:0.88rem;">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">البريد الإلكتروني</label>
                    <div class="input-group-custom" style="position:relative">
                        <span
                            style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#bbb;font-size:1.1rem">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            style="padding-right:42px" placeholder="admin@lamsitkhait.com" required
                            autocomplete="email">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">كلمة المرور</label>
                    <div class="pass-wrap">
                        <span
                            style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#bbb;font-size:1.1rem;z-index:2">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" id="passwordInput" class="form-control"
                            style="padding-right:42px;padding-left:44px" placeholder="••••••••" required
                            autocomplete="current-password">
                        <button type="button" class="eye-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <label class="d-flex align-items-center gap-2 cursor-pointer" style="font-size:0.87rem;color:#666">
                        <input type="checkbox" name="remember"
                            style="width:16px;height:16px;accent-color:var(--maroon)">
                        تذكرني
                    </label>
                </div>

                <button type="submit" class="btn-auth">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    تسجيل الدخول
                </button>
            </form>

            <div class="divider">أو</div>

            <div class="text-center">
                <a href="{{ route('home') }}" class="site-link">
                    <i class="bi bi-house me-1"></i> العودة إلى الموقع
                </a>
            </div>

            <div class="text-center mt-4" style="font-size:0.78rem;color:#bbb">
                {{ setting('site.name') }} © {{ date('Y') }} — {{ setting('site.tagline') }}
            </div>
        </div>

        <!-- Decorative floating elements -->
        <div
            style="position:fixed;top:10%;left:8%;font-size:4rem;opacity:0.07;transform:rotate(-15deg);pointer-events:none">
            🪡</div>
        <div
            style="position:fixed;bottom:15%;right:6%;font-size:5rem;opacity:0.07;transform:rotate(12deg);pointer-events:none">
            🧶</div>
        <div style="position:fixed;top:60%;left:4%;font-size:3rem;opacity:0.06;pointer-events:none">✂️</div>
    </div>

    <script>
        function togglePassword() {
            const inp = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                inp.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>

</html>
