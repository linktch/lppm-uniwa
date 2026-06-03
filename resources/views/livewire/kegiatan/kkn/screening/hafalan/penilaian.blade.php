<div class="p-6">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4 md:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-400 flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold text-gray-800">Penilaian Hafalan Mahasiswa</h4>
                        <p class="text-xs md:text-sm text-gray-500">Beri penilaian hafalan Al-Qur'an peserta KKN</p>
                    </div>
                </div>
                <a href="{{ route('kegiatan.kkn.screening.hafalan.index', ['role' => $role]) }}" 
                   wire:navigate 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm font-medium transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Search dan Info -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-full">
                <i class="fas fa-users"></i> Total Mahasiswa: {{ $mahasiswas->total() }}
            </span>
        </div>
        <div class="relative w-full md:w-72">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" 
                   class="w-full pl-9 pr-8 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                   placeholder="Cari mahasiswa (Nama/NIM)..." 
                   wire:model.live="search">
            @if($search)
            <button wire:click="$set('search', '')" 
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xs"></i>
            </button>
            @endif
        </div>
    </div>

    <!-- Tabel Mahasiswa -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-wrap items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                <h5 class="font-semibold text-gray-800">Daftar Mahasiswa Peserta KKN</h5>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                    Menampilkan {{ $mahasiswas->firstItem() }} - {{ $mahasiswas->lastItem() }} 
                    dari {{ $mahasiswas->total() }} Mahasiswa
                </span>
            </div>
        </div>
        
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Kelompok</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Jenis Kelamin</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mahasiswas as $index => $mahasiswa)
                        <tr class="hover:bg-gray-50 transition" wire:key="row-{{ $mahasiswa->id }}">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $mahasiswas->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono font-semibold text-gray-600 text-sm">{{ $mahasiswa->nim }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-800 text-sm">{{ $mahasiswa->nama }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $mahasiswa->prodi }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $mahasiswa->kelompok }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($mahasiswa->jenis_kelamin == 'L' || $mahasiswa->jenis_kelamin == 'Laki-laki')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                    <i class="fas fa-mars"></i> Laki-laki
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-pink-100 text-pink-700 text-xs rounded-full">
                                    <i class="fas fa-venus"></i> Perempuan
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a wire:navigate 
                                   href="{{ route('kegiatan.kkn.screening.hafalan.penilaian.detail', ['role' => $role, 'mahasiswaId' => $mahasiswa->id]) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg transition group relative">
                                    <i class="fas fa-star"></i>
                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                        Beri Penilaian
                                    </span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-users text-gray-400 text-5xl"></i>
                                    <p class="text-gray-500">Tidak ada data mahasiswa</p>
                                    <small class="text-gray-400">Belum ada mahasiswa yang terdaftar di kelompok KKN periode ini</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($mahasiswas->hasPages())
            <div class="mt-6">
                {{ $mahasiswas->links() }}
            </div>
            @endif
        </div>
    </div>
</div>