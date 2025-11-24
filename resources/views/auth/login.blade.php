<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login – Fixplay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap Icons untuk ikon mata & lainnya --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Kalau kamu punya CSS global (vite), boleh aktifkan ini --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <style>
        :root {
            --fixplay-purple: #7b2ff7;
            --fixplay-blue: #00c6ff;
            --bg-gray: #0f172a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top left, #1f2937, #020617);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e5e7eb;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 960px;
            min-height: 480px;
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
            background: #020617;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.8);
        }

        .auth-sidebar {
            background: linear-gradient(180deg, var(--fixplay-purple), var(--fixplay-blue));
            padding: 32px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #f9fafb;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .auth-logo img {
            width: 52px;
            height: 52px;
            object-fit: contain;
            filter: drop-shadow(0 0 20px rgba(15,23,42,0.6));
        }

        .auth-logo span {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .auth-sidebar h2 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .auth-sidebar p {
            margin: 0;
            opacity: .9;
        }

        .auth-sidebar-footer {
            font-size: 13px;
            opacity: .85;
        }

        .auth-main {
            padding: 32px 32px 28px;
            background: #020617;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-main-header {
            margin-bottom: 24px;
        }

        .auth-main-header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            color: #e5e7eb;
        }

        .auth-main-header p {
            margin: 0;
            font-size: 14px;
            color: #9ca3af;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #d1d5db;
        }

        .form-control-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #1f2937;
            background: #020617;
            color: #e5e7eb;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }

        .form-control::placeholder {
            color: #6b7280;
        }

        .form-control:focus {
            border-color: var(--fixplay-purple);
            box-shadow: 0 0 0 1px rgba(123, 47, 247, 0.6);
            background: #020617;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
        }

        .toggle-password:hover {
            color: #e5e7eb;
        }

        .btn-primary {
            border: none;
            border-radius: 999px;
            padding: 10px 16px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, var(--fixplay-purple), var(--fixplay-blue));
            color: #fff;
            margin-top: 4px;
            box-shadow: 0 16px 40px rgba(37, 99, 235, 0.35);
        }

        .btn-primary:hover {
            filter: brightness(1.05);
        }

        .auth-footer {
            margin-top: 18px;
            font-size: 12px;
            color: #6b7280;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.6);
            color: #fecaca;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 13px;
            margin-bottom: 4px;
        }

        @media (max-width: 768px) {
            .auth-wrapper {
                grid-template-columns: minmax(0, 1fr);
                max-width: 480px;
            }

            .auth-sidebar {
                display: none;
            }

            .auth-main {
                padding: 24px 20px 20px;
            }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    {{-- Panel kiri ala sidebar Fixplay --}}
    <aside class="auth-sidebar">
        <div>
            <div class="auth-logo">
                <img src="{{ asset('img/logo-fixplay.png') }}" alt="Fixplay Logo">
                <span>FIXPLAY</span>
            </div>

            <div style="margin-top: 48px;">
                <h2>Kasir Fixplay</h2>
                <p>Kelola rental PS, makanan, dan laporan harian dalam satu dashboard.</p>
            </div>
        </div>

        <div class="auth-sidebar-footer">
            <div>💾 Data tersimpan otomatis di sistem.</div>
            <div>🔒 Akses hanya untuk akun terdaftar.</div>
        </div>
    </aside>

    {{-- Panel kanan: form login --}}
    <main class="auth-main">
        <div>
            <div class="auth-main-header">
                <h1>Masuk ke Fixplay</h1>
                <p>Gunakan email dan password yang sudah terdaftar.</p>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="form-control-wrapper">
                        <input id="email"
                               type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}"
                               placeholder="contoh: karyawan@fixplay.test"
                               required
                               autofocus>
                    </div>
                </div>

                {{-- Password + ikon mata --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="form-control-wrapper">
                        <input id="password"
                               type="password"
                               name="password"
                               class="form-control"
                               placeholder="Masukkan password"
                               required>

                        <button type="button"
                                class="toggle-password"
                                id="togglePassword"
                                aria-label="Tampilkan / sembunyikan password">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    Masuk
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <span>© {{ date('Y') }} Fixplay. All rights reserved.</span>
            <span>Versi kasir: v1.0</span>
        </div>
    </main>
</div>

<script>
    const toggleBtn = document.getElementById('togglePassword');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    const pwdInput = document.getElementById('password');

    if (toggleBtn && pwdInput && toggleIcon) {
        toggleBtn.addEventListener('click', () => {
            const isHidden = pwdInput.type === 'password';
            pwdInput.type = isHidden ? 'text' : 'password';

            // Ganti ikon
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    }
</script>
</body>
</html>
