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
        <!-- Search & Info (Responsive) -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-2 px-3 py-2 bg-white text-gray-700 text-sm rounded-full shadow-sm">
                    <i class="fas fa-users"></i> Total: {{ $mahasiswas->total() }}
                </span>
                <a href="{{ route('kegiatan.screening.hafalan.index', ['role' => $role, 'jenisKegiatan' => $jenisKegiatan ?? 'KKN']) }}" 
                   wire:navigate
                   class="inline-flex items-center gap-2 px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="relative w-full md:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" wire:model.live="search"
                       class="w-full pl-9 pr-8 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                       placeholder="Cari mahasiswa...">
                @if($search)
                <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-times text-xs"></i>
                </button>
                @endif
            </div>
        </div>

        <!-- ==================== TAMPILAN MOBILE (CARD LIST) ==================== -->
        <div class="block md:hidden space-y-3">
            @forelse($mahasiswas as $index => $mahasiswa)
            @php
                $progress = $mahasiswa->progress ?? 0;
                $sudahDinilai = $mahasiswa->sudah_dinilai ?? 0;
                $totalIndikator = $mahasiswa->total_indikator ?? 0;
                $totalNilai = $mahasiswa->total_nilai ?? 0;
                $maxNilai = $mahasiswa->max_nilai ?? 0;
            @endphp
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Header Card -->
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-xs text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $mahasiswa->nama }}</p>
                            <p class="text-xs text-gray-400">{{ $mahasiswa->nim }}</p>
                        </div>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                        {{ $mahasiswa->kelompok }}
                    </span>
                </div>
                
                <!-- Body Card -->
                <div class="p-4">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Prodi: {{ $mahasiswa->prodi }}</span>
                        <span>JK: {{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    
                    <!-- Progress -->
                    <div class="mt-2">
                        <div class="flex justify-between text-xs mb-1">
                            <span>Progress</span>
                            <span class="font-semibold {{ $progress < 30 ? 'text-red-600' : ($progress < 70 ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $progress }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full" style="width: {{ $progress }}%; background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span><i class="fas fa-check-circle text-green-500"></i> {{ $sudahDinilai }}/{{ $totalIndikator }}</span>
                            <span><i class="fas fa-star text-yellow-500"></i> {{ $totalNilai }}/{{ $maxNilai }}</span>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="flex gap-2 mt-4">
                        <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/screening/hafalan/detail/{$mahasiswa->id}") }}" 
                           wire:navigate
                           class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg text-sm transition">
                            <i class="fas fa-star"></i> Nilai
                        </a>
                        <button wire:click="openPasswordModal({{ $mahasiswa->id }}, '{{ $mahasiswa->nama }}')"
                                class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg text-sm transition">
                            <i class="fas fa-certificate"></i> Sertifikat
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <i class="fas fa-users text-gray-300 text-5xl mb-3 block"></i>
                <p class="text-gray-500">Tidak ada data mahasiswa</p>
            </div>
            @endforelse

            <!-- Pagination Mobile -->
            @if($mahasiswas->hasPages())
            <div class="mt-4">
                {{ $mahasiswas->links() }}
            </div>
            @endif
        </div>

        <!-- ==================== TAMPILAN DESKTOP (TABEL) ==================== -->
        <div class="hidden md:block bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-5 py-4 border-b bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-sm"></i>
                    </div>
                    <h5 class="font-semibold text-gray-800">Daftar Mahasiswa</h5>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                        {{ $mahasiswas->firstItem() }} - {{ $mahasiswas->lastItem() }} dari {{ $mahasiswas->total() }}
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 w-24">NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Prodi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 w-24">Kelompok</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 w-28">Jenis Kelamin</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 w-44">Progress</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mahasiswas as $index => $mahasiswa)
                        @php
                            $progress = $mahasiswa->progress ?? 0;
                            $sudahDinilai = $mahasiswa->sudah_dinilai ?? 0;
                            $totalIndikator = $mahasiswa->total_indikator ?? 0;
                            $totalNilai = $mahasiswa->total_nilai ?? 0;
                            $maxNilai = $mahasiswa->max_nilai ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $mahasiswas->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-mono text-sm">{{ $mahasiswa->nim }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $mahasiswa->nama }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $mahasiswa->prodi }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $mahasiswa->kelompok }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $mahasiswa->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                    <i class="fas {{ $mahasiswa->jenis_kelamin == 'L' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                    {{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="w-40">
                                    <div class="flex justify-between text-xs mb-0.5">
                                        <span class="text-gray-500">Progress</span>
                                        <span class="font-semibold {{ $progress < 30 ? 'text-red-600' : ($progress < 70 ? 'text-yellow-600' : 'text-green-600') }}">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full" style="width: {{ $progress }}%; background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span><i class="fas fa-check-circle text-green-500"></i> {{ $sudahDinilai }}/{{ $totalIndikator }}</span>
                                        <span><i class="fas fa-star text-yellow-500"></i> {{ $totalNilai }}/{{ $maxNilai }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/screening/hafalan/detail/{$mahasiswa->id}") }}" 
                                       wire:navigate
                                       class="inline-flex items-center justify-center w-9 h-9 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg transition">
                                        <i class="fas fa-star"></i>
                                    </a>
                                    <button wire:click="openPasswordModal({{ $mahasiswa->id }}, '{{ $mahasiswa->nama }}')"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg transition">
                                        <i class="fas fa-certificate"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-12 text-center text-gray-500">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($mahasiswas->hasPages())
            <div class="px-5 py-3 bg-gray-50 border-t">
                {{ $mahasiswas->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Password Sertifikat -->
    @if($showPasswordModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-600 to-green-500">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas fa-certificate text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-white font-semibold">Generate Sertifikat</h5>
                            <p class="text-white/80 text-xs">Konfirmasi password digital signature</p>
                        </div>
                    </div>
                    <button type="button" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center" wire:click="closePasswordModal">
                        <i class="fas fa-times text-white text-sm"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto">
                            <i class="fas fa-lock text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-3">
                            Masukkan password digital signature untuk generate sertifikat
                        </p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">{{ $selectedMahasiswaName }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <input type="password" wire:model="password" wire:keydown.enter="generateSertifikat"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Masukkan password digital signature" autofocus>
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-info-circle text-yellow-600 text-sm mt-0.5"></i>
                            <p class="text-xs text-yellow-700">Password digunakan untuk otorisasi penerbitan sertifikat</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 p-4 border-t bg-gray-50">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm" wire:click="closePasswordModal">Batal</button>
                    <button type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm" wire:click="generateSertifikat" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="fas fa-check mr-1"></i> Generate</span>
                        <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Proses</span>
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
            window.open(url, '_blank');
        });
    });
</script>
@endpush