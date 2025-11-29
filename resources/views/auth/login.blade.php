<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login – Fixplay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    {{-- Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #111; 
            overflow: hidden;
        }

        /* --- LOGIKA DESKTOP (DEFAULT) --- */
        .box {
            position: relative;
            width: 380px;
            height: 100px; 
            background: #1c1c1c;
            border-radius: 8px;
            overflow: hidden;
            transition: 0.5s ease-in-out;
            z-index: 10;
        }

        /* Hover hanya berlaku di desktop: Membuka form */
        .box:hover {
            height: 520px; 
        }

        /* --- EFEK NEON BERPUTAR --- */
        .box::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 380px; height: 420px;
            background: linear-gradient(0deg, transparent, transparent, #00c6ff, #00c6ff, #00c6ff);
            z-index: 1;
            transform-origin: bottom right;
            animation: animate 6s linear infinite;
        }

        .box::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 380px; height: 420px;
            background: linear-gradient(0deg, transparent, transparent, #7b2ff7, #7b2ff7, #7b2ff7);
            z-index: 1;
            transform-origin: bottom right;
            animation: animate 6s linear infinite;
            animation-delay: -3s;
        }

        @keyframes animate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* --- ISI FORM --- */
        .form-content {
            position: absolute;
            inset: 4px; 
            background: #222; 
            padding: 30px 40px;
            border-radius: 8px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            overflow: hidden; 
        }

        h2 {
            color: #fff; font-weight: 500; text-align: center; letter-spacing: 0.1em;
            margin-top: 5px; margin-bottom: 40px; width: 100%;
            display: flex; justify-content: center; align-items: center; gap: 10px;
        }

        .logo-icon { width: 30px; height: auto; filter: drop-shadow(0 0 5px rgba(123, 47, 247, 0.8)); }

        /* --- INPUT FIELD --- */
        .inputBox {
            position: relative; width: 100%; margin-top: 35px;
            opacity: 0; transform: translateY(20px); transition: 0.5s;
        }

        .box:hover .inputBox { opacity: 1; transform: translateY(0); }
        .box:hover .inputBox:nth-child(2) { transition-delay: 0.1s; }
        .box:hover .inputBox:nth-child(3) { transition-delay: 0.2s; }

        .inputBox input {
            position: relative; width: 100%; padding: 20px 10px 10px;
            background: transparent; outline: none; box-shadow: none; border: none;
            color: #23242a; font-size: 1em; letter-spacing: 0.05em; transition: 0.5s; z-index: 10;
        }

        .inputBox span {
            position: absolute; left: 0; padding: 20px 10px 10px; pointer-events: none;
            font-size: 1em; color: #8f8f8f; letter-spacing: 0.05em; transition: 0.5s;
        }

        .inputBox input:valid ~ span, .inputBox input:focus ~ span {
            color: #fff; font-size: 0.75em; transform: translateY(-34px);
        }

        .inputBox i {
            position: absolute; left: 0; bottom: 0; width: 100%; height: 2px;
            background: #fff; border-radius: 4px; overflow: hidden; transition: 0.5s; pointer-events: none;
        }

        .inputBox input:valid ~ i, .inputBox input:focus ~ i {
            height: 44px; background: #fff; border: 1px solid #fff;
        }
        .inputBox input:valid, .inputBox input:focus { color: #222; padding-left: 15px; }

        /* --- TOMBOL MATA --- */
        .toggle-password {
            position: absolute; right: 10px; top: 25px; border: none; background: transparent;
            /* PERBAIKAN WARNA DEFAULT: Abu terang agar terlihat di background gelap */
            color: #8f8f8f; 
            cursor: pointer; z-index: 11; opacity: 0; transition: 0.3s;
            display: flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 50%;
        }
        
        /* Saat input password aktif (background jadi putih), ubah ikon jadi hitam agar kontras */
        .inputBox input:focus ~ .toggle-password, .inputBox input:valid ~ .toggle-password { 
            opacity: 1; 
            color: #222; 
        }
        
        /* EFEK SINAR KUNING PADA MATA (SAAT AKTIF) */
        .toggle-password.active {
            color: #ffeb3b !important; /* Paksa kuning terang */
            text-shadow: 0 0 5px #ffeb3b, 0 0 10px #ffeb3b, 0 0 20px #ffeb3b; 
            filter: drop-shadow(0 0 5px rgba(255, 235, 59, 0.8));
            z-index: 100; 
        }

        /* Efek "Senter" Mata (Beam) */
        .beam {
            position: absolute;
            top: 50%;
            right: 35px; 
            /* PERBAIKAN PANJANG: Diperpanjang jadi 300px */
            width: 300px; 
            height: 100px;
            /* Gradient disesuaikan agar lebih halus memudar ke kiri */
            background: linear-gradient(90deg, rgba(255, 235, 59, 0.35), transparent 90%);
            transform-origin: right center;
            transform: translateY(-50%) rotate(var(--beamDegrees, 0deg));
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            clip-path: polygon(100% 50%, 0 0, 0 100%); 
            z-index: 90;
        }
        
        .show-password .beam {
            opacity: 1;
        }

        /* Footer Links */
        .links {
            display: flex; justify-content: space-between; margin-top: 20px;
            opacity: 0; transition: 0.5s; transition-delay: 0s;
        }
        .box:hover .links { opacity: 1; transition-delay: 0.3s; }
        .links a, .links span { font-size: 0.75em; color: #8f8f8f; text-decoration: none; }
        .links a:hover { color: #fff; }

        /* Submit Button */
        .submit-container {
            display: flex; justify-content: center; margin-top: 20px;
            opacity: 0; transition: 0.5s;
        }
        .box:hover .submit-container { opacity: 1; transition-delay: 0.4s; }

        button[type="submit"] {
            border: none; outline: none; padding: 11px 25px;
            background: #00c6ff; cursor: pointer; border-radius: 4px;
            font-weight: 600; width: 120px; color: #000; transition: 0.3s;
        }
        button[type="submit"]:hover {
            background: #7b2ff7; box-shadow: 0 0 10px #7b2ff7, 0 0 40px #7b2ff7; color: #fff;
        }

        .error-message {
            color: #ff2770; font-size: 0.75em; text-align: center;
            margin-top: -10px; margin-bottom: 10px; display: block; opacity: 0;
            animation: fadeIn 0.5s forwards 0.5s;
        }
        @keyframes fadeIn { to { opacity: 1; } }

        /* KHUSUS HP (LAYAR KECIL < 768px) */
        @media (max-width: 768px) {
            .box { height: 520px; width: 350px; }
            .inputBox, .links, .submit-container, .error-message {
                opacity: 1 !important; transform: translateY(0) !important; transition-delay: 0s !important;
            }
        }
    </style>
</head>
<body>

    <div class="box" id="root">
        <div class="form-content">
            <h2>
                <img src="{{ asset('img/logo-fixplay.png') }}" alt="Logo" class="logo-icon" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline'"> 
                <span class="bi bi-controller" style="display:none; font-size:1.2em;"></span>
                FIXPLAY LOGIN
            </h2>

            @if ($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="inputBox">
                    <input type="email" name="email" required="required" value="{{ old('email') }}">
                    <span>Email</span>
                    <i></i>
                </div>

                <div class="inputBox">
                    <input type="password" name="password" id="password" required="required">
                    <span>Password</span>
                    <i></i>
                    
                    <!-- Beam Effect (Sinar Kuning) -->
                    <div class="beam"></div>

                    <!-- Ikon Mata -->
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>

                <div class="links">
                    <span>&copy; {{ date('Y') }} Fixplay</span>
                </div>

                <div class="submit-container">
                    <button type="submit">Masuk</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const root = document.documentElement; // Atau document.body
        const toggleBtn = document.getElementById('togglePassword');
        const pwdInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        const beam = document.querySelector('.beam');

        // Logika Pergerakan Sinar (Beam) mengikuti Mouse
        document.addEventListener('mousemove', (e) => {
            if (!beam) return;
            let rect = beam.getBoundingClientRect();
            let mouseX = rect.right; 
            let mouseY = rect.top + (rect.height / 2);
            let rad = Math.atan2(mouseX - e.pageX, mouseY - e.pageY);
            let degrees = (rad * (180 / Math.PI) * -1) + 90; 
            root.style.setProperty('--beamDegrees', `${degrees}deg`);
        });

        // Logika Klik Mata
        if(toggleBtn && pwdInput){
            toggleBtn.addEventListener('click', function(e){
                e.preventDefault(); 
                
                const type = pwdInput.getAttribute('type') === 'password' ? 'text' : 'password';
                pwdInput.setAttribute('type', type);
                
                document.body.classList.toggle('show-password'); 
                pwdInput.focus();

                // Ganti ikon bootstrap & efek glow tombol
                if(type === 'text'){
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-fill');
                    toggleBtn.classList.add('active');
                } else {
                    icon.classList.remove('bi-eye-fill');
                    icon.classList.add('bi-eye');
                    toggleBtn.classList.remove('active');
                }
            });
        }
    </script>

</body>
</html>