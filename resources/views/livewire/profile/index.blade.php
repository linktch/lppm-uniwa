<div class="min-h-screen bg-gray-100 pb-20">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white sticky top-0 z-10 shadow-lg">
        <div class="px-4 py-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user-circle text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Profil Saya</h1>
                    <p class="text-xs text-white/80 mt-0.5">Kelola informasi akun Anda</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-6 max-w-3xl mx-auto">
        
        <!-- Card Profil -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header Card -->
            <div class="px-5 py-4 border-b bg-gray-50">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i>
                    <h5 class="font-semibold text-gray-800">Informasi Profil</h5>
                </div>
            </div>

            <div class="p-5 space-y-4">
                <!-- Foto Profil - Semua role bisa upload -->
                <div class="flex flex-col items-center sm:flex-row sm:items-start gap-4">
                    <div class="relative">
                        @if($existing_foto)
                            <img src="{{ Storage::url($existing_foto) }}" alt="Profile" 
                                 class="w-24 h-24 rounded-full object-cover ring-4 ring-blue-100">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center text-white text-3xl">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <label for="foto" class="absolute bottom-0 right-0 bg-blue-600 text-white p-1 rounded-full cursor-pointer hover:bg-blue-700">
                            <i class="fas fa-camera text-xs"></i>
                        </label>
                        <input type="file" wire:model="foto" accept="image/*" id="foto" class="hidden">
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-sm text-gray-500">Foto Profil</p>
                        <p class="text-xs text-gray-400">Klik icon kamera untuk ganti foto (Max 2MB)</p>
                        @error('foto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="foto" class="text-xs text-blue-500 mt-1">
                            <i class="fas fa-spinner fa-pulse"></i> Mengupload...
                        </div>
                        @if($foto)
                        <div class="mt-2">
                            <button wire:click="updateFoto" class="px-3 py-1 bg-green-600 text-white rounded-lg text-xs">
                                <i class="fas fa-save mr-1"></i> Simpan Foto
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Data Diri (Readonly untuk mahasiswa) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" 
                               class="w-full px-3 py-2 border rounded-lg text-sm {{ $role == 'mahasiswa' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                               {{ $role == 'mahasiswa' ? 'disabled' : '' }}>
                        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email" 
                               class="w-full px-3 py-2 border rounded-lg text-sm {{ $role == 'mahasiswa' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                               {{ $role == 'mahasiswa' ? 'disabled' : '' }}>
                        @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Depan</label>
                        <input type="text" wire:model="first_name" 
                               class="w-full px-3 py-2 border rounded-lg text-sm {{ $role == 'mahasiswa' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                               {{ $role == 'mahasiswa' ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Belakang</label>
                        <input type="text" wire:model="last_name" 
                               class="w-full px-3 py-2 border rounded-lg text-sm {{ $role == 'mahasiswa' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                               {{ $role == 'mahasiswa' ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" wire:model="phone" 
                               class="w-full px-3 py-2 border rounded-lg text-sm {{ $role == 'mahasiswa' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                               {{ $role == 'mahasiswa' ? 'disabled' : '' }}>
                    </div>
                </div>

                <!-- Data Mahasiswa (khusus role mahasiswa) - Readonly -->
                @if($role == 'mahasiswa')
                <div class="border-t pt-4 mt-2">
                    <h6 class="font-semibold text-gray-800 mb-3">Data Akademik</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                            <input type="text" wire:model="nim" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 cursor-not-allowed" readonly disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mahasiswa</label>
                            <input type="text" wire:model="nama_mahasiswa" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 cursor-not-allowed" readonly disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <input type="text" wire:model="nama_program_studi" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 cursor-not-allowed" readonly disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                            <input type="text" wire:model="semester" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 cursor-not-allowed" readonly disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <input type="text" wire:model="jenis_kelamin" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 cursor-not-allowed" readonly disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                            <input type="text" wire:model="no_hp" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 cursor-not-allowed" readonly disabled>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tombol Simpan (Hanya untuk admin/superadmin) -->
                @if($role != 'mahasiswa')
                <div class="flex justify-end pt-4">
                    <button wire:click="updateProfile" wire:loading.attr="disabled"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                        <span wire:loading.remove><i class="fas fa-save mr-1"></i> Simpan Perubahan</span>
                        <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Menyimpan...</span>
                    </button>
                </div>
                @else
                <div class="flex justify-end pt-4">
                    <div class="text-sm text-gray-400 italic">
                        <i class="fas fa-info-circle mr-1"></i> Data profil tidak dapat diubah, hubungi admin jika ada perubahan
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Card Ganti Password (Semua Role Bisa) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mt-4">
            <div class="px-5 py-4 border-b bg-gray-50 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-lock text-blue-600"></i>
                    <h5 class="font-semibold text-gray-800">Keamanan</h5>
                </div>
                <button wire:click="togglePasswordForm" class="text-blue-600 hover:text-blue-700 text-sm">
                    {{ $showPasswordForm ? 'Tutup' : 'Ganti Password' }}
                </button>
            </div>

            @if($showPasswordForm)
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" wire:model="current_password" class="w-full px-3 py-2 border rounded-lg text-sm">
                    @error('current_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" wire:model="new_password" class="w-full px-3 py-2 border rounded-lg text-sm">
                    @error('new_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" wire:model="new_password_confirmation" class="w-full px-3 py-2 border rounded-lg text-sm">
                </div>
                <div class="flex justify-end">
                    <button wire:click="changePassword" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                        <span wire:loading.remove><i class="fas fa-key mr-1"></i> Ubah Password</span>
                        <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Memproses...</span>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>