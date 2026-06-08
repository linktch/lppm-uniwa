<div class="rounded-xl min-h-screen bg-gray-100 pb-20">
    <!-- Header dengan Gradient -->
    <div class="rounded-xl bg-gradient-to-r from-blue-700 to-blue-500 text-white sticky top-0 z-10 shadow-lg">
        <div class="px-4 py-5 md:px-8 md:py-6">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-folder-open text-2xl md:text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold">Berkas Saya</h1>
                    <p class="text-xs md:text-sm text-white/80 mt-0.5">
                        Lihat dan kelola berkas persyaratan {{ $jenisKegiatan }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-6 md:px-8 md:py-8">
        <!-- Tombol Kembali -->
        <div class="mb-6">
            <a href="/{{ $role }}/kegiatan/{{ $jenisKegiatan }}/pendaftaran" 
               wire:navigate
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm transition-all duration-200">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Informasi Sumber Data -->
        @if(!empty($dokumen) && isset($dokumen[0]->dari_screening) && $dokumen[0]->dari_screening)
        <div class="mb-6 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fas fa-database"></i>
            <span>Data berkas diambil dari data screening sebelumnya</span>
        </div>
        @endif

        <!-- Grid Dokumen - 2 kolom (mobile) / 3 kolom (tablet) / 4 kolom (desktop) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">
            
            <!-- CARD SERTIFIKAT -->
            @if($sertifikat)
            <a href="/{{ $role }}/kegiatan/{{ $jenisKegiatan }}/sertifikat/{{ auth()->id() }}" 
               target="_blank"
               class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 border-2 border-blue-500 bg-blue-50 active:scale-95 hover:scale-105 cursor-pointer">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-certificate text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Sertifikat</h3>
                <div class="flex items-center justify-center md:justify-start gap-1 mt-2">
                    <i class="fas fa-award text-blue-500 text-xs"></i>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $sertifikat->nomor_sertifikat }}</p>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                    <span class="px-2 py-1 bg-blue-500 text-white rounded-lg text-xs inline-flex items-center gap-1">
                        <i class="fas fa-external-link-alt"></i> Lihat Sertifikat
                    </span>
                </div>
            </a>
            @else
            <div class="group bg-white rounded-2xl shadow-sm transition-all duration-200 p-4 border-2 border-gray-300 bg-gray-50 cursor-not-allowed">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center mb-3 transition mx-auto md:mx-0">
                    <i class="fas fa-certificate text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-500 text-sm md:text-base text-center md:text-left">Sertifikat</h3>
                <div class="flex items-center justify-center md:justify-start gap-1 mt-2">
                    <i class="fas fa-award text-gray-400 text-xs"></i>
                    <p class="text-xs text-gray-400 mt-0.5">Belum Tersedia</p>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                    <span class="px-2 py-1 bg-gray-300 text-white rounded-lg text-xs cursor-not-allowed">
                        <i class="fas fa-certificate"></i> Belum Ada
                    </span>
                </div>
            </div>
            @endif

            <!-- LOOP DOKUMEN -->
            @forelse($dokumen as $item)
            @php
                $statusColor = match($item->status) {
                    'valid' => 'border-green-500 bg-green-50',
                    'pending' => 'border-yellow-500 bg-yellow-50',
                    'ditolak' => 'border-red-500 bg-red-50',
                    default => 'border-gray-300 bg-gray-50'
                };
                $statusIcon = match($item->status) {
                    'valid' => 'fa-check-circle text-green-600',
                    'pending' => 'fa-clock text-yellow-600',
                    'ditolak' => 'fa-times-circle text-red-600',
                    default => 'fa-question-circle text-gray-600'
                };
                $statusText = match($item->status) {
                    'valid' => 'Valid',
                    'pending' => 'Pending',
                    'ditolak' => 'Ditolak',
                    default => ucfirst($item->status ?? 'Pending')
                };
                
                $headerIcon = match($item->jenis_dokumen) {
                    'surat' => 'fa-envelope',
                    'ktp' => 'fa-id-card',
                    'spp' => 'fa-receipt',
                    default => 'fa-file-alt'
                };
                $headerBg = match($item->jenis_dokumen) {
                    'surat' => 'bg-purple-100 text-purple-700',
                    'ktp' => 'bg-green-100 text-green-700',
                    'spp' => 'bg-yellow-100 text-yellow-700',
                    default => 'bg-blue-100 text-blue-700'
                };
            @endphp
            
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 border-2 {{ $statusColor }} active:scale-95 hover:scale-105">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl {{ $headerBg }} flex items-center justify-center mb-3 transition mx-auto md:mx-0">
                    <i class="fas {{ $headerIcon }} text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">{{ $item->label ?? ucfirst($item->jenis_dokumen) }}</h3>
                <div class="flex items-center justify-center md:justify-start gap-1 mt-2">
                    <i class="fas {{ $statusIcon }} text-xs"></i>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $statusText }}</p>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                    <button wire:click="previewFile('{{ $item->file_path }}')" 
                            class="px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs transition">
                        <i class="fas fa-eye"></i> Lihat
                    </button>
                    <button wire:click="downloadFile('{{ $item->file_path }}', '{{ $item->file_name ?? 'dokumen.pdf' }}')" 
                            class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs transition">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
                @if($item->status == 'ditolak' && isset($item->keterangan) && $item->keterangan)
                    <p class="text-xs text-red-500 mt-2 truncate">{{ $item->keterangan }}</p>
                @endif
            </div>
            @empty
            <div class="col-span-2 md:col-span-3 lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                    <i class="fas fa-folder-open text-gray-300 text-5xl mb-3 block"></i>
                    <p class="text-gray-500">Belum ada berkas yang diupload</p>
                    <p class="text-sm text-gray-400 mt-1">Silakan upload berkas di menu Pendaftaran</p>
                    <a href="/{{ $role }}/kegiatan/{{ $jenisKegiatan }}/pendaftaran" 
                       wire:navigate
                       class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm transition-all duration-200">
                        <i class="fas fa-upload"></i> Upload Berkas
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('style')
<style>
/* Touch-friendly untuk mobile */
@media (max-width: 768px) {
    .group {
        min-height: auto;
        display: flex;
        flex-direction: column;
        text-align: center;
    }

    .active\:scale-95:active {
        transform: scale(0.95);
    }
}

/* Hover effect untuk desktop */
@media (min-width: 769px) {
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
}

/* Smooth transition */
.group {
    transition: all 0.2s ease;
}

button {
    transition: all 0.2s ease;
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openPreview', (url) => {
            window.open(url, '_blank');
        });
    });
</script>
@endpush