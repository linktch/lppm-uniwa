<div class="min-h-screen bg-gray-300 pb-20">
    <!-- Header dengan Gradient (sama untuk semua device) -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white sticky top-0 z-10 shadow-lg">
        <div class="px-4 py-5 md:px-6 md:py-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-quran text-2xl md:text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold">Penilaian Hafalan</h1>
                    <p class="text-xs md:text-sm text-white/80 mt-0.5">Kelola indikator dan nilai hafalan peserta KKN</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 md:px-6 py-4 md:py-6">
        
        <!-- ==================== TAMPILAN MOBILE (CARD LIST) ==================== -->
        <div class="block md:hidden">
            
            <!-- 2 Card: Indikator & Penilaian (Mobile) -->
            <div class="space-y-4 mb-4">
                
                @if(Auth()->user()->role == 'superadmin')
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fas fa-list text-white text-base"></i>
                            </div>
                            <h3 class="text-white font-semibold text-sm">Indikator Hafalan</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-600 text-sm mb-3">Kelola daftar indikator penilaian hafalan.</p>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                <i class="fas fa-tag"></i> {{ $indikators->count() }} Indikator
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded-full">
                                <i class="fas fa-mars"></i> {{ $indikators->where('laki_laki', true)->count() }} Laki
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-pink-100 text-pink-600 text-xs rounded-full">
                                <i class="fas fa-venus"></i> {{ $indikators->where('perempuan', true)->count() }} Perempuan
                            </span>
                        </div>
                        <button wire:click="openModalTambah" class="w-full bg-gradient-to-r from-blue-700 to-blue-500 text-white font-medium py-2 rounded-full text-sm">
                            <i class="fas fa-plus-circle mr-2"></i> Kelola Indikator
                        </button>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fas fa-users text-white text-base"></i>
                            </div>
                            <h3 class="text-white font-semibold text-sm">Penilaian Hafalan</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-600 text-sm mb-3">Lakukan penilaian hafalan peserta KKN.</p>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                <i class="fas fa-user-graduate"></i> {{ $totalMahasiswa ?? 0 }} Mahasiswa
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                                <i class="fas fa-check-circle"></i> {{ $sudahDinilai ?? 0 }} Dinilai
                            </span>
                        </div>
                        <a href="{{ route('kegiatan.screening.hafalan.penilaian', ['role' => auth()->user()->role, 'jenisKegiatan' => $jenisKegiatan ?? 'KKN']) }}" 
                           wire:navigate 
                           class="w-full bg-gradient-to-r from-teal-600 to-teal-500 text-white font-medium py-2 rounded-full text-sm text-center inline-block">
                            <i class="fas fa-star mr-2"></i> Mulai Penilaian
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabel Indikator (Mobile - Card List) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-list text-blue-600"></i>
                        <h5 class="font-semibold text-gray-800 text-sm">Daftar Indikator</h5>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($indikators as $index => $indikator)
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-medium text-gray-800 text-sm">{{ $loop->iteration }}. {{ $indikator->nama_indikator }}</span>
                            <div class="flex gap-1">
                                @if(Auth()->user()->role == 'superadmin')
                                <button wire:click="editIndikator({{ $indikator->id }})" class="p-1 text-yellow-600">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-3 mt-2">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $indikator->laki_laki ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                <i class="fas fa-mars mr-1"></i> {{ $indikator->laki_laki ? 'Wajib' : 'Tidak' }}
                            </span>
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $indikator->perempuan ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                <i class="fas fa-venus mr-1"></i> {{ $indikator->perempuan ? 'Wajib' : 'Tidak' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">Belum ada indikator</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ==================== TAMPILAN DESKTOP (TABEL) ==================== -->
        <div class="hidden md:block">
            
            <!-- 2 Card: Indikator & Penilaian (Desktop) -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                
                @if(Auth()->user()->role == 'superadmin')
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fas fa-list text-white text-lg"></i>
                            </div>
                            <h3 class="text-white font-semibold text-lg">Indikator Hafalan</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-500 text-sm mb-4">Kelola daftar indikator penilaian hafalan.</p>
                        <div class="flex flex-wrap gap-3 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                                <i class="fas fa-tag"></i> {{ $indikators->count() }} Indikator
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-600 text-sm rounded-full">
                                <i class="fas fa-mars"></i> {{ $indikators->where('laki_laki', true)->count() }} Laki
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-pink-100 text-pink-600 text-sm rounded-full">
                                <i class="fas fa-venus"></i> {{ $indikators->where('perempuan', true)->count() }} Perempuan
                            </span>
                        </div>
                        <button wire:click="openModalTambah" class="w-full bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white font-medium py-2.5 rounded-full transition">
                            <i class="fas fa-plus-circle mr-2"></i> Kelola Indikator
                        </button>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                            <h3 class="text-white font-semibold text-lg">Penilaian Hafalan</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-500 text-sm mb-4">Lakukan penilaian hafalan peserta KKN.</p>
                        <div class="flex flex-wrap gap-3 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                                <i class="fas fa-user-graduate"></i> {{ $totalMahasiswa ?? 0 }} Mahasiswa
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-600 text-sm rounded-full">
                                <i class="fas fa-check-circle"></i> {{ $sudahDinilai ?? 0 }} Dinilai
                            </span>
                        </div>
                        <a href="{{ route('kegiatan.screening.hafalan.penilaian', ['role' => auth()->user()->role, 'jenisKegiatan' => $jenisKegiatan ?? 'KKN']) }}" 
                           wire:navigate 
                           class="w-full bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-medium py-2.5 rounded-full transition text-center inline-block">
                            <i class="fas fa-star mr-2"></i> Mulai Penilaian
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabel Indikator (Desktop - Tabel) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50">
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
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 w-12">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Indikator</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 w-28">Laki-laki</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 w-28">Perempuan</th>
                                    @if(Auth()->user()->role == 'superadmin')
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 w-20">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($indikators as $index => $indikator)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">{{ $indikator->nama_indikator }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $indikator->laki_laki ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $indikator->laki_laki ? 'Wajib' : 'Tidak Wajib' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $indikator->perempuan ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $indikator->perempuan ? 'Wajib' : 'Tidak Wajib' }}
                                        </span>
                                    </td>
                                    @if(Auth()->user()->role == 'superadmin')
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <button wire:click="editIndikator({{ $indikator->id }})" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-600 p-2 rounded-lg">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada indikator hafalan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal (sama untuk semua device) -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-700 to-blue-500">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas {{ $isEditing ? 'fa-edit' : 'fa-plus-circle' }} text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-white font-semibold">{{ $isEditing ? 'Edit Indikator' : 'Tambah Indikator' }}</h5>
                            <p class="text-white/80 text-xs">{{ $isEditing ? 'Ubah data' : 'Isi form di bawah' }}</p>
                        </div>
                    </div>
                    <button type="button" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center" wire:click="closeModal">
                        <i class="fas fa-times text-white text-sm"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Indikator <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="nama_indikator" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        @error('nama_indikator') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Laki-laki</label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="laki_laki" value="1" class="hidden peer">
                                <div class="text-center p-2 rounded-lg border-2 peer-checked:border-green-500">Wajib</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="laki_laki" value="0" class="hidden peer">
                                <div class="text-center p-2 rounded-lg border-2 peer-checked:border-red-500">Tidak</div>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Perempuan</label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="perempuan" value="1" class="hidden peer">
                                <div class="text-center p-2 rounded-lg border-2 peer-checked:border-green-500">Wajib</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="perempuan" value="0" class="hidden peer">
                                <div class="text-center p-2 rounded-lg border-2 peer-checked:border-red-500">Tidak</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 p-4 border-t bg-gray-50">
                    <button type="button" class="px-4 py-2 bg-gray-200 rounded-lg text-sm" wire:click="closeModal">Batal</button>
                    <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm" wire:click="save">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>