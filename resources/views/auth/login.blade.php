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
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            background:#111;
            overflow:hidden;
        }

        .box{
            position:relative;
            width:380px;
            height:100px;
            background:#1c1c1c;
            border-radius:8px;
            overflow:hidden;
            transition:.5s ease-in-out;
            z-index:10;
        }

        /* hover versi desktop */
        .box:hover{
            height:520px;
        }

        .box::before,
        .box::after{
            content:'';
            position:absolute;
            top:-50%;
            left:-50%;
            width:380px;
            height:420px;
            transform-origin:bottom right;
            animation:animate 6s linear infinite;
            z-index:1;
        }
        .box::before{
            background:linear-gradient(0deg,transparent,transparent,#00c6ff,#00c6ff,#00c6ff);
        }
        .box::after{
            background:linear-gradient(0deg,transparent,transparent,#7b2ff7,#7b2ff7,#7b2ff7);
            animation-delay:-3s;
        }

        @keyframes animate{
            0%{transform:rotate(0deg);}
            100%{transform:rotate(360deg);}
        }

        .form-content{
            position:absolute;
            inset:4px;
            background:#222;
            padding:30px 40px;
            border-radius:8px;
            z-index:2;
            display:flex;
            flex-direction:column;
            overflow:hidden;
        }

        h2{
            color:#fff;
            font-weight:500;
            text-align:center;
            letter-spacing:.1em;
            margin-top:5px;
            margin-bottom:40px;
            width:100%;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:10px;
        }
        .logo-icon{
            width:30px;
            height:auto;
            filter:drop-shadow(0 0 5px rgba(123,47,247,.8));
        }

        .inputBox{
            position:relative;
            width:100%;
            margin-top:35px;
            opacity:0;
            transform:translateY(20px);
            transition:.5s;
        }
        .box:hover .inputBox{
            opacity:1;
            transform:translateY(0);
        }
        .box:hover .inputBox:nth-child(2){transition-delay:.1s;}
        .box:hover .inputBox:nth-child(3){transition-delay:.2s;}

        .inputBox input{
            position:relative;
            width:100%;
            padding:20px 40px 10px 10px; /* tambah ruang kanan untuk ikon */
            background:transparent;
            outline:none;
            box-shadow:none;
            border:none;
            color:#23242a;
            font-size:1em;
            letter-spacing:.05em;
            transition:.5s;
            z-index:5;
        }

        .inputBox span{
            position:absolute;
            left:0;
            padding:20px 10px 10px;
            pointer-events:none;
            font-size:1em;
            color:#8f8f8f;
            letter-spacing:.05em;
            transition:.5s;
            z-index:4;
        }

        .inputBox input:valid ~ span,
        .inputBox input:focus ~ span{
            color:#fff;
            font-size:.75em;
            transform:translateY(-34px);
        }

        /* background putih di belakang input */
        .inputBox i{
            position:absolute;
            left:0;
            bottom:0;
            width:100%;
            height:2px;
            background:#fff;
            border-radius:4px;
            overflow:hidden;
            transition:.5s;
            pointer-events:none;
            z-index:3;
        }

        .inputBox input:valid ~ i,
        .inputBox input:focus ~ i{
            height:44px;
            background:#fff;
            border:1px solid #fff;
        }

        .inputBox input:valid,
        .inputBox input:focus{
            color:#222;
            padding-left:15px;
        }

        /* TOMBOL MATA FUTURISTIK BIRU */
        .toggle-password{
            position:absolute;
            right:8px;
            top:18px;
            border:none;
            cursor:pointer;
            z-index:20; /* pasti di atas background putih */
            display:flex;
            align-items:center;
            justify-content:center;
            width:30px;
            height:30px;
            border-radius:999px;
            background:radial-gradient(circle at 30% 0,#38bdf8,#0ea5e9 45%,#1d4ed8 100%);
            border:1px solid rgba(56,189,248,.9);
            color:#e0f2fe;
            box-shadow:0 0 8px rgba(56,189,248,.8),0 0 16px rgba(37,99,235,.5);
            opacity:0;
            transition:.3s;
        }

        .toggle-password i{
            font-size:1rem;
        }

        /* muncul saat input aktif/berisi */
        .inputBox input:focus ~ .toggle-password,
        .inputBox input:valid ~ .toggle-password{
            opacity:1;
        }

        .toggle-password:hover{
            filter:brightness(1.15);
            box-shadow:0 0 12px rgba(56,189,248,.9),0 0 24px rgba(37,99,235,.75);
        }

        /* saat aktif: kuning + glow supaya beda */
        .toggle-password.active{
            background:radial-gradient(circle at 30% 0,#facc15,#fbbf24 40%,#f97316 100%);
            border-color:#fde047;
            color:#111827;
            text-shadow:0 0 4px #fde047;
            box-shadow:0 0 14px rgba(250,204,21,.9),0 0 28px rgba(251,191,36,.8);
        }

        /* BEAM (sinar) */
        .beam{
            position:absolute;
            top:50%;
            right:38px;
            width:300px;
            height:100px;
            background:linear-gradient(90deg,rgba(250,204,21,.35),transparent 90%);
            transform-origin:right center;
            transform:translateY(-50%) rotate(var(--beamDegrees,0deg));
            pointer-events:none;
            opacity:0;
            transition:opacity .3s;
            clip-path:polygon(100% 50%,0 0,0 100%);
            z-index:15;
        }
        .show-password .beam{
            opacity:1;
        }

        .links{
            display:flex;
            justify-content:space-between;
            margin-top:20px;
            opacity:0;
            transition:.5s;
        }
        .box:hover .links{
            opacity:1;
            transition-delay:.3s;
        }
        .links a,
        .links span{
            font-size:.75em;
            color:#8f8f8f;
            text-decoration:none;
        }
        .links a:hover{color:#fff;}

        .submit-container{
            display:flex;
            justify-content:center;
            margin-top:20px;
            opacity:0;
            transition:.5s;
        }
        .box:hover .submit-container{
            opacity:1;
            transition-delay:.4s;
        }

        button[type="submit"]{
            border:none;
            outline:none;
            padding:11px 25px;
            background:#00c6ff;
            cursor:pointer;
            border-radius:4px;
            font-weight:600;
            width:120px;
            color:#000;
            transition:.3s;
        }
        button[type="submit"]:hover{
            background:#7b2ff7;
            box-shadow:0 0 10px #7b2ff7,0 0 40px #7b2ff7;
            color:#fff;
        }

        .error-message{
            color:#ff2770;
            font-size:.75em;
            text-align:center;
            margin-top:-10px;
            margin-bottom:10px;
            display:block;
            opacity:0;
            animation:fadeIn .5s forwards .5s;
        }

        /* ==== BACKGROUND SLIDESHOW FUTURISTIK ==== */
        .bg-slideshow {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .bg-slideshow img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            animation: slideShow 18s infinite;
            filter: brightness(0.35) blur(2px); /* futuristik gelap */
        }

        .bg-slideshow img:nth-child(1) { animation-delay: 0s; }
        .bg-slideshow img:nth-child(2) { animation-delay: 6s; }
        .bg-slideshow img:nth-child(3) { animation-delay: 12s; }

        @keyframes slideShow {
            0% { opacity: 0; }
            10% { opacity: 1; }
            30% { opacity: 1; }
            40% { opacity: 0; }
            100% { opacity: 0; }
        }


        @keyframes fadeIn{
            to{opacity:1;}
        }

        /* HP */
        @media (max-width:768px){
            .box{
                height:520px;
                width:350px;
            }
            .inputBox,
            .links,
            .submit-container,
            .error-message{
                opacity:1 !important;
                transform:translateY(0) !important;
                transition-delay:0s !important;
            }
        }
    </style>
</head>
<body>
<div class="bg-slideshow">
    <img src="{{ asset('img/bg1.png') }}" alt="">
    <img src="{{ asset('img/bg2.png') }}" alt="">
    <img src="{{ asset('img/bg3.png') }}" alt="">
</div>

<div class="box" id="root">
    <div class="form-content">
        <h2>
            <img src="{{ asset('img/logo-fixplay.png') }}" alt="Logo" class="logo-icon"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline'">
            <span class="bi bi-controller" style="display:none;font-size:1.2em;"></span>
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

                {{-- Beam --}}
                <div class="beam"></div>

                {{-- Ikon Mata --}}
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
    const root       = document.documentElement;
    const toggleBtn  = document.getElementById('togglePassword');
    const pwdInput   = document.getElementById('password');
    const icon       = document.getElementById('toggleIcon');
    const beam       = document.querySelector('.beam');

    // Gerak beam mengikuti mouse
    document.addEventListener('mousemove', (e) => {
        if (!beam) return;
        const rect   = beam.getBoundingClientRect();
        const mouseX = rect.right;
        const mouseY = rect.top + (rect.height / 2);
        const rad    = Math.atan2(mouseX - e.pageX, mouseY - e.pageY);
        const deg    = (rad * (180 / Math.PI) * -1) + 90;
        root.style.setProperty('--beamDegrees', `${deg}deg`);
    });

    // Toggle show/hide password
    if (toggleBtn && pwdInput){
        toggleBtn.addEventListener('click', function(e){
            e.preventDefault();

            const type = pwdInput.getAttribute('type') === 'password' ? 'text' : 'password';
            pwdInput.setAttribute('type', type);

            document.body.classList.toggle('show-password');
            pwdInput.focus();

            if (type === 'text'){
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
