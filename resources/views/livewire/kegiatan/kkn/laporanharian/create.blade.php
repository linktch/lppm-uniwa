<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header Form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-4 md:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-file-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg md:text-xl font-semibold text-gray-800">
                                {{ $isEditing ? 'Edit Laporan' : 'Tambah Laporan Harian' }}
                            </h4>
                            <p class="text-xs md:text-sm text-gray-500">
                                Isi form di bawah ini untuk membuat laporan kegiatan harian
                            </p>
                        </div>
                    </div>
                    <button wire:click="back" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm font-medium transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Informasi Dasar -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-info-circle text-white text-lg"></i>
                    </div>
                    <h5 class="font-semibold text-gray-800">Informasi Dasar</h5>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Tanggal Laporan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-calendar-alt text-gray-400 mr-1"></i> Tanggal Laporan
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-calendar-day absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input readonly type="date" wire:model="tanggal" wire:change="updateTanggal"
                                   class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal') border-red-500 @enderror">
                        </div>
                        @error('tanggal')
                            <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Jam Pelaksanaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-clock text-gray-400 mr-1"></i> Jam Pelaksanaan
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-clock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input readonly type="time" wire:model="jam"
                                   class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jam') border-red-500 @enderror">
                        </div>
                        @error('jam')
                            <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Narasi Kegiatan -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-pen-alt text-white text-lg"></i>
                    </div>
                    <h5 class="font-semibold text-gray-800">Narasi Kegiatan</h5>
                </div>
            </div>
            <div class="p-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-align-left text-gray-400 mr-1"></i> Deskripsi Kegiatan
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-edit absolute left-3 top-3 text-gray-400 text-sm"></i>
                        <textarea wire:model="narasi" rows="6"
                                  class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-y @error('narasi') border-red-500 @enderror"
                                  placeholder="Tuliskan deskripsi kegiatan yang Anda lakukan hari ini..."></textarea>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <div class="flex items-center gap-1 text-xs text-gray-400">
                            <i class="fas fa-info-circle"></i>
                            <span>Minimal 500 karakter, jelaskan kegiatan secara detail dan spesifik</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            <i class="fas fa-keyboard"></i> {{ strlen($narasi ?? '') }} / Minimal 500 karakter
                        </div>
                    </div>
                    @error('narasi')
                        <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Upload Foto -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-camera text-white text-lg"></i>
                    </div>
                    <h5 class="font-semibold text-gray-800">Dokumentasi Foto</h5>
                </div>
            </div>
            <div class="p-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-image text-gray-400 mr-1"></i> Upload Foto Kegiatan
                        <span class="text-red-500">*</span>
                    </label>

                    <!-- Area Upload -->
                    <div x-data="{ isDragging: false }" 
                         class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition-all duration-200"
                         :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50/30'"
                         @click="$refs.fotoInput.click()"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="isDragging = false; $refs.fotoInput.files = $event.dataTransfer.files; $wire.setFile('foto', $event.dataTransfer.files[0])">

                        <input type="file" wire:model="foto" accept="image/*" style="display: none;" x-ref="fotoInput">

                        <div wire:loading.remove wire:target="foto">
                            <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-3 block"></i>
                            <p class="text-gray-700 font-medium mb-1">Klik atau drag & drop foto di sini</p>
                            <small class="text-gray-400">Format: JPG, PNG, JPEG (Max. 2MB)</small>
                        </div>
                        <div wire:loading wire:target="foto">
                            <i class="fas fa-spinner fa-pulse text-3xl text-blue-500 mb-3 block"></i>
                            <p class="text-gray-600">Mengupload foto...</p>
                        </div>
                    </div>

                    <!-- Preview Foto -->
                    @if ($foto && !is_string($foto))
                        <div class="mt-4 relative inline-block">
                            <div class="relative">
                                <img src="{{ $foto->temporaryUrl() }}" alt="Preview Foto" 
                                     class="w-48 h-36 object-cover rounded-xl border-2 border-gray-200 shadow-sm">
                                <button type="button" wire:click="removeFoto"
                                        class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition shadow-md">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                    @elseif($foto && is_string($foto))
                        <div class="mt-4 relative inline-block">
                            <div class="relative">
                                <img src="{{ Storage::url($foto) }}" alt="Foto Kegiatan" 
                                     class="w-48 h-36 object-cover rounded-xl border-2 border-gray-200 shadow-sm">
                                <button type="button" wire:click="removeFoto"
                                        class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition shadow-md">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @error('foto')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-500">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror

                    <div class="flex items-center gap-1 mt-2 text-xs text-gray-400">
                        <i class="fas fa-info-circle"></i>
                        <span>Upload foto dokumentasi kegiatan Anda (wajib diisi)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-5">
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="back" 
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium transition">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" wire:click="saveLaporan" wire:loading.attr="disabled"
                            class="px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white rounded-full text-sm font-medium transition disabled:opacity-50">
                        <span wire:loading.remove>
                            <i class="fas fa-save mr-1"></i> {{ $isEditing ? 'Update Laporan' : 'Simpan Laporan' }}
                        </span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-pulse mr-1"></i> Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>