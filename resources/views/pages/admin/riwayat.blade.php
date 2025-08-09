@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container-fluid px-2 sm:px-4 py-4 sm:py-6" x-data="riwayatData()">
    <!-- Header Section -->
    <div class="flex flex-col space-y-4 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900">Riwayat Pembayaran</h1>
                <p class="text-sm sm:text-base text-secondary-600 mt-1">Daftar semua transaksi pembayaran infaq siswa</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <button @click="exportExcel()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg font-medium transition-colors flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="hidden sm:inline">Export Excel</span>
                    <span class="sm:hidden">Export</span>
                </button>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-white rounded-lg border border-secondary-200 p-3 sm:p-4">
                <div class="flex items-center">
                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-secondary-600">Total Transaksi</p>
                        <p class="text-sm sm:text-lg font-bold text-secondary-900">{{ number_format($stats['total_transaksi']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-secondary-200 p-3 sm:p-4">
                <div class="flex items-center">
                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-secondary-600">Total Nominal</p>
                        <p class="text-sm sm:text-lg font-bold text-secondary-900">Rp {{ number_format($stats['total_nominal']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-secondary-200 p-3 sm:p-4">
                <div class="flex items-center">
                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-secondary-600">Hari Ini</p>
                        <p class="text-sm sm:text-lg font-bold text-secondary-900">{{ number_format($stats['transaksi_hari_ini']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-secondary-200 p-3 sm:p-4">
                <div class="flex items-center">
                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-secondary-600">Nominal Hari Ini</p>
                        <p class="text-sm sm:text-lg font-bold text-secondary-900">Rp {{ number_format($stats['nominal_hari_ini']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl border border-secondary-200 p-4 sm:p-6 mb-6">
        <form method="GET" class="space-y-4">
            <!-- Row 1: Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                </div>
            </div>

            <!-- Row 2: Siswa & Kelas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Cari Siswa</label>
                    <input type="text" name="siswa_search" placeholder="Nama atau NIS..." value="{{ request('siswa_search') }}" 
                           class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Kelas</label>
                    <select name="kelas_id" class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Row 3: User & Bulan (conditional) -->
            <div class="grid grid-cols-1 sm:grid-cols-{{ Auth::user()->role === 'admin' ? '2' : '1' }} gap-4">
                @if(Auth::user()->role === 'admin')
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">User TU</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Bulan</label>
                    <input type="month" name="bulan" value="{{ request('bulan') }}" 
                           class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                </div>
            </div>

            <!-- Button Row -->
            <div class="flex flex-col sm:flex-row gap-2 pt-2">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('riwayat.index') }}" class="bg-secondary-500 hover:bg-secondary-600 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl border border-secondary-200 overflow-hidden">
        <!-- Mobile View -->
        <div class="block sm:hidden">
            @forelse($transaksi as $item)
            <div class="border-b border-secondary-200 p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold text-secondary-900">{{ $item->siswa->nama_lengkap }}</h3>
                        <p class="text-sm text-secondary-600">{{ $item->siswa->nis }} • {{ $item->siswa->kelas->nama_kelas }}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                        Rp {{ number_format($item->nominal) }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-sm text-secondary-600">
                    <span>{{ \Carbon\Carbon::parse($item->bulan_bayar)->format('M Y') }} • {{ $item->tanggal_bayar->format('d/m/Y') }}</span>
                    <div class="flex space-x-2">
                        <button @click="printBukti({{ $item->id }})" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H3a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <button @click="resendWhatsapp({{ $item->id }})" class="text-green-600 hover:text-green-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-secondary-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p>Tidak ada riwayat pembayaran ditemukan</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-secondary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Tanggal Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Nominal</th>
                        @if(Auth::user()->role === 'admin')
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Input Oleh</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-secondary-200">
                    @forelse($transaksi as $item)
                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-secondary-900">{{ $item->siswa->nama_lengkap }}</div>
                                <div class="text-sm text-secondary-500">{{ $item->siswa->nis }} • {{ $item->siswa->kelas->nama_kelas }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                            {{ \Carbon\Carbon::parse($item->bulan_bayar)->format('F Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                            {{ $item->tanggal_bayar->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">
                                Rp {{ number_format($item->nominal) }}
                            </span>
                        </td>
                        @if(Auth::user()->role === 'admin')
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                            {{ $item->user->name }}
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button @click="printBukti({{ $item->id }})" 
                                    class="text-blue-600 hover:text-blue-800 transition-colors" title="Print Bukti">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H3a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <button @click="resendWhatsapp({{ $item->id }})" 
                                    class="text-green-600 hover:text-green-800 transition-colors" title="Kirim Ulang WhatsApp">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('riwayat.show', $item->id) }}" 
                               class="text-secondary-600 hover:text-secondary-800 transition-colors" title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role === 'admin' ? '6' : '5' }}" class="text-center py-8 text-secondary-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p>Tidak ada riwayat pembayaran ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($transaksi->hasPages())
    <div class="mt-6">
        {{ $transaksi->links() }}
    </div>
    @endif
</div>

<script>
function riwayatData() {
    return {
        exportExcel() {
            // Get current filter parameters
            const params = new URLSearchParams(window.location.search);
            
            // Submit export form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("riwayat.export") }}';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add filter parameters
            for (const [key, value] of params) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        },

        printBukti(id) {
            // Open print page in new window
            const printWindow = window.open(`{{ route('riwayat.print', ':id') }}`.replace(':id', id), '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        },

        async resendWhatsapp(id) {
            if (!confirm('Kirim ulang notifikasi WhatsApp?')) return;

            try {
                const response = await fetch(`{{ route('riwayat.resend-whatsapp', ':id') }}`.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Notifikasi WhatsApp berhasil dikirim ulang');
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan saat mengirim notifikasi');
                console.error(error);
            }
        }
    }
}
</script>
@endsection