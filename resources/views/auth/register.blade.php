<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sweetwen - Create Account</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%); }
    </style>
</head>
<body class="hero-gradient">
    <div class="min-h-screen flex flex-col justify-center items-center p-4">
        <!-- Logo -->
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Sweetwen" class="h-16 w-auto mx-auto mb-3" onerror="this.src='https://placehold.co/64x64/FFFFFF/white?text=SW'">
            <h1 class="text-2xl font-bold text-white">Sweetwen</h1>
            <p class="text-sm text-red-100 mt-1">Create your account</p>
        </div>

        <!-- Register Card -->
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        
                        <!-- Hidden role - always user -->
                        <input type="hidden" name="role" value="user">

                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name
                            </label>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition @error('name') border-red-500 @enderror"
                                placeholder="Juan Dela Cruz"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition @error('email') border-red-500 @enderror"
                                placeholder="name@example.com"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <input 
                                id="phone" 
                                type="tel" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                required 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition @error('phone') border-red-500 @enderror"
                                placeholder="09123456789"
                            >
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition @error('password') border-red-500 @enderror"
                                placeholder="Create a password"
                            >
                            <p class="text-xs text-gray-400 mt-1">Minimum 8 characters</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password
                            </label>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                placeholder="Confirm your password"
                            >
                        </div>

                        <!-- Info Note -->
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-blue-700">
                                By creating an account, you agree to be part of the Sweetwen talent pool.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-medium py-2.5 rounded-lg transition shadow-sm">
                            Create Account
                        </button>

                        <!-- Login Link -->
                        <div class="text-center pt-2">
                            <p class="text-sm text-gray-600">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-medium">
                                    Sign in
                                </a>
                            </p>
                        </div>

                        <!-- Admin Note -->
                        <div class="text-center">
                            <p class="text-xs text-gray-400">
                                Admin accounts are created by existing administrators.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-xs text-red-200">© {{ date('Y') }} Sweetwen Foods Corporation</p>
        </div>
    </div>
</body>
</html>