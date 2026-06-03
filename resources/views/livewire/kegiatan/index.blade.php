<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-tasks mr-2 text-blue-600"></i> Manajemen {{ $jenis }}
                </h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ url('/dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-700">{{ $jenis }}</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Card Kelompok -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Kelompok</h3>
                </div>
                <p class="text-gray-500 text-sm mb-6">
                    Kelola data kelompok mahasiswa.
                </p>
                <a href="{{ url('/superadmin/kegiatan/' . $jenis . '/kelompok') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-sm font-medium transition-all duration-200">
                    <i class="fas fa-users"></i>
                    Kelola Kelompok
                </a>
            </div>
        </div>

        <!-- Card Laporan -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan</h3>
                </div>
                <p class="text-gray-500 text-sm mb-6">
                    Lihat dan unduh laporan kegiatan mahasiswa.
                </p>
                <a href="#"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg text-sm font-medium transition-all duration-200">
                    <i class="fas fa-chart-line"></i>
                    Kelola Laporan
                </a>
            </div>
        </div>

        <!-- Card Presensi/Kehadiran -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Presensi/Kehadiran</h3>
                </div>
                <p class="text-gray-500 text-sm mb-6">
                    Kelola presensi dan kehadiran mahasiswa.
                </p>
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg text-sm font-medium transition-all duration-200">
                    <i class="fas fa-calendar-check"></i>
                    Kelola Presensi
                </button>
            </div>
        </div>
    </div>
</div>