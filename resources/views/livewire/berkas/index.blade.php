<div>
    <div class="p-4 md:p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-3 md:p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br from-pink-600 to-pink-400 flex items-center justify-center shadow-sm">
                                <i class="fas fa-folder-open text-white text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-base md:text-xl font-bold text-gray-800">Dokumen Kelompok</h4>
                                <p class="text-xs text-gray-500 hidden md:block">
                                    Lihat program kerja, laporan akhir, dan artikel kegiatan {{ $jenisKegiatan }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="back" 
                                class="inline-flex items-center gap-1 md:gap-2 px-3 py-1.5 md:px-4 md:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs md:text-sm font-medium transition">
                            <i class="fas fa-arrow-left"></i> <span class="hidden md:inline">Kembali</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid 3 Kolom -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                
                <!-- Card 1: Program Kerja -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 h-full flex flex-col">
                    <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-tasks text-white text-lg"></i>
                            <h5 class="font-semibold text-white text-sm md:text-base">Program Kerja</h5>
                        </div>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        @if($existingProgramKerja)
                            <!-- Tampilkan file yang sudah diupload -->
                            <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-800 truncate">{{ $existingProgramKerja->judul ?? 'Program Kerja' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $existingProgramKerja->file_name ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-calendar-alt mr-1"></i> {{ $existingProgramKerja->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    <!-- Tombol View (Preview) -->
                                    <button wire:click="viewFile({{ $existingProgramKerja->id }})" 
                                            class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <!-- Tombol Download -->
                                    <button wire:click="downloadFile({{ $existingProgramKerja->id }}, 'program_kerja')" 
                                            class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                    <!-- Tombol Hapus (Hanya untuk Admin/Superadmin) -->
                                    @if(auth()->user()->role != 'mahasiswa')
                                    <button wire:click="deleteDokumen('program_kerja')" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus file ini?"
                                            class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Jika belum upload -->
                            <div class="flex-1 flex flex-col justify-center items-center text-center py-6">
                                <i class="fas fa-file-pdf text-gray-300 text-5xl mb-3"></i>
                                <p class="text-sm text-gray-500 mb-3">Belum ada file Program Kerja</p>
                                <!-- Form upload hanya untuk admin/superadmin -->
                                @if(auth()->user()->role != 'mahasiswa')
                                <form wire:submit.prevent="uploadProgramKerja" class="w-full">
                                    <div class="mb-3">
                                        <input type="text" wire:model="judul_program_kerja" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="Judul Program Kerja">
                                    </div>
                                    <label class="block w-full">
                                        <input type="file" wire:model="file_program_kerja" accept=".pdf" 
                                               class="hidden" id="file_program_kerja">
                                        <div class="w-full border-2 border-dashed border-gray-300 rounded-lg p-3 text-center cursor-pointer hover:border-blue-500 transition">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-lg mb-1 block"></i>
                                            <span class="text-xs text-gray-500">Pilih File PDF</span>
                                        </div>
                                    </label>
                                    @if($file_program_kerja)
                                        <div class="mt-2 text-xs text-gray-500">
                                            {{ $file_program_kerja->getClientOriginalName() }}
                                        </div>
                                    @endif
                                    <button type="submit" 
                                            class="w-full mt-3 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                        <i class="fas fa-upload"></i> Upload Program Kerja
                                    </button>
                                </form>
                                @else
                                <p class="text-xs text-gray-400">Belum ada file yang diupload</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card 2: Laporan Akhir -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 h-full flex flex-col">
                    <div class="px-4 py-3 bg-gradient-to-r from-green-600 to-green-500">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-alt text-white text-lg"></i>
                            <h5 class="font-semibold text-white text-sm md:text-base">Laporan Akhir</h5>
                        </div>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        @if($existingLaporanAkhir)
                            <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-800 truncate">{{ $existingLaporanAkhir->judul ?? 'Laporan Akhir' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $existingLaporanAkhir->file_name ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-calendar-alt mr-1"></i> {{ $existingLaporanAkhir->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    <button wire:click="viewFile({{ $existingLaporanAkhir->id }})" 
                                            class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button wire:click="downloadFile({{ $existingLaporanAkhir->id }}, 'laporan_akhir')" 
                                            class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                    @if(auth()->user()->role != 'mahasiswa')
                                    <button wire:click="deleteDokumen('laporan_akhir')" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus file ini?"
                                            class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex-1 flex flex-col justify-center items-center text-center py-6">
                                <i class="fas fa-file-pdf text-gray-300 text-5xl mb-3"></i>
                                <p class="text-sm text-gray-500 mb-3">Belum ada file Laporan Akhir</p>
                                @if(auth()->user()->role != 'mahasiswa')
                                <form wire:submit.prevent="uploadLaporanAkhir" class="w-full">
                                    <div class="mb-3">
                                        <input type="text" wire:model="judul_laporan_akhir" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                                               placeholder="Judul Laporan Akhir">
                                    </div>
                                    <label class="block w-full">
                                        <input type="file" wire:model="file_laporan_akhir" accept=".pdf" 
                                               class="hidden" id="file_laporan_akhir">
                                        <div class="w-full border-2 border-dashed border-gray-300 rounded-lg p-3 text-center cursor-pointer hover:border-green-500 transition">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-lg mb-1 block"></i>
                                            <span class="text-xs text-gray-500">Pilih File PDF</span>
                                        </div>
                                    </label>
                                    @if($file_laporan_akhir)
                                        <div class="mt-2 text-xs text-gray-500">
                                            {{ $file_laporan_akhir->getClientOriginalName() }}
                                        </div>
                                    @endif
                                    <button type="submit" 
                                            class="w-full mt-3 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                                        <i class="fas fa-upload"></i> Upload Laporan Akhir
                                    </button>
                                </form>
                                @else
                                <p class="text-xs text-gray-400">Belum ada file yang diupload</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card 3: Artikel -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 h-full flex flex-col">
                    <div class="px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-500">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-newspaper text-white text-lg"></i>
                            <h5 class="font-semibold text-white text-sm md:text-base">Artikel</h5>
                        </div>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        @if($existingArtikel)
                            <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-800 truncate">{{ $existingArtikel->judul ?? 'Artikel' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $existingArtikel->file_name ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-calendar-alt mr-1"></i> {{ $existingArtikel->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    <button wire:click="viewFile({{ $existingArtikel->id }})" 
                                            class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button wire:click="downloadFile({{ $existingArtikel->id }}, 'artikel')" 
                                            class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                    @if(auth()->user()->role != 'mahasiswa')
                                    <button wire:click="deleteDokumen('artikel')" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus file ini?"
                                            class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex-1 flex flex-col justify-center items-center text-center py-6">
                                <i class="fas fa-newspaper text-gray-300 text-5xl mb-3"></i>
                                <p class="text-sm text-gray-500 mb-3">Belum ada Artikel</p>
                                @if(auth()->user()->role != 'mahasiswa')
                                <form wire:submit.prevent="uploadArtikel" class="w-full">
                                    <div class="mb-3">
                                        <input type="text" wire:model="judul_artikel" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                               placeholder="Judul Artikel">
                                    </div>
                                    <div class="mb-3">
                                        <textarea wire:model="konten_artikel" rows="2" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                                  placeholder="Isi Artikel..."></textarea>
                                    </div>
                                    <label class="block w-full">
                                        <input type="file" wire:model="file_artikel" accept=".pdf" 
                                               class="hidden" id="file_artikel">
                                        <div class="w-full border-2 border-dashed border-gray-300 rounded-lg p-3 text-center cursor-pointer hover:border-purple-500 transition">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-lg mb-1 block"></i>
                                            <span class="text-xs text-gray-500">Pilih File PDF (Opsional)</span>
                                        </div>
                                    </label>
                                    @if($file_artikel)
                                        <div class="mt-2 text-xs text-gray-500">
                                            {{ $file_artikel->getClientOriginalName() }}
                                        </div>
                                    @endif
                                    <button type="submit" 
                                            class="w-full mt-3 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                                        <i class="fas fa-upload"></i> Publikasi Artikel
                                    </button>
                                </form>
                                @else
                                <p class="text-xs text-gray-400">Belum ada artikel yang dipublikasi</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="text-center text-xs text-gray-400 mt-6 py-2">
                <i class="fas fa-file-pdf mr-1"></i> 
                Format file: PDF (Maksimal 5MB)
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }
    
    button, label, .cursor-pointer {
        min-height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (max-width: 640px) {
        button, label, .cursor-pointer {
            min-height: 36px;
        }
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('open-new-tab', (url) => {
            window.open(url, '_blank');
        });
    });
</script>
@endpush