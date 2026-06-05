<div class="p-6">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4 md:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold text-gray-800">Tambah Mahasiswa ke Kelompok</h4>
                        <p class="text-xs md:text-sm text-gray-500">
                            Pilih mahasiswa yang akan ditambahkan ke kelompok:
                            <strong class="text-blue-600">{{ $kelompok->nama_kelompok ?? 'Kelompok KKN' }}</strong>
                        </p>
                    </div>
                </div>
                <button wire:click="backToDetail" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm font-medium transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                    <span class="text-sm font-medium text-gray-700">Filter Angkatan</span>
                </div>
                <select wire:model.live="filterAngkatan" 
                        class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Angkatan</option>
                    <option value="2025/2026">2025/2026</option>
                    <option value="2024/2025">2024/2025</option>
                    <option value="2023/2024">2023/2024</option>
                    <option value="2022/2023">2022/2023</option>
                    <option value="2021/2022">2021/2022</option>
                    <option value="2020/2021">2020/2021</option>
                </select>
            </div>
        </div>
        <div class="flex-[2]">
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" wire:model.live="searchMahasiswa" 
                           class="w-full pl-9 pr-8 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Cari mahasiswa berdasarkan NIM atau Nama...">
                    @if($searchMahasiswa)
                    <button wire:click="$set('searchMahasiswa', '')" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Mahasiswa -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-wrap items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-list text-white text-sm"></i>
                </div>
                <h5 class="font-semibold text-gray-800">Daftar Mahasiswa</h5>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Total: {{ $mahasiswas->total() }} Mahasiswa</span>
                @if(count($selectedMahasiswa) > 0)
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                    <i class="fas fa-check-circle"></i> {{ count($selectedMahasiswa) }} Dipilih
                </span>
                @endif
            </div>
        </div>
        
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model.live="selectAll" id="selectAll"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Angkatan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Semester</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mahasiswas as $mahasiswa)
                        @php
                        $data = is_array($mahasiswa->data_mahasiswa) 
                            ? $mahasiswa->data_mahasiswa 
                            : (json_decode($mahasiswa->data_mahasiswa ?? '[]', true) ?: []);
                        $tahun = explode(' ', $data['nama_periode_masuk'] ?? '')[0];
                        $isSelected = in_array($mahasiswa->id, $selectedMahasiswa);
                        @endphp
                        <tr class="hover:bg-gray-50 transition {{ $isSelected ? 'bg-blue-50' : '' }}">
                            <td class="px-4 py-3">
                                <input type="checkbox" value="{{ $mahasiswa->id }}" wire:model.live="selectedMahasiswa"
                                       id="mahasiswa_{{ $mahasiswa->id }}"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3 font-mono text-sm font-semibold text-gray-600">{{ $data['nim'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                        <i class="fas fa-user-circle text-sm"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $data['nama_mahasiswa'] ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full">{{ $tahun }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $data['nama_program_studi'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Semester {{ $mahasiswa->semester ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if(isset($mahasiswa->sudah_terdaftar) && $mahasiswa->sudah_terdaftar)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">
                                    <i class="fas fa-check-circle"></i> Sudah Terdaftar
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    <i class="fas fa-plus-circle"></i> Tersedia
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-user-graduate text-gray-300 text-5xl"></i>
                                    <p class="text-gray-500">Tidak ada mahasiswa yang tersedia</p>
                                    <small class="text-gray-400">Coba ubah filter atau kata kunci pencarian</small>
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

    <!-- Floating Action Button -->
    @if(count($selectedMahasiswa) > 0)
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 animate-slide-up">
        <div class="bg-gray-800 rounded-full px-6 py-3 flex items-center gap-6 shadow-xl">
            <div class="flex items-center gap-2 text-white">
                <i class="fas fa-check-circle text-green-400"></i>
                <span class="text-sm font-medium">{{ count($selectedMahasiswa) }} mahasiswa dipilih</span>
            </div>
            <button wire:click="saveMahasiswa" wire:loading.attr="disabled"
                    class="px-5 py-1.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white rounded-full text-sm font-medium transition">
                <span wire:loading.remove><i class="fas fa-save mr-1"></i> Tambahkan ke Kelompok</span>
                <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Menyimpan...</span>
            </button>
        </div>
    </div>
    @endif
    <style>
    @keyframes slide-up {
        from {
            transform: translateX(-50%) translateY(100px);
            opacity: 0;
        }
        to {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }
    .animate-slide-up {
        animation: slide-up 0.3s ease-out;
    }
    </style>
</div>
