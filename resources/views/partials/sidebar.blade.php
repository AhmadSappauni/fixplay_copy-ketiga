<style>
    /* --- CSS FUTURISTIC SIDEBAR --- */
    .sidebar-futuristic {
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        min-height: 100vh;
        padding: 20px 15px;
        font-family: 'Poppins', sans-serif;
    }

    /* Brand Logo Styling */
    .brand-futuristic {
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .brand-futuristic .brand-text {
        font-weight: 800;
        font-size: 1.8rem;
        background: linear-gradient(45deg, #00d2ff, #3a7bd5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0px 0px 15px rgba(0, 210, 255, 0.3);
        letter-spacing: 2px;
    }

    /* Label Kategori (Laporan, Absensi, dll) */
    .sidebar-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #64748b;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        padding-left: 1rem;
    }

    /* Menu Link & Button Styling */
    .nav-item-custom {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        margin-bottom: 8px;
        color: #94a3b8;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        border: 1px solid transparent;
        background: transparent;
        width: 100%;
        font-size: 0.95rem;
    }

    .nav-item-custom i {
        font-size: 1.2rem;
        margin-right: 12px;
        transition: transform 0.3s ease;
    }

    /* --- HOVER EFFECT --- */
    .nav-item-custom:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3);
        border-color: rgba(255, 255, 255, 0.1);
    }

    .nav-item-custom:hover i {
        transform: scale(1.2) rotate(-5deg);
        color: #38bdf8;
    }

    /* --- ACTIVE STATE --- */
    .nav-item-custom.active {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        box-shadow: 0 8px 15px -3px rgba(37, 99, 235, 0.5);
        transform: translateY(-2px);
        font-weight: 500;
    }

    /* Logout Button */
    form button.nav-item-custom {
        text-align: left;
        border: none;
        cursor: pointer;
    }
    
    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.15) !important;
        color: #ef4444 !important;
        border-color: rgba(239, 68, 68, 0.3);
    }
    .logout-btn:hover i {
        color: #ef4444 !important;
    }
</style>

<div class="sidebar-futuristic">
    
    {{-- LOGO BRAND --}}
    <div class="brand-futuristic">
        <span class="brand-text">FIXPLAY</span>
    </div>

    <nav class="menu d-flex flex-column">
        
        {{-- 1. BERANDA (Semua User) --}}
        <a href="{{ route('dashboard') }}"
           class="nav-item-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i> <span>Beranda</span>
        </a>

        {{-- ============================================================
             MENU OPERASIONAL (KHUSUS KARYAWAN)
             ============================================================ --}}
        @if(auth()->check() && auth()->user()->role !== 'boss')
            
            {{-- Rental PS --}}
            <a href="{{ route('sessions.index') }}"
               class="nav-item-custom {{ request()->is('sessions*') ? 'active' : '' }}">
                <i class="bi bi-controller"></i> <span>Rental</span>
            </a>

            {{-- Unit PS --}}
            <a href="{{ route('ps_units.index') }}"
               class="nav-item-custom {{ request()->is('ps-units*') ? 'active' : '' }}">
                <i class="bi bi-hdd-network"></i> <span>Unit PS</span>
            </a>

            {{-- [DIKEMBALIKAN] Produk & Stok (Karyawan Bisa Lihat) --}}
            <a href="{{ route('products.index') }}"
               class="nav-item-custom {{ request()->is('products*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> <span>Produk &amp; Stok</span>
            </a>

            {{-- POS Kasir --}}
            <a href="{{ route('pos.index') }}"
               class="nav-item-custom {{ request()->routeIs('pos.index') ? 'active' : '' }}">
                <i class="bi bi-calculator"></i> <span>POS Kasir</span>
            </a>

            {{-- Beli Stok --}}
            <a href="{{ route('purchases.expenses.index') }}"
               class="nav-item-custom {{ request()->is('purchases/expenses*') ? 'active' : '' }}">
                <i class="bi bi-cart-plus"></i> <span>Beli Stok</span>
            </a>

            {{-- LABEL LAPORAN --}}
            <div class="sidebar-label">Laporan</div>

            <a href="{{ route('reports.index') }}"
               class="nav-item-custom {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> <span>Laporan Keuangan</span>
            </a>

            {{-- LABEL ABSENSI --}}
            <div class="sidebar-label">Absensi</div>

            <a href="{{ route('presensi.index') }}"
               class="nav-item-custom {{ request()->routeIs('presensi.index') ? 'active' : '' }}">
                <i class="bi bi-fingerprint"></i> <span>Presensi Hari Ini</span>
            </a>

            <a href="{{ route('jadwal.index') }}"
               class="nav-item-custom {{ request()->routeIs('jadwal.index') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> <span>Jadwal Mingguan</span>
            </a>

        @endif

        {{-- ============================================================
             MENU UMUM / SHARED (RIWAYAT)
             ============================================================ --}}
        @if(auth()->check())
            <a href="{{ route('presensi.riwayat') }}"
               class="nav-item-custom {{ request()->routeIs('presensi.riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> <span>Riwayat Presensi</span>
            </a>
        @endif


        {{-- ============================================================
             MENU KHUSUS BOS (OWNER AREA)
             ============================================================ --}}
        @if(auth()->check() && auth()->user()->role === 'boss')
            
            <div class="sidebar-label">Owner Area</div>

            {{-- Produk & Stok (Akses Full Bos) --}}
            <a href="{{ route('products.index') }}"
               class="nav-item-custom {{ request()->is('products*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> <span>Produk &amp; Stok</span>
            </a>

            {{-- Laporan Keuangan --}}
            <a href="{{ route('reports.index') }}"
               class="nav-item-custom {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> <span>Laporan Keuangan</span>
            </a>

            {{-- Laporan Presensi --}}
            <a href="{{ route('presensi.report') }}"
               class="nav-item-custom {{ request()->routeIs('presensi.report') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i> <span>Laporan Presensi</span>
            </a>

            {{-- Manajemen Karyawan --}}
            <a href="{{ route('karyawan.index') }}"
               class="nav-item-custom {{ request()->is('karyawan*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span>Manajemen Karyawan</span>
            </a>

        @endif

        {{-- LOGOUT --}}
        @if(auth()->check())
            <div style="margin-top: auto; padding-top: 2rem;">
                <form action="{{ route('logout') }}"
                      method="POST"
                      data-confirm="Yakin ingin logout dari Fixplay?">
                    @csrf
                    <button type="submit" class="nav-item-custom logout-btn">
                        <i class="bi bi-power"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
        @endif

    </nav>
</div>