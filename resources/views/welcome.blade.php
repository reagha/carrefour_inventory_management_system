<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carrefour Central Warehouse — Inventory Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:  #004B9B;
            --red:   #E30613;
            --dark:  #0A0F1E;
            --mid:   #111827;
            --light: #F5F7FA;
            --muted: rgba(255,255,255,0.45);
            --grid:  rgba(255,255,255,0.04);
        }

        html, body {
            height: 100%;
            background: var(--dark);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            overflow-x: hidden;
        }

        /* ── Background grid ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--grid) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Glow orbs ── */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.18;
        }
        .orb-blue { width: 600px; height: 600px; background: var(--blue); top: -200px; left: -100px; animation: drift 18s ease-in-out infinite alternate; }
        .orb-red  { width: 400px; height: 400px; background: var(--red);  bottom: -100px; right: -80px; animation: drift 14s ease-in-out infinite alternate-reverse; }

        @keyframes drift {
            from { transform: translate(0, 0); }
            to   { transform: translate(40px, 30px); }
        }

        /* ── Layout ── */
        .page { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Header ── */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 48px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(10px);
            position: sticky; top: 0; z-index: 50;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .logo-mark {
            width: 44px; height: 44px;
            background: var(--red);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            color: #fff;
            letter-spacing: -1px;
            flex-shrink: 0;
            box-shadow: 0 0 20px rgba(227,6,19,0.4);
        }
        .logo-text { line-height: 1.2; }
        .logo-text strong { display: block; font-size: 1rem; font-weight: 600; letter-spacing: .02em; color: #fff; }
        .logo-text span { font-size: 0.7rem; color: var(--muted); letter-spacing: .08em; text-transform: uppercase; }

        nav { display: flex; align-items: center; gap: 12px; }
        .nav-link {
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
            color: var(--muted);
            border: 1px solid transparent;
        }
        .nav-link:hover { color: #fff; border-color: rgba(255,255,255,0.15); background: rgba(255,255,255,0.05); }
        .nav-link.primary {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
            box-shadow: 0 0 20px rgba(0,75,155,0.4);
        }
        .nav-link.primary:hover { background: #0059bb; box-shadow: 0 0 30px rgba(0,75,155,0.6); }

        /* ── Hero ── */
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 80px 48px;
            gap: 32px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(227,6,19,0.12);
            border: 1px solid rgba(227,6,19,0.3);
            color: #ff6b6b;
            font-family: 'DM Mono', monospace;
            font-size: 0.72rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 999px;
            animation: fadeUp .6s ease both;
        }
        .hero-eyebrow::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--red);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }

        .hero-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(4rem, 10vw, 9rem);
            line-height: .92;
            letter-spacing: .01em;
            animation: fadeUp .7s .1s ease both;
        }
        .hero-title .accent { color: var(--red); }
        .hero-title .outline {
            -webkit-text-stroke: 2px rgba(255,255,255,0.25);
            color: transparent;
        }

        .hero-sub {
            max-width: 520px;
            font-size: 1.1rem;
            font-weight: 300;
            line-height: 1.7;
            color: rgba(255,255,255,0.6);
            animation: fadeUp .7s .2s ease both;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeUp .7s .3s ease both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .25s;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: var(--blue);
            color: #fff;
            box-shadow: 0 0 32px rgba(0,75,155,0.5), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        .btn-primary:hover { background: #0059bb; transform: translateY(-2px); box-shadow: 0 8px 40px rgba(0,75,155,0.6); }
        .btn-ghost {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); color: #fff; transform: translateY(-2px); }

        /* ── Stats strip ── */
        .stats-strip {
            display: flex;
            justify-content: center;
            gap: 0;
            border-top: 1px solid rgba(255,255,255,0.07);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            animation: fadeUp .7s .4s ease both;
        }
        .stat-item {
            flex: 1;
            max-width: 200px;
            padding: 28px 32px;
            text-align: center;
            border-right: 1px solid rgba(255,255,255,0.07);
            position: relative;
        }
        .stat-item:last-child { border-right: none; }
        .stat-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.4rem;
            color: #fff;
            line-height: 1;
        }
        .stat-num span { color: var(--red); }
        .stat-label { font-size: 0.72rem; color: var(--muted); letter-spacing: .08em; text-transform: uppercase; margin-top: 4px; }

        /* ── Features ── */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1px;
            background: rgba(255,255,255,0.07);
            border-top: 1px solid rgba(255,255,255,0.07);
            animation: fadeUp .7s .5s ease both;
        }
        .feature-card {
            background: var(--dark);
            padding: 36px 32px;
            transition: background .2s;
        }
        .feature-card:hover { background: rgba(255,255,255,0.03); }
        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
            font-size: 1.2rem;
        }
        .feature-title { font-size: 1rem; font-weight: 600; margin-bottom: 8px; }
        .feature-desc { font-size: 0.85rem; color: rgba(255,255,255,0.5); line-height: 1.6; }

        /* ── Role badges ── */
        .roles-section {
            padding: 48px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.07);
            animation: fadeUp .7s .6s ease both;
        }
        .roles-label { font-size: 0.72rem; color: var(--muted); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 20px; }
        .roles-list { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; }
        .role-badge {
            padding: 6px 18px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid;
        }
        .role-admin      { background: rgba(0,75,155,.15);   border-color: rgba(0,75,155,.4);   color: #60a5fa; }
        .role-warehouse  { background: rgba(16,185,129,.1);  border-color: rgba(16,185,129,.3);  color: #34d399; }
        .role-procurement{ background: rgba(245,158,11,.1);  border-color: rgba(245,158,11,.3);  color: #fbbf24; }
        .role-branch     { background: rgba(167,139,250,.1); border-color: rgba(167,139,250,.3); color: #c4b5fd; }
        .role-auditor    { background: rgba(251,191,36,.1);  border-color: rgba(251,191,36,.3);  color: #fde68a; }

        /* ── Footer ── */
        footer {
            padding: 20px 48px;
            border-top: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.3);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 640px) {
            header { padding: 16px 24px; }
            .hero  { padding: 48px 24px; }
            .stats-strip { flex-wrap: wrap; }
            .stat-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.07); }
            footer { flex-direction: column; gap: 8px; text-align: center; }
            .roles-section { padding: 32px 24px; }
        }
    </style>
</head>
<body>
<div class="orb orb-blue"></div>
<div class="orb orb-red"></div>

<div class="page">

    {{-- ── Header ── --}}
    <header>
        <div class="logo">
            <div class="logo-mark">C</div>
            <div class="logo-text">
                <strong>Carrefour Uganda</strong>
                <span>Central Warehouse</span>
            </div>
        </div>
        <nav>
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-link primary">Go to Dashboard →</a>
            @else
                <a href="{{ route('login') }}" class="nav-link">Sign In</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-link primary">Get Access →</a>
                @endif
            @endauth
        </nav>
    </header>

    {{-- ── Hero ── --}}
    <section class="hero">
        <div class="hero-eyebrow">Inventory Management System v1.0</div>

        <h1 class="hero-title">
            <span class="outline">Stock.</span><br>
            <span class="accent">Track.</span><br>
            Deliver.
        </h1>

        <p class="hero-sub">
            One platform connecting procurement, warehouse, branches, and auditors.
            Real-time visibility across every product, order, and shipment.
        </p>

        <div class="hero-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Sign In to System
                </a>
                <a href="#features" class="btn btn-ghost">Learn More</a>
            @endauth
        </div>
    </section>

    {{-- ── Stats ── --}}
    <div class="stats-strip">
        <div class="stat-item">
            <div class="stat-num">5<span>+</span></div>
            <div class="stat-label">Branch Locations</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">20<span>+</span></div>
            <div class="stat-label">Product SKUs</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">5<span>+</span></div>
            <div class="stat-label">Suppliers</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">24<span>/7</span></div>
            <div class="stat-label">Live Tracking</div>
        </div>
    </div>

    {{-- ── Features ── --}}
    <div class="features" id="features">
        <div class="feature-card">
            <div class="feature-icon" style="background:rgba(0,75,155,0.2);">📦</div>
            <div class="feature-title">Warehouse Control</div>
            <div class="feature-desc">Monitor live stock levels, receive shipments from suppliers, and dispatch to branches with full traceability.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:rgba(227,6,19,0.15);">🛒</div>
            <div class="feature-title">Procurement</div>
            <div class="feature-desc">Create and approve purchase orders, track supplier deliveries, and manage vendor relationships end-to-end.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:rgba(16,185,129,0.15);">🏪</div>
            <div class="feature-title">Branch Requests</div>
            <div class="feature-desc">Branch managers submit stock requests that flow through the approval pipeline to warehouse dispatch.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:rgba(245,158,11,0.15);">⚠️</div>
            <div class="feature-title">Low Stock Alerts</div>
            <div class="feature-desc">Automatic alerts when any product drops below reorder levels, so you never run out of critical stock.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:rgba(167,139,250,0.15);">📊</div>
            <div class="feature-title">Analytics & Reports</div>
            <div class="feature-desc">Inventory valuation, branch consumption analysis, and supplier expenditure reports with date range filters.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:rgba(251,191,36,0.15);">🔐</div>
            <div class="feature-title">Role-Based Access</div>
            <div class="feature-desc">Admin, Procurement, Warehouse, Branch Manager, and Auditor roles — each sees exactly what they need.</div>
        </div>
    </div>

    {{-- ── Roles ── --}}
    <div class="roles-section">
        <div class="roles-label">System Roles</div>
        <div class="roles-list">
            <span class="role-badge role-admin">Admin</span>
            <span class="role-badge role-warehouse">Warehouse Manager</span>
            <span class="role-badge role-procurement">Procurement Officer</span>
            <span class="role-badge role-branch">Branch Manager</span>
            <span class="role-badge role-auditor">Auditor</span>
        </div>
    </div>

    {{-- ── Footer ── --}}
    <footer>
        <span>&copy; {{ date('Y') }} Carrefour Uganda — Central Warehouse IMS</span>
        <span style="font-family:'DM Mono',monospace;font-size:.7rem;">Laravel {{ app()->version() }} · PHP {{ PHP_MAJOR_VERSION }}.{{ PHP_MINOR_VERSION }}</span>
    </footer>

</div>
</body>
</html>