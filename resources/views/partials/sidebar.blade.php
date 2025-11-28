<div class="brand mb-3">
  <span class="fw-bold fs-4 text-white">FIXPLAY</span>
</div>

<nav class="menu">
  {{-- 1. BERANDA (SEMUA USER MELIHAT INI) --}}
  <a href="{{ route('dashboard') }}"
     class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="bi bi-house"></i> <span>Beranda</span>
  </a>

  {{-- ============================================================
       MENU OPERASIONAL (KHUSUS KARYAWAN)
       ============================================================ --}}
  @if(auth()->check() && auth()->user()->role !== 'boss')
      
      {{-- Rental PS --}}
      <a href="{{ route('sessions.index') }}"
         class="{{ request()->is('sessions*') ? 'active' : '' }}">
        <i class="bi bi-controller"></i> <span>Rental</span>
      </a>

      {{-- Unit PS --}}
      <a href="{{ route('ps_units.index') }}"
         class="{{ request()->is('ps-units*') ? 'active' : '' }}">
        <i class="bi bi-laptop"></i> <span>Unit PS</span>
      </a>

      {{-- Produk --}}
      <a href="{{ route('products.index') }}"
         class="{{ request()->is('products*') ? 'active' : '' }}">
        <i class="bi bi-cup-straw"></i> <span>Produk &amp; Stok</span>
      </a>

      {{-- POS Kasir --}}
      <a href="{{ route('pos.index') }}"
         class="{{ request()->routeIs('pos.index') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i> <span>POS Kasir</span>
      </a>

      {{-- Beli Stok --}}
      <a href="{{ route('purchases.expenses.index') }}"
         class="{{ request()->is('purchases/expenses*') ? 'active' : '' }}">
        <i class="bi bi-bag-plus"></i> <span>Beli Stok</span>
      </a>

      {{-- --- TAMBAHAN: AKSES LAPORAN UNTUK KARYAWAN --- --}}
      <div class="mt-3 mb-1 px-3 text-uppercase"
           style="font-size: .7rem; letter-spacing:.12em; opacity:.65;">
          Laporan
      </div>

      <a href="{{ route('reports.index') }}"
         class="{{ request()->routeIs('reports.index') ? 'active' : '' }}">
        <i class="bi bi-graph-up-arrow"></i> <span>Laporan Keuangan</span>
      </a>
      {{-- ------------------------------------------------ --}}

      {{-- Presensi Hari Ini (Input Absen) --}}
      <div class="mt-3 mb-1 px-3 text-uppercase"
           style="font-size: .7rem; letter-spacing:.12em; opacity:.65;">
          Absensi
      </div>

      <a href="{{ route('presensi.index') }}"
         class="{{ request()->routeIs('presensi.index') ? 'active' : '' }}">
        <i class="bi bi-clipboard-check"></i> <span>Presensi Hari Ini</span>
      </a>

      <a href="{{ route('jadwal.index') }}"
         class="{{ request()->routeIs('jadwal.index') ? 'active' : '' }}">
        <i class="bi bi-calendar-week"></i> <span>Jadwal Mingguan</span>
      </a>

  @endif

  {{-- ============================================================
       MENU UMUM / SHARED (RIWAYAT)
       ============================================================ --}}
  @if(auth()->check())
      <a href="{{ route('presensi.riwayat') }}"
         class="{{ request()->routeIs('presensi.riwayat') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i> <span>Riwayat Presensi</span>
      </a>
  @endif


  {{-- ============================================================
       MENU KHUSUS BOS (MANAGEMENT & REPORT)
       ============================================================ --}}
  @if(auth()->check() && auth()->user()->role === 'boss')
    
    <div class="mt-3 mb-1 px-3 text-uppercase"
         style="font-size: .7rem; letter-spacing:.12em; opacity:.65;">
      Laporan &amp; Manajemen
    </div>

    {{-- Laporan Keuangan (Bos juga tetap punya ini) --}}
    <a href="{{ route('reports.index') }}"
       class="{{ request()->routeIs('reports.index') ? 'active' : '' }}">
      <i class="bi bi-graph-up-arrow"></i> <span>Laporan Keuangan</span>
    </a>

    {{-- Laporan Presensi --}}
    <a href="{{ route('presensi.report') }}"
       class="{{ request()->routeIs('presensi.report') ? 'active' : '' }}">
      <i class="bi bi-bar-chart-line"></i> <span>Laporan Presensi</span>
    </a>

    {{-- Manajemen Karyawan --}}
    <a href="{{ route('karyawan.index') }}"
       class="{{ request()->is('karyawan*') ? 'active' : '' }}">
      <i class="bi bi-people"></i> <span>Manajemen Karyawan</span>
    </a>

  @endif

  {{-- LOGOUT --}}
  @if(auth()->check())
    <form action="{{ route('logout') }}" method="POST" class="mt-3">
      @csrf
      <button type="submit"
              style="background:none;border:none;width:100%;text-align:left;padding:0.75rem 1rem;color:#fff;">
        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
      </button>
    </form>
  @endif
</nav>