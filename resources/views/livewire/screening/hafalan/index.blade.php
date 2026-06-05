<div class="p-6">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4 md:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-quran text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold text-gray-800">Penilaian Hafalan Do'a dan Tahlil</h4>
                        <p class="text-xs md:text-sm text-gray-500">Kelola indikator dan nilai hafalan peserta KKN</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Card: Indikator Hafalan & Penilaian Mahasiswa -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        
        <!-- Card Indikator Hafalan (Only Superadmin) -->
        @if(Auth()->user()->role == 'superadmin')
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-list text-white text-base"></i>
                    </div>
                    <h3 class="text-white font-semibold text-base md:text-lg">Indikator Hafalan</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                    Kelola daftar indikator penilaian hafalan yang akan digunakan untuk menilai peserta KKN.
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                        <i class="fas fa-tag"></i> {{ $indikators->count() }} Indikator
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 text-blue-600 text-xs rounded-full">
                        <i class="fas fa-mars"></i> {{ $indikators->where('laki_laki', true)->count() }} Laki-laki
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-pink-100 text-pink-600 text-xs rounded-full">
                        <i class="fas fa-venus"></i> {{ $indikators->where('perempuan', true)->count() }} Perempuan
                    </span>
                </div>
                <button wire:click="openModalTambah" 
                        class="w-full bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105">
                    <i class="fas fa-plus-circle mr-2"></i> Kelola Indikator
                </button>
            </div>
        </div>
        @endif

        <!-- Card Penilaian Hafalan Mahasiswa -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-users text-white text-base"></i>
                    </div>
                    <h3 class="text-white font-semibold text-base md:text-lg">Penilaian Hafalan Mahasiswa</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                    Lakukan penilaian hafalan untuk setiap peserta KKN berdasarkan indikator yang telah ditentukan.
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                        <i class="fas fa-user-graduate"></i> {{ $totalMahasiswa ?? 0 }} Mahasiswa
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                        <i class="fas fa-check-circle"></i> {{ $sudahDinilai ?? 0 }} Sudah Dinilai
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-100 text-yellow-600 text-xs rounded-full">
                        <i class="fas fa-clock"></i> {{ $belumDinilai ?? 0 }} Belum Dinilai
                    </span>
                </div>
                <a href="{{ route('kegiatan.screening.hafalan.penilaian', [
    'role' => auth()->user()->role,
    'jenisKegiatan' => $jenisKegiatan ?? 'KKN'
]) }}" 
   wire:navigate 
   class="w-full bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105 text-center inline-block">
    <i class="fas fa-star mr-2"></i> Mulai Penilaian
</a>
            </div>
        </div>
    </div>

    <!-- Tabel Indikator Hafalan -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-list text-blue-600 text-sm"></i>
                </div>
                <h5 class="font-semibold text-gray-800">Daftar Indikator Hafalan</h5>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Total: {{ $indikators->count() }} Indikator</span>
            </div>
        </div>
        
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indikator</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Laki-laki</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Perempuan</th>
                            @if(Auth()->user()->role == 'superadmin')
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($indikators as $index => $indikator)
                        <tr class="hover:bg-gray-50 transition" wire:key="row-{{ $indikator->id }}">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-gray-800">{{ $indikator->nama_indikator }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($indikator->laki_laki)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    <i class="fas fa-check-circle"></i> Wajib
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">
                                    <i class="fas fa-times-circle"></i> Tidak Wajib
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($indikator->perempuan)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    <i class="fas fa-check-circle"></i> Wajib
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">
                                    <i class="fas fa-times-circle"></i> Tidak Wajib
                                </span>
                                @endif
                            </td>
                            @if(Auth()->user()->role == 'superadmin')
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button wire:click="editIndikator({{ $indikator->id }})" 
                                            class="bg-yellow-50 hover:bg-yellow-100 text-yellow-600 p-2 rounded-lg transition group relative">
                                        <i class="fas fa-edit"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                            Edit
                                        </span>
                                    </button>
                                    <button wire:click="deleteIndikator({{ $indikator->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus indikator ini?"
                                            class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition group relative">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                            Hapus
                                        </span>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth()->user()->role == 'superadmin' ? 5 : 4 }}" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-quran text-gray-400 text-5xl"></i>
                                    <p class="text-gray-500">Belum ada indikator hafalan</p>
                                    <small class="text-gray-400">Klik tombol "Kelola Indikator" untuk menambahkan indikator penilaian</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Indikator -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-700 to-blue-500">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas {{ $isEditing ? 'fa-edit' : 'fa-plus-circle' }} text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-white font-semibold">{{ $isEditing ? 'Edit Indikator' : 'Tambah Indikator Hafalan' }}</h5>
                            <p class="text-white/80 text-xs">{{ $isEditing ? 'Ubah data indikator' : 'Silakan isi form di bawah ini' }}</p>
                        </div>
                    </div>
                    <button type="button" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition" wire:click="closeModal">
                        <i class="fas fa-times text-white text-sm"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5 space-y-4">
                    <!-- Nama Indikator -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-tag text-gray-400 mr-1"></i> Nama Indikator
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-font absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" 
                                   class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_indikator') border-red-500 @enderror" 
                                   wire:model="nama_indikator" 
                                   placeholder="Masukkan nama indikator">
                        </div>
                        @error('nama_indikator')
                            <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Status Laki-laki -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-mars text-gray-400 mr-1"></i> Status untuk Laki-laki
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="laki_laki" value="1" class="hidden peer">
                                <div class="flex items-center justify-center gap-2 p-2 rounded-lg border-2 border-gray-200 bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span class="text-sm font-medium text-green-700">Wajib</span>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="laki_laki" value="0" class="hidden peer">
                                <div class="flex items-center justify-center gap-2 p-2 rounded-lg border-2 border-gray-200 bg-gray-50 peer-checked:border-red-500 peer-checked:bg-red-50 transition">
                                    <i class="fas fa-times-circle text-red-600"></i>
                                    <span class="text-sm font-medium text-red-700">Tidak Wajib</span>
                                </div>
                            </label>
                        </div>
                        @error('laki_laki')
                            <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Status Perempuan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-venus text-gray-400 mr-1"></i> Status untuk Perempuan
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="perempuan" value="1" class="hidden peer">
                                <div class="flex items-center justify-center gap-2 p-2 rounded-lg border-2 border-gray-200 bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span class="text-sm font-medium text-green-700">Wajib</span>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="perempuan" value="0" class="hidden peer">
                                <div class="flex items-center justify-center gap-2 p-2 rounded-lg border-2 border-gray-200 bg-gray-50 peer-checked:border-red-500 peer-checked:bg-red-50 transition">
                                    <i class="fas fa-times-circle text-red-600"></i>
                                    <span class="text-sm font-medium text-red-700">Tidak Wajib</span>
                                </div>
                            </label>
                        </div>
                        @error('perempuan')
                            <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition" wire:click="closeModal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition" wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="fas {{ $isEditing ? 'fa-save' : 'fa-plus-circle' }} mr-1"></i>
                            {{ $isEditing ? 'Update' : 'Simpan' }}
                        </span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-pulse mr-1"></i> Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>