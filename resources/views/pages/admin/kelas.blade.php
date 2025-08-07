@extends('layouts.app')

@section('title', 'Kelola Kelas')
@section('page-title', 'Kelola Kelas')

@section('content')
<div x-data="kelasManager()" class="space-y-4 sm:space-y-6">
    <!-- Header with Add Button -->
    <div class="bg-white overflow-hidden shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-secondary-900">Kelola Kelas SMA</h2>
                    <p class="text-secondary-600 mt-1 text-sm sm:text-base">Manage kelas reguler dan peminatan dengan nominal masing-masing</p>
                </div>
                <button @click="openCreateModal" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-lg sm:rounded-xl hover:from-primary-600 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:-translate-y-0.5 transition-all duration-200 shadow-lg text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Tambah Kelas</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white overflow-hidden shadow-xl rounded-xl sm:rounded-2xl border border-secondary-200">
        <!-- Desktop Table -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full divide-y divide-secondary-200">
                <thead class="bg-secondary-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-secondary-700 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-secondary-700 uppercase tracking-wider">
                            Tingkat
                        </th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-secondary-700 uppercase tracking-wider">
                            Jenis
                        </th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-secondary-700 uppercase tracking-wider">
                            Nominal Bulanan
                        </th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-secondary-700 uppercase tracking-wider">
                            Tahun Ajaran
                        </th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-secondary-700 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-secondary-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-secondary-700 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-secondary-200">
                    @forelse($kelas as $kelasItem)
                        <tr class="hover:bg-secondary-50 transition-colors duration-200">
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-secondary-900">{{ $kelasItem->nama_kelas }}</div>
                                        <div class="text-xs text-secondary-500">{{ $kelasItem->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    Kelas {{ $kelasItem->tingkat_sma }}
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                    {{ $kelasItem->jenis_kelas === 'reguler' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-purple-100 text-purple-800' }}">
                                    {{ $kelasItem->jenis_kelas === 'reguler' ? 'Reguler' : 'Peminatan' }}
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-primary-600">
                                    {{ $kelasItem->nominal_format }}
                                </div>
                                <div class="text-xs text-secondary-500">per bulan</div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-secondary-900 font-medium">{{ $kelasItem->tahunAjaran->nama_tahun }}</div>
                                <div class="text-xs text-secondary-500">
                                    {{ $kelasItem->tahunAjaran->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-secondary-900">
                                    {{ $kelasItem->siswas()->count() }}
                                </div>
                                <div class="text-xs text-secondary-500">siswa</div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <button @click="toggleActive({{ $kelasItem->id }})" 
                                    :disabled="togglingIds.includes({{ $kelasItem->id }})"
                                    :class="togglingIds.includes({{ $kelasItem->id }}) ? 'opacity-50 cursor-not-allowed' : ''"
                                    class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 
                                    {{ $kelasItem->is_active 
                                        ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                                        : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                    <span class="w-2 h-2 rounded-full mr-2 {{ $kelasItem->is_active ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                                    <span x-show="!togglingIds.includes({{ $kelasItem->id }})">
                                        {{ $kelasItem->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    <span x-show="togglingIds.includes({{ $kelasItem->id }})" class="flex items-center">
                                        <svg class="animate-spin h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </button>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium space-x-1 sm:space-x-2">
                                <button @click="editKelas({{ $kelasItem->id }})" 
                                    class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all duration-200">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="deleteKelas({{ $kelasItem->id }}, '{{ $kelasItem->nama_kelas }}')" 
                                    class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all duration-200">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <p class="text-secondary-500 font-medium text-sm sm:text-base">Belum ada kelas</p>
                                <p class="text-xs sm:text-sm text-secondary-400 mt-1">Klik tombol "Tambah Kelas" untuk memulai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden">
            @forelse($kelas as $kelasItem)
                <div class="p-4 border-b border-secondary-200 last:border-b-0">
                    <div class="bg-gradient-to-r from-secondary-50 to-blue-50 rounded-xl p-4 border border-secondary-200">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-secondary-900 truncate">{{ $kelasItem->nama_kelas }}</h3>
                                    <p class="text-xs text-blue-600 font-medium">Kelas {{ $kelasItem->tingkat_sma }}</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $kelasItem->jenis_kelas === 'reguler' 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-purple-100 text-purple-800' }}">
                                            {{ $kelasItem->jenis_kelas === 'reguler' ? 'Reguler' : 'Peminatan' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button @click="toggleActive({{ $kelasItem->id }})" 
                                :disabled="togglingIds.includes({{ $kelasItem->id }})"
                                :class="togglingIds.includes({{ $kelasItem->id }}) ? 'opacity-50 cursor-not-allowed' : ''"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 flex-shrink-0
                                {{ $kelasItem->is_active 
                                    ? 'bg-green-100 text-green-800' 
                                    : 'bg-gray-100 text-gray-800' }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $kelasItem->is_active ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                                <span x-show="!togglingIds.includes({{ $kelasItem->id }})">
                                    {{ $kelasItem->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                                <span x-show="togglingIds.includes({{ $kelasItem->id }})" class="flex items-center">
                                    <svg class="animate-spin h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <p class="text-xs text-secondary-600 font-medium">Nominal Bulanan</p>
                                <p class="text-sm text-primary-600 font-bold">{{ $kelasItem->nominal_format }}</p>
                                <p class="text-xs text-secondary-500">per bulan</p>
                            </div>
                            <div>
                                <p class="text-xs text-secondary-600 font-medium">Jumlah Siswa</p>
                                <p class="text-sm text-secondary-900 font-bold">{{ $kelasItem->siswas()->count() }} Siswa</p>
                                <p class="text-xs text-secondary-500">terdaftar</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-3 border border-secondary-200 mb-4">
                            <p class="text-xs text-secondary-600 font-medium mb-1">Tahun Ajaran</p>
                            <p class="text-sm text-secondary-900 font-semibold">{{ $kelasItem->tahunAjaran->nama_tahun }}</p>
                            <p class="text-xs text-secondary-500">
                                {{ $kelasItem->tahunAjaran->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2 pt-3 border-t border-secondary-200">
                            <button @click="editKelas({{ $kelasItem->id }})" 
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all duration-200 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                            <button @click="deleteKelas({{ $kelasItem->id }}, '{{ $kelasItem->nama_kelas }}')" 
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all duration-200 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <p class="text-secondary-500 font-medium">Belum ada kelas</p>
                    <p class="text-sm text-secondary-400 mt-1">Klik tombol "Tambah" untuk memulai</p>
                </div>
            @endforelse
        </div>

        @if($kelas->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-secondary-200 bg-secondary-50">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-3 sm:space-y-0">
                    <!-- Results Info -->
                    <div class="text-sm text-secondary-600">
                        Menampilkan <span class="font-semibold text-secondary-900">{{ $kelas->firstItem() }}</span> 
                        sampai <span class="font-semibold text-secondary-900">{{ $kelas->lastItem() }}</span> 
                        dari <span class="font-semibold text-secondary-900">{{ $kelas->total() }}</span> kelas
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($kelas->onFirstPage())
                            <span class="px-3 py-2 text-sm text-secondary-400 bg-white border border-secondary-200 rounded-lg cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $kelas->previousPageUrl() }}" class="px-3 py-2 text-sm text-secondary-600 bg-white border border-secondary-200 rounded-lg hover:bg-secondary-50 hover:text-secondary-900 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($kelas->getUrlRange(max(1, $kelas->currentPage() - 2), min($kelas->lastPage(), $kelas->currentPage() + 2)) as $page => $url)
                            @if ($page == $kelas->currentPage())
                                <span class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-600 border border-primary-500 rounded-lg shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-secondary-600 bg-white border border-secondary-200 rounded-lg hover:bg-secondary-50 hover:text-secondary-900 transition-all duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($kelas->hasMorePages())
                            <a href="{{ $kelas->nextPageUrl() }}" class="px-3 py-2 text-sm text-secondary-600 bg-white border border-secondary-200 rounded-lg hover:bg-secondary-50 hover:text-secondary-900 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2 text-sm text-secondary-400 bg-white border border-secondary-200 rounded-lg cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Create/Edit -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.away="closeModal" 
         @keydown.escape.window="closeModal"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" 
         style="display: none;">
        
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            
            <form @submit.prevent="submitForm">
                <!-- Modal Header -->
                <div class="p-6 border-b border-secondary-200">
                    <div class="flex items-center justify-between">
                        <h3 x-text="modalTitle" class="text-xl font-bold text-secondary-900"></h3>
                        <button type="button" @click="closeModal" class="text-secondary-400 hover:text-secondary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Nama Kelas -->
                    <div>
                        <label for="nama_kelas" class="block text-sm font-semibold text-secondary-700 mb-2">
                            Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               x-model="formData.nama_kelas"
                               placeholder="contoh: X-IPA-1, XI-IPS-2, XII-BAHASA"
                               :class="errors.nama_kelas ? 'border-red-500 ring-red-500' : 'border-secondary-300'"
                               class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        <div x-show="errors.nama_kelas" x-text="errors.nama_kelas" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Tingkat -->
                    <div>
                        <label for="tingkat" class="block text-sm font-semibold text-secondary-700 mb-2">
                            Tingkat Kelas <span class="text-red-500">*</span>
                        </label>
                        <select x-model="formData.tingkat"
                                :class="errors.tingkat ? 'border-red-500 ring-red-500' : 'border-secondary-300'"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                            <option value="">Pilih Tingkat</option>
                            <option value="10">Kelas X</option>
                            <option value="11">Kelas XI</option>
                            <option value="12">Kelas XII</option>
                        </select>
                        <div x-show="errors.tingkat" x-text="errors.tingkat" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Jenis Kelas -->
                    <div>
                        <label for="jenis_kelas" class="block text-sm font-semibold text-secondary-700 mb-2">
                            Jenis Kelas <span class="text-red-500">*</span>
                        </label>
                        <select x-model="formData.jenis_kelas"
                                :class="errors.jenis_kelas ? 'border-red-500 ring-red-500' : 'border-secondary-300'"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                            <option value="">Pilih Jenis Kelas</option>
                            <option value="reguler">Reguler</option>
                            <option value="peminatan">Peminatan</option>
                        </select>
                        <div x-show="errors.jenis_kelas" x-text="errors.jenis_kelas" class="text-red-500 text-sm mt-1"></div>
                        <p class="text-xs text-secondary-500 mt-1">
                            <strong>Reguler:</strong> Kelas X (umum) | 
                            <strong>Peminatan:</strong> Kelas XI & XII (jurusan)
                        </p>
                    </div>

                    <!-- Nominal Bulanan -->
                    <div>
                        <label for="nominal_bulanan" class="block text-sm font-semibold text-secondary-700 mb-2">
                            Nominal Bulanan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-secondary-500">Rp</span>
                            <input type="number" 
                                   x-model="formData.nominal_bulanan"
                                   placeholder="150000"
                                   :class="errors.nominal_bulanan ? 'border-red-500 ring-red-500' : 'border-secondary-300'"
                                   class="w-full pl-12 pr-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        </div>
                        <div x-show="errors.nominal_bulanan" x-text="errors.nominal_bulanan" class="text-red-500 text-sm mt-1"></div>
                        <p class="text-xs text-secondary-500 mt-1">
                            <strong>Saran:</strong> Kelas X: 150.000 | Kelas XI: 200.000 | Kelas XII: 250.000
                        </p>
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label for="tahun_ajaran_id" class="block text-sm font-semibold text-secondary-700 mb-2">
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <select x-model="formData.tahun_ajaran_id"
                                :class="errors.tahun_ajaran_id ? 'border-red-500 ring-red-500' : 'border-secondary-300'"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach($tahunAjarans as $tahunAjaran)
                                <option value="{{ $tahunAjaran->id }}">
                                    {{ $tahunAjaran->nama_tahun }} 
                                    @if($tahunAjaran->is_active) (Aktif) @endif
                                </option>
                            @endforeach
                        </select>
                        <div x-show="errors.tahun_ajaran_id" x-text="errors.tahun_ajaran_id" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" 
                               x-model="formData.is_active"
                               class="w-5 h-5 text-primary-600 border-secondary-300 rounded focus:ring-primary-500">
                        <label class="text-sm font-medium text-secondary-700">
                            Kelas aktif
                        </label>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-secondary-200">
                    <div class="flex space-x-3">
                        <button type="button" @click="closeModal" 
                            class="flex-1 px-4 py-3 border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200 font-medium">
                            Batal
                        </button>
                        <button type="submit" 
                            :disabled="isSubmitting"
                            :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : 'hover:from-primary-600 hover:to-primary-700'"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl transition-all duration-200 font-medium">
                            <span x-show="!isSubmitting" x-text="submitButtonText"></span>
                            <span x-show="isSubmitting" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteConfirm.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.away="cancelDelete" 
         @keydown.escape.window="cancelDelete"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" 
         style="display: none;">
        
        <div x-show="deleteConfirm.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-secondary-200">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-secondary-900">Konfirmasi Hapus</h3>
                        <p class="text-sm text-secondary-600">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-secondary-700 mb-2">
                    Apakah Anda yakin ingin menghapus kelas:
                </p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <p class="font-semibold text-red-800" x-text="deleteConfirm.nama"></p>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Peringatan!</p>
                            <p class="text-sm text-yellow-700">Data kelas yang dihapus tidak dapat dikembalikan. Pastikan kelas tidak memiliki siswa.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-secondary-200">
                <div class="flex space-x-3">
                    <button type="button" @click="cancelDelete" 
                        :disabled="deleteConfirm.isDeleting"
                        class="flex-1 px-4 py-3 border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200 font-medium">
                        Batal
                    </button>
                    <button @click="confirmDelete" 
                        :disabled="deleteConfirm.isDeleting"
                        :class="deleteConfirm.isDeleting ? 'opacity-50 cursor-not-allowed' : 'hover:from-red-600 hover:to-red-700'"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl transition-all duration-200 font-medium">
                        <span x-show="!deleteConfirm.isDeleting" class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Ya, Hapus
                        </span>
                        <span x-show="deleteConfirm.isDeleting" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menghapus...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div x-show="showNotification"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         :class="notificationType === 'success' ? 'bg-green-500' : 'bg-red-500'"
         class="fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm"
         style="display: none;">
        <div class="flex items-center space-x-2">
            <svg x-show="notificationType === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="notificationType === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span x-text="notificationMessage"></span>
        </div>
    </div>
</div>

<script>
function kelasManager() {
    return {
        // State
        showModal: false,
        isSubmitting: false,
        modalTitle: 'Tambah Kelas',
        submitButtonText: 'Simpan',
        editMode: false,
        editId: null,
        
        // Notification
        showNotification: false,
        notificationMessage: '',
        notificationType: 'success',
        
        // Loading states
        togglingIds: [],
        
        // Delete confirmation
        deleteConfirm: {
            show: false,
            id: null,
            nama: '',
            isDeleting: false
        },
        
        // Form data
        formData: {
            nama_kelas: '',
            tingkat: '',
            jenis_kelas: '',
            nominal_bulanan: '',
            tahun_ajaran_id: '',
            is_active: true
        },
        
        // Errors
        errors: {},
        
        // Methods
        openCreateModal() {
            this.editMode = false;
            this.editId = null;
            this.modalTitle = 'Tambah Kelas';
            this.submitButtonText = 'Simpan';
            this.resetForm();
            this.showModal = true;
        },
        
        async editKelas(id) {
            try {
                const response = await fetch(`/admin/kelas/${id}`);
                const result = await response.json();
                
                if (result.success) {
                    this.editMode = true;
                    this.editId = id;
                    this.modalTitle = 'Edit Kelas';
                    this.submitButtonText = 'Update';
                    
                    // Fill form
                    this.formData = {
                        nama_kelas: result.data.nama_kelas,
                        tingkat: result.data.tingkat.toString(),
                        jenis_kelas: result.data.jenis_kelas,
                        nominal_bulanan: result.data.nominal_bulanan,
                        tahun_ajaran_id: result.data.tahun_ajaran_id.toString(),
                        is_active: result.data.is_active
                    };
                    
                    this.clearErrors();
                    this.showModal = true;
                } else {
                    this.showNotificationToast('Gagal mengambil data kelas', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotificationToast('Terjadi kesalahan saat mengambil data', 'error');
            }
        },
        
        closeModal() {
            this.showModal = false;
            this.resetForm();
        },
        
        resetForm() {
            this.formData = {
                nama_kelas: '',
                tingkat: '',
                jenis_kelas: '',
                nominal_bulanan: '',
                tahun_ajaran_id: '',
                is_active: true
            };
            this.clearErrors();
        },
        
        clearErrors() {
            this.errors = {};
        },
        
        async submitForm() {
            if (this.isSubmitting) return;
            
            this.isSubmitting = true;
            this.clearErrors();
            
            try {
                const formData = new FormData();
                
                // Append form data
                Object.keys(this.formData).forEach(key => {
                    if (key === 'is_active') {
                        formData.append(key, this.formData[key] ? '1' : '0');
                    } else {
                        formData.append(key, this.formData[key]);
                    }
                });
                
                // Add CSRF token
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                let url = '/admin/kelas';
                if (this.editMode) {
                    url += `/${this.editId}`;
                    formData.append('_method', 'PUT');
                }
                
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotificationToast(result.message, 'success');
                    this.closeModal();
                    
                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    if (response.status === 422 && result.errors) {
                        // Validation errors
                        this.errors = result.errors;
                    } else {
                        this.showNotificationToast(result.message || 'Terjadi kesalahan', 'error');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotificationToast('Terjadi kesalahan saat menyimpan data', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        async toggleActive(id) {
            if (this.togglingIds.includes(id)) return;
            
            this.togglingIds.push(id);
            
            try {
                const response = await fetch(`/admin/kelas/${id}/toggle-active`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotificationToast(result.message, 'success');
                    
                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.showNotificationToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotificationToast('Terjadi kesalahan saat mengubah status', 'error');
            } finally {
                this.togglingIds = this.togglingIds.filter(toggleId => toggleId !== id);
            }
        },
        
        async deleteKelas(id, nama) {
            this.deleteConfirm = {
                show: true,
                id: id,
                nama: nama,
                isDeleting: false
            };
        },
        
        async confirmDelete() {
            if (this.deleteConfirm.isDeleting) return;
            
            this.deleteConfirm.isDeleting = true;
            const id = this.deleteConfirm.id;
            
            try {
                const response = await fetch(`/admin/kelas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotificationToast(result.message, 'success');
                    this.deleteConfirm.show = false;
                    
                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.showNotificationToast(result.message, 'error');
                    this.deleteConfirm.isDeleting = false;
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotificationToast('Terjadi kesalahan saat menghapus data', 'error');
                this.deleteConfirm.isDeleting = false;
            }
        },
        
        cancelDelete() {
            this.deleteConfirm = {
                show: false,
                id: null,
                nama: '',
                isDeleting: false
            };
        },
        
        showNotificationToast(message, type = 'success') {
            this.notificationMessage = message;
            this.notificationType = type;
            this.showNotification = true;
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                this.showNotification = false;
            }, 3000);
        }
    }
}
</script>
@endsection