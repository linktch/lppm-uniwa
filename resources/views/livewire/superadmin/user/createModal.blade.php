@if ($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);" wire:ignore.self>
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[modalSlideIn_0.3s_ease-out]">
                <form wire:submit.prevent="storeUser">
                    <!-- Header Modal -->
                    <div class="flex items-center justify-between p-5 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user-plus text-blue-600"></i>
                            </div>
                            <div>
                                <h5 class="text-lg font-semibold text-gray-800">Tambah User</h5>
                                <p class="text-xs text-gray-500 mt-0.5">Isi data user baru dengan lengkap</p>
                            </div>
                        </div>
                        <button type="button" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition" wire:click="closeModal">
                            <i class="fas fa-times text-gray-500 text-sm"></i>
                        </button>
                    </div>

                    <!-- Body Modal -->
                    <div class="p-5 space-y-4">
                        <!-- Nama -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-user text-gray-400 mr-1"></i> Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" 
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 ring-red-500 @enderror" 
                                       wire:model.defer="name"
                                       placeholder="Masukkan nama lengkap">
                            </div>
                            @error('name')
                                <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                <i class="fas fa-info-circle"></i> Nama lengkap sesuai identitas
                            </div>
                        </div>

                        <!-- Email / NIM -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-envelope text-gray-400 mr-1"></i> Email / NIM
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" 
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 ring-red-500 @enderror" 
                                       wire:model.defer="email"
                                       placeholder="Masukkan email atau NIM">
                            </div>
                            @error('email')
                                <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                <i class="fas fa-info-circle"></i> Gunakan email aktif atau NIM mahasiswa
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-user-tag text-gray-400 mr-1"></i> Role / Hak Akses
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-shield-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <select class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white @error('role') border-red-500 ring-red-500 @enderror" 
                                        wire:model.defer="role">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            </div>
                            @error('role')
                                <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                <i class="fas fa-info-circle"></i> Role menentukan hak akses user
                            </div>
                        </div>

                        <!-- Prodi Optional -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-graduation-cap text-gray-400 mr-1"></i> Program Studi
                            </label>
                            <div class="relative">
                                <i class="fas fa-university absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <select class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white @error('prodi_id') border-red-500 ring-red-500 @enderror" 
                                        wire:model.defer="prodi_id">
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->id_prodi }}">
                                            {{ ucfirst($prodi->nama_program_studi) }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            </div>
                            @error('prodi_id')
                                <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                <i class="fas fa-info-circle"></i> Khusus untuk user dengan role mahasiswa
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-lock text-gray-400 mr-1"></i> Password
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="password" 
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 ring-red-500 @enderror" 
                                       wire:model.defer="password"
                                       placeholder="Masukkan password minimal 6 karakter">
                            </div>
                            @error('password')
                                <div class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="flex items-center gap-1 mt-1 text-xs text-gray-400">
                                <i class="fas fa-info-circle"></i> Password minimal 6 karakter
                            </div>
                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                        <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition flex items-center gap-2" wire:click="closeModal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="storeUser">
                                <i class="fas fa-save"></i> Simpan User
                            </span>
                            <span wire:loading wire:target="storeUser">
                                <i class="fas fa-spinner fa-pulse"></i> Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- Animasi untuk modal -->
<style>
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>