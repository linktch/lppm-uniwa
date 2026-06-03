@if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);" wire:ignore.self>
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[modalSlideIn_0.3s_ease-out]">
                
                <!-- Header -->
                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas {{ $updateMode ? 'fa-edit' : 'fa-calendar-plus' }} text-blue-600"></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-semibold text-gray-800">
                                {{ $updateMode ? 'Edit Periode' : 'Tambah Periode' }}
                            </h5>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $updateMode ? 'Ubah data periode yang sudah ada' : 'Isi data periode baru dengan lengkap' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition" wire:click="closeModal">
                        <i class="fas fa-times text-gray-500 text-sm"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5 space-y-4">
                    <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" id="periodeForm">
                        
                        <!-- Nama Periode -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-tag text-gray-400 mr-1"></i> Nama Periode
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" 
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_periode') border-red-500 ring-red-500 @enderror" 
                                       wire:model="nama_periode"
                                       placeholder="Contoh: Semester Ganjil 2024/2025">
                            </div>
                            @error('nama_periode') 
                                <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                <i class="fas fa-info-circle"></i> Nama periode harus unik dan deskriptif
                            </div>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-play-circle text-gray-400 mr-1"></i> Tanggal Mulai
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-calendar-day absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="date" 
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_mulai') border-red-500 ring-red-500 @enderror" 
                                       wire:model="tanggal_mulai">
                            </div>
                            @error('tanggal_mulai') 
                                <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                <i class="fas fa-info-circle"></i> Tanggal dimulainya periode kegiatan
                            </div>
                        </div>

                        <!-- Tanggal Selesai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-stop-circle text-gray-400 mr-1"></i> Tanggal Selesai
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-calendar-check absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="date" 
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_selesai') border-red-500 ring-red-500 @enderror" 
                                       wire:model="tanggal_selesai">
                            </div>
                            @error('tanggal_selesai') 
                                <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                <i class="fas fa-info-circle"></i> Tanggal berakhirnya periode kegiatan
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition flex items-center gap-2" wire:click="closeModal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" form="periodeForm" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="{{ $updateMode ? 'update' : 'store' }}">
                            <i class="fas fa-save"></i> {{ $updateMode ? 'Update Periode' : 'Simpan Periode' }}
                        </span>
                        <span wire:loading wire:target="{{ $updateMode ? 'update' : 'store' }}">
                            <i class="fas fa-spinner fa-pulse"></i> {{ $updateMode ? 'Mengupdate...' : 'Menyimpan...' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Animasi untuk modal -->
<style>
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Touch-friendly untuk mobile */
@media (max-width: 640px) {
    input, button {
        font-size: 16px !important;
    }
    
    button {
        min-height: 44px;
    }
}
</style>