@php
    $currentRoute = request()->route()->getName();
@endphp

<nav class="bg-gradient-to-r from-red-700 to-red-800 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Sweetwen" class="h-10 w-auto" onerror="this.src='https://placehold.co/40x40/FFFFFF/white?text=SW'">
                    <span class="text-xl font-bold text-white">Sweetwen</span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition">
                    Home
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition">
                        Dashboard
                    </a>
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('jobs.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition">
                            Manage Jobs
                        </a>
                        <a href="{{ route('admin.applications') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition">
                            Applications
                        </a>
                        <a href="{{ route('admin.resignations') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition">
                            Resignations
                        </a>
                    @endif
                    
                    <!-- Fixed User Dropdown -->
                    <div class="relative ml-4 pl-4 border-l border-white/30">
                        <div class="flex items-center space-x-2 cursor-pointer group">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm font-medium text-white">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 hidden group-hover:block">
                            <div class="px-4 py-2 text-xs text-gray-500 border-b">
                                {{ auth()->user()->email }}
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded-b-lg">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/15 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="ml-2 px-5 py-2 bg-white text-red-700 hover:bg-gray-100 font-medium rounded-lg transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>