<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carrefour Inventory Management System</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='45' fill='%23E30613'/%3E%3Ccircle cx='50' cy='50' r='35' fill='white'/%3E%3Ccircle cx='50' cy='50' r='12' fill='%23E30613'/%3E%3Ctext x='50' y='56' text-anchor='middle' fill='white' font-family='Arial,sans-serif' font-size='16' font-weight='bold'%3EC%3C/text%3E%3C/svg%3E">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-mark {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-mark svg {
            width: 100%;
            height: 100%;
        }

        .logo-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: #111827;
        }
        .logo-text span {
            color: #E30613;
        }

        .auth-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-login {
            padding: 8px 20px;
            border: 1px solid #004B9B;
            color: #004B9B;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #004B9B;
            color: white;
        }

        .btn-register {
            padding: 8px 20px;
            background: #004B9B;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .btn-register:hover {
            background: #003580;
        }

        /* Main Content */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .welcome-container {
            text-align: center;
            max-width: 600px;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }

        .welcome-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .welcome-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 12px 32px;
            background: #E30613;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-primary:hover {
            background: #B91C1C;
            transform: translateY(-1px);
        }

        .btn-secondary {
            padding: 12px 32px;
            background: #004B9B;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #003580;
            transform: translateY(-1px);
        }

        /* Footer */
        footer {
            background: #1f2937;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 0.9rem;
        }

        footer p {
            color: #d1d5db;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 20px;
                padding: 16px 20px;
            }
            .welcome-title {
                font-size: 2rem;
            }
            .welcome-actions {
                flex-direction: column;
                align-items: center;
            }
            .btn-primary, .btn-secondary {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="logo-section">
            <div class="logo-mark">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Carrefour Logo -->
                    <circle cx="50" cy="50" r="45" fill="#E30613"/>
                    <circle cx="50" cy="50" r="35" fill="white"/>
                    <path d="M25 35 Q25 25 35 25 L65 25 Q75 25 75 35 L75 65 Q75 75 65 75 L35 75 Q25 75 25 65 Z" fill="#E30613"/>
                    <circle cx="35" cy="35" r="8" fill="white"/>
                    <circle cx="65" cy="35" r="8" fill="white"/>
                    <circle cx="35" cy="65" r="8" fill="white"/>
                    <circle cx="65" cy="65" r="8" fill="white"/>
                    <circle cx="50" cy="50" r="12" fill="#E30613"/>
                    <text x="50" y="56" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="16" font-weight="bold">C</text>
                </svg>
            </div>
            <div class="logo-text">Carrefour<br><span>IMS</span></div>
        </div>
        <div class="auth-buttons">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-register">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn-register">Sign Up</a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="welcome-container">
            <h1 class="welcome-title">Welcome to Carrefour IMS</h1>
            <p class="welcome-subtitle">
                Your comprehensive inventory management solution for streamlined procurement,
                warehouse operations, and branch logistics.
            </p>
            <div class="welcome-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Get Started</a>
                    <a href="{{ route('register') }}" class="btn-secondary">Create Account</a>
                @endauth
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} Carrefour Inventory Management System. All rights reserved.</p>
    </footer>

</body>
</html>