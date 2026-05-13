<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrefour IMS - System Login</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-red-700 flex items-center justify-center">

    <div class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-2 bg-white rounded-2xl shadow-2xl overflow-hidden">

        <!-- LEFT SIDE (BRANDING PANEL) -->
        <div class="hidden md:flex flex-col justify-center items-center text-white p-10 bg-gradient-to-br from-blue-900 to-red-600">

            <h1 class="text-3xl font-bold mb-4">Carrefour IMS</h1>

            <p class="text-center text-sm opacity-90"> 
                Inventory • Procurement • Branch Requests • Logistics Control System
            </p>

            <div class="mt-8 text-xs opacity-80 text-center">
                Secure access for Admin, Warehouse, Procurement & Branch Managers
            </div>

        </div>

        <!-- RIGHT SIDE (LOGIN FORM) -->
        <div class="p-10">

            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-blue-900">Sign In</h2>
                <p class="text-sm text-gray-500">Login to access your dashboard</p>
            </div>

            <!-- SESSION STATUS -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="text-sm font-medium text-blue-900">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full mt-1 px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    >
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-sm font-medium text-blue-900">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full mt-1 px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    >
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- REMEMBER -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                    >
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition"
                >
                    Log In
                </button>

                <!-- FORGOT PASSWORD -->
                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-blue-700 hover:text-red-600 underline">
                            Forgot your password?
                        </a>
                    </div>
                @endif

            </form>

            <!-- FOOTER -->
            <div class="mt-6 text-center text-xs text-gray-400">
                © {{ date('Y') }} Warehouse Management System
            </div>

        </div>
    </div>

</body>
</html>