<div class="min-h-screen bg-gray-300 pb-20">
    <!-- Header dengan Gradient -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white sticky top-0 z-10 shadow-lg">
        <div class="px-4 py-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Penilaian Hafalan</h1>
                    <p class="text-xs text-white/80 mt-0.5">Beri penilaian hafalan Al-Qur'an peserta KKN</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 mt-4">
        <!-- Tombol Aksi (Responsive) -->
        <div class="flex flex-wrap gap-3 mb-4">
            @if($totalNilai > 0)
            <button type="button" wire:click="exportToPDF" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                <span wire:loading.remove><i class="fas fa-file-pdf"></i> Export PDF</span>
                <span wire:loading><i class="fas fa-spinner fa-pulse"></i> Proses...</span>
            </button>
            @endif
            <a href="{{ route('kegiatan.screening.hafalan.penilaian', ['role' => $role, 'jenisKegiatan' => $jenisKegiatan]) }}" 
               wire:navigate 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Detail Mahasiswa Card (Responsive) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-4">
            <div class="p-4">
                <!-- Mobile Layout (Stack) -->
                <div class="block md:hidden text-center">
                    <!-- Avatar -->
                    <div class="flex justify-center mb-3">
                        @if($mahasiswa->foto)
                        <img src="{{ Storage::url($mahasiswa->foto) }}" alt="{{ $mahasiswa->nama }}" 
                             class="w-20 h-20 rounded-full object-cover ring-4 ring-blue-100">
                        @else
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center text-white text-3xl">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $mahasiswa->nama }}</h3>
                    <div class="space-y-1 text-sm mb-3">
                        <p><span class="text-gray-400">NIM:</span> <span class="font-medium">{{ $mahasiswa->nim }}</span></p>
                        <p><span class="text-gray-400">Prodi:</span> <span class="font-medium">{{ $mahasiswa->prodi }}</span></p>
                        <p><span class="text-gray-400">JK:</span> <span class="font-medium">
                            @if($mahasiswa->jenis_kelamin == 'L' || $mahasiswa->jenis_kelamin == 'Laki-laki')
                            <i class="fas fa-mars text-blue-500 mr-1"></i> Laki-laki
                            @else
                            <i class="fas fa-venus text-pink-500 mr-1"></i> Perempuan
                            @endif
                        </span></p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-700 to-blue-500 rounded-2xl px-4 py-2 inline-block">
                        <span class="text-2xl font-bold text-white">{{ $totalNilai }}</span>
                        <span class="text-white/80 text-sm">/ {{ $maxNilai }}</span>
                        <p class="text-xs text-white/80 mt-1">{{ round(($totalNilai / ($maxNilai ?: 1)) * 100) }}%</p>
                    </div>
                </div>

                <!-- Desktop Layout (Row) -->
                <div class="hidden md:flex md:items-center md:gap-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @if($mahasiswa->foto)
                        <img src="{{ Storage::url($mahasiswa->foto) }}" alt="{{ $mahasiswa->nama }}" 
                             class="w-24 h-24 rounded-full object-cover ring-4 ring-blue-100">
                        @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center text-white text-4xl">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Info Mahasiswa -->
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $mahasiswa->nama }}</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div><p class="text-xs text-gray-400">NIM</p><p class="text-sm font-medium">{{ $mahasiswa->nim }}</p></div>
                            <div><p class="text-xs text-gray-400">Prodi</p><p class="text-sm font-medium">{{ $mahasiswa->prodi }}</p></div>
                            <div><p class="text-xs text-gray-400">JK</p><p class="text-sm font-medium">
                                @if($mahasiswa->jenis_kelamin == 'L' || $mahasiswa->jenis_kelamin == 'Laki-laki')
                                <i class="fas fa-mars text-blue-500 mr-1"></i> Laki-laki
                                @else
                                <i class="fas fa-venus text-pink-500 mr-1"></i> Perempuan
                                @endif
                            </p></div>
                        </div>
                    </div>
                    
                    <!-- Score -->
                    <div class="text-center flex-shrink-0">
                        <div class="bg-gradient-to-br from-blue-700 to-blue-500 rounded-2xl px-5 py-3">
                            <span class="text-3xl font-bold text-white">{{ $totalNilai }}</span>
                            <span class="text-white/80 text-sm">/ {{ $maxNilai }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Total Nilai</p>
                        <p class="text-sm font-semibold text-green-600">{{ round(($totalNilai / ($maxNilai ?: 1)) * 100) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline (jika ada) -->
        @if($timeline)
        <div class="bg-yellow-50 rounded-xl p-3 mb-4 border-l-4 border-yellow-500">
            <div class="flex items-start gap-3">
                <i class="fas fa-calendar-alt text-yellow-600 text-lg"></i>
                <div class="flex-1">
                    <p class="font-semibold text-yellow-800 text-sm">Jadwal Pembekalan</p>
                    <p class="text-xs text-yellow-700">{{ \Carbon\Carbon::parse($timeline->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabel Indikator -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 py-3 border-b bg-gray-50">
                <div class="flex items-center gap-2">
                    <i class="fas fa-list-check text-blue-600"></i>
                    <h5 class="font-semibold text-gray-800 text-sm">Indikator Penilaian</h5>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">{{ $indikators->count() }} Indikator</span>
                </div>
            </div>
            
            <!-- Mobile View (Card List) -->
            <div class="block md:hidden divide-y divide-gray-100">
                @foreach($indikators as $index => $indikator)
                @php
                $isWajib = ($mahasiswa->jenis_kelamin == 'L' || $mahasiswa->jenis_kelamin == 'Laki-laki')
                    ? $indikator->laki_laki
                    : $indikator->perempuan;
                @endphp
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-medium text-gray-800 text-sm">{{ $loop->iteration }}. {{ $indikator->nama_indikator }}</span>
                        @if(!$isWajib)
                        <span class="inline-flex px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">Tidak Wajib</span>
                        @else
                        <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Wajib</span>
                        @endif
                    </div>
                    @if($isWajib)
                    <div class="flex gap-2 mt-2">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" wire:model="penilaian.{{ $indikator->id }}" value="sangat_lancar" class="hidden peer">
                            <div class="text-center p-2 rounded-lg border-2 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 border-gray-200 bg-gray-50 text-green-700">
                                <i class="fas fa-star"></i>
                                <span class="text-xs ml-1">Sangat Lancar</span>
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" wire:model="penilaian.{{ $indikator->id }}" value="cukup_lancar" class="hidden peer">
                            <div class="text-center p-2 rounded-lg border-2 peer-checked:bg-amber-600 peer-checked:text-white peer-checked:border-amber-600 border-gray-200 bg-gray-50 text-amber-600">
                                <i class="fas fa-thumbs-up"></i>
                                <span class="text-xs ml-1">Cukup Lancar</span>
                            </div>
                        </label>
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center mt-2">- Tidak wajib dinilai -</p>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Desktop View (Tabel) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Indikator</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 w-28">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Penilaian</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($indikators as $index => $indikator)
                        @php
                        $isWajib = ($mahasiswa->jenis_kelamin == 'L' || $mahasiswa->jenis_kelamin == 'Laki-laki')
                            ? $indikator->laki_laki
                            : $indikator->perempuan;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $indikator->nama_indikator }}</td>
                            <td class="px-4 py-3 text-center">
                                @if(!$isWajib)
                                <span class="inline-flex px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">Tidak Wajib</span>
                                @else
                                <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Wajib</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($isWajib)
                                <div class="flex gap-3 justify-center">
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="penilaian.{{ $indikator->id }}" value="sangat_lancar" class="hidden peer">
                                        <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-xs font-medium border-2 peer-checked:bg-green-600 peer-checked:text-white border-gray-200 bg-gray-50 text-green-700">
                                            <i class="fas fa-star"></i> Sangat Lancar
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="penilaian.{{ $indikator->id }}" value="cukup_lancar" class="hidden peer">
                                        <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-xs font-medium border-2 peer-checked:bg-amber-600 peer-checked:text-white border-gray-200 bg-gray-50 text-amber-600">
                                            <i class="fas fa-thumbs-up"></i> Cukup Lancar
                                        </span>
                                    </label>
                                </div>
                                @else
                                <span class="text-sm text-gray-400">- Tidak wajib -</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end p-4 border-t bg-gray-50">
                <button type="button" wire:click="savePenilaian" wire:loading.attr="disabled"
                        class="px-5 py-2 bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white rounded-lg text-sm font-medium transition">
                    <span wire:loading.remove><i class="fas fa-save mr-1"></i> Simpan</span>
                    <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>