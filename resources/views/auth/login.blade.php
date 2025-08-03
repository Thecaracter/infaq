@extends('layouts.auth')

@section('title', 'Login')
@section('subtitle', 'Selamat datang kembali! Silakan masuk ke akun Anda.')

@section('content')
<!-- Login Form dengan animasi -->
<div class="animate-fade-in-up">
    <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false, isLoading: false }">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-primary-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                </div>
                <input id="email" 
                       class="block w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-200 bg-white shadow-sm focus:border-primary-500 focus:ring-4 focus:ring-primary-100 transition-all duration-300 text-gray-900 placeholder-gray-400 @error('email') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username"
                       placeholder="admin@simaniis.com" />
            </div>
            @error('email')
                <div class="animate-shake">
                    <p class="mt-2 text-sm text-red-600 flex items-center bg-red-50 px-3 py-2 rounded-lg">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-primary-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <input id="password" 
                       :type="showPassword ? 'text' : 'password'"
                       class="block w-full pl-12 pr-12 py-4 rounded-2xl border-2 border-gray-200 bg-white shadow-sm focus:border-primary-500 focus:ring-4 focus:ring-primary-100 transition-all duration-300 text-gray-900 placeholder-gray-400 @error('password') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                       name="password"
                       required 
                       autocomplete="current-password"
                       placeholder="••••••••" />
                <!-- Show/Hide Password Button -->
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-primary-600 transition-colors duration-200 z-10">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.5 6.5m3.378 3.378a3 3 0 004.243 4.243m0 0L17.5 17.5m-3.378-3.378l3.378 3.378m0 0l3.378-3.378"></path>
                    </svg>
                </button>
            </div>
            @error('password')
                <div class="animate-shake">
                    <p class="mt-2 text-sm text-red-600 flex items-center bg-red-50 px-3 py-2 rounded-lg">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center group cursor-pointer">
                <input id="remember" 
                       type="checkbox" 
                       class="h-4 w-4 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-400 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-all duration-200" 
                       name="remember">
                <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-800 transition-colors duration-200 select-none">Ingat saya</span>
            </label>
            
            <div class="text-sm">
                <a href="#" class="font-medium text-primary-600 hover:text-primary-500 transition-colors duration-200 hover:underline">
                    Lupa password?
                </a>
            </div>
        </div>

        <!-- Submit Button dengan loading state -->
        <div class="mt-8">
            <button type="submit" 
                    :disabled="isLoading"
                    :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                    class="group relative w-full flex justify-center items-center py-4 px-6 border border-transparent text-base font-semibold rounded-2xl text-white bg-gradient-to-r from-primary-600 via-primary-700 to-green-600 hover:from-primary-700 hover:via-primary-800 hover:to-green-700 focus:outline-none focus:ring-4 focus:ring-primary-200 focus:ring-offset-2 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl active:scale-[0.98] shadow-xl hover:shadow-primary-500/25 disabled:hover:scale-100">
                
                <!-- Loading Spinner -->
                <svg x-show="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                
                <!-- Login Icon -->
                <span x-show="!isLoading" class="absolute left-0 inset-y-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-primary-200 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </span>
                
                <!-- Button Text -->
                <span x-show="!isLoading" class="transition-all duration-300">Masuk ke Sistem</span>
                <span x-show="isLoading" class="transition-all duration-300" style="display: none;">Sedang Masuk...</span>
                
                <!-- Shine Effect -->
                <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                </div>
            </button>
        </div>
    </form>
</div>

<!-- Custom CSS untuk animasi -->
<style>
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
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out;
    }
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    /* Background animations */
    body {
        background: linear-gradient(-45deg, #f0fdf4, #dcfce7, #bbf7d0, #86efac);
        background-size: 400% 400%;
        animation: gradient-shift 15s ease infinite;
    }
    
    /* Floating elements */
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    /* Input smooth focus */
    input:focus {
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1), 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection