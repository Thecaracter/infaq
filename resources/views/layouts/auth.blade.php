<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMANIIS') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased overflow-hidden">
    <!-- Beautiful Gradient Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-green-100 via-emerald-50 to-primary-100"></div>
    <div class="fixed inset-0 bg-gradient-to-tl from-primary-200/30 via-transparent to-green-200/30"></div>
    <div class="fixed inset-0 bg-gradient-to-tr from-emerald-100/50 via-transparent to-primary-150/50"></div>
    
    <!-- Floating Components -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <!-- Floating Icon Cards -->
        <div class="absolute top-20 left-10 w-18 h-18 bg-gradient-to-br from-white/60 to-white/40 backdrop-blur-lg rounded-2xl shadow-lg border border-white/30 animate-float-slow rotate-12">
            <div class="flex items-center justify-center h-full">
                <svg class="w-9 h-9 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
        </div>
        
        <div class="absolute top-40 right-16 w-16 h-16 bg-gradient-to-br from-white/50 to-white/30 backdrop-blur-lg rounded-xl shadow-lg border border-white/20 animate-float-medium">
            <div class="flex items-center justify-center h-full">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
        
        <div class="absolute bottom-32 left-20 w-20 h-20 bg-gradient-to-br from-white/55 to-white/35 backdrop-blur-lg rounded-3xl shadow-xl border border-white/25 animate-float-fast -rotate-6">
            <div class="flex items-center justify-center h-full">
                <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
        
        <div class="absolute bottom-20 right-12 w-14 h-14 bg-gradient-to-br from-white/45 to-white/25 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-float-slow rotate-45">
            <div class="flex items-center justify-center h-full">
                <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        
        <div class="absolute top-60 left-1/3 w-12 h-12 bg-gradient-to-br from-white/40 to-white/20 backdrop-blur-md rounded-full shadow-lg border border-white/15 animate-float-medium">
            <div class="flex items-center justify-center h-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        
        <div class="absolute top-32 right-1/3 w-16 h-16 bg-gradient-to-br from-white/35 to-white/15 backdrop-blur-md rounded-2xl shadow-lg border border-white/10 animate-float-fast rotate-12">
            <div class="flex items-center justify-center h-full">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        
        <!-- Small Decorative Elements with Gradients -->
        <div class="absolute top-80 left-1/4 w-8 h-8 bg-gradient-to-r from-primary-300/60 to-green-300/60 rounded-full shadow-md animate-bounce-slow"></div>
        <div class="absolute bottom-60 right-1/4 w-6 h-6 bg-gradient-to-r from-green-400/70 to-emerald-400/70 rounded-full shadow-md animate-bounce-medium"></div>
        <div class="absolute top-96 right-20 w-10 h-10 bg-gradient-to-r from-emerald-300/50 to-primary-300/50 rounded-full shadow-lg animate-bounce-slow"></div>
        <div class="absolute bottom-40 left-1/3 w-7 h-7 bg-gradient-to-r from-primary-400/60 to-green-400/60 rounded-full shadow-md animate-bounce-medium"></div>
        
        <!-- Geometric Shapes with Gradients -->
        <div class="absolute top-24 left-1/2 w-16 h-3 bg-gradient-to-r from-primary-200/70 to-green-200/70 rounded-full shadow-sm animate-slide-horizontal"></div>
        <div class="absolute bottom-24 right-1/2 w-3 h-16 bg-gradient-to-b from-green-200/70 to-emerald-200/70 rounded-full shadow-sm animate-slide-vertical"></div>
        
        <!-- Large Floating Gradient Orbs -->
        <div class="absolute top-1/3 left-12 w-40 h-40 bg-gradient-to-r from-primary-200/40 to-green-200/40 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-1/3 right-12 w-48 h-48 bg-gradient-to-r from-emerald-200/35 to-primary-200/35 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-36 h-36 bg-gradient-to-r from-green-300/30 to-emerald-300/30 rounded-full blur-2xl animate-pulse-slow" style="animation-delay: 4s;"></div>
    </div>
    
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Main Card with Enhanced Design -->
        <div class="relative w-full sm:max-w-md mt-6 px-8 py-10 bg-gradient-to-br from-white/95 to-white/90 backdrop-blur-2xl shadow-2xl overflow-hidden sm:rounded-3xl border border-white/40">
            <!-- Decorative Elements on Card -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary-400 via-green-400 to-emerald-400 rounded-t-3xl"></div>
            <div class="absolute -top-2 -left-2 w-6 h-6 bg-gradient-to-br from-primary-300 to-green-300 rounded-full opacity-60"></div>
            <div class="absolute -top-1 -right-3 w-4 h-4 bg-gradient-to-br from-green-400 to-emerald-400 rounded-full opacity-70"></div>
            
            <!-- Logo dengan animasi enhanced -->
            <div class="flex justify-center mb-10">
                <div class="text-center">
                    <div class="inline-block p-6 bg-gradient-to-br from-primary-500 via-primary-600 to-green-600 rounded-3xl shadow-2xl mb-6 animate-float-logo border-4 border-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-700 via-primary-600 to-green-600 bg-clip-text text-transparent mb-2">SIMANIIS</h1>
                    <div class="w-24 h-1.5 bg-gradient-to-r from-primary-400 via-green-400 to-emerald-400 mx-auto mt-3 rounded-full shadow-sm"></div>
                </div>
            </div>
            
            <!-- Subtitle Enhanced -->
            <div class="text-center mb-8">
                <p class="text-gray-800 font-semibold text-lg">Sistem Manajemen Infaq Siswa</p>
                <p class="text-gray-600 mt-2 text-sm">@yield('subtitle', 'Silakan masuk untuk melanjutkan')</p>
            </div>

            <!-- Flash Messages Enhanced -->
            @if (session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 text-green-800 px-5 py-4 rounded-r-xl mb-6 shadow-lg animate-fade-in-up">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-400 text-red-800 px-5 py-4 rounded-r-xl mb-6 shadow-lg animate-fade-in-up">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Content -->
            @yield('content')

            <!-- Additional Info -->
            @yield('additional-info')
            
            <!-- Decorative Bottom Elements -->
            <div class="absolute bottom-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 via-green-400 to-primary-400 rounded-b-3xl"></div>
        </div>
        
        <!-- Enhanced Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-700 font-medium">© {{ date('Y') }} SIMANIIS</p>
            <p class="text-xs text-gray-600 mt-1">Sistem Manajemen Infaq Siswa</p>
        </div>
    </div>
    
    <!-- Enhanced Animation Styles -->
    <style>
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        
        @keyframes float-medium {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(-3deg); }
        }
        
        @keyframes float-fast {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(8deg); }
        }
        
        @keyframes float-logo {
            0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
            50% { transform: translateY(-8px) rotate(3deg) scale(1.05); }
        }
        
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-12px) scale(1.1); }
        }
        
        @keyframes bounce-medium {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-18px) scale(1.15); }
        }
        
        @keyframes slide-horizontal {
            0%, 100% { transform: translateX(0px) scaleX(1); }
            50% { transform: translateX(25px) scaleX(1.1); }
        }
        
        @keyframes slide-vertical {
            0%, 100% { transform: translateY(0px) scaleY(1); }
            50% { transform: translateY(-25px) scaleY(1.1); }
        }
        
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }
        
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-float-slow {
            animation: float-slow 6s ease-in-out infinite;
        }
        
        .animate-float-medium {
            animation: float-medium 4s ease-in-out infinite;
            animation-delay: 1s;
        }
        
        .animate-float-fast {
            animation: float-fast 3s ease-in-out infinite;
            animation-delay: 2s;
        }
        
        .animate-float-logo {
            animation: float-logo 5s ease-in-out infinite;
        }
        
        .animate-bounce-slow {
            animation: bounce-slow 3s ease-in-out infinite;
        }
        
        .animate-bounce-medium {
            animation: bounce-medium 2s ease-in-out infinite;
            animation-delay: 0.5s;
        }
        
        .animate-slide-horizontal {
            animation: slide-horizontal 4s ease-in-out infinite;
        }
        
        .animate-slide-vertical {
            animation: slide-vertical 5s ease-in-out infinite;
            animation-delay: 1s;
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 4s ease-in-out infinite;
        }
        
        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out;
        }
    </style>
</body>
</html>