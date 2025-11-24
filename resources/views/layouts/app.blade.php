<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Fixplay')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap + Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Tambahan style per‑halaman --}}
    @stack('styles')

    {{-- Tema Fixplay utama --}}
    <link rel="stylesheet" href="{{ asset('css/fixplay.css') }}">
</head>
<body style="min-height:100vh; background:#020617;">

    {{-- ====== WRAPPER: SIDEBAR + MAIN AREA ====== --}}
    <div class="d-flex" style="min-height:100vh;">

        {{-- SIDEBAR FIXPLAY --}}
        <aside style="width:240px; background:linear-gradient(180deg,#4c1d95,#0ea5e9);">
            @include('partials.sidebar')
        </aside>

        {{-- MAIN AREA (TOP NAV + CONTENT) --}}
        <div class="flex-grow-1 d-flex flex-column">

            {{-- TOP NAVBAR --}}
            <nav class="navbar navbar-expand-lg border-bottom" style="background:#ffffff;">
                <div class="container-fluid px-4">

                    {{-- Logo / Judul --}}
                    <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('dashboard') }}">
                        <span class="me-2" style="font-size: 1.3rem;">🎮</span>
                        <span>Fixplay</span>
                    </a>

                    {{-- Menu kanan --}}
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="bi bi-bag me-1"></i>Produk
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            {{-- KONTEN HALAMAN --}}
            <main class="flex-grow-1">
                <div class="container-fluid py-4 px-4">
                    @yield('content')
                </div>
            </main>

        </div> {{-- end main area --}}

    </div> {{-- end wrapper --}}

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
