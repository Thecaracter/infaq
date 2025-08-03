@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard ' . ($user->role === 'admin' ? 'Admin' : 'Tata Usaha'))

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Welcome Header - Responsive -->
    <div class="bg-gradient-to-r from-white via-primary-50 to-primary-100 overflow-hidden shadow-xl rounded-xl sm:rounded-2xl border border-primary-200">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent break-words">
                        Selamat Datang, {{ $user->name }}! 👋
                    </h1>
                    <p class="text-secondary-600 mt-1 sm:mt-2 text-sm sm:text-base lg:text-lg">
                        Dashboard {{ $user->role === 'admin' ? 'Administrator' : 'Tata Usaha' }}
                    </p>
                    @if($tahunAjaranAktif)
                        <div class="flex items-center mt-2 sm:mt-4 space-x-2">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-400 rounded-full animate-pulse flex-shrink-0"></div>
                            <p class="text-xs sm:text-sm font-medium text-secondary-700 truncate">
                                Tahun Ajaran Aktif: <span class="text-primary-600 font-semibold">{{ $tahunAjaranAktif->nama_tahun }}</span>
                            </p>
                        </div>
                    @endif
                </div>
                <div class="hidden sm:block flex-shrink-0">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-xl">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($user->role === 'admin')
        <!-- Statistics Cards untuk Admin - Responsive Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Total Siswa -->
            <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-secondary-200 rounded-xl sm:rounded-2xl">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-secondary-600 uppercase tracking-wider truncate">Total Siswa</p>
                            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900">{{ number_format($totalSiswa) }}</p>
                            <p class="text-xs text-blue-600 font-medium mt-1">Siswa Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kelas -->
            <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-secondary-200 rounded-xl sm:rounded-2xl">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-secondary-600 uppercase tracking-wider truncate">Total Kelas</p>
                            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900">{{ number_format($totalKelas) }}</p>
                            <p class="text-xs text-primary-600 font-medium mt-1">Kelas Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Bulan Ini -->
            <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-secondary-200 rounded-xl sm:rounded-2xl">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-secondary-600 uppercase tracking-wider truncate">Pembayaran</p>
                            <p class="text-sm sm:text-lg lg:text-2xl font-bold text-secondary-900">Rp {{ number_format($totalPembayaranBulanIni / 1000, 0) }}K</p>
                            <p class="text-xs text-yellow-600 font-medium mt-1">{{ \Carbon\Carbon::now()->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Tunggakan -->
            <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-secondary-200 rounded-xl sm:rounded-2xl">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-secondary-600 uppercase tracking-wider truncate">Tunggakan</p>
                            <p class="text-sm sm:text-lg lg:text-2xl font-bold text-secondary-900">Rp {{ number_format($totalTunggakan / 1000, 0) }}K</p>
                            <p class="text-xs text-red-600 font-medium mt-1">Perlu Tindak</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Recent Data untuk Admin - Responsive Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Chart Pembayaran -->
            <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                        <h3 class="text-lg sm:text-xl font-bold text-secondary-900">Grafik Pembayaran</h3>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-xs sm:text-sm text-secondary-600">6 Bulan Terakhir</span>
                        </div>
                    </div>
                    <div class="relative h-64 sm:h-80">
                        <canvas id="paymentChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Terbaru -->
            <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                        <h3 class="text-lg sm:text-xl font-bold text-secondary-900">Pembayaran Terbaru</h3>
                        <span class="px-2 py-1 sm:px-3 sm:py-1 bg-primary-100 text-primary-700 text-xs sm:text-sm font-medium rounded-full">Live</span>
                    </div>
                    <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 overflow-y-auto">
                        @forelse($pembayaranTerbaru as $pembayaran)
                            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-secondary-50 to-primary-50 rounded-lg sm:rounded-xl border border-secondary-200 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3 flex-1 min-w-0">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs sm:text-sm font-bold">{{ substr($pembayaran->siswa->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-secondary-900 text-sm sm:text-base truncate">{{ $pembayaran->siswa->nama_lengkap }}</p>
                                        <p class="text-xs sm:text-sm text-secondary-600 truncate">{{ $pembayaran->bulan_bayar }} • {{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</p>
                                        <p class="text-xs text-secondary-500 truncate">{{ $pembayaran->user->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 ml-2">
                                    <p class="font-bold text-primary-600 text-sm sm:text-lg">Rp {{ number_format($pembayaran->nominal / 1000, 0) }}K</p>
                                    <div class="w-2 h-2 bg-green-400 rounded-full mx-auto mt-1"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 sm:py-12">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-secondary-500 font-medium text-sm sm:text-base">Belum ada pembayaran</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Tunggakan Terbanyak - Responsive Table -->
        <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                    <h3 class="text-lg sm:text-xl font-bold text-secondary-900">Siswa dengan Tunggakan Terbanyak</h3>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-red-500 rounded-full animate-pulse"></div>
                        <span class="text-xs sm:text-sm text-red-600 font-medium">Prioritas Tinggi</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="hidden sm:table-header-group">
                            <tr class="bg-secondary-50">
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-secondary-700 uppercase tracking-wider rounded-l-xl">Nama Siswa</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-secondary-700 uppercase tracking-wider">Bulan</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-secondary-700 uppercase tracking-wider">Nominal</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-secondary-700 uppercase tracking-wider rounded-r-xl">Jatuh Tempo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-200">
                            @forelse($tunggakanTerbanyak as $tunggakan)
                                <!-- Desktop View -->
                                <tr class="hidden sm:table-row hover:bg-red-50 transition-colors duration-200">
                                    <td class="px-4 sm:px-6 py-3 sm:py-4">
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                                <span class="text-white text-xs sm:text-sm font-bold">{{ substr($tunggakan->siswa->nama_lengkap, 0, 1) }}</span>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-semibold text-secondary-900 text-sm sm:text-base truncate">{{ $tunggakan->siswa->nama_lengkap }}</div>
                                                <div class="text-xs sm:text-sm text-secondary-500 truncate">{{ $tunggakan->siswa->nis }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4">
                                        <span class="px-2 py-1 sm:px-3 sm:py-1 bg-yellow-100 text-yellow-800 text-xs sm:text-sm font-medium rounded-full">
                                            {{ $tunggakan->bulan_tunggakan }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4">
                                        <span class="font-bold text-red-600 text-sm sm:text-lg">
                                            Rp {{ number_format($tunggakan->nominal / 1000, 0) }}K
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6"></path>
                                            </svg>
                                            <span class="text-xs sm:text-sm text-secondary-600">{{ $tunggakan->tanggal_jatuh_tempo->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Mobile Card View -->
                                <tr class="sm:hidden">
                                    <td colspan="4" class="p-0">
                                        <div class="p-4 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-lg mb-3 hover:shadow-md transition-all duration-200">
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white text-sm font-bold">{{ substr($tunggakan->siswa->nama_lengkap, 0, 1) }}</span>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="font-semibold text-secondary-900 truncate">{{ $tunggakan->siswa->nama_lengkap }}</p>
                                                        <p class="text-sm text-secondary-600 truncate">{{ $tunggakan->siswa->nis }}</p>
                                                    </div>
                                                </div>
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full flex-shrink-0">
                                                    {{ $tunggakan->bulan_tunggakan }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center pt-3 border-t border-red-200">
                                                <div>
                                                    <p class="text-xs text-secondary-600">Nominal</p>
                                                    <p class="font-bold text-red-600">Rp {{ number_format($tunggakan->nominal / 1000, 0) }}K</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xs text-secondary-600">Jatuh Tempo</p>
                                                    <p class="text-sm text-secondary-700 font-medium">{{ $tunggakan->tanggal_jatuh_tempo->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-secondary-500 font-medium text-sm sm:text-base">Tidak ada tunggakan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @else
        <!-- Statistics Cards untuk TU - Responsive -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
            <!-- Total Siswa -->
            <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200 hover:shadow-xl sm:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-secondary-600 uppercase tracking-wider truncate">Total Siswa</p>
                            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900">{{ number_format($totalSiswa) }}</p>
                            <p class="text-xs text-blue-600 font-medium mt-1">Siswa Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Pembayaran Bulan Ini -->
            <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200 hover:shadow-xl sm:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-secondary-600 uppercase tracking-wider truncate">Input Pembayaran</p>
                            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900">{{ number_format($pembayaranBulanIni) }}</p>
                            <p class="text-xs text-primary-600 font-medium mt-1">Bulan Ini</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Nominal Bulan Ini -->
            <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200 hover:shadow-xl sm:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-secondary-600 uppercase tracking-wider truncate">Total Nominal</p>
                            <p class="text-sm sm:text-lg lg:text-2xl font-bold text-secondary-900">Rp {{ number_format($totalNominalBulanIni / 1000, 0) }}K</p>
                            <p class="text-xs text-yellow-600 font-medium mt-1">Bulan Ini</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Siswa Menunggak -->
            <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200 hover:shadow-xl sm:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-secondary-600 uppercase tracking-wider truncate">Siswa Menunggak</p>
                            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900">{{ number_format($totalTunggakan) }}</p>
                            <p class="text-xs text-red-600 font-medium mt-1">Perlu Tindakan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content untuk TU - Responsive -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Pembayaran yang Saya Input -->
            <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                        <h3 class="text-lg sm:text-xl font-bold text-secondary-900">Pembayaran yang Saya Input</h3>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-primary-500 rounded-full"></div>
                            <span class="text-xs sm:text-sm text-primary-600 font-medium">Terbaru</span>
                        </div>
                    </div>
                    <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 overflow-y-auto">
                        @forelse($pembayaranTerbaru as $pembayaran)
                            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-secondary-50 to-primary-50 rounded-lg sm:rounded-xl border border-secondary-200 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center space-x-2 sm:space-x-3 flex-1 min-w-0">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs sm:text-sm font-bold">{{ substr($pembayaran->siswa->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-secondary-900 text-sm sm:text-base truncate">{{ $pembayaran->siswa->nama_lengkap }}</p>
                                        <p class="text-xs sm:text-sm text-secondary-600 truncate">{{ $pembayaran->bulan_bayar }} • {{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</p>
                                        <p class="text-xs text-secondary-500 truncate">{{ $pembayaran->kode_transaksi }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 ml-2">
                                    <p class="font-bold text-primary-600 text-sm sm:text-lg">Rp {{ number_format($pembayaran->nominal / 1000, 0) }}K</p>
                                    <div class="w-2 h-2 bg-green-400 rounded-full mx-auto mt-1"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 sm:py-12">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-secondary-500 font-medium text-sm sm:text-base">Belum ada pembayaran yang diinput</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Siswa Menunggak -->
            <div class="bg-white overflow-hidden shadow-lg sm:shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                        <h3 class="text-lg sm:text-xl font-bold text-secondary-900">Siswa dengan Tunggakan</h3>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-red-500 rounded-full animate-pulse"></div>
                            <span class="text-xs sm:text-sm text-red-600 font-medium">Perhatian</span>
                        </div>
                    </div>
                    <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 overflow-y-auto">
                        @forelse($siswaMenunggak as $siswa)
                            <div class="p-3 sm:p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg sm:rounded-xl border border-red-200 hover:shadow-md transition-all duration-200">
                                <div class="flex justify-between items-start mb-2 sm:mb-3">
                                    <div class="flex items-center space-x-2 sm:space-x-3 flex-1 min-w-0">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs sm:text-sm font-bold">{{ substr($siswa->nama_lengkap, 0, 1) }}</span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-secondary-900 text-sm sm:text-base truncate">{{ $siswa->nama_lengkap }}</p>
                                            <p class="text-xs sm:text-sm text-secondary-600 truncate">{{ $siswa->nis }} • Kelas {{ $siswa->kelas->nama_kelas }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 sm:px-3 sm:py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full flex-shrink-0">
                                        {{ $siswa->tunggakans->count() }} bulan
                                    </span>
                                </div>
                                <div class="pt-2 sm:pt-3 border-t border-red-200">
                                    <p class="text-xs sm:text-sm font-bold text-red-600">
                                        Total: Rp {{ number_format($siswa->tunggakans->sum('nominal') / 1000, 0) }}K
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 sm:py-12">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-secondary-500 font-medium text-sm sm:text-base">Tidak ada siswa yang menunggak</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if($user->role === 'admin')
<!-- Chart Script untuk Admin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('paymentChart').getContext('2d');
    const chartData = @json($chartData);
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(34, 197, 94, 0.3)');
    gradient.addColorStop(1, 'rgba(34, 197, 94, 0.05)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(item => item.bulan),
            datasets: [{
                label: 'Pembayaran (Rp)',
                data: chartData.map(item => item.total),
                borderColor: '#22c55e',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#22c55e',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#22c55e',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return 'Total: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            weight: 'bold',
                            size: window.innerWidth < 640 ? 10 : 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        callback: function(value) {
                            return 'Rp ' + (value / 1000) + 'K';
                        },
                        font: {
                            weight: 'bold',
                            size: window.innerWidth < 640 ? 10 : 12
                        }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection