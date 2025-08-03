<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'SIMANIIS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .sidebar-gradient {
            background: linear-gradient(135deg, #15803d 0%, #166534 25%, #14532d 50%, #052e16 100%);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #f1f5f9 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        .pulse-dot {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Responsive layout fixes */
        @media (max-width: 1023px) {
            .main-content {
                width: 100%;
                margin-left: 0;
            }
            
            /* Ensure sidebar is full height on mobile */
            .sidebar-mobile {
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 50;
            }
        }
        
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 0;
            }
            
            /* Desktop sidebar positioning */
            .sidebar-desktop {
                position: relative;
                height: 100vh;
            }
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #10b981, #059669);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #059669, #047857);
        }

        /* Sidebar Scrollbar */
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin: 8px 0;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.2));
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.4));
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb:active {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
        }
        
        /* Scrollbar corner */
        .sidebar-scrollbar::-webkit-scrollbar-corner {
            background: transparent;
        }
        
        /* Firefox scrollbar */
        .sidebar-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.4) rgba(255, 255, 255, 0.1);
        }
        
        /* Smooth scrolling */
        .sidebar-scrollbar {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50 to-secondary-100">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        @auth
            <!-- Sidebar -->
            <div :class="{ 
                'translate-x-0': sidebarOpen, 
                '-translate-x-full': !sidebarOpen 
            }" 
            class="w-64 transition-transform duration-300 ease-in-out fixed lg:relative lg:translate-x-0 z-50 h-full bg-white shadow-xl lg:shadow-none">
                <div class="h-full sidebar-scrollbar overflow-y-auto">
                    @include('components.sidebar')
                </div>
            </div>

            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" 
                 @click="sidebarOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
                 style="display: none;">
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col h-screen">
                <!-- Header - Fixed at top -->
                <div class="flex-shrink-0 z-30">
                    @include('components.header')
                </div>
                
                <!-- Main Content Container - Takes remaining height -->
                <div class="flex-1 flex flex-col min-h-0">
                    <!-- Scrollable Content Area -->
                    <main class="flex-1 custom-scrollbar overflow-y-auto bg-gradient-to-br from-gray-50 via-primary-50 to-secondary-100">
                        <!-- Flash Messages -->
                        <div class="px-4 sm:px-6 lg:px-8 pt-6">
                            @if (session('success'))
                                <div class="bg-primary-50 border-l-4 border-primary-500 p-4 mb-6 rounded-r-lg shadow-lg" x-data="{ show: true }" x-show="show" x-transition>
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-primary-800">{{ session('success') }}</p>
                                        </div>
                                        <div class="ml-auto pl-3">
                                            <button @click="show = false" class="text-primary-500 hover:text-primary-700 transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-lg" x-data="{ show: true }" x-show="show" x-transition>
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                        </div>
                                        <div class="ml-auto pl-3">
                                            <button @click="show = false" class="text-red-500 hover:text-red-700 transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Page Content -->
                        <div class="px-4 sm:px-6 lg:px-8 py-6 pb-8">
                            @yield('content')
                        </div>
                    </main>
                    
                    <!-- Footer - Fixed at bottom -->
                    <div class="flex-shrink-0">
                        @include('components.footer')
                    </div>
                </div>
            </div>
        @else
            <main class="flex-1">
                @yield('content')
            </main>
        @endauth
    </div>
</body>
</html>