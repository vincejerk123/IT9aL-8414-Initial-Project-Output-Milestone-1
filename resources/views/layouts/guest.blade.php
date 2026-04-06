<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Kalyeng Trabaho') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center p-4">
        <!-- Logo + Title -->
        <div class="text-center mb-8 animate-fade-in">
            <a href="/" class="flex flex-col items-center gap-3 group">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <img src="/images/logo.png" class="w-12 h-12">
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                        Kalyeng Trabaho
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Find your dream job today</p>
                </div>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="p-6 sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>