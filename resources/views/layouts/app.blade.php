<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Carrefour IMS') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --carrefour-blue: #004B9B;
            --carrefour-red:  #E30613;
            --sidebar-width: 260px;
        }
        body { background: #F3F4F6; font-family: 'Figtree', sans-serif; margin: 0; }

        #sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-width); height: 100vh;
            background: var(--carrefour-blue);
            display: flex; flex-direction: column;
            z-index: 50;
        }
        #sidebar .logo-area {
            background: #003580; padding: 20px 24px;
            display: flex; align-items: center; gap: 12px;
        }
        #sidebar .logo-area .logo-c {
            font-size: 2rem; font-weight: 900; color: #fff;
            background: var(--carrefour-red);
            width: 44px; height: 44px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        #sidebar .logo-area .logo-text { color: #fff; line-height: 1.2; }
        #sidebar .logo-area .logo-text strong { display: block; font-size: 1rem; }
        #sidebar .logo-area .logo-text span { font-size: .7rem; opacity: .7; text-transform: uppercase; letter-spacing: .05em; }

        #sidebar nav { flex: 1; overflow-y: auto; padding: 16px 0; }
        #sidebar nav .nav-section { padding: 8px 24px 4px; font-size: .65rem; font-weight: 700; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .1em; }
        #sidebar nav a {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 24px; color: rgba(255,255,255,.8);
            font-size: .875rem; text-decoration: none;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        #sidebar nav a:hover, #sidebar nav a.active {
            background: rgba(255,255,255,.1); color: #fff;
            border-left-color: var(--carrefour-red);
        }
        #sidebar nav a svg { width: 18px; height: 18px; flex-shrink: 0; }
        #sidebar .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,.1);
            font-size: .8rem; color: rgba(255,255,255,.6);
        }
        #sidebar .sidebar-footer .user-name { font-weight: 600; color: #fff; }

        #main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }

        #topbar {
            background: #fff; border-bottom: 1px solid #E5E7EB;
            padding: 0 32px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
        }
        #topbar .page-title { font-size: 1.2rem; font-weight: 700; color: #111827; }
        #topbar .topbar-right { display: flex; align-items: center; gap: 16px; }
        .role-badge { font-size: .72rem; font-weight: 700; padding: 3px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: .05em; }
        .role-admin       { background: #EFF6FF; color: #1D4ED8; }
        .role-procurement { background: #FFF7ED; color: #C2410C; }
        .role-warehouse   { background: #F0FDF4; color: #15803D; }
        .role-branch      { background: #FDF4FF; color: #7E22CE; }
        .role-auditor     { background: #F0F9FF; color: #0369A1; }

        #page-body { flex: 1; padding: 32px; }

        .stat-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.06); border: 1px solid #F3F4F6; }
        .stat-card .stat-label { font-size: .78rem; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: .05em; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; color: #111827; margin: 4px 0; }
        .stat-card .stat-sub   { font-size: .8rem; color: #6B7280; }
        .stat-card .stat-icon  { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }

        .ims-table { width: 100%; border-collapse: collapse; }
        .ims-table th { background: #F9FAFB; padding: 10px 16px; text-align: left; font-size: .75rem; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid #E5E7EB; }
        .ims-table td { padding: 12px 16px; font-size: .875rem; color: #374151; border-bottom: 1px solid #F3F4F6; }
        .ims-table tr:last-child td { border-bottom: none; }
        .ims-table tr:hover td { background: #F9FAFB; }

        .section-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.06); border: 1px solid #F3F4F6; overflow: hidden; }
        .section-card .section-header { padding: 18px 24px; border-bottom: 1px solid #F3F4F6; display: flex; align-items: center; justify-content: space-between; }
        .section-card .section-title  { font-size: 1rem; font-weight: 700; color: #111827; }

        .badge { display: inline-block; font-size: .7rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
        .badge-green  { background: #DCFCE7; color: #15803D; }
        .badge-yellow { background: #FEF9C3; color: #854D0E; }
        .badge-red    { background: #FEE2E2; color: #B91C1C; }
        .badge-blue   { background: #DBEAFE; color: #1D4ED8; }
        .badge-gray   { background: #F3F4F6; color: #6B7280; }
    </style>
</head>
<body>

<aside id="sidebar">
    <div class="logo-area">
        <div class="logo-c">C</div>
        <div class="logo-text">
            <strong>Carrefour</strong>
            <span>Inventory System</span>
        </div>
    </div>

    <nav>
        <div class="nav-section">Dashboard</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        @if(in_array(auth()->user()->role ?? '', ['admin','procurement']))
        <div class="nav-section">Procurement</div>
        <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
            Suppliers
        </a>
        <a href="{{ route('purchase-orders.index') }}" class="{{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
            Purchase Orders
        </a>
        @endif

        @if(in_array(auth()->user()->role ?? '', ['admin','warehouse']))
        <div class="nav-section">Warehouse</div>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Products
        </a>
        @endif

        @if(in_array(auth()->user()->role ?? '', ['admin','branch_manager']))
        <div class="nav-section">Branch</div>
        <a href="{{ route('branch-requests.index') }}" class="{{ request()->routeIs('branch-requests.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Stock Requests
        </a>
        @endif

        @if(in_array(auth()->user()->role ?? '', ['admin','auditor']))
        <div class="nav-section">Reports</div>
        <a href="{{ route('reports.valuation') }}" class="{{ request()->routeIs('reports.valuation') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Inventory Valuation
        </a>
        <a href="{{ route('reports.consumption') }}" class="{{ request()->routeIs('reports.consumption') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Branch Consumption
        </a>
        <a href="{{ route('reports.supplier-expenditure') }}" class="{{ request()->routeIs('reports.supplier-expenditure') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Supplier Expenditure
        </a>
        @endif

        @if(auth()->user()->role === 'admin')
        <div class="nav-section">Administration</div>
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Users
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-name">{{ auth()->user()->name ?? 'Guest' }}</div>
        <div>{{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'guest')) }}</div>
    </div>
</aside>

<div id="main-content">
    <header id="topbar">
        <div class="page-title">@yield('title', 'Dashboard')</div>
        <div class="topbar-right">
            <span class="role-badge role-{{ auth()->user()->role ?? 'admin' }}">{{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'Admin')) }}</span>
        </div>
    </header>

    <main id="page-body">
        @yield('content')
    </main>
</div>

</body>
</html>