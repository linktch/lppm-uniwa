<div>
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-400 flex items-center justify-center">
                                <i class="fas fa-undo-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">Revisi Laporan Harian</h4>
                                <p class="text-sm text-gray-500">
                                    Silakan perbaiki laporan berdasarkan catatan review
                                </p>
                            </div>
                        </div>
                        <button wire:click="back" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                    </div>
                </div>
            </div>

            <!-- Catatan Revisi dari Reviewer -->
            @if($reviews && $reviews->count() > 0)
            <div class="bg-yellow-50 rounded-xl p-5 mb-6 border-l-4 border-yellow-500">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clipboard-list text-yellow-600"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-yellow-800 mb-2">Catatan Revisi dari Reviewer</h5>
                        @foreach($reviews as $review)
                        <div class="mb-3 pb-2 border-b border-yellow-200 last:border-0">
                            <p class="text-sm text-yellow-800">{{ $review->komentar }}</p>
                            <p class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-user mr-1"></i> {{ $review->user->name ?? 'Reviewer' }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock mr-1"></i> {{ $review->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        @endforeach
                        <div class="mt-3 p-3 bg-yellow-100 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                Silakan perbaiki laporan sesuai catatan di atas, lalu submit kembali.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Revisi Laporan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-400 flex items-center justify-center">
                            <i class="fas fa-edit text-white text-sm"></i>
                        </div>
                        <h5 class="font-semibold text-gray-800">Form Revisi Laporan</h5>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Informasi Dasar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i> Tanggal Laporan
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-calendar-day absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="date" wire:model="tanggal"
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('tanggal') border-red-500 @enderror">
                            </div>
                            @error('tanggal')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
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

                    <!-- Narasi Kegiatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-align-left text-gray-400 mr-1"></i> Narasi Kegiatan
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-edit absolute left-3 top-3 text-gray-400 text-sm"></i>
                            <textarea wire:model="aktivitas" rows="6"
                                      class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent resize-y @error('aktivitas') border-red-500 @enderror"
                                      placeholder="Tuliskan deskripsi kegiatan yang telah diperbaiki..."></textarea>
                        </div>
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i> Minimal 500 karakter
                            </p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-keyboard mr-1"></i> {{ strlen($aktivitas ?? '') }} karakter
                            </p>
                        </div>
                        @error('aktivitas')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dokumentasi Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-camera text-gray-400 mr-1"></i> Dokumentasi Foto
                        </label>
                        
                        <!-- Preview Foto Lama -->
                        @if($oldFoto)
                            <div class="relative inline-block mb-3">
                                <div class="relative">
                                    <img src="{{ Storage::url($oldFoto) }}" alt="Foto Lama" 
                                         class="w-32 h-24 object-cover rounded-lg border-2 border-gray-200">
                                    <button type="button" wire:click="removeFoto"
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-xs">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Foto lama</p>
                            </div>
                        @endif

                        <!-- Upload Foto Baru -->
                        <div x-data="{ isDragging: false }" 
                             class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all duration-200"
                             :class="isDragging ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300 bg-gray-50 hover:border-yellow-400'"
                             @click="$refs.fotoInput.click()"
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="isDragging = false; $refs.fotoInput.files = $event.dataTransfer.files; $wire.setFile('foto', $event.dataTransfer.files[0])">
                            
                            <input type="file" wire:model="foto" accept="image/*" style="display: none;" x-ref="fotoInput">
                            
                            <div wire:loading.remove wire:target="foto">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2 block"></i>
                                <p class="text-sm text-gray-600">Klik atau drag & drop foto baru</p>
                                <p class="text-xs text-gray-400">Format: JPG, PNG (Max. 2MB)</p>
                            </div>
                            <div wire:loading wire:target="foto">
                                <i class="fas fa-spinner fa-pulse text-3xl text-yellow-500 mb-2 block"></i>
                                <p class="text-sm text-gray-600">Mengupload...</p>
                            </div>
                        </div>

                        <!-- Preview Foto Baru -->
                        @if($foto)
                            <div class="mt-3 relative inline-block">
                                <img src="{{ $foto->temporaryUrl() }}" alt="Preview" 
                                     class="w-32 h-24 object-cover rounded-lg border-2 border-green-400">
                                <button type="button" wire:click="$set('foto', null)"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                                <p class="text-xs text-green-500 mt-1">Foto baru siap diupload</p>
                            </div>
                        @endif
                        
                        @error('foto')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button wire:click="back" 
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button wire:click="updateLaporan" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-600 hover:to-yellow-500 text-white rounded-lg text-sm font-medium transition">
                            <span wire:loading.remove>
                                <i class="fas fa-paper-plane mr-1"></i> Submit Revisi
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
</div>