<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sweetwen - Job Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .nav-link {
            transition: all 0.2s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .card-hover {
            transition: all 0.2s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Fixed Dropdown Menu */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 8px;
            background-color: white;
            min-width: 180px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.15s ease, visibility 0.15s ease;
        }
        
        .user-dropdown:hover .user-dropdown-content {
            opacity: 1;
            visibility: visible;
        }
        
        .user-dropdown-content a,
        .user-dropdown-content button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px 16px;
            font-size: 14px;
            color: #374151;
            transition: background-color 0.2s;
        }
        
        .user-dropdown-content a:hover,
        .user-dropdown-content button:hover {
            background-color: #f3f4f6;
        }
        
        .user-dropdown-content button {
            border-top: 1px solid #e5e7eb;
            color: #dc2626;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation Bar - Gradient Red -->
    <nav class="bg-gradient-to-r from-red-700 to-red-800 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Sweetwen" class="h-10 w-auto" onerror="this.src='https://placehold.co/40x40/FFFFFF/white?text=SW'">
                    <div>
                        <span class="text-xl font-bold text-white">Sweetwen</span>
                        <span class="text-xs text-red-200 bg-white/20 px-2 py-0.5 rounded ml-2">Careers</span>
                    </div>
                </div>
                
<!-- Desktop Navigation -->
<div class="hidden md:flex items-center space-x-1">
    @auth
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition nav-link">
            Dashboard
        </a>
        
        @if(auth()->user()->isAdmin())
            <a href="{{ route('jobs.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition nav-link">
                Jobs
            </a>
            <a href="{{ route('admin.applications') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition nav-link">
                Applications
            </a>
            <a href="{{ route('admin.resignations') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition nav-link">
                Resignations
            </a>
        @endif
        
        <!-- User Dropdown - Fixed -->
        <div class="user-dropdown ml-4 pl-4 border-l border-white/30">
            <button class="flex items-center space-x-2 text-white hover:text-white">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="user-dropdown-content">
                <div class="px-4 py-2 text-xs text-gray-500 border-b">
                    {{ auth()->user()->email }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition nav-link">
            Sign In
        </a>
        <a href="{{ route('register') }}" class="ml-2 px-5 py-2 bg-white text-red-700 hover:bg-gray-100 font-medium rounded-lg transition shadow-sm">
            Create Account
        </a>
    @endauth
</div>
                
                <!-- Mobile menu button -->
                <button class="md:hidden p-2 rounded-lg text-white hover:bg-white/15">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-4 md:mb-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Sweetwen" class="h-8 w-auto" onerror="this.src='https://placehold.co/32x32/C41E3A/white?text=SW'">
                    <span class="text-sm text-gray-500">Sweetwen Foods Corporation</span>
                </div>
                <div class="text-center text-sm text-gray-400">
                    © {{ date('Y') }} Sweetwen. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</body>
</html>