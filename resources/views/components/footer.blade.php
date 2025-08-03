<footer class="bg-gradient-to-r from-white via-secondary-50 to-primary-50 border-t border-secondary-200 px-4 py-6 sm:px-6 lg:px-8 shadow-lg">
    <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
        <!-- Left section -->
        <div class="flex items-center space-x-4 text-sm text-secondary-600">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2"></path>
                    </svg>
                </div>
                <span class="font-semibold">© {{ date('Y') }} SIMANIIS</span>
            </div>
            <span class="hidden sm:inline text-secondary-400">•</span>
            <span class="hidden sm:inline text-secondary-500">Sistem Manajemen Infaq Siswa</span>
        </div>

        <!-- Right section -->
        <div class="flex items-center space-x-4 text-sm">
            <div class="flex items-center space-x-2 px-3 py-1 bg-primary-100 text-primary-700 rounded-full">
                <div class="w-2 h-2 bg-primary-500 rounded-full pulse-dot"></div>
                <span class="font-medium">Version 1.0.0</span>
            </div>
            <span class="text-secondary-400">•</span>
            <span class="text-secondary-600 font-medium">User: {{ Auth::user()->name }}</span>
        </div>
    </div>
</footer>