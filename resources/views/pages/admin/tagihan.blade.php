@extends('layouts.app')

@section('title', 'Management Tagihan')

@section('content')
<div class="container-fluid px-2 sm:px-4 py-4 sm:py-6">
    <!-- Header Section - Responsive -->
    <div class="flex flex-col space-y-4 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900">Management Tagihan</h1>
                <p class="text-sm sm:text-base text-secondary-600 mt-1">Kelola tagihan dan pembayaran infaq siswa</p>
            </div>
            
            @if(Auth::user()->role === 'admin')
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <button onclick="showGenerateModal()" 
                        class="bg-primary-600 hover:bg-primary-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden sm:inline">Generate Tagihan</span>
                    <span class="sm:hidden">Generate</span>
                </button>
                <button onclick="bulkReminder()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span class="hidden sm:inline">Bulk Reminder (50)</span>
                    <span class="sm:hidden">WA (50)</span>
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards - Responsive Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-4 sm:mb-6">
        <div class="bg-white p-3 sm:p-6 rounded-xl shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 bg-blue-100 rounded-lg">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-2 sm:ml-4">
                    <p class="text-xs sm:text-sm text-gray-600">Total Siswa</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($siswaList->total()) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-3 sm:p-6 rounded-xl shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 bg-green-100 rounded-lg">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-2 sm:ml-4">
                    <p class="text-xs sm:text-sm text-gray-600">Lunas</p>
                    <p class="text-lg sm:text-2xl font-bold text-green-600">{{ number_format($statistik['lunas']) }}</p>
                    <p class="text-xs text-gray-500">{{ $statistik['persentase_lunas'] }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-3 sm:p-6 rounded-xl shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 bg-red-100 rounded-lg">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-2 sm:ml-4">
                    <p class="text-xs sm:text-sm text-gray-600">Ada Tunggakan</p>
                    <p class="text-lg sm:text-2xl font-bold text-red-600">{{ $siswaList->where('total_tunggakan', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-3 sm:p-6 rounded-xl shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-2 sm:ml-4 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Total Outstanding</p>
                    <p class="text-sm sm:text-2xl font-bold text-yellow-600 truncate">Rp {{ number_format($siswaList->sum('nominal_tunggakan') / 1000) }}K</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form - Mobile Optimized -->
    <div class="bg-white rounded-xl shadow-sm border p-3 sm:p-4 mb-4 sm:mb-6">
        <form id="searchForm" method="GET" action="{{ route('tagihan.index') }}" class="space-y-3 sm:space-y-0 sm:flex sm:flex-wrap sm:items-end sm:gap-4">
            <!-- Search Input - Full width on mobile -->
            <div class="w-full sm:flex-1 sm:min-w-0">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau NIS siswa..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-primary-500 focus:border-primary-500"
                       autocomplete="off">
            </div>
            
            <!-- Filters Row -->
            <div class="grid grid-cols-2 gap-2 sm:flex sm:gap-4">
                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-2 sm:px-3 py-2 text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="nunggak" {{ $status == 'nunggak' ? 'selected' : '' }}>Ada Tunggakan</option>
                        <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Tidak Ada Tunggakan</option>
                    </select>
                </div>

                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas" class="w-full border border-gray-300 rounded-lg px-2 sm:px-3 py-2 text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelasItem)
                            <option value="{{ $kelasItem->id }}" {{ $kelas == $kelasItem->id ? 'selected' : '' }}>
                                {{ $kelasItem->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none bg-primary-600 hover:bg-primary-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>
                
                <a href="{{ route('tagihan.index') }}" class="flex-1 sm:flex-none text-center bg-gray-600 hover:bg-gray-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table - Responsive -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <!-- Mobile Card View (Hidden on Desktop) -->
        <div class="block sm:hidden">
            @forelse($siswaList as $siswa)
            <div class="p-4 border-b border-gray-200 last:border-b-0" onclick="showSiswaDetail({{ $siswa->id }})">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-sm">{{ substr($siswa->nama_lengkap, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $siswa->nama_lengkap }}</h3>
                                <p class="text-xs text-gray-500">{{ $siswa->nis }} • {{ $siswa->kelas->nama_kelas ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $siswa->orangTua->nama_wali ?? '-' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                @if($siswa->nominal_tunggakan > 0)
                                    <span class="text-xs font-bold text-red-700">Rp {{ number_format($siswa->nominal_tunggakan / 1000) }}K</span>
                                @else
                                    <span class="text-xs text-gray-500">-</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center">
                                @if($siswa->total_tunggakan > 0)
                                    <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                    <span class="text-xs text-red-700">{{ $siswa->total_tunggakan }} bulan</span>
                                @else
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                    <span class="text-xs text-green-700">Lunas</span>
                                @endif
                            </div>
                            
                            <div class="flex space-x-1" onclick="event.stopPropagation()">
                                <button onclick="showSiswaDetail({{ $siswa->id }})" 
                                        class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                    Detail
                                </button>
                                @if($siswa->total_tunggakan > 0)
                                    <button onclick="kirimReminderSiswa({{ $siswa->id }})" 
                                            class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
                                        WA
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-500 font-medium">Tidak ada data siswa</p>
                <p class="text-gray-400 text-sm">Coba ubah filter pencarian</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View (Hidden on Mobile) -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orang Tua</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Tunggakan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Outstanding</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswaList as $siswa)
                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="showSiswaDetail({{ $siswa->id }})">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($siswa->nama_lengkap, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500">{{ $siswa->nis }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                {{ $siswa->kelas->nama_kelas ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $siswa->orangTua->nama_wali ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $siswa->orangTua->no_hp ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($siswa->total_tunggakan > 0)
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-red-700">{{ $siswa->total_tunggakan }} bulan tunggak</span>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-green-700">Tidak ada tunggakan</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($siswa->nominal_tunggakan > 0)
                                <span class="text-sm font-bold text-red-700">Rp {{ number_format($siswa->nominal_tunggakan, 0, ',', '.') }}</span>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                            <div class="flex space-x-2">
                                <button onclick="showSiswaDetail({{ $siswa->id }})" 
                                        class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </button>
                                
                                @if($siswa->total_tunggakan > 0)
                                    <button onclick="kirimReminderSiswa({{ $siswa->id }})" 
                                            class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        WA
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-500 font-medium">Tidak ada data siswa</p>
                                <p class="text-gray-400 text-sm">Coba ubah filter pencarian</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-3 border-t border-gray-200">
            {{ $siswaList->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal Generate Tagihan - Mobile Optimized -->
@if(Auth::user()->role === 'admin')
<div id="generateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Generate Tagihan Bulanan</h3>
        </div>
        <form id="generateForm" class="p-4 sm:p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <input type="month" id="generateBulan" value="{{ \Carbon\Carbon::now()->format('Y-m') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg font-medium transition-colors">
                    Generate
                </button>
                <button type="button" onclick="hideGenerateModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-medium transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Detail Siswa - Mobile Optimized -->
<div id="siswaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[95vh] overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Detail Siswa & Tunggakan</h3>
            <button onclick="hideSiswaModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="overflow-y-auto max-h-[calc(95vh-80px)]">
            <div id="siswaContent">
                <div class="p-4 sm:p-6">
                    <div class="animate-pulse space-y-4">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran - Mobile Optimized -->
<div id="pembayaranModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="p-4 sm:p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Input Pembayaran</h3>
            <button onclick="hidePembayaranModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="pembayaranForm" class="p-4 sm:p-6">
            <input type="hidden" id="tunggakanId">
            <div id="pembayaranInfo" class="mb-4 p-3 bg-gray-50 rounded-lg"></div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                <input type="number" id="nominalBayar" placeholder="Masukkan nominal" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar</label>
                <input type="date" id="tanggalBayar" value="{{ date('Y-m-d') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea id="keteranganBayar" rows="3" placeholder="Catatan pembayaran..." 
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium transition-colors">
                    Simpan Pembayaran
                </button>
                <button type="button" onclick="hidePembayaranModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-medium transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Prevent race conditions dengan debouncing
let searchTimeout;
let loadingStates = new Map();

// Modal functions
function showGenerateModal() {
    document.getElementById('generateModal').classList.remove('hidden');
    document.getElementById('generateModal').classList.add('flex');
}

function hideGenerateModal() {
    document.getElementById('generateModal').classList.add('hidden');
    document.getElementById('generateModal').classList.remove('flex');
}

function showSiswaModal() {
    document.getElementById('siswaModal').classList.remove('hidden');
    document.getElementById('siswaModal').classList.add('flex');
}

function hideSiswaModal() {
    document.getElementById('siswaModal').classList.add('hidden');
    document.getElementById('siswaModal').classList.remove('flex');
}

function showPembayaranModal() {
    document.getElementById('pembayaranModal').classList.remove('hidden');
    document.getElementById('pembayaranModal').classList.add('flex');
}

function hidePembayaranModal() {
    document.getElementById('pembayaranModal').classList.add('hidden');
    document.getElementById('pembayaranModal').classList.remove('flex');
    document.getElementById('pembayaranForm').reset();
}

// Load siswa detail dengan staging - Mobile Optimized
async function showSiswaDetail(siswaId) {
    if (loadingStates.has(siswaId)) {
        return; // Prevent duplicate requests
    }
    
    showSiswaModal();
    loadingStates.set(siswaId, true);
    
    try {
        const response = await fetch(`/tagihan/siswa/${siswaId}`);
        const data = await response.json();
        
        if (data.success) {
            renderSiswaDetail(data.data);
        } else {
            showError('Gagal memuat data siswa');
        }
    } catch (error) {
        showError('Error memuat data: ' + error.message);
    } finally {
        loadingStates.delete(siswaId);
    }
}

function renderSiswaDetail(data) {
    const { siswa, tunggakan_belum, tunggakan_lunas, riwayat_pembayaran, summary } = data;
    
    const html = `
        <div class="p-3 sm:p-6 space-y-4 sm:space-y-6">
            <!-- Profile Siswa - Mobile Responsive -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 sm:p-6 rounded-xl">
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-lg sm:text-xl">${siswa.nama_lengkap.charAt(0)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 break-words">${siswa.nama_lengkap}</h3>
                        <p class="text-sm sm:text-base text-gray-600">NIS: ${siswa.nis} • Kelas: ${siswa.kelas.nama_kelas}</p>
                        <p class="text-sm sm:text-base text-gray-600 break-words">Orang Tua: ${siswa.orang_tua?.nama_wali || '-'} • ${siswa.orang_tua?.no_hp || '-'}</p>
                    </div>
                    <div class="text-right w-full sm:w-auto">
                        <div class="text-xl sm:text-2xl font-bold text-red-600">Rp ${summary.total_tunggakan.toLocaleString()}</div>
                        <div class="text-xs sm:text-sm text-gray-500">${summary.jumlah_bulan_nunggak} bulan tunggak</div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards - Mobile Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                <div class="bg-red-50 p-3 sm:p-4 rounded-lg">
                    <div class="text-red-700 font-semibold text-sm">Belum Bayar</div>
                    <div class="text-lg sm:text-2xl font-bold text-red-800">${summary.jumlah_bulan_nunggak} bulan</div>
                </div>
                <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg">
                    <div class="text-yellow-700 font-semibold text-sm">Terlambat</div>
                    <div class="text-lg sm:text-2xl font-bold text-yellow-800">${summary.bulan_terlambat} bulan</div>
                </div>
                <div class="bg-green-50 p-3 sm:p-4 rounded-lg">
                    <div class="text-green-700 font-semibold text-sm">Total Lunas</div>
                    <div class="text-lg sm:text-2xl font-bold text-green-800">Rp ${summary.total_lunas.toLocaleString()}</div>
                </div>
            </div>

            <!-- Tabs - Mobile Friendly -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-4 sm:space-x-8 overflow-x-auto">
                    <button onclick="switchTab('tunggakan')" id="tab-tunggakan" class="py-2 px-1 border-b-2 border-red-500 font-medium text-sm text-red-600 whitespace-nowrap">
                        Tunggakan (${summary.jumlah_bulan_nunggak})
                    </button>
                    <button onclick="switchTab('riwayat')" id="tab-riwayat" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                        Riwayat Pembayaran
                    </button>
                </nav>
            </div>

            <!-- Tab Content: Tunggakan - Mobile Cards -->
            <div id="content-tunggakan" class="space-y-3">
                ${tunggakan_belum.length > 0 ? tunggakan_belum.map(item => `
                    <div class="border border-red-200 bg-red-50 rounded-lg p-3 sm:p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                                    <span class="font-semibold text-red-800">${formatMonth(item.bulan_tunggakan)}</span>
                                    ${item.tanggal_jatuh_tempo < new Date().toISOString().split('T')[0] ? 
                                        '<span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full inline-block mt-1 sm:mt-0">Terlambat</span>' : ''
                                    }
                                </div>
                                <div class="text-xs sm:text-sm text-red-600 mt-1">
                                    Jatuh tempo: ${formatDate(item.tanggal_jatuh_tempo)} • Rp ${item.nominal.toLocaleString()}
                                </div>
                            </div>
                            <button onclick="bayarTunggakan(${item.id}, '${item.bulan_tunggakan}', ${item.nominal})" 
                                    class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors mt-2 sm:mt-0">
                                Bayar
                            </button>
                        </div>
                    </div>
                `).join('') : '<div class="text-center py-8 text-gray-500">Tidak ada tunggakan</div>'}
            </div>

            <!-- Tab Content: Riwayat - Mobile Cards -->
            <div id="content-riwayat" class="space-y-3 hidden">
                ${riwayat_pembayaran.length > 0 ? riwayat_pembayaran.map(item => `
                    <div class="border border-green-200 bg-green-50 rounded-lg p-3 sm:p-4">
                        <div class="flex flex-col sm:flex-row justify-between">
                            <div class="flex-1">
                                <div class="font-semibold text-green-800">${formatMonth(item.bulan_bayar)}</div>
                                <div class="text-xs sm:text-sm text-green-600">
                                    ${formatDate(item.tanggal_bayar)} • Oleh: ${item.user.name}
                                </div>
                                ${item.keterangan ? `<div class="text-xs sm:text-sm text-gray-600 mt-1">${item.keterangan}</div>` : ''}
                            </div>
                            <div class="text-right mt-2 sm:mt-0">
                                <div class="font-bold text-green-700">Rp ${item.nominal.toLocaleString()}</div>
                                <div class="text-xs text-green-600">${item.kode_transaksi}</div>
                            </div>
                        </div>
                    </div>
                `).join('') : '<div class="text-center py-8 text-gray-500">Belum ada pembayaran</div>'}
            </div>

            <!-- Action Buttons - Mobile Full Width -->
            ${summary.jumlah_bulan_nunggak > 0 ? `
                <div class="pt-4 border-t">
                    <button onclick="kirimReminderSiswa(${siswa.id})" 
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg font-medium transition-colors">
                        Kirim Reminder WA
                    </button>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('siswaContent').innerHTML = html;
}

// Helper functions
function formatMonth(monthStr) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const [year, month] = monthStr.split('-');
    return `${months[parseInt(month) - 1]} ${year}`;
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit', 
        year: 'numeric'
    });
}

// Tab switching
function switchTab(tab) {
    // Reset all tabs
    document.getElementById('tab-tunggakan').className = 'py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap';
    document.getElementById('tab-riwayat').className = 'py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap';
    
    document.getElementById('content-tunggakan').classList.add('hidden');
    document.getElementById('content-riwayat').classList.add('hidden');
    
    // Activate selected tab
    if (tab === 'tunggakan') {
        document.getElementById('tab-tunggakan').className = 'py-2 px-1 border-b-2 border-red-500 font-medium text-sm text-red-600 whitespace-nowrap';
        document.getElementById('content-tunggakan').classList.remove('hidden');
    } else {
        document.getElementById('tab-riwayat').className = 'py-2 px-1 border-b-2 border-green-500 font-medium text-sm text-green-600 whitespace-nowrap';
        document.getElementById('content-riwayat').classList.remove('hidden');
    }
}

// Bayar tunggakan
function bayarTunggakan(tunggakanId, bulan, nominal) {
    document.getElementById('tunggakanId').value = tunggakanId;
    document.getElementById('nominalBayar').value = nominal;
    document.getElementById('pembayaranInfo').innerHTML = `
        <div class="text-sm">
            <div class="font-semibold text-gray-800">Pembayaran ${formatMonth(bulan)}</div>
            <div class="text-gray-600">Nominal tagihan: Rp ${nominal.toLocaleString()}</div>
        </div>
    `;
    showPembayaranModal();
}

// Form submissions
document.getElementById('generateForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const bulan = document.getElementById('generateBulan').value;
    
    try {
        const response = await fetch('{{ route("tagihan.generate.ajax") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ bulan: bulan })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Generate tagihan berhasil!\n' + data.message);
            hideGenerateModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showError('Error: ' + data.message);
        }
    } catch (error) {
        showError('Error: ' + error.message);
    }
});

document.getElementById('pembayaranForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = {
        tunggakan_id: document.getElementById('tunggakanId').value,
        nominal: document.getElementById('nominalBayar').value,
        tanggal_bayar: document.getElementById('tanggalBayar').value,
        keterangan: document.getElementById('keteranganBayar').value
    };
    
    try {
        const response = await fetch('{{ route("tagihan.pembayaran") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Pembayaran berhasil diproses!');
            hidePembayaranModal();
            hideSiswaModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showError('Error: ' + data.message);
        }
    } catch (error) {
        showError('Error: ' + error.message);
    }
});

// Reminder functions
async function kirimReminderSiswa(siswaId) {
    if (!confirm('Kirim reminder WhatsApp untuk semua tunggakan siswa ini?')) {
        return;
    }
    
    try {
        const response = await fetch(`/tagihan/reminder/${siswaId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        showNotification(data.message, data.success ? 'success' : 'error');
        
        if (data.success) {
            setTimeout(() => location.reload(), 1500);
        }
    } catch (error) {
        showError('Error: ' + error.message);
    }
}

async function bulkReminder() {
    if (!confirm('Kirim reminder untuk maksimal 50 tunggakan yang perlu reminder?')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("tagihan.bulk.reminder") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        showNotification(data.message, data.success ? 'success' : 'error');
        
        if (data.success) {
            setTimeout(() => location.reload(), 2000);
        }
    } catch (error) {
        showError('Error: ' + error.message);
    }
}

// Notification functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-[60] p-3 sm:p-4 rounded-lg shadow-lg text-white max-w-xs sm:max-w-sm transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' ? 
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                    type === 'error' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' :
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                }
            </svg>
            <span class="text-sm">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

function showSuccess(message) {
    showNotification(message, 'success');
}

function showError(message) {
    showNotification(message, 'error');
}

// AJAX search tanpa reload halaman - CLEAN VERSION
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(async () => {
        const form = document.getElementById('searchForm');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        // Build query parameters
        for (let [key, value] of formData.entries()) {
            if (value.trim() !== '') {
                params.append(key, value);
            }
        }
        
        const searchUrl = `${form.action}?${params.toString()}`;
        
        try {
            // Show loading state for both mobile and desktop
            const mobileList = document.querySelector('.block.sm\\:hidden');
            const tableBody = document.querySelector('tbody');
            
            if (mobileList) {
                mobileList.innerHTML = '<div class="p-8 text-center">Mencari...</div>';
            }
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-8">Mencari...</td></tr>';
            }
            
            // Fetch data via AJAX
            const response = await fetch(searchUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });
            
            if (response.ok) {
                const html = await response.text();
                
                // Parse response dan update content
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Update mobile view
                const newMobileList = doc.querySelector('.block.sm\\:hidden');
                if (newMobileList && mobileList) {
                    mobileList.innerHTML = newMobileList.innerHTML;
                }
                
                // Update desktop table
                const newTableBody = doc.querySelector('tbody');
                if (newTableBody && tableBody) {
                    tableBody.innerHTML = newTableBody.innerHTML;
                }
                
                // Update pagination
                const newPagination = doc.querySelector('.border-t.border-gray-200');
                const currentPagination = document.querySelector('.border-t.border-gray-200');
                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                }
                
                // Update URL tanpa reload
                window.history.pushState({}, '', searchUrl);
            } else {
                // Fallback ke submit biasa jika AJAX gagal
                form.submit();
            }
        } catch (error) {
            // Fallback ke submit biasa jika error
            form.submit();
        }
    }, 500);
}

// Event listener untuk search input dengan AJAX
document.querySelector('input[name="search"]')?.addEventListener('input', debounceSearch);

// Event listener untuk select changes
document.querySelector('select[name="status"]')?.addEventListener('change', debounceSearch);
document.querySelector('select[name="kelas"]')?.addEventListener('change', debounceSearch);

// Auto-focus search when page loads
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // ESC to close modals
    if (e.key === 'Escape') {
        if (!document.getElementById('siswaModal').classList.contains('hidden')) {
            hideSiswaModal();
        } else if (!document.getElementById('pembayaranModal').classList.contains('hidden')) {
            hidePembayaranModal();
        } else if (!document.getElementById('generateModal').classList.contains('hidden')) {
            hideGenerateModal();
        }
    }
    
    // Ctrl+F to focus search
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        document.querySelector('input[name="search"]')?.focus();
    }
});

// Touch and swipe handling for mobile
let touchStartX = 0;
let touchStartY = 0;

document.addEventListener('touchstart', function(e) {
    touchStartX = e.touches[0].clientX;
    touchStartY = e.touches[0].clientY;
});

document.addEventListener('touchend', function(e) {
    if (!touchStartX || !touchStartY) {
        return;
    }

    let touchEndX = e.changedTouches[0].clientX;
    let touchEndY = e.changedTouches[0].clientY;

    let diffX = touchStartX - touchEndX;
    let diffY = touchStartY - touchEndY;

    // Most significant is horizontal swipe
    if (Math.abs(diffX) > Math.abs(diffY)) {
        // Swipe left/right actions could be added here if needed
    }

    touchStartX = 0;
    touchStartY = 0;
});

// Performance: Lazy load and optimize scrolling
const observerOptions = {
    root: null,
    rootMargin: '50px',
    threshold: 0.1
};

const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        }
    });
}, observerOptions);

// Observe all lazy images
document.querySelectorAll('img[data-src]').forEach(img => {
    imageObserver.observe(img);
});

// Prevent zoom on double tap for iOS
let lastTouchEnd = 0;
document.addEventListener('touchend', function (event) {
    const now = (new Date()).getTime();
    if (now - lastTouchEnd <= 300) {
        event.preventDefault();
    }
    lastTouchEnd = now;
}, false);

// Handle orientation change
window.addEventListener('orientationchange', function() {
    // Small delay to allow the orientation change to complete
    setTimeout(function() {
        // Force a reflow to fix any layout issues
        document.body.style.display = 'none';
        document.body.offsetHeight; // trigger reflow
        document.body.style.display = '';
    }, 100);
});
</script>

@endsection