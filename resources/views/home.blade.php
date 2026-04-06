<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Kalyeng Trabaho') }} - Find Your Dream Job</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100">
    @auth
        @include('layouts.navigation')
    @endauth

    <main>
        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
                <div class="text-center">
                    <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 mb-6">
                        Find Your
                        <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                            Dream Job
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        Kalyeng Trabaho connects talented professionals with leading companies. Start your journey to a better career today.
                    </p>
                    @guest
                        <div class="flex gap-4 justify-center">
                            <a href="{{ route('register') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" 
                               class="bg-white hover:bg-gray-50 text-gray-700 px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl border border-gray-200">
                                Sign In
                            </a>
                        </div>
                    @else
                        <a href="{{ route('dashboard') }}" 
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                            Browse Jobs →
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Kalyeng Trabaho?</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">We make job searching simple and effective</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Latest Job Listings</h3>
                        <p class="text-gray-600">Access hundreds of job opportunities from top companies across various industries.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Verified Companies</h3>
                        <p class="text-gray-600">All companies are verified to ensure legitimate job opportunities.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Application</h3>
                        <p class="text-gray-600">Simple and straightforward application process to save your time.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8 text-center text-white">
                    <div>
                        <div class="text-4xl font-bold mb-2">500+</div>
                        <div class="text-blue-100">Job Listings</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold mb-2">200+</div>
                        <div class="text-blue-100">Companies</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold mb-2">1000+</div>
                        <div class="text-blue-100">Happy Applicants</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-20">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Start Your Career Journey?</h2>
                <p class="text-gray-600 mb-8">Join thousands of professionals who found their dream jobs through Kalyeng Trabaho.</p>
                @guest
                    <a href="{{ route('register') }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        Create Free Account
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        Explore Jobs Now
                    </a>
                @endguest
            </div>
        </div>
    </main>
</body>
</html>