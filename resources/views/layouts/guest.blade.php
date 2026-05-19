<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sweetwen Careers - Join the Sweetwen Family!</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            font-family: 'figtree', sans-serif;
            background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%);
            min-height: 100vh;
        }
        .sweetwen-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce {
            animation: bounce 2s infinite;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col justify-center items-center p-4">
        <!-- Sweetwen Logo -->
        <div class="text-center mb-8">
            <div class="text-6xl mb-2 animate-bounce">🍔⭐</div>
            <div class="text-white text-2xl font-bold tracking-wide">SWEETWEN</div>
            <div class="text-yellow-300 text-sm mt-1">Careers</div>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md">
            <div class="sweetwen-card overflow-hidden">
                <div class="p-6 sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white text-xs opacity-75">
                © {{ date('Y') }} Sweetwen Foods Corporation. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>