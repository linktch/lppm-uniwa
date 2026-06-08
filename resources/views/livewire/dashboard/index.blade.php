<div class="min-h-screen bg-gray-100">
    <div class="p-4 md:p-6">
        
        @if($role == 'superadmin')
        <!-- ==================== SUPERADMIN DASHBOARD ==================== -->
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Superadmin</h1>
            <p class="text-gray-500 text-sm mt-1">Selamat datang, {{ Auth::user()->name }}</p>
        </div>

        <!-- Statistik Cards Utama -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Mahasiswa</p>
                        <p class="text-2xl font-bold">{{ $totalMahasiswa }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user-graduate text-blue-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Dosen</p>
                        <p class="text-2xl font-bold">{{ $totalDosen }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-chalkboard-user text-green-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Kelompok</p>
                        <p class="text-2xl font-bold">{{ $totalKelompok }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-users text-purple-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Kegiatan</p>
                        <p class="text-2xl font-bold">{{ $totalKegiatan }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-tasks text-yellow-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Laporan -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Laporan</p>
                        <p class="text-2xl font-bold">{{ $totalLaporan }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-file-alt text-indigo-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Disetujui</p>
                        <p class="text-2xl font-bold">{{ $laporanDisetujui }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Revisi</p>
                        <p class="text-2xl font-bold">{{ $laporanRevisi }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-undo-alt text-yellow-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pending</p>
                        <p class="text-2xl font-bold">{{ $laporanPending }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-clock text-red-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik per Kegiatan -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Statistik Laporan per Kegiatan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Kegiatan</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Total</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Disetujui</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Revisi</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Pending</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($statistikPerKegiatan as $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $stat['nama'] }}</td>
                            <td class="px-4 py-3 text-sm text-center">{{ $stat['total'] }}</td>
                            <td class="px-4 py-3 text-sm text-center text-green-600">{{ $stat['approved'] }}</td>
                            <td class="px-4 py-3 text-sm text-center text-yellow-600">{{ $stat['revisi'] }}</td>
                            <td class="px-4 py-3 text-sm text-center text-red-600">{{ $stat['pending'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4">
                <h3 class="font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-3">
                    @forelse($aktivitasTerbaru as $aktivitas)
                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas {{ $aktivitas['icon'] }} text-blue-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">{{ $aktivitas['pesan'] }}</p>
                            <p class="text-xs text-gray-400">{{ $aktivitas['waktu'] }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>Belum ada aktivitas</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Menu Cepat -->
            <div class="bg-white rounded-xl shadow-sm p-4">
                <h3 class="font-semibold text-gray-800 mb-4">Menu Cepat</h3>
                <div class="space-y-2">
                    <a href="#" wire:navigate class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-tasks text-blue-500 w-5"></i>
                        <span class="text-sm">Kelola Kegiatan</span>
                    </a>
                    <a href="#" wire:navigate class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-users text-green-500 w-5"></i>
                        <span class="text-sm">Kelola User</span>
                    </a>
                    <a href="#" wire:navigate class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-layer-group text-purple-500 w-5"></i>
                        <span class="text-sm">Kelola Kelompok</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Laporan Terbaru -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between">
                <h3 class="font-semibold text-gray-800">Laporan Terbaru</h3>
                <a href="#" class="text-sm text-blue-500">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Mahasiswa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Kelompok</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Kegiatan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($laporanTerbaru as $laporan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $laporan['tanggal'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $laporan['mahasiswa'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $laporan['kelompok'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $laporan['kegiatan'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full 
                                    {{ $laporan['status'] == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $laporan['status'] == 'revisi' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $laporan['status'] == 'submitted' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ ucfirst($laporan['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="#" class="text-blue-500"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-8 text-gray-400">Belum ada laporan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @elseif($kelompokUser)
        <!-- ==================== MAHASISWA DASHBOARD ==================== -->
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Mahasiswa</h1>
            <p class="text-gray-500 text-sm mt-1">
                Selamat datang, {{ $userData['nama_mahasiswa'] ?? Auth::user()->name ?? 'Mahasiswa' }}
            </p>
        </div>

        <!-- Info Kegiatan & Kelompok -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl shadow-lg p-4 mb-6 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-white/80">Kegiatan</p>
                        <p class="font-semibold">{{ $namaKegiatan }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-white/80">Kelompok</p>
                        <p class="font-semibold">{{ $kelompok->nama_kelompok ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-white/80">Lokasi</p>
                        <p class="font-semibold">{{ $kelompok->desa ?? '-' }}, {{ $kelompok->kecamatan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs md:text-sm">Total Laporan</p>
                        <p class="text-xl md:text-2xl font-bold">{{ $totalLaporan }}</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs md:text-sm">Disetujui</p>
                        <p class="text-xl md:text-2xl font-bold">{{ $laporanDisetujui }}</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs md:text-sm">Perlu Revisi</p>
                        <p class="text-xl md:text-2xl font-bold">{{ $laporanRevisi }}</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-undo-alt text-yellow-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs md:text-sm">Sertifikat</p>
                        <p class="text-xl md:text-2xl font-bold">{{ $sertifikat ? 'Ada' : '-' }}</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-certificate text-purple-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru & Menu Cepat -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-4">
                <h3 class="font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-3">
                    @forelse($aktivitasTerbaru as $aktivitas)
                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas {{ $aktivitas['icon'] }} text-blue-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">{{ $aktivitas['pesan'] }}</p>
                            <p class="text-xs text-gray-400">{{ $aktivitas['waktu'] }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>Belum ada aktivitas</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4">
                <h3 class="font-semibold text-gray-800 mb-4">Menu Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('kegiatan.laporanharian.create', ['role' => $role, 'jenisKegiatan' => $namaKegiatan]) }}" 
                       class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-plus-circle text-green-500 w-5"></i>
                        <span class="text-sm">Buat Laporan Baru</span>
                    </a>
                    <a href="{{ route('kegiatan.laporanharian.index', ['role' => $role, 'jenisKegiatan' => $namaKegiatan]) }}" 
                       class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-list text-blue-500 w-5"></i>
                        <span class="text-sm">Lihat Semua Laporan</span>
                    </a>
                    @if($sertifikat)
                    <a href="/{{ $role }}/kegiatan/{{ $namaKegiatan }}/sertifikat/{{ Auth::id() }}" 
                       target="_blank"
                       class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-certificate text-purple-500 w-5"></i>
                        <span class="text-sm">Lihat Sertifikat</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Laporan Terbaru -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between">
                <h3 class="font-semibold text-gray-800">Laporan Terbaru</h3>
                <a href="{{ route('kegiatan.laporanharian.index', ['role' => $role, 'jenisKegiatan' => $namaKegiatan]) }}" 
                   class="text-sm text-blue-500">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Aktivitas</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($laporanTerbaru as $laporan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $laporan['tanggal'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $laporan['aktivitas'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full 
                                    {{ $laporan['status'] == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $laporan['status'] == 'revisi' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $laporan['status'] == 'submitted' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ ucfirst($laporan['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('kegiatan.laporanharian.view', ['role' => $role, 'jenisKegiatan' => $namaKegiatan, 'id' => $laporan['id']]) }}" 
                                   class="text-blue-500">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-8 text-gray-400">Belum ada laporan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>



        @else
        <!-- Belum terdaftar kelompok -->
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <i class="fas fa-users-slash text-gray-300 text-5xl mb-3 block"></i>
            <p class="text-gray-500">Anda belum terdaftar dalam kelompok</p>
            <p class="text-sm text-gray-400 mt-1">Silakan hubungi admin untuk pendaftaran</p>
        </div>
        @endif
    </div>
</div>