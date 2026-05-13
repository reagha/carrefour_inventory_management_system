<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Carrefour IMS') }}
        — @yield('title', 'Dashboard')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --carrefour-blue: #004B9B;
            --carrefour-red: #E30613;
            --sidebar-width: 260px;
        }

        * { box-sizing: border-box; }

        body {
            background: #F3F4F6;
            font-family: 'Figtree', sans-serif;
            margin: 0;
        }

        /* ===============================
           SIDEBAR
        =============================== */

        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--carrefour-blue);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform .3s ease;
        }

        #sidebar .logo-area {
            background: #003580;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        #sidebar .logo-area .logo-c {
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
            background: var(--carrefour-red);
            width: 44px; height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #sidebar .logo-area .logo-text { color: #fff; line-height: 1.2; }
        #sidebar .logo-area .logo-text strong { display: block; font-size: 1rem; }
        #sidebar .logo-area .logo-text span {
            font-size: .7rem; opacity: .7;
            text-transform: uppercase; letter-spacing: .05em;
        }

        #sidebar nav {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
        }

        /* ── Nav group button ── */
        .nav-group-btn {
            width: 100%;
            background: none;
            border: none;
            border-left: 3px solid transparent;
            color: rgba(255,255,255,.85);
            font-size: .875rem;
            font-family: 'Figtree', sans-serif;
            font-weight: 600;
            padding: 10px 20px 10px 21px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            text-align: left;
            transition: background .15s, border-color .15s;
        }

        .nav-group-btn:hover,
        .nav-group-btn.open {
            background: rgba(255,255,255,.1);
            color: #fff;
            border-left-color: var(--carrefour-red);
        }

        .nav-group-btn svg { width: 18px; height: 18px; flex-shrink: 0; opacity: .8; }

        .nav-group-btn .chevron {
            margin-left: auto;
            width: 14px; height: 14px;
            transition: transform .2s;
            opacity: .6;
            flex-shrink: 0;
        }

        .nav-group-btn.open .chevron { transform: rotate(180deg); }

        /* ── Dropdown ── */
        .nav-dropdown {
            overflow: hidden;
            max-height: 0;
            transition: max-height .25s ease;
        }

        .nav-dropdown.open { max-height: 300px; }

        .nav-dropdown a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px 8px 48px;
            color: rgba(255,255,255,.65);
            font-size: .825rem;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all .15s;
            position: relative;
        }

        .nav-dropdown a::before {
            content: '';
            width: 4px; height: 4px;
            border-radius: 50%;
            background: rgba(255,255,255,.35);
            flex-shrink: 0;
        }

        .nav-dropdown a:hover,
        .nav-dropdown a.active {
            background: rgba(255,255,255,.08);
            color: #fff;
            border-left-color: var(--carrefour-red);
        }

        .nav-dropdown a.active::before { background: var(--carrefour-red); }

        /* ── Dashboard single link ── */
        .nav-single {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px 10px 24px;
            color: rgba(255,255,255,.85);
            font-size: .875rem;
            font-weight: 600;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all .15s;
        }

        .nav-single svg { width: 18px; height: 18px; flex-shrink: 0; opacity: .8; }

        .nav-single:hover,
        .nav-single.active {
            background: rgba(255,255,255,.1);
            color: #fff;
            border-left-color: var(--carrefour-red);
        }

        /* ── Notification dot on supplier ── */
        .nav-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--carrefour-red);
            margin-left: auto;
            flex-shrink: 0;
        }

        /* ── Sidebar footer ── */
        #sidebar .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,.1);
            font-size: .8rem;
            color: rgba(255,255,255,.6);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .sidebar-footer .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }

        .sidebar-footer .user-name { font-weight: 600; color: #fff; }

        /* ===============================
           MAIN CONTENT
        =============================== */

        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===============================
           TOPBAR
        =============================== */

        #topbar {
            background: #fff;
            border-bottom: 1px solid #E5E7EB;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .page-title { font-size: 1.2rem; font-weight: 700; color: #111827; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }

        #menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #111827;
        }

        /* ===============================
           ROLE BADGES
        =============================== */

        .role-badge {
            font-size: .72rem; font-weight: 700;
            padding: 4px 10px; border-radius: 999px;
            text-transform: uppercase; letter-spacing: .05em;
        }
        .role-admin          { background:#EFF6FF; color:#1D4ED8; }
        .role-procurement    { background:#FFF7ED; color:#C2410C; }
        .role-warehouse      { background:#F0FDF4; color:#15803D; }
        .role-branchManager  { background:#FDF4FF; color:#7E22CE; }
        .role-auditor        { background:#F0F9FF; color:#0369A1; }

        /* ===============================
           PAGE BODY
        =============================== */

        #page-body { flex: 1; padding: 32px; }

        /* ===============================
           DASHBOARD GRID
        =============================== */

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        /* ===============================
           STAT CARDS
        =============================== */

        .stat-card {
            background: #fff; border-radius: 12px; padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06); border: 1px solid #F3F4F6;
        }
        .stat-card .stat-label {
            font-size: .78rem; font-weight: 600; color: #6B7280;
            text-transform: uppercase; letter-spacing: .05em;
        }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; color: #111827; margin: 4px 0; }
        .stat-card .stat-sub { font-size: .8rem; color: #6B7280; }

        /* ===============================
           SECTION CARDS
        =============================== */

        .section-card {
            background: #fff; border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06); border: 1px solid #F3F4F6; overflow: hidden;
        }
        .section-card .section-header {
            padding: 18px 24px; border-bottom: 1px solid #F3F4F6;
            display: flex; align-items: center; justify-content: space-between;
        }
        .section-card .section-title { font-size: 1rem; font-weight: 700; color: #111827; }

        /* ===============================
           TABLES
        =============================== */

        .ims-table { width: 100%; border-collapse: collapse; }
        .ims-table th {
            background: #F9FAFB; padding: 10px 16px; text-align: left;
            font-size: .75rem; font-weight: 700; color: #6B7280;
            text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid #E5E7EB;
        }
        .ims-table td { padding: 12px 16px; font-size: .875rem; color: #374151; border-bottom: 1px solid #F3F4F6; }
        .ims-table tr:hover td { background: #F9FAFB; }

        /* ===============================
           BADGES
        =============================== */

        .badge { display: inline-block; font-size: .7rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
        .badge-green  { background: #DCFCE7; color: #15803D; }
        .badge-yellow { background: #FEF9C3; color: #854D0E; }
        .badge-red    { background: #FEE2E2; color: #B91C1C; }
        .badge-blue   { background: #DBEAFE; color: #1D4ED8; }
        .badge-gray   { background: #F3F4F6; color: #6B7280; }

        /* ===============================
           MOBILE RESPONSIVE
        =============================== */

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main-content { margin-left: 0; }
            #topbar { padding: 0 16px; }
            #page-body { padding: 16px; }
            #menu-toggle { display: block; }
        }
    </style>
</head>

<body>

@php
    $user = auth()->user();
    $role = $user?->role;

    /* Helper: is the current route within a given group? */
    $inGroup = fn(array $patterns) => collect($patterns)->contains(fn($p) => request()->routeIs($p));
@endphp

<!-- ===============================
     SIDEBAR
================================ -->

<aside id="sidebar">

    <div class="logo-area">
        <div class="logo-c">C</div>
        <div class="logo-text">
            <strong>Carrefour</strong>
            <span>Inventory System</span>
        </div>
    </div>

    <nav>

        {{-- ── DASHBOARD (single link, no dropdown) ── --}}
        <a href="{{ route('dashboard') }}"
           class="nav-single {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>

        {{-- ── PROCUREMENT ── --}}
        @if(in_array($role, ['admin', 'procurement']))
        @php $procOpen = $inGroup(['suppliers.*', 'purchase-orders.*']); @endphp
        <button class="nav-group-btn {{ $procOpen ? 'open' : '' }}"
                onclick="toggleNav(this)">
            <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4"/><circle cx="9" cy="19" r="1"/><circle cx="20" cy="19" r="1"/></svg>
            Procurement
            <svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="nav-dropdown {{ $procOpen ? 'open' : '' }}">
            <a href="{{ route('suppliers.index') }}"
               class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                Suppliers
                @if(isset($pendingSuppliersCount) && $pendingSuppliersCount > 0)
                    <span class="nav-dot" style="margin-left:auto"></span>
                @endif
            </a>
            <a href="{{ route('purchase-orders.index') }}"
               class="{{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
                Purchase Orders
            </a>
        </div>
        @endif

        {{-- ── WAREHOUSE ── --}}
        @if(in_array($role, ['admin', 'warehouse']))
        @php $whOpen = $inGroup(['products.*']); @endphp
        <button class="nav-group-btn {{ $whOpen ? 'open' : '' }}"
                onclick="toggleNav(this)">
            <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M3 9.5L12 4l9 5.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/><path d="M9 21V12h6v9"/></svg>
            Warehouse
            <svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="nav-dropdown {{ $whOpen ? 'open' : '' }}">
            <a href="{{ route('products.index') }}"
               class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                Products
            </a>
            <a href="{{ route('branch-requests.index') }}"
               class="{{ request()->routeIs('branch-requests.*') ? 'active' : '' }}">
                Stock Requests
            </a>
        </div>
        @endif

        {{-- ── BRANCH ── --}}
        @if(in_array($role, ['admin', 'branchManager']))
        @php $brOpen = $inGroup(['branch-requests.*']); @endphp
        <button class="nav-group-btn {{ $brOpen ? 'open' : '' }}"
                onclick="toggleNav(this)">
            <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/><path d="M12 7v4m0 0l-5 6m5-6l5 6"/></svg>
            Branch
            <svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="nav-dropdown {{ $brOpen ? 'open' : '' }}">
            <a href="{{ route('branch-requests.index') }}"
               class="{{ request()->routeIs('branch-requests.*') ? 'active' : '' }}">
                Stock Requests
            </a>
        </div>
        @endif

        {{-- ── REPORTS ── --}}
        @if(in_array($role, ['admin', 'auditor']))
        @php $rpOpen = $inGroup(['reports.*']); @endphp
        <button class="nav-group-btn {{ $rpOpen ? 'open' : '' }}"
                onclick="toggleNav(this)">
            <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Reports
            <svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="nav-dropdown {{ $rpOpen ? 'open' : '' }}">
            <a href="{{ route('reports.valuation') }}"
               class="{{ request()->routeIs('reports.valuation') ? 'active' : '' }}">
                Inventory Valuation
            </a>
            <a href="{{ route('reports.consumption') }}"
               class="{{ request()->routeIs('reports.consumption') ? 'active' : '' }}">
                Branch Consumption
            </a>
            <a href="{{ route('reports.supplier-expenditure') }}"
               class="{{ request()->routeIs('reports.supplier-expenditure') ? 'active' : '' }}">
                Supplier Expenditure
            </a>
        </div>
        @endif

        {{-- ── ADMINISTRATION ── --}}
        @if($role === 'admin')
        @php $adOpen = $inGroup(['users.*']); @endphp
        <button class="nav-group-btn {{ $adOpen ? 'open' : '' }}"
                onclick="toggleNav(this)">
            <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            Administration
            <svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="nav-dropdown {{ $adOpen ? 'open' : '' }}">
            <a href="{{ route('users.index') }}"
               class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                Users
            </a>
        </div>
        @endif

    </nav>

    <div class="sidebar-footer">
        @php
            $initials = collect(explode(' ', $user?->name ?? 'G'))
                ->map(fn($w) => strtoupper($w[0]))->take(2)->implode('');
        @endphp
        <div class="avatar">{{ $initials }}</div>
        <div>
            <div class="user-name">{{ $user?->name ?? 'Guest' }}</div>
            <div>{{ ucfirst(str_replace('_', ' ', $user?->role ?? 'guest')) }}</div>
        </div>
    </div>

</aside>

<!-- ===============================
     MAIN CONTENT
================================ -->

<div id="main-content">

    <!-- TOPBAR -->
    <header id="topbar">
        <div class="topbar-left">
            <button id="menu-toggle">☰</button>
            <div class="page-title">@yield('title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
            <div class="relative">
                🔔
                <span class="badge badge-red">{{ $lowStockCount ?? 0 }}</span>
            </div>
            <span class="role-badge role-{{ $user?->role ?? 'admin' }}">
                {{ ucfirst(str_replace('_', ' ', $user?->role ?? 'Admin')) }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:#E30613;color:white;border:none;padding:8px 14px;border-radius:8px;cursor:pointer;font-size:.85rem;font-weight:600;">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- PAGE BODY -->
    <main id="page-body">

        @if(session('success'))
            <div style="background:#DCFCE7;color:#166534;padding:14px 18px;border-radius:10px;margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#FEE2E2;color:#991B1B;padding:14px 18px;border-radius:10px;margin-bottom:20px;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

    <footer style="text-align:center;padding:16px;color:#6B7280;font-size:.85rem;">
        Carrefour Inventory Management System © {{ date('Y') }}
    </footer>

</div>

<!-- ===============================
     SCRIPTS
================================ -->

<script>
    /* Mobile sidebar toggle */
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar    = document.getElementById('sidebar');
    if (menuToggle) {
        menuToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
    }

    /* Dropdown nav toggle — only one open at a time */
    function toggleNav(btn) {
        const dropdown = btn.nextElementSibling;
        const isOpen   = dropdown.classList.contains('open');

        /* Close all */
        document.querySelectorAll('.nav-dropdown.open').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.nav-group-btn.open').forEach(b => b.classList.remove('open'));

        /* Open clicked (unless it was already open) */
        if (!isOpen) {
            dropdown.classList.add('open');
            btn.classList.add('open');
        }
    }
</script>

</body>
</html>
