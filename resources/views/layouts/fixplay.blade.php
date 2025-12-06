<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Fixplay')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tema Fixplay -->
    <link rel="stylesheet" href="{{ asset('css/fixplay.css') }}">
    @stack('styles')

    <style>
        .navbar{ display:none !important; }
        .container{ max-width:100% !important; padding:0; }

        .fix-shell{
            display:flex;
            min-height:100vh;
            background:#d1d5db;
        }

        .shell-aside{
            width:270px;
            background: linear-gradient(180deg,#4c1d95 0%,#7c3aed 40%,#3b82f6 100%);
            color:#ecf0ff;
            padding:22px 18px;
            box-shadow:8px 0 30px rgba(0,0,0,.15) inset;

            /* scroll di sidebar */
            overflow-y:auto;
            overflow-x:hidden;
            -webkit-overflow-scrolling:touch;

            transition: width .25s ease, padding .25s ease, border-width .25s ease;
        }

        .fix-shell.sidebar-collapsed .shell-aside{
            width:0;
            padding:0;
            border-width:0;
        }

        .shell-main{
            flex:1;
            display:flex;
            flex-direction:column;
        }

        .topbar{
            position: sticky;
            top:0;
            z-index:1030;
            background:#fff;
            color:#111827;
            padding:12px 20px;
            border-bottom:1px solid #e5e7eb;
            display:flex;
            align-items:center;
        }
        .topbar .title{
            font-weight:900;
            font-size:20px;
        }

        .content-pad{
            padding:24px;
        }

        .menu a{
            display:flex;
            align-items:center;
            gap:12px;
            padding:10px 12px;
            border-radius:12px;
            color:#ecf0ff;
            text-decoration:none;
            font-weight:700;
            margin-bottom:6px;
        }
        .menu a:hover{ background: rgba(255,255,255,.15); }
        .menu a.active{
            background: rgba(255,255,255,.22);
            box-shadow:0 0 0 1px rgba(255,255,255,.25) inset;
        }
        .menu i{ font-size:22px; }

        .card-dark{
            background:
                radial-gradient(120% 120% at 0% 0%, rgba(124,58,237,.25), transparent 40%),
                radial-gradient(120% 120% at 100% 0%, rgba(59,130,246,.22), transparent 45%),
                linear-gradient(180deg,#151528,#0f1020);
            border:1px solid rgba(122,92,255,.25);
            color:#eef2ff;
            border-radius:14px;
            box-shadow:0 10px 30px rgba(0,0,0,.35), 0 0 18px rgba(124,58,237,.12) inset;
        }
        .card-dark .card-header{
            background:rgba(15,16,32,.55);
            border-bottom:1px solid rgba(122,92,255,.25);
            color:#eaeaff;
            border-radius:14px 14px 0 0;
            font-weight:800;
        }
        .table thead th{
            color:#cfd3ff;
            background:rgba(25,25,45,.6);
        }
        .table td,.table th{
            border-color:rgba(122,92,255,.15) !important;
        }

        .notif-menu{
            min-width:320px;
            background:
                radial-gradient(100% 140% at 0% 0%, rgba(124,58,237,.25), transparent 40%),
                radial-gradient(120% 120% at 100% 0%, rgba(59,130,246,.22), transparent 45%),
                linear-gradient(180deg,#151528,#0f1020);
            color:#eef2ff;
            border:1px solid rgba(122,92,255,.35);
        }
        .notif-menu .list-group-item{
            background:transparent;
            color:#eef2ff;
            border-color:rgba(122,92,255,.18);
        }
        .notif-menu .list-group-item .small{
            color:#cdd1ff;
        }

        /* ====== RESPONSIVE LAYOUT (TABLET & HP) ====== */
        @media (max-width: 992px){
            .fix-shell{
                flex-direction: column;
                position: relative;
                min-height: 100vh;
            }

            /* Sidebar jadi drawer dari kiri */
            .shell-aside{
                position: fixed;
                inset: 0 auto 0 0;
                width: 260px;
                max-width: 80%;
                height: 100vh;
                border-radius: 0;
                padding: 18px 14px;
                box-shadow: 16px 0 40px rgba(15,23,42,.65);
                transform: translateX(-100%);
                transition: transform .25s ease;
                z-index: 1040;
            }

            /* Saat TIDAK collapsed → sidebar muncul */
            .fix-shell:not(.sidebar-collapsed) .shell-aside{
                transform: translateX(0);
            }

            /* Overlay gelap di belakang sidebar */
            .fix-shell::after{
                content:"";
                position: fixed;
                inset: 0;
                background: rgba(15,23,42,.65);
                opacity: 0;
                pointer-events: none;
                transition: opacity .25s ease;
                z-index: 1035;
            }
            .fix-shell:not(.sidebar-collapsed)::after{
                opacity: 1;
                pointer-events: auto;
            }

            .topbar{
                padding-inline: 10px;
                padding-block: 8px;
            }

            .content-pad{
                padding: 14px 10px 20px;
            }

            .menu a{
                padding: 9px 10px;
                border-radius: 10px;
                font-size: .9rem;
            }
        }

        /* ====== HP kecil (≤576px) ====== */
        @media (max-width: 576px){
            .topbar .title{
                font-size: 15px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 55vw;
            }

            #sidebarToggle{
                padding: 4px 8px;
            }

            .dash-shell{
                padding: 1rem .75rem 1.4rem;
                border-radius: 1.1rem;
            }

            .stat-card{
                padding: .8rem .9rem;
                margin-bottom: .4rem;
            }
            .stat-card .stat-value{
                font-size: 1.25rem;
            }

            .card-graph .card-body,
            .card-trans .card-body{
                padding: .9rem .75rem;
            }
            .chart-wrapper{
                min-height: 190px;
                max-height: 230px;
            }

            .table-neon thead th{
                padding: .45rem .5rem;
                font-size: .68rem;
                white-space: nowrap;
            }
            .table-neon tbody td{
                padding: .45rem .5rem;
                font-size: .76rem;
            }

            .btn-action-group .btn{
                padding: .25rem .5rem;
                font-size: .7rem;
            }

            .toast-container{
                bottom: 0.75rem !important;
                right: .75rem !important;
            }
        }

        @media print{
            .shell-aside,
            .topbar{ display:none !important; }
            .content-pad{ padding:0; }
        }
    </style>
</head>
<body>
<div class="fix-shell sidebar-collapsed"><!-- default tertutup di mobile -->
    <aside class="shell-aside d-print-none">
        @include('partials.sidebar')
    </aside>

    <main class="shell-main">
        <div class="topbar d-print-none">
            <button id="sidebarToggle" class="btn btn-outline-dark me-2" type="button" title="Tutup/Buka menu">
                <i class="bi bi-list"></i>
            </button>

            <div class="title">@yield('page_title')</div>

            <div class="ms-auto d-flex align-items-center gap-2">
                @if(request()->routeIs('sessions.index'))
                    {{-- Tombol riwayat & notif hanya di halaman kasir --}}
                    <button id="historyBtn" class="btn btn-outline-dark" type="button" title="Riwayat Notifikasi">
                        <i class="bi bi-clock-history"></i>
                    </button>

                    <div class="dropdown">
                        <button id="notifBtn" class="btn btn-outline-dark position-relative" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                            <i class="bi bi-bell"></i>
                            <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">0</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg notif-menu">
                            <div class="p-2 border-bottom d-flex justify-content-between align-items-center">
                                <strong>Notifikasi</strong>
                                <button class="btn btn-sm btn-link text-decoration-none" id="notifClear">
                                    Tandai sudah dibaca
                                </button>
                            </div>
                            <div id="notifList" class="list-group list-group-flush" style="max-height:320px;overflow:auto;">
                                <div class="p-3 text-muted">Belum ada notifikasi.</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="content-pad">
            @yield('page_content')
        </div>
    </main>
</div>

{{-- TOAST & AUDIO --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3 d-print-none" style="z-index: 1090;">
    <div id="liveToast" class="toast align-items-center text-white bg-primary border-0 shadow-lg"
         role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <strong class="d-block mb-1" id="toastTitle">Notifikasi</strong>
                <span id="toastBody">Pesan notifikasi...</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<audio id="notifSound"
       src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"
       preload="auto"></audio>

{{-- MODAL RIWAYAT NOTIFIKASI --}}
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-clock-history me-2"></i> Riwayat Notifikasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="historyList" class="list-group list-group-flush">
                    {{-- diisi via JS --}}
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-danger btn-sm" id="clearHistoryBtn">
                    Hapus Semua Riwayat
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI GLOBAL (hapus + logout) --}}
<div class="modal fade" id="fxConfirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fx-neon-card">
            <div class="modal-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="fx-neon-icon"><i class="bi bi-exclamation-triangle"></i></div>
                    <div>
                        <h5 class="m-0 fw-bold">Konfirmasi</h5>
                        <div id="fxConfirmText" class="mt-1 text-neon-sub">Yakin?</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer fx-neon-footer">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                <button id="fxConfirmOk" type="button" class="btn fx-btn-primary">OK</button>
            </div>
        </div>
    </div>
</div>

<style>
    .fx-neon-card{
        background:
            radial-gradient(120% 140% at 0% 0%, rgba(124,58,237,.18), transparent 45%),
            radial-gradient(120% 140% at 100% 0%, rgba(59,130,246,.15), transparent 50%),
            linear-gradient(180deg,#151528,#0f1020);
        color:#eef2ff;
        border:1px solid rgba(139,92,246,.55);
        box-shadow:0 0 0 2px rgba(139,92,246,.25) inset,0 10px 30px rgba(0,0,0,.55),0 0 22px rgba(139,92,246,.35);
        border-radius:16px;
    }
    .fx-neon-icon{
        width:42px;
        height:42px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:rgba(139,92,246,.15);
        border:1px solid rgba(139,92,246,.6);
        box-shadow:0 0 14px rgba(139,92,246,.45);
        font-size:20px;
        color:#c4b5fd;
    }
    .text-neon-sub{ color:#cdd1ff; }
    .fx-neon-footer{ border-top-color:rgba(139,92,246,.2); }
    .fx-btn-primary{
        background:linear-gradient(135deg,#7c3aed,#3b82f6);
        border:0;
        color:#fff;
        font-weight:700;
        box-shadow:0 6px 18px rgba(124,58,237,.35);
    }
    .fx-btn-primary:hover{ filter:brightness(1.06); }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Script utama (sidebar, notifikasi, riwayat, konfirmasi) --}}
<script>
(function () {
    // ===== Sidebar persist =====
    const KEY_SIDEBAR = 'fixplay.sidebar.collapsed';
    const root       = document.querySelector('.fix-shell');
    const sidebarBtn = document.getElementById('sidebarToggle');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

    function applySidebar(collapsed) {
        if (!root) return;
        if (collapsed) root.classList.add('sidebar-collapsed');
        else root.classList.remove('sidebar-collapsed');
    }

    // default dari localStorage (desktop)
    applySidebar(localStorage.getItem(KEY_SIDEBAR) === '1');

    sidebarBtn?.addEventListener('click', () => {
        const next = !root.classList.contains('sidebar-collapsed');
        applySidebar(next);
        localStorage.setItem(KEY_SIDEBAR, next ? '1' : '0');
    });

    sidebarCloseBtn?.addEventListener('click', () => {
        applySidebar(true);
        localStorage.setItem(KEY_SIDEBAR, '1');
    });

    // klik overlay di luar sidebar → tutup (mobile)
    root?.addEventListener('click', (e) => {
        if (window.innerWidth > 992) return;
        if (root.classList.contains('sidebar-collapsed')) return;
        if (e.target.closest('.shell-aside')) return;
        if (e.target.closest('#sidebarToggle')) return;

        applySidebar(true);
        localStorage.setItem(KEY_SIDEBAR, '1');
    });

        // Klik salah satu menu di sidebar (HP/tablet) -> sidebar otomatis menutup
    document.querySelectorAll('.shell-aside .menu a').forEach(link => {
        link.addEventListener('click', () => {
            // Hanya berlaku untuk layar kecil
            if (window.innerWidth <= 992) {
                applySidebar(true);                 // tutup sidebar
                localStorage.setItem(KEY_SIDEBAR, '1'); // simpan state tertutup
            }
        });
    });


    // klik menu / logout di sidebar → auto close di HP
    document.querySelectorAll('.shell-aside a[data-close-sidebar], .shell-aside button[data-close-sidebar]')
        .forEach(el => {
            el.addEventListener('click', () => {
                if (window.innerWidth <= 992) {
                    applySidebar(true);
                    localStorage.setItem(KEY_SIDEBAR, '1');
                }
            });
        });

    // ===== Notifikasi & Riwayat =====
    const KEY_TIMERS  = 'fixplay.rental.timers';
    const KEY_INBOX   = 'fixplay.rental.inbox';
    const KEY_HISTORY = 'fixplay.rental.history';
    const POLL_MS     = 15000;

    const isKasirPage = !!document.querySelector('.session-shell');
    const notifBadge  = document.getElementById('notifBadge');
    const notifList   = document.getElementById('notifList');
    const clearBtn    = document.getElementById('notifClear');

    const historyBtn   = document.getElementById('historyBtn');
    const historyList  = document.getElementById('historyList');
    const clearHistBtn = document.getElementById('clearHistoryBtn');

    const toastEl    = document.getElementById('liveToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastBody  = document.getElementById('toastBody');
    const notifSound = document.getElementById('notifSound');

    const bsToast     = toastEl ? new bootstrap.Toast(toastEl) : null;
    const historyModal = document.getElementById('historyModal')
        ? new bootstrap.Modal(document.getElementById('historyModal'))
        : null;

    const jget = (k, d) => {
        try {
            const v = localStorage.getItem(k);
            return v === null ? d : JSON.parse(v);
        } catch (e) {
            return d;
        }
    };
    const jset = (k, v) => localStorage.setItem(k, JSON.stringify(v));

    function renderInbox() {
        if (!notifList) return;
        const inbox = jget(KEY_INBOX, []);

        if (notifBadge) {
            if (inbox.length) {
                notifBadge.textContent = String(inbox.length);
                notifBadge.classList.remove('d-none');
            } else {
                notifBadge.classList.add('d-none');
            }
        }

        notifList.innerHTML = '';
        if (!inbox.length) {
            notifList.innerHTML = '<div class="p-3 text-muted">Belum ada notifikasi.</div>';
            return;
        }

        inbox.slice().reverse().forEach(item => {
            const a = document.createElement('a');
            a.href = '#';
            a.className = 'list-group-item list-group-item-action';
            const d = new Date(item.time);
            const t = d.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});

            a.innerHTML =
                `<div class="d-flex w-100 justify-content-between">
                    <div><strong>${item.title}</strong></div>
                    <small class="small">${t}</small>
                 </div>
                 ${item.detail ? `<div class="small mt-1">${item.detail}</div>` : ''}`;

            a.addEventListener('click', e => {
                e.preventDefault();
                moveToHistory(item);
            });

            notifList.appendChild(a);
        });
    }

    function addToHistoryStorage(item) {
        const hist = jget(KEY_HISTORY, []);
        hist.unshift(item);
        if (hist.length > 100) hist.length = 100;
        jset(KEY_HISTORY, hist);
    }

    function renderHistory() {
        if (!historyList) return;
        const hist = jget(KEY_HISTORY, []);

        historyList.innerHTML = '';
        if (!hist.length) {
            historyList.innerHTML = '<div class="p-4 text-center text-muted">Belum ada riwayat.</div>';
            return;
        }

        hist.forEach(it => {
            const d = new Date(it.time);
            const dateStr = d.toLocaleDateString('id-ID', {day:'numeric', month:'short'});
            const timeStr = d.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});

            const div = document.createElement('div');
            div.className = 'list-group-item';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div class="fw-bold text-dark">${it.title}</div>
                    <small class="text-muted" style="font-size:0.75rem;">${dateStr} ${timeStr}</small>
                </div>
                ${it.detail ? `<div class="small text-muted mt-1">${it.detail}</div>` : ''}
            `;
            historyList.appendChild(div);
        });
    }

    function addNotification(title, detail) {
        const inbox = jget(KEY_INBOX, []);
        inbox.push({
            id: Date.now() + '-' + Math.random().toString(36).slice(2),
            title,
            detail,
            time: new Date().toISOString()
        });
        jset(KEY_INBOX, inbox);
        renderInbox();

        try {
            if (notifSound) {
                notifSound.currentTime = 0;
                notifSound.play().catch(() => {});
            }
        } catch (e) {}

        if (bsToast && toastTitle && toastBody) {
            toastTitle.textContent = title;
            toastBody.textContent  = detail || '';
            bsToast.show();
        }
    }

    function moveToHistory(item) {
        const inbox = jget(KEY_INBOX, []);
        const newInbox = inbox.filter(x => String(x.id) !== String(item.id));
        jset(KEY_INBOX, newInbox);
        addToHistoryStorage(item);
        renderInbox();
    }

    function pollTimers() {
        // waktu habis hanya dicek di halaman kasir
        if (!isKasirPage) return;

        const now = new Date();
        const timers = jget(KEY_TIMERS, []);
        let changed = false;

        timers.forEach(t => {
            const endAt = new Date(t.endAt);
            if (!t.notified && endAt <= now) {
                addNotification('Waktu Habis!', `Unit ${t.unit} telah selesai.`);
                t.notified = true;
                changed = true;
            }
        });

        const keep = timers.filter(t =>
            (Date.now() - new Date(t.endAt)) < 24 * 3600 * 1000
        );
        if (changed || keep.length !== timers.length) {
            jset(KEY_TIMERS, keep);
        }
    }

    clearBtn?.addEventListener('click', e => {
        e.preventDefault();
        const inbox = jget(KEY_INBOX, []);
        if (inbox.length) {
            const hist = jget(KEY_HISTORY, []);
            const newHist = [...inbox.reverse(), ...hist].slice(0, 100);
            jset(KEY_HISTORY, newHist);
            jset(KEY_INBOX, []);
            renderInbox();
        }
    });

    historyBtn?.addEventListener('click', () => {
        renderHistory();
        historyModal?.show();
    });

    clearHistBtn?.addEventListener('click', () => {
        if (confirm('Yakin ingin menghapus semua riwayat notifikasi?')) {
            jset(KEY_HISTORY, []);
            renderHistory();
        }
    });

    window.addEventListener('storage', e => {
        if ([KEY_INBOX, KEY_TIMERS, KEY_HISTORY].includes(e.key)) {
            renderInbox();
            renderHistory();
        }
    });

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            renderInbox();
            pollTimers();
        }
    });

    // ===== Modal konfirmasi global (hapus & logout) =====
    (function setupConfirmModal() {
        let pendingForm = null;
        const modalEl = document.getElementById('fxConfirm');
        if (!modalEl) return;

        const modal = new bootstrap.Modal(modalEl);
        const txt   = document.getElementById('fxConfirmText');
        const okBtn = document.getElementById('fxConfirmOk');

        document.querySelectorAll('form.confirm-delete, form[data-confirm]').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                pendingForm = this;
                txt.textContent = this.dataset.confirm || 'Yakin?';
                modal.show();
            });
        });

        okBtn?.addEventListener('click', function () {
            if (pendingForm) {
                const unitName = pendingForm.dataset.timerUnit;
                if (unitName) {
                    try {
                        const timers = jget(KEY_TIMERS, []);
                        const keep = timers.filter(t => t.unit !== unitName);
                        jset(KEY_TIMERS, keep);
                    } catch (e) {
                        console.warn('Gagal membersihkan timer lokal:', e);
                    }
                }
                pendingForm.submit();
                pendingForm = null;
            }
            modal.hide();
        });
    })();

    // initial
    renderInbox();
    if (isKasirPage) {
        pollTimers();
        setInterval(pollTimers, POLL_MS);
    }
})();
</script>

@stack('scripts')
</body>
</html>
