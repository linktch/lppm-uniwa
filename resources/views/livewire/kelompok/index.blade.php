<div class="p-6">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4 md:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold text-gray-800">Manajemen Kelompok KKN</h4>
                        <p class="text-xs md:text-sm text-gray-500">Kelola data kelompok Kuliah Kerja Nyata</p>
                    </div>
                </div>
                <button wire:click="openModal"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white rounded-full text-sm font-medium transition">
                    <i class="fas fa-plus-circle"></i> Tambah Kelompok
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                    <span class="text-sm font-medium text-gray-700">Filter Periode</span>
                </div>
                <div class="flex-1 max-w-xs">
                    <select wire:model.live="selectedPeriode"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Periode</option>
                        @foreach($periodes as $periode)
                        <option value="{{ $periode->id }}" {{ $periode->status == 'AKTIF' ? 'selected' : '' }}>
                            {{ $periode->nama_periode }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kelompok -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-wrap items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-list text-white text-sm"></i>
                </div>
                <h5 class="font-semibold text-gray-800">Daftar Kelompok KKN</h5>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Total: {{ $kelompoks->total() }}
                    Kelompok</span>
            </div>
        </div>

        <div class="p-5">
            <!-- Search -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                <div class="flex items-center gap-2">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                    <span class="text-sm text-gray-600">Cari Kelompok</span>
                </div>
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" wire:model.live="search"
                        class="w-full pl-9 pr-8 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari nama kelompok...">
                    @if($search)
                    <button wire:click="$set('search', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Tabel Desktop -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Kelompok</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                Periode</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                Kegiatan KKN</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">
                                Lokasi</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kelompoks as $index => $kelompok)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $kelompoks->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div>
                                    <strong
                                        class="text-sm font-semibold text-gray-800">{{ $kelompok->nama_kelompok }}</strong>
                                    <div class="mt-1">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-cyan-50 text-cyan-700 text-xs rounded-full">
                                            <i class="fas fa-user-graduate text-xs"></i>
                                            Anggota: {{ $kelompok->anggota_count ?? 'Belum ada anggota' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                                    <i class="fas fa-calendar"></i>
                                    {{ $kelompok->periode->nama_periode ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-amber-50 text-amber-700 text-xs rounded-full">
                                    <i class="fas fa-tasks"></i>
                                    {{ $kelompok->kegiatan->nama_kegiatan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-sky-50 text-sky-700 text-xs rounded-full">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $kelompok->lokasi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('kegiatan.kelompok.detail', [
    'role' => auth()->user()->role, 
    'jenisKegiatan' => $jenisKegiatan ?? 'KKN',
    'kelompokID' => $kelompok->id
]) }}" 
   wire:navigate 
   class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition group relative inline-flex items-center justify-center">
    <i class="fas fa-info-circle"></i>
    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
        Detail Kelompok
    </span>
</a>
                                    <button wire:click="editKelompok({{ $kelompok->id }})"
                                        class="w-9 h-9 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition group relative inline-flex items-center justify-center">
                                        <i class="fas fa-edit"></i>
                                        <span
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                            Edit Kelompok
                                        </span>
                                    </button>
                                    <button
                                        wire:click="$dispatch('confirmDelete', { type: 'kelompok', id: {{ $kelompok->id }} })"
                                        class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition group relative inline-flex items-center justify-center">
                                        <i class="fas fa-trash-alt"></i>
                                        <span
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                            Hapus Kelompok
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-users text-gray-400 text-5xl"></i>
                                    <p class="text-gray-500">Belum ada kelompok KKN</p>
                                    <small class="text-gray-400">Klik tombol "Tambah Kelompok" untuk membuat kelompok
                                        baru</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($kelompoks->hasPages())
            <div class="mt-6">
                {{ $kelompoks->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah/Edit Kelompok -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto"
        style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-700 to-blue-500">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas {{ $isEditing ? 'fa-edit' : 'fa-plus-circle' }} text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-white font-semibold">
                                {{ $isEditing ? 'Edit Kelompok' : 'Tambah Kelompok Baru' }}</h5>
                            <p class="text-white/80 text-xs">
                                {{ $isEditing ? 'Ubah data kelompok' : 'Silakan isi form di bawah ini' }}</p>
                        </div>
                    </div>
                    <button type="button"
                        class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition"
                        wire:click="closeModal">
                        <i class="fas fa-times text-white text-sm"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5 space-y-4">
                    <!-- Nama Kelompok -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-tag text-gray-400 mr-1"></i> Nama Kelompok
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-font absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" wire:model="nama_kelompok"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_kelompok') border-red-500 @enderror"
                                placeholder="Contoh: Kelompok A, Kelompok 1, Kelompok Desa Sukamaju">
                        </div>
                        @error('nama_kelompok')
                        <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Periode -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-calendar-alt text-gray-400 mr-1"></i> Periode
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select wire:model="periode_id"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('periode_id') border-red-500 @enderror">
                                <option value="">Pilih Periode</option>
                                @foreach($periodes as $periode)
                                <option value="{{ $periode->id }}" {{ $periode->status == 'AKTIF' ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('periode_id')
                        <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Kegiatan KKN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-tasks text-gray-400 mr-1"></i> Kegiatan KKN
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-briefcase absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select wire:model="kegiatan_id"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kegiatan_id') border-red-500 @enderror">
                                <option value="">Pilih Kegiatan KKN</option>
                                @foreach($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('kegiatan_id')
                        <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> Lokasi KKN
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-location-dot absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" wire:model="lokasi"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lokasi') border-red-500 @enderror"
                                placeholder="Contoh: Desa Sukamaju, Kec. Cisarua, Kab. Bogor">
                        </div>
                        @error('lokasi')
                        <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                    <button type="button"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium transition"
                        wire:click="closeModal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button"
                        class="px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white rounded-full text-sm font-medium transition"
                        wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove><i
                                class="fas {{ $isEditing ? 'fa-save' : 'fa-plus-circle' }} mr-1"></i>
                            {{ $isEditing ? 'Update' : 'Simpan' }}</span>
                        <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>