@php
    $currentRoute = request()->route()->getName();
@endphp

<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- LEFT SECTION - Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-200">
                        <img src="/images/logo.png" alt="Logo" class="w-6 h-6">
                    </div>
                    <span class="font-bold text-gray-900 text-lg hidden sm:inline">
                        Kalyeng Trabaho
                    </span>
                </a>
            </div>

            <!-- DESKTOP NAVIGATION LINKS -->
            <div class="hidden md:flex items-center gap-1">
                <!-- Home Link -->
                <a href="{{ route('home') }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Home</span>
                    </div>
                </a>

                <!-- Dashboard Link (Shows All Jobs) - Visible to authenticated users -->
                @auth
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>All Jobs</span>
                    </div>
                </a>

                <!-- Applied Jobs Link (Visible to non-admin users) -->
                @if(auth()->user()->role != 'admin')
                    <a href="{{ route('applications.my') }}" 
                       class="px-4 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('applications.my') ? 'text-purple-600 bg-purple-50' : 'text-gray-700 hover:text-purple-600 hover:bg-purple-50' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>My Applications</span>
                            @php
                                $pendingCount = Auth::user()->applications()->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="bg-yellow-500 text-white text-xs rounded-full px-2 py-0.5 ml-1">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endif

                <!-- Add Jobs Link (Admin Only!) -->
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('jobs.create') }}" 
                       class="px-4 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('jobs.create') ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm hover:shadow-md' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Add Job</span>
                        </div>
                    </a>
                @endif

                <!-- Manage Applications Link (Admin Only) -->
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('applications.manage') }}" 
                       class="px-4 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('applications.manage') ? 'text-purple-600 bg-purple-50' : 'text-gray-700 hover:text-purple-600 hover:bg-purple-50' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Applications</span>
                            @php
                                $pendingApplicationsCount = App\Models\Application::where('status', 'pending')->count();
                            @endphp
                            @if($pendingApplicationsCount > 0)
                                <span class="bg-yellow-500 text-white text-xs rounded-full px-2 py-0.5 ml-1">
                                    {{ $pendingApplicationsCount }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endif
                @endauth
            </div>

            <!-- RIGHT SECTION - User Menu / Auth Links -->
            <div class="flex items-center gap-4">
                @auth
                    <!-- Authenticated User Menu -->
                    <div class="hidden md:flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        
                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-red-600 hover:bg-red-50 font-medium transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Guest Links (Login & Register) -->
                    <div class="hidden md:flex items-center gap-2">
                        <a href="{{ route('login') }}" 
                           class="px-4 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('login') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Login</span>
                            </div>
                        </a>

                        <a href="{{ route('register') }}" 
                           class="px-4 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('register') ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <span>Register</span>
                            </div>
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="open = !open" 
                        class="md:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none transition-all duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" 
                              class="inline-flex" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }" 
                              class="hidden" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden border-t border-gray-200 bg-white shadow-lg">
        <div class="px-4 py-3 space-y-2">
            <!-- Mobile Home Link -->
            <a href="{{ route('home') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Home</span>
            </a>

            @auth
                <!-- Mobile Dashboard Link -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>All Jobs</span>
                </a>

                <!-- Mobile Applied Jobs Link (Visible to non-admin users) -->
                @if(auth()->user()->role != 'admin')
                    <a href="{{ route('applications.my') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('applications.my') ? 'text-purple-600 bg-purple-50' : 'text-gray-700 hover:text-purple-600 hover:bg-purple-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>My Applications</span>
                        @php
                            $pendingCount = Auth::user()->applications()->where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="bg-yellow-500 text-white text-xs rounded-full px-2 py-0.5 ml-1">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Mobile Add Job Link (Admin Only) -->
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('jobs.create') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('jobs.create') ? 'bg-blue-600 text-white' : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Job</span>
                    </a>

                    <!-- Mobile Manage Applications Link (Admin Only) -->
                    <a href="{{ route('applications.manage') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('applications.manage') ? 'text-purple-600 bg-purple-50' : 'text-gray-700 hover:text-purple-600 hover:bg-purple-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Applications</span>
                        @php
                            $pendingApplicationsCount = App\Models\Application::where('status', 'pending')->count();
                        @endphp
                        @if($pendingApplicationsCount > 0)
                            <span class="bg-yellow-500 text-white text-xs rounded-full px-2 py-0.5 ml-1">
                                {{ $pendingApplicationsCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Divider -->
                <div class="border-t border-gray-200 my-3"></div>

                <!-- Mobile User Info -->
                <div class="px-3 py-2">
                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                </div>

                <!-- Mobile Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 font-medium transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <!-- Mobile Guest Links -->
                <div class="border-t border-gray-200 my-3"></div>
                
                <a href="{{ route('login') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('login') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Login</span>
                </a>

                <a href="{{ route('register') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('register') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span>Register</span>
                </a>
            @endauth
        </div>
    </div>
</nav>