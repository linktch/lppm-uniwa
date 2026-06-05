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
               <a href="{{ route('kegiatan.screening.hafalan.index', [
    'role' => $role,
    'jenisKegiatan' => $jenisKegiatan ?? 'KKN'
]) }}" 
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
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-44">Progress</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
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
                            <td class="px-4 py-3">
                                @php
                                    $progress = $mahasiswa->progress ?? 0;
                                    $sudahDinilai = $mahasiswa->sudah_dinilai ?? 0;
                                    $totalIndikator = $mahasiswa->total_indikator ?? 0;
                                    $totalNilai = $mahasiswa->total_nilai ?? 0;
                                    $maxNilai = $mahasiswa->max_nilai ?? 0;
                                @endphp
                                <div class="w-full min-w-[180px]">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-medium text-gray-600">Progress</span>
                                        <span class="text-xs font-semibold 
                                            {{ $progress < 30 ? 'text-red-600' : '' }}
                                            {{ $progress >= 30 && $progress < 70 ? 'text-yellow-600' : '' }}
                                            {{ $progress >= 70 ? 'text-green-600' : '' }}">
                                            {{ $progress }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden shadow-inner">
                                        <div class="h-2.5 rounded-full transition-all duration-500" 
                                             style="width: {{ $progress }}%; min-width: 4%;
                                                    background: linear-gradient(90deg, 
                                                        #ef4444 0%, 
                                                        #f59e0b 50%, 
                                                        #10b981 100%);">
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center mt-1.5">
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                                            <span class="text-xs text-gray-500">{{ $sudahDinilai }}/{{ $totalIndikator }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                                            <span class="text-xs text-gray-500">{{ $totalNilai }}/{{ $maxNilai }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Beri Penilaian -->
                                   <a wire:navigate
   href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/screening/hafalan/detail/{$mahasiswa->id}") }}"
   class="inline-flex items-center justify-center w-9 h-9 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg transition group relative">
    <i class="fas fa-star"></i>
    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
        Beri Penilaian
    </span>
</a>

                                    <!-- Tombol Generate Sertifikat -->
                                    <button wire:click="openPasswordModal({{ $mahasiswa->id }}, '{{ $mahasiswa->nama }}')"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg transition group relative">
                                        <i class="fas fa-certificate"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                            Generate Sertifikat
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
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

    <!-- MODAL KONFIRMASI PASSWORD -->
    @if($showPasswordModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePasswordModal"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-lock text-green-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Konfirmasi Password Digital Signature
                            </h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 mb-3">
                                    Masukkan password digital signature untuk generate sertifikat <br>
                                    <strong class="text-gray-700">{{ $selectedMahasiswaName }}</strong>
                                </p>
                                
                                <div class="mt-2">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                        Password
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           wire:model="password"
                                           wire:keydown.enter="generateSertifikat"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                           placeholder="Masukkan password digital signature"
                                           autofocus>
                                    @error('password') 
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mt-3 bg-yellow-50 border-l-4 border-yellow-400 p-2">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-yellow-600 text-sm mt-0.5"></i>
                                        <p class="ml-2 text-xs text-yellow-700">
                                            Password digital signature digunakan untuk otorisasi penerbitan sertifikat
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="generateSertifikat"
                            wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span wire:loading.remove wire:target="generateSertifikat">
                            <i class="fas fa-check mr-1"></i> Generate
                        </span>
                        <span wire:loading wire:target="generateSertifikat">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Memproses...
                        </span>
                    </button>
                    <button wire:click="closePasswordModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openPdfPreview', (url) => {
            console.log('Opening PDF:', url);
            window.open(url, '_blank');
        });
    });
</script>
@endpush