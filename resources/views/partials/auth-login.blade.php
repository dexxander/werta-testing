<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sign In - Werta' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F5EFE0] min-h-screen flex items-center justify-center font-[Lato,sans-serif]">
    <div class="w-full max-w-md mx-4">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta Logo" class="h-12 w-auto">
                <span class="flex items-baseline gap-px">
                    <span style="font-family: 'Great Vibes', cursive; font-size: 3rem; color: #C4A840; line-height: 1;">W</span>
                    <span style="font-family: 'Lato', sans-serif; font-size: 1.4rem; font-weight: 700; letter-spacing: 4px; color: #7B6B35;">ERTA</span>
                </span>
            </a>
        </div>

        <!-- Sign In Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-[#C4A840]/20 p-8">
            @if(!empty($portalLabel))
                <div class="flex items-center justify-center gap-2 mb-2">
                    <i class="bi {{ $badgeIcon ?? 'bi-shield-lock' }} text-[#C4A840] text-xl"></i>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#7B6B35]">{{ $portalLabel }}</span>
                </div>
            @endif

            <h2 class="text-2xl font-bold text-[#2C2416] text-center mb-2">{{ $heading ?? 'Welcome Back' }}</h2>
            <p class="text-sm text-gray-500 text-center mb-6">{{ $subheading ?? 'Sign in to access your dashboard.' }}</p>

            @if(session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                    <i class="bi bi-exclamation-circle mr-1"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                    <i class="bi bi-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ $postUrl ?? url('/login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#C4A840] focus:ring-2 focus:ring-[#C4A840]/20 outline-none transition-colors" placeholder="Enter your username">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#C4A840] focus:ring-2 focus:ring-[#C4A840]/20 outline-none transition-colors" placeholder="Enter your password">
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#C4A840] hover:bg-[#7B6B35] text-white font-semibold py-2.5 rounded-lg transition-colors shadow-sm">
                    Sign In
                </button>
            </form>

            @if(!empty($showRegisterLink))
                <p class="text-center text-sm text-gray-500 mt-5 pt-4 border-t border-gray-100">
                    Don't have an account? <a href="{{ $registerUrl ?? url('/auth/register') }}" class="text-[#7B6B35] hover:text-[#C4A840] font-semibold">Register here</a>
                </p>
            @endif
        </div>

        <!-- Back to Home -->
        <p class="text-center text-sm text-gray-500 mt-6">
            <a href="/" class="text-[#7B6B35] hover:text-[#C4A840] font-semibold"><i class="bi bi-arrow-left mr-1"></i> Back to Home</a>
        </p>
    </div>
</body>
</html>
