<header class="header-gradient border-b border-secondary-200 w-full backdrop-blur-sm">
    <div class="px-3 sm:px-4 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Mobile menu button -->
            <button 
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-lg text-secondary-500 hover:text-primary-600 hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all duration-200 shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Logo for mobile -->
            <div class="lg:hidden">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"></path>
                        </svg>
                    </div>
                    <h1 class="text-base sm:text-lg font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
                        SIMANIIS
                    </h1>
                </div>
            </div>

            <!-- Page title for desktop -->
            <div class="hidden lg:block">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-primary-700 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-secondary-800">
                        @yield('page-title', 'Dashboard')
                    </h2>
                </div>
            </div>

            <!-- Right section -->
            <div class="flex items-center space-x-2 lg:space-x-4">
                <!-- User dropdown -->
                <div class="relative" x-data="{ userDropdownOpen: false }">
                    <button 
                        @click="userDropdownOpen = !userDropdownOpen"
                        class="flex items-center space-x-2 lg:space-x-3 p-1 lg:p-2 pr-2 lg:pr-4 rounded-lg lg:rounded-xl text-secondary-700 hover:bg-primary-50 transition-all duration-200 shadow-sm border border-secondary-200">
                        <!-- User avatar -->
                        <div class="relative">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg lg:rounded-xl flex items-center justify-center shadow-md">
                                <span class="text-white text-xs lg:text-sm font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-3 h-3 lg:w-4 lg:h-4 bg-green-400 border-2 border-white rounded-full"></div>
                        </div>
                        
                        <!-- User info (hidden on mobile) -->
                        <div class="hidden sm:block text-left">
                            <div class="text-sm font-semibold text-secondary-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-primary-600 capitalize font-medium">{{ Auth::user()->role }}</div>
                        </div>

                        <!-- Dropdown arrow -->
                        <svg class="h-3 w-3 lg:h-4 lg:w-4 text-secondary-400 transition-transform" :class="userDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div 
                        x-show="userDropdownOpen" 
                        @click.away="userDropdownOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-3 w-48 lg:w-56 bg-white rounded-lg lg:rounded-xl shadow-xl border border-secondary-200 py-2 z-50 backdrop-blur-sm"
                        style="display: none;">
                        
                        <div class="px-3 lg:px-4 py-2 lg:py-3 text-sm border-b border-secondary-100">
                            <div class="font-semibold text-secondary-900">{{ Auth::user()->name }}</div>
                            <div class="text-secondary-500 truncate">{{ Auth::user()->email }}</div>
                        </div>

                        <a href="{{ route('change-password') }}" class="flex items-center px-3 lg:px-4 py-2 lg:py-3 text-sm text-secondary-700 hover:bg-primary-50 hover:text-primary-700 transition-all duration-200">
                            <svg class="w-3 h-3 lg:w-4 lg:h-4 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Ganti Password
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-3 lg:px-4 py-2 lg:py-3 text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                                <svg class="w-3 h-3 lg:w-4 lg:h-4 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>