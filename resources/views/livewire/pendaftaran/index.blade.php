<div>
    <div class="p-4 md:p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-4 md:p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-600 to-teal-500 flex items-center justify-center">
                                <i class="fas fa-edit text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg md:text-xl font-bold text-gray-800">Pendaftaran {{ $jenisKegiatan }}</h4>
                                <p class="text-xs md:text-sm text-gray-500">Upload dokumen persyaratan {{ $jenisKegiatan }}</p>
                            </div>
                        </div>
                        <button wire:click="back" 
                                class="inline-flex items-center gap-1 md:gap-2 px-3 py-1.5 md:px-4 md:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs md:text-sm font-medium transition">
                            <i class="fas fa-arrow-left"></i> <span class="hidden md:inline">Kembali</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Mahasiswa -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h5 class="font-semibold text-gray-800">Data Mahasiswa</h5>
                </div>
                <div class="p-4 md:p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400">Nama Lengkap</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $data_mahasiswa['nama_mahasiswa'] ?? $mahasiswa->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">NIM</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $data_mahasiswa['nim'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Program Studi</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $data_mahasiswa['nama_program_studi'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Semester</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $data_mahasiswa['semester'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Kelompok (Jika sudah diplotting) -->
            @if(!$isPlotted)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    <div>
                        <p class="font-semibold text-yellow-800">Belum Diplotting</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Anda belum diplotting ke kelompok. Silakan hubungi admin.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Upload Dokumen Persyaratan (Hanya jika sudah diplotting) -->
            @if($isPlotted)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h5 class="font-semibold text-gray-800">Upload Dokumen Persyaratan</h5>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($jenisKegiatan == 'KKN')
                        Persyaratan untuk pendaftaran KKN
                        @elseif($jenisKegiatan == 'PKM')
                        Persyaratan untuk pendaftaran PKM
                        @else
                        Persyaratan untuk pendaftaran {{ $jenisKegiatan }}
                        @endif
                    </p>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    @foreach($persyaratan as $key => $item)
                    <div class="border rounded-lg p-4 {{ $item['required'] ? 'border-gray-200' : 'border-dashed border-gray-300' }}">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas {{ $item['icon'] }} text-gray-600"></i>
                            </div>
                            <div class="flex-1">
                                <label class="font-medium text-gray-800">
                                    {{ $item['label'] }}
                                    @if($item['required'])
                                    <span class="text-red-500 text-xs">*</span>
                                    @else
                                    <span class="text-gray-400 text-xs">(Opsional)</span>
                                    @endif
                                </label>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $item['description'] }}</p>
                            </div>
                        </div>
                        
                        @if(isset($existingDocuments[$key]))
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-green-50 p-3 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-gray-600">File sudah diupload</span>
                                <span class="text-xs text-gray-400">({{ $existingDocuments[$key]->file_name }})</span>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="downloadFile('{{ $key }}')" 
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs transition">
                                    <i class="fas fa-download"></i> Download
                                </button>
                                <button wire:click="deleteFile('{{ $key }}')" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus file ini?"
                                        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs transition">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="flex-1">
                                <input type="file" wire:model="files.{{ $key }}" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file_{{ $key }}">
                                <label for="file_{{ $key }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-teal-500 transition">
                                    <i class="fas fa-cloud-upload-alt text-gray-400"></i>
                                    <span class="text-sm text-gray-500">Pilih File</span>
                                </label>
                            </div>
                            <button wire:click="uploadFile('{{ $key }}')" 
                                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </div>
                        @if($item['required'] && !isset($existingDocuments[$key]))
                        <p class="text-xs text-red-500 mt-2">* Wajib diupload</p>
                        @endif
                        @error("files.{$key}")
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <div wire:loading wire:target="uploadFile('{{ $key }}')" class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-spinner fa-pulse"></i> Mengupload...
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                <!-- Info Footer -->
                <div class="px-4 py-3 bg-gray-50 border-t">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format file: PDF, JPG, JPEG, PNG (Maksimal 2MB per file)
                        </p>
                        <p class="text-xs text-gray-400">
                            * Dokumen akan diverifikasi oleh admin
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>