<!-- Sidebar Content -->
<aside class="h-full sidebar-gradient text-white flex flex-col">
    
    <!-- Logo section - Fixed at top -->
    <div class="flex items-center justify-center h-16 sm:h-20 border-b border-white/20 bg-gradient-to-r from-white/10 to-transparent flex-shrink-0 px-4">
        <div class="flex items-center space-x-2 sm:space-x-4">
            <div class="relative">
                <div class="w-8 h-8 sm:w-12 sm:h-12 bg-white rounded-xl sm:rounded-2xl flex items-center justify-center floating-icon shadow-xl">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-3 h-3 sm:w-4 sm:h-4 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full pulse-dot"></div>
            </div>
            <div class="hidden sm:block">
                <h1 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-white to-primary-100 bg-clip-text text-transparent">SIMANIIS</h1>
                <p class="text-xs sm:text-sm text-white/80 font-medium">Infaq Management System</p>
            </div>
        </div>
    </div>

    <!-- Navigation - Scrollable content with custom scrollbar -->
    <div class="flex-1 overflow-y-auto sidebar-scrollbar">
        <nav class="px-3 sm:px-6 py-4 sm:py-8 space-y-1 sm:space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="nav-item flex items-center px-3 sm:px-6 py-3 sm:py-4 mb-2 sm:mb-3 rounded-xl sm:rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }} backdrop-blur-sm border border-white/10">
                <div class="w-5 h-5 sm:w-6 sm:h-6 mr-3 sm:mr-4 bg-gradient-to-r from-primary-400 to-primary-500 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm sm:text-base">Dashboard</span>
                @if(request()->routeIs('dashboard'))
                    <div class="ml-auto w-2 h-2 bg-primary-300 rounded-full pulse-dot flex-shrink-0"></div>
                @endif
            </a>

            @if(Auth::user()->role === 'admin')
                <!-- Admin Menu -->
                <div class="mt-4 sm:mt-8">
                    <h3 class="px-3 sm:px-6 text-xs sm:text-sm font-bold text-white/70 uppercase tracking-wider mb-2 sm:mb-4 flex items-center">
                        <div class="w-1 h-3 sm:h-4 bg-gradient-to-b from-primary-400 to-primary-600 rounded-full mr-2 sm:mr-3 flex-shrink-0"></div>
                        Management
                    </h3>
                    
                    <!-- Data Master -->
                    <div class="mb-2 sm:mb-3" x-data="{ masterOpen: {{ request()->routeIs('admin.tahun-ajaran.*') || request()->routeIs('admin.kelas.*') || request()->routeIs('admin.siswa.*') ? 'true' : 'false' }} }">
                        <button @click="masterOpen = !masterOpen" 
                                class="nav-item flex items-center justify-between w-full px-3 sm:px-6 py-3 sm:py-4 rounded-xl sm:rounded-2xl text-white/90 hover:bg-white/10 hover:text-white transition-all duration-300 backdrop-blur-sm border border-white/5 hover:border-white/20 {{ request()->routeIs('admin.tahun-ajaran.*') || request()->routeIs('admin.kelas.*') || request()->routeIs('admin.siswa.*') ? 'bg-white/10 text-white border-white/20' : '' }}">
                            <div class="flex items-center min-w-0">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 mr-3 sm:mr-4 bg-gradient-to-r from-blue-400 to-blue-500 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2"></path>
                                    </svg>
                                </div>
                                <span class="font-semibold">Data Master</span>
                            </div>
                            <svg :class="masterOpen ? 'rotate-90 text-primary-300' : 'text-white/60'" class="w-5 h-5 transition-all duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="masterOpen" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="ml-10 mt-2 space-y-2" style="display: none;">
                            <a href="{{ route('admin.tahun-ajaran.index') }}" 
                               class="block px-4 py-3 text-sm transition-all duration-200 border border-transparent rounded-xl {{ request()->routeIs('admin.tahun-ajaran.*') ? 'text-white bg-white/20 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 hover:border-white/20' }}">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full mr-3 flex-shrink-0"></div>
                                    <span>Tahun Ajaran</span>
                                </div>
                            </a>
                            <a href="{{ route('admin.kelas.index') }}" 
                               class="block px-4 py-3 text-sm transition-all duration-200 border border-transparent rounded-xl {{ request()->routeIs('admin.kelas.*') ? 'text-white bg-white/20 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 hover:border-white/20' }}">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-primary-400 rounded-full mr-3 flex-shrink-0"></div>
                                    <span>Kelas</span>
                                </div>
                            </a>
                            <a href="{{ route('admin.siswa.index') }}" 
                               class="block px-4 py-3 text-sm transition-all duration-200 border border-transparent rounded-xl {{ request()->routeIs('admin.siswa.*') ? 'text-white bg-white/20 border-white/30' : 'text-white/80 hover:text-white hover:bg-white/10 hover:border-white/20' }}">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-purple-400 rounded-full mr-3 flex-shrink-0"></div>
                                    <span>Siswa</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- User Management -->
                    <a href="{{ route('admin.user.index') }}" class="nav-item flex items-center px-3 sm:px-6 py-3 sm:py-4 mb-2 sm:mb-3 rounded-xl sm:rounded-2xl text-white/90 hover:bg-white/10 hover:text-white transition-all duration-300 backdrop-blur-sm border border-white/5 hover:border-white/20 {{ request()->routeIs('admin.user.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 mr-3 sm:mr-4 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m4-2a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-sm sm:text-base">Kelola User</span>
                    </a>
                </div>
            @endif

            <!-- Transaksi -->
            <div class="mt-4 sm:mt-8">
                <h3 class="px-3 sm:px-6 text-xs sm:text-sm font-bold text-white/70 uppercase tracking-wider mb-2 sm:mb-4 flex items-center">
                    <div class="w-1 h-3 sm:h-4 bg-gradient-to-b from-green-400 to-blue-500 rounded-full mr-2 sm:mr-3 flex-shrink-0"></div>
                    Transaksi
                </h3>
                
                <a href="{{ route('tagihan.index') }}" class="nav-item flex items-center px-3 sm:px-6 py-3 sm:py-4 mb-2 sm:mb-3 rounded-xl sm:rounded-2xl text-white/90 hover:bg-white/10 hover:text-white transition-all duration-300 backdrop-blur-sm border border-white/5 hover:border-white/20 {{ request()->routeIs('tagihan.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                    <div class="w-5 h-5 sm:w-6 sm:h-6 mr-3 sm:mr-4 bg-gradient-to-r from-red-400 to-pink-500 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M7 21h10a2 2 0 002-2V9a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm sm:text-base">Management Tagihan</span>
                    @if(request()->routeIs('tagihan.*'))
                        <div class="ml-auto w-2 h-2 bg-red-300 rounded-full pulse-dot flex-shrink-0"></div>
                    @endif
                </a>

                

               <a href="{{ route('riwayat.index') }}" class="nav-item flex items-center px-3 sm:px-6 py-3 sm:py-4 mb-2 sm:mb-3 rounded-xl sm:rounded-2xl text-white/90 hover:bg-white/10 hover:text-white transition-all duration-300 backdrop-blur-sm border border-white/5 hover:border-white/20 {{ request()->routeIs('riwayat.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
    <div class="w-5 h-5 sm:w-6 sm:h-6 mr-3 sm:mr-4 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
    </div>
    <span class="font-semibold text-sm sm:text-base">Riwayat Pembayaran</span>
    @if(request()->routeIs('riwayat.*'))
        <div class="ml-auto w-2 h-2 bg-blue-300 rounded-full pulse-dot flex-shrink-0"></div>
    @endif
</a>
            </div>
            <!-- Extra space at bottom for better scroll -->
            <div class="h-8"></div>
        </nav>
    </div>

    <!-- Bottom section - Fixed at bottom -->
    <div class="border-t border-white/20 bg-gradient-to-r from-white/5 to-transparent backdrop-blur-sm p-6 flex-shrink-0">
        <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/20 flex-shrink-0">
                <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-white/70 capitalize">{{ Auth::user()->role }}</p>
            </div>
            <div class="w-3 h-3 bg-green-400 rounded-full shadow-sm flex-shrink-0"></div>
        </div>
    </div>
</aside>