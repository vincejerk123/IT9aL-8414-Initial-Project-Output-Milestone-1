<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sweetwen Careers</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%); }
        .feature-card { transition: all 0.3s ease; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="bg-white">
    @auth
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Sweetwen" class="h-10 w-auto" onerror="this.src='https://placehold.co/40x40/C41E3A/white?text=SW'">
                        <span class="text-xl font-bold text-gray-900">Sweetwen</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <main>
        <!-- Hero Section -->
        <div class="hero-gradient">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
                <div class="max-w-3xl mx-auto text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Sweetwen" class="h-20 w-auto mx-auto mb-8" onerror="this.src='https://placehold.co/80x80/C41E3A/white?text=SW'">
                    
                    <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6 tracking-tight">
                        Build Your Career at
                        <span class="text-yellow-300">Sweetwen</span>
                    </h1>
                    
                    <p class="text-lg text-red-100 mb-8 max-w-2xl mx-auto leading-relaxed">
                        Join the leading fast-food chain in the Philippines. We're looking for talented individuals to grow with us.
                    </p>
                    
                    <div class="flex flex-wrap gap-4 justify-center">
                        @guest
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-red-700 font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                                Join Our Team
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition-all">
                                Employee Login
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-white text-red-700 font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                                Go to Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Sweetwen Section -->
        <div class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Why Work at Sweetwen?</h2>
                    <p class="text-gray-500 mt-2 max-w-2xl mx-auto">We offer more than just a job — we offer a career.</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="feature-card p-6 text-center border border-gray-100 rounded-xl bg-white">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Career Growth</h3>
                        <p class="text-sm text-gray-500">Clear promotion paths from crew to management.</p>
                    </div>
                    
                    <div class="feature-card p-6 text-center border border-gray-100 rounded-xl bg-white">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Competitive Pay</h3>
                        <p class="text-sm text-gray-500">Above-market salaries and performance bonuses.</p>
                    </div>
                    
                    <div class="feature-card p-6 text-center border border-gray-100 rounded-xl bg-white">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Health Benefits</h3>
                        <p class="text-sm text-gray-500">Comprehensive health and wellness coverage.</p>
                    </div>
                    
                    <div class="feature-card p-6 text-center border border-gray-100 rounded-xl bg-white">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Work-Life Balance</h3>
                        <p class="text-sm text-gray-500">Flexible schedules and paid time off.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Openings Section -->
        <div class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Current Openings</h2>
                    <p class="text-gray-500 mt-2">Find your perfect role at Sweetwen</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-800">Service Crew</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">Frontline heroes serving happiness to every customer.</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">📍 Nationwide</span>
                            <span class="text-green-600 font-semibold">Open</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-800">Cashier</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">Handle transactions with a smile and positive attitude.</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">📍 Nationwide</span>
                            <span class="text-green-600 font-semibold">Open</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-800">Kitchen Crew</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">Prepare delicious Sweetwen meals with love and care.</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">📍 Nationwide</span>
                            <span class="text-green-600 font-semibold">Open</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-10">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-red-700 hover:bg-red-800 text-white font-semibold rounded-lg transition shadow-sm">
                        View All Positions
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- CTA Section -->
        <div class="bg-red-700 py-16">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-3xl font-bold text-white mb-4">Ready to Join Us?</h2>
                <p class="text-red-100 text-lg mb-8">Start your journey with Sweetwen today!</p>
                @guest
                    <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-white text-red-700 font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                        Apply Now
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 bg-white text-red-700 font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                        Browse Open Positions
                    </a>
                @endguest
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-gray-400 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm">© {{ date('Y') }} Sweetwen Foods Corporation. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>