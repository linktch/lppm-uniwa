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
                        <h4 class="text-lg md:text-xl font-semibold text-gray-800">Penilaian Hafalan</h4>
                        <p class="text-xs md:text-sm text-gray-500">Beri penilaian hafalan Al-Qur'an peserta KKN</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <!-- Tombol Export PDF -->
                    @if($totalNilai > 0)
                    <button type="button" wire:click="exportToPDF" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-full text-sm font-medium transition">
                        <span wire:loading.remove><i class="fas fa-file-pdf"></i> Export PDF</span>
                        <span wire:loading><i class="fas fa-spinner fa-pulse"></i> Memproses...</span>
                    </button>
                    @endif
                    <a href="#" wire:navigate 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm font-medium transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Mahasiswa Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-5 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col md:flex-row md:items-center gap-6">
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">NIM</p>
                            <p class="text-sm font-medium text-gray-700">{{ $mahasiswa->nim }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Program Studi</p>
                            <p class="text-sm font-medium text-gray-700">{{ $mahasiswa->prodi }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Kelompok</p>
                            <p class="text-sm font-medium text-gray-700">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Jenis Kelamin</p>
                            <p class="text-sm font-medium text-gray-700">
                                @if($mahasiswa->jenis_kelamin == 'L' || $mahasiswa->jenis_kelamin == 'Laki-laki')
                                <i class="fas fa-mars text-blue-500 mr-1"></i> Laki-laki
                                @else
                                <i class="fas fa-venus text-pink-500 mr-1"></i> Perempuan
                                @endif
                            </p>
                        </div>
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

    <!-- Timeline Pembekalan -->
    @if($timeline)
    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-4 mb-6 border-l-4 border-amber-600">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-alt text-white"></i>
            </div>
            <div class="flex-1">
                <h5 class="font-semibold text-amber-800 mb-2">Jadwal Pembekalan KKN</h5>
                <div class="flex flex-wrap gap-4 mb-2">
                    <span class="inline-flex items-center gap-1 text-xs text-amber-700">
                        <i class="fas fa-calendar-day"></i>
                        {{ \Carbon\Carbon::parse($timeline->tanggal_mulai)->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-amber-700">
                        <i class="fas fa-clock"></i>
                        {{ \Carbon\Carbon::parse($timeline->jam_mulai)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($timeline->jam_selesai)->format('H:i') }}
                    </span>
                    @if($timeline->tempat)
                    <span class="inline-flex items-center gap-1 text-xs text-amber-700">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $timeline->tempat }}
                    </span>
                    @endif
                </div>
                @if($timeline->deskripsi)
                <p class="text-xs text-amber-700 mt-1">{{ $timeline->deskripsi }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Tabel Indikator Penilaian -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-wrap items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-list-check text-white text-sm"></i>
                </div>
                <h5 class="font-semibold text-gray-800">Daftar Indikator Penilaian Hafalan</h5>
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
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Penilaian</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($indikators as $index => $indikator)
                        @php
                        $isWajib = ($mahasiswa->jenis_kelamin == 'L' || $mahasiswa->jenis_kelamin == 'Laki-laki')
                            ? $indikator->laki_laki
                            : $indikator->perempuan;
                        @endphp
                        <tr class="hover:bg-gray-50 transition" wire:key="indikator-{{ $indikator->id }}">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-gray-800">{{ $indikator->nama_indikator }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if(!$isWajib)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">
                                    <i class="fas fa-ban"></i> Tidak Wajib
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    <i class="fas fa-check-circle"></i> Wajib
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($isWajib)
                                <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="penilaian.{{ $indikator->id }}" value="sangat_lancar" class="hidden peer">
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium border-2 border-gray-200 bg-gray-50 text-green-700 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition">
                                            <i class="fas fa-star"></i> Sangat Lancar
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="penilaian.{{ $indikator->id }}" value="cukup_lancar" class="hidden peer">
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium border-2 border-gray-200 bg-gray-50 text-amber-600 peer-checked:bg-amber-600 peer-checked:text-white peer-checked:border-amber-600 transition">
                                            <i class="fas fa-thumbs-up"></i> Cukup Lancar
                                        </span>
                                    </label>
                                </div>
                                @else
                                <span class="text-sm text-gray-400">- Tidak wajib dinilai -</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end pt-5 mt-4 border-t border-gray-200">
                <button type="button" wire:click="savePenilaian" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white rounded-full text-sm font-medium transition">
                    <span wire:loading.remove><i class="fas fa-save"></i> Simpan Penilaian</span>
                    <span wire:loading><i class="fas fa-spinner fa-pulse"></i> Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>