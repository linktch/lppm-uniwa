<div class="p-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-5">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-800 to-cyan-600 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold text-gray-800">Validasi Dokumen KKN</h4>
                        <p class="text-sm text-gray-500">Validasi dokumen persyaratan peserta KKN</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="button" 
                        class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white rounded-full text-sm font-medium transition-all duration-200 hover:scale-105 flex items-center gap-2"
                        wire:click="exportKTPValid" wire:loading.attr="disabled">
                        <i class="fas fa-id-card"></i>
                        <span>Export KTP Valid</span>
                    </button>
                    <button type="button" 
                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white rounded-full text-sm font-medium transition-all duration-200 hover:scale-105 flex items-center gap-2"
                        wire:click="exportToPDF" wire:loading.attr="disabled">
                        <i class="fas fa-file-pdf"></i>
                        <span>Export ke PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Validasi -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-800 to-cyan-600 flex items-center justify-center">
                    <i class="fas fa-list text-white text-sm"></i>
                </div>
                <h5 class="font-semibold text-gray-800">Daftar Peserta KKN</h5>
                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs rounded-full">
                    Total: {{ $files->count() }} Peserta
                </span>
            </div>
        </div>

        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Prodi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Surat Pernyataan</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">KTP</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Bukti Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($files as $i => $file)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $i+1 }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono font-semibold text-gray-600 text-sm">{{ $file->nim }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full">{{ $file->prodi }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                        <i class="fas fa-user-circle text-blue-500 text-lg"></i>
                                    </div>
                                    <span class="font-medium text-gray-700 text-sm">{{ $file->nama }}</span>
                                </div>
                            </td>

                            <!-- Surat -->
                            <td class="px-4 py-3">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                        {{ $file->status_surat == 'valid' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $file->status_surat == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $file->status_surat == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                        @if($file->status_surat == 'valid')
                                            <i class="fas fa-check-circle"></i> Valid
                                        @elseif($file->status_surat == 'ditolak')
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        @else
                                            <i class="fas fa-clock"></i> Pending
                                        @endif
                                    </span>
                                    <button class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white transition flex items-center justify-center relative group"
                                        wire:click="lihatFile({{ $file->id }}, 'surat')"
                                        title="Lihat Surat">
                                        <i class="fas fa-eye text-sm"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                            Lihat Surat
                                        </span>
                                    </button>
                                </div>
                            </td>

                            <!-- KTP -->
                            <td class="px-4 py-3">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                        {{ $file->status_ktp == 'valid' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $file->status_ktp == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $file->status_ktp == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                        @if($file->status_ktp == 'valid')
                                            <i class="fas fa-check-circle"></i> Valid
                                        @elseif($file->status_ktp == 'ditolak')
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        @else
                                            <i class="fas fa-clock"></i> Pending
                                        @endif
                                    </span>
                                    <button class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white transition flex items-center justify-center relative group"
                                        wire:click="lihatFile({{ $file->id }}, 'ktp')"
                                        title="Lihat KTP">
                                        <i class="fas fa-eye text-sm"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                            Lihat KTP
                                        </span>
                                    </button>
                                </div>
                            </td>

                            <!-- SPP/Bukti Pembayaran -->
                            <td class="px-4 py-3">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                        {{ $file->status_spp == 'valid' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $file->status_spp == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $file->status_spp == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                        @if($file->status_spp == 'valid')
                                            <i class="fas fa-check-circle"></i> Valid
                                        @elseif($file->status_spp == 'ditolak')
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        @else
                                            <i class="fas fa-clock"></i> Pending
                                        @endif
                                    </span>
                                    <button class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white transition flex items-center justify-center relative group"
                                        wire:click="lihatFile({{ $file->id }}, 'spp')"
                                        title="Lihat Pembayaran">
                                        <i class="fas fa-eye text-sm"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                            Lihat Pembayaran
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <i class="fas fa-folder-open text-5xl"></i>
                                    <p class="text-gray-500">Belum ada data berkas</p>
                                    <small class="text-gray-400">Belum ada peserta yang mengupload berkas</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL VALIDASI DOKUMEN DENGAN ZOOM --}}
    @if($modalActive)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-800 to-cyan-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                <i class="fas fa-file-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-white font-semibold">Validasi Dokumen</h5>
                                <p class="text-white/80 text-sm">
                                    {{ $selectedType == 'surat' ? 'Surat Pernyataan' : ($selectedType == 'ktp' ? 'Foto KTP' : 'Bukti Pembayaran') }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="text-white/80 hover:text-white transition" wire:click="closeModal">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body dengan Fitur Zoom -->
                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                    @if($selectedFile)
                    <!-- Info Mahasiswa -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <small class="text-gray-500 text-xs">NIM</small>
                                <p class="font-semibold text-gray-800">{{ $selectedFile->nim }}</p>
                            </div>
                            <div>
                                <small class="text-gray-500 text-xs">Prodi</small>
                                <p class="font-semibold text-gray-800">{{ $selectedFile->prodi }}</p>
                            </div>
                            <div>
                                <small class="text-gray-500 text-xs">Nama Mahasiswa</small>
                                <p class="font-semibold text-gray-800">{{ $selectedFile->nama }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Zoom Controls -->
                    <div class="flex justify-center gap-3 mb-4">
                        <button type="button" id="zoomOutBtn" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition flex items-center gap-1">
                            <i class="fas fa-search-minus"></i> Zoom Out
                        </button>
                        <span id="zoomLevel" class="px-3 py-1.5 bg-gray-100 rounded-lg text-sm font-medium">100%</span>
                        <button type="button" id="zoomInBtn" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition flex items-center gap-1">
                            <i class="fas fa-search-plus"></i> Zoom In
                        </button>
                        <button type="button" id="zoomResetBtn" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition flex items-center gap-1">
                            <i class="fas fa-sync-alt"></i> Reset
                        </button>
                    </div>

                    <!-- Preview Dokumen dengan Zoom -->
                    <div id="documentContainer" class="mb-4 overflow-auto bg-gray-100 rounded-xl p-2 flex justify-center" style="max-height: 500px;">
                        @if($selectedType == 'surat')
                        <iframe id="documentFrame" src="{{ Storage::url($selectedFile->file_surat) }}" 
                            width="100%" height="500" class="border rounded-lg transition-transform duration-200"
                            style="transform-origin: center;"></iframe>
                        @endif

                        @if($selectedType == 'ktp')
                        <div class="text-center">
                            <img id="documentImage" src="{{ Storage::url($selectedFile->file_ktp) }}" 
                                class="rounded-xl border transition-transform duration-200"
                                style="transform-origin: center; max-width: 100%; height: auto;">
                        </div>
                        @endif

                        @if($selectedType == 'spp')
                        <iframe id="documentFrame" src="{{ Storage::url($selectedFile->file_spp) }}" 
                            width="100%" height="500" class="border rounded-lg transition-transform duration-200"
                            style="transform-origin: center;"></iframe>
                        @endif
                    </div>

                    <!-- Cek KRS (khusus SPP) -->
                    @if($selectedType == 'spp')
                    <div class="mb-4">
                        <button type="button" 
                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-full text-sm font-medium transition flex items-center gap-2"
                            wire:click="cekValidasiKRSViaAPI('{{ $selectedFile->id_registrasi_mahasiswa }}')"
                            wire:loading.attr="disabled">
                            <i class="fas fa-check-double"></i>
                            <span wire:loading.remove>Cek Validasi KRS</span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin"></i> Mengecek via API...
                            </span>
                        </button>

                        @if($hasilCekKRS)
                        <div class="mt-2 p-3 rounded-lg {{ $hasilCekKRS['success'] ? 'bg-green-50 border-l-4 border-green-600' : 'bg-red-50 border-l-4 border-red-600' }}">
                            <div class="flex items-center gap-2">
                                <i class="fas {{ $hasilCekKRS['success'] ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }}"></i>
                                <div>
                                    <strong class="{{ $hasilCekKRS['success'] ? 'text-green-700' : 'text-red-700' }}">{{ $hasilCekKRS['success'] ? 'Valid' : 'Tidak Valid' }}</strong>
                                    <p class="text-sm mb-0 {{ $hasilCekKRS['success'] ? 'text-green-600' : 'text-red-600' }}">{{ $hasilCekKRS['message'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-comment mr-1"></i> Keterangan (jika ditolak)
                        </label>
                        <textarea rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            wire:model="keterangan.{{ $selectedFile->id }}.{{ $selectedType }}"
                            placeholder="Masukkan alasan penolakan..."></textarea>
                        @error("keterangan.{$selectedFile->id}.{$selectedType}")
                        <div class="mt-1 text-xs text-red-600">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium transition" wire:click="closeModal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    @if($selectedFile)
                    <button type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-full text-sm font-medium transition" wire:click="valid({{ $selectedFile->id }}, '{{ $selectedType }}')">
                        <i class="fas fa-check-circle mr-1"></i> Validasi
                    </button>
                    <button type="button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-full text-sm font-medium transition" wire:click="tolak({{ $selectedFile->id }}, '{{ $selectedType }}')">
                        <i class="fas fa-times-circle mr-1"></i> Tolak
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


@push('scripts')
<script>
document.addEventListener('livewire:initialized', () => {
    console.log('Livewire initialized - Zoom script loaded');
    
    let currentZoom = 100;
    const zoomStep = 10;
    const maxZoom = 300;
    const minZoom = 30;

    function updateZoom(zoomValue) {
        currentZoom = Math.min(maxZoom, Math.max(minZoom, zoomValue));
        
        // Update tampilan persentase
        const zoomLevelElem = document.getElementById('zoomLevel');
        if (zoomLevelElem) {
            zoomLevelElem.textContent = currentZoom + '%';
            console.log('Zoom level:', currentZoom + '%');
        }
        
        // Zoom untuk iframe (PDF/Surat)
        const documentFrame = document.getElementById('documentFrame');
        if (documentFrame) {
            if (currentZoom === 100) {
                documentFrame.style.transform = 'scale(1)';
                documentFrame.style.width = '100%';
                documentFrame.style.height = '500px';
            } else {
                const scale = currentZoom / 100;
                documentFrame.style.transform = `scale(${scale})`;
                documentFrame.style.width = `${100 / scale}%`;
                documentFrame.style.height = `${500 / scale}px`;
            }
            documentFrame.style.transformOrigin = 'center top';
        }
        
        // Zoom untuk gambar
        const documentImage = document.getElementById('documentImage');
        if (documentImage) {
            documentImage.style.transform = `scale(${currentZoom / 100})`;
            documentImage.style.transformOrigin = 'center';
            documentImage.style.transition = 'transform 0.2s ease';
        }
        
        // Zoom untuk container
        const docContainer = document.getElementById('documentContainer');
        if (docContainer) {
            docContainer.style.overflow = 'auto';
        }
    }

    // Tunggu sebentar agar DOM siap
    setTimeout(() => {
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const zoomResetBtn = document.getElementById('zoomResetBtn');
        
        console.log('Zoom buttons found:', {
            zoomIn: !!zoomInBtn,
            zoomOut: !!zoomOutBtn,
            zoomReset: !!zoomResetBtn
        });
        
        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Zoom In clicked');
                updateZoom(currentZoom + zoomStep);
            });
        }
        
        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Zoom Out clicked');
                updateZoom(currentZoom - zoomStep);
            });
        }
        
        if (zoomResetBtn) {
            zoomResetBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Zoom Reset clicked');
                updateZoom(100);
            });
        }
    }, 100);

    // Reset zoom saat modal dibuka
    Livewire.on('openModal', () => {
        console.log('Modal opened - resetting zoom');
        setTimeout(() => updateZoom(100), 200);
    });
    
    Livewire.on('closeModal', () => {
        console.log('Modal closed - resetting zoom');
        updateZoom(100);
    });
});
</script>
@endpush