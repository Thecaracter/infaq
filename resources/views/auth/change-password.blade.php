@extends('layouts.auth')

@section('title', 'Ganti Password')
@section('subtitle', 'Ubah password akun Anda')

@section('content')
<form method="POST" action="{{ route('change-password') }}" class="space-y-6">
    @csrf

    <!-- Current Password -->
    <div class="space-y-2">
        <label for="current_password" class="block text-sm font-semibold text-gray-700">Password Lama</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <input id="current_password" 
                   class="block w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition duration-200 @error('current_password') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror" 
                   type="password" 
                   name="current_password" 
                   required 
                   placeholder="Masukkan password lama" />
        </div>
        @error('current_password')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- New Password -->
    <div class="space-y-2">
        <label for="password" class="block text-sm font-semibold text-gray-700">Password Baru</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <input id="password" 
                   class="block w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition duration-200 @error('password') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                   type="password"
                   name="password"
                   required 
                   placeholder="Masukkan password baru" />
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="space-y-2">
        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <input id="password_confirmation" 
                   class="block w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition duration-200"
                   type="password"
                   name="password_confirmation"
                   required 
                   placeholder="Konfirmasi password baru" />
        </div>
    </div>

    <div class="flex items-center justify-between space-x-4 mt-8">
        <a href="{{ route('dashboard') }}" 
           class="flex-1 bg-gray-100 hover:bg-gray-200 focus:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl transition ease-in-out duration-200 text-center border border-gray-300 hover:border-gray-400">
            Kembali
        </a>
        
        <button type="submit" class="flex-1 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl">
            Ganti Password
        </button>
    </div>
</form>
@endsection