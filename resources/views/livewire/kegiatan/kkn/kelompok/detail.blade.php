<div class="p-6 max-w-7xl mx-auto">
    <!-- Row 1: Informasi Kelompok -->
    <div class="bg-white rounded-xl shadow-md p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <!-- Bagian Kiri -->
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center text-white text-2xl">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $kelompok->nama_kelompok ?? 'Nama Kelompok' }}</h2>
                <div class="flex flex-wrap gap-2 mt-2">
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $kelompok->periode->nama_periode ?? 'Periode Belum Ditentukan' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-sky-100 text-sky-700 text-xs rounded-full">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $kelompok->lokasi ?? 'Lokasi Belum Ditentukan' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Bagian Kanan -->
        <div class="flex gap-3">
            <button wire:click="backToList" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm font-medium transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            <button wire:click="editKelompok" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white rounded-full text-sm font-medium transition">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div>
    </div>

    <!-- Row 2: Anggota Kelompok -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center text-white">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Anggota Kelompok</h3>
                    <p class="text-xs text-gray-500">Daftar mahasiswa yang tergabung dalam kelompok ini</p>
                </div>
            </div>
            <a href="{{ route('kegiatan.kkn.kelompok.tambah-anggota', [auth()->user()->role, $kelompok->id]) }}"
               wire:navigate 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white rounded-full text-sm font-medium transition">
                <i class="fas fa-plus-circle"></i> Tambah Anggota
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-28">NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program Studi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Semester</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($anggota as $index => $member)
                        @php
                        $data = json_decode($member->user->data_mahasiswa ?? '{}', true);
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-mono text-sm text-gray-600">{{ $data['nim'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                        <i class="fas fa-user-circle text-sm"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $data['nama_mahasiswa'] ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $data['nama_program_studi'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    Semester {{ $member->semester ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button wire:click="viewAnggota({{ $member->id }})" 
                                            class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition group relative">
                                        <i class="fas fa-eye"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">Detail</span>
                                    </button>
                                    <button wire:click="removeAnggota({{ $member->id }})"
                                            wire:confirm="Apakah Anda yakin ingin mengeluarkan anggota ini dari kelompok?"
                                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition group relative">
                                        <i class="fas fa-user-minus"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">Keluarkan</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-users-slash text-gray-300 text-5xl"></i>
                                    <p class="text-gray-500">Belum ada anggota dalam kelompok ini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Statistik Anggota -->
            @if($anggota && $anggota->count() > 0)
            <div class="flex flex-wrap gap-6 p-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center gap-2">
                    <i class="fas fa-users text-blue-500"></i>
                    <span class="text-lg font-bold text-gray-800">{{ $anggota->count() }}</span>
                    <span class="text-xs text-gray-500">Total Anggota</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-blue-500"></i>
                    <span class="text-lg font-bold text-gray-800">{{ $prodiCount ?? $anggota->groupBy('prodi_id')->count() }}</span>
                    <span class="text-xs text-gray-500">Program Studi</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-500"></i>
                    <span class="text-lg font-bold text-gray-800">{{ $rataSemester ?? round($anggota->avg('semester'), 1) }}</span>
                    <span class="text-xs text-gray-500">Rata-rata Semester</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Row 3: Tim Dosen & Pembimbing Lapangan -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center text-white">
                    <i class="fas fa-chalkboard-user"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tim Kelompok</h3>
                    <p class="text-xs text-gray-500">Daftar dosen pembimbing dan pengurus kelompok</p>
                </div>
            </div>
            <button wire:click="openTimModal" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white rounded-full text-sm font-medium transition">
                <i class="fas fa-plus-circle"></i> Tambah Tim
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Role</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tim as $index => $member)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-sky-100 flex items-center justify-center text-sky-600">
                                        <i class="fas fa-user-tie text-sm"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $member->user->first_name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $member->user->email ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                    {{ $member->role == 'dosen_pembimbing' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $member->role == 'ketua' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $member->role == 'anggota' ? 'bg-sky-100 text-sky-700' : '' }}">
                                    <i class="fas {{ $member->role == 'dosen_pembimbing' ? 'fa-chalkboard-user' : ($member->role == 'ketua' ? 'fa-crown' : 'fa-user') }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $member->role ?? 'Anggota')) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button wire:click="viewTim({{ $member->id }})" 
                                            class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition group relative">
                                        <i class="fas fa-eye"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">Detail</span>
                                    </button>
                                    <button wire:click="editTim({{ $member->id }})" 
                                            class="w-8 h-8 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition group relative">
                                        <i class="fas fa-edit"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">Edit</span>
                                    </button>
                                    <button wire:click="removeTim({{ $member->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus tim ini dari kelompok?"
                                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition group relative">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-users-slash text-gray-300 text-5xl"></i>
                                    <p class="text-gray-500">Belum ada tim dalam kelompok ini</p>
                                    <button wire:click="openTimModal" class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full text-sm font-medium transition">
                                        <i class="fas fa-plus"></i> Tambah Tim Sekarang
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Statistik Tim -->
            @if($tim && $tim->count() > 0)
            <div class="flex flex-wrap gap-6 p-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center gap-2">
                    <i class="fas fa-users text-blue-500"></i>
                    <span class="text-lg font-bold text-gray-800">{{ $tim->count() }}</span>
                    <span class="text-xs text-gray-500">Total Tim</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-chalkboard-user text-blue-500"></i>
                    <span class="text-lg font-bold text-gray-800">{{ $tim->where('role', 'dosen_pembimbing')->count() }}</span>
                    <span class="text-xs text-gray-500">Dosen Pembimbing</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-crown text-yellow-500"></i>
                    <span class="text-lg font-bold text-gray-800">{{ $tim->where('role', 'ketua')->count() }}</span>
                    <span class="text-xs text-gray-500">Ketua</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-user text-blue-500"></i>
                    <span class="text-lg font-bold text-gray-800">{{ $tim->where('role', 'anggota')->count() }}</span>
                    <span class="text-xs text-gray-500">Anggota Tim</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah Tim -->
    @if($showTimModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-700 to-blue-500">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-white font-semibold">Tambah Tim ke Kelompok</h5>
                            <p class="text-white/80 text-xs">Tambahkan dosen pembimbing atau pengurus kelompok</p>
                        </div>
                    </div>
                    <button type="button" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition" wire:click="closeTimModal">
                        <i class="fas fa-times text-white text-sm"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-user text-gray-400 mr-1"></i> Pilih User
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select wire:model="selected_user_id" 
                                    class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih User</option>
                                @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->first_name }} - {{ $user->email }} ({{ ucfirst($user->role) }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('selected_user_id')
                        <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-tag text-gray-400 mr-1"></i> Role
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-briefcase absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select wire:model="role" 
                                    class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Role</option>
                                <option value="dospem">Dosen Pembimbing</option>
                                <option value="tim">Anggota Tim</option>
                                <option value="korlap">Koordinator Lapangan</option>
                            </select>
                        </div>
                        @error('role')
                        <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-info-circle text-gray-400 mr-1"></i> Keterangan (Opsional)
                        </label>
                        <div class="relative">
                            <i class="fas fa-note-sticky absolute left-3 top-3 text-gray-400 text-sm"></i>
                            <textarea wire:model="keterangan" rows="3"
                                      class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Masukkan keterangan tambahan..."></textarea>
                        </div>
                        @error('keterangan')
                        <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium transition" wire:click="closeTimModal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" class="px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 hover:from-blue-800 hover:to-blue-600 text-white rounded-full text-sm font-medium transition" wire:click="saveTim" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="fas fa-save mr-1"></i> Simpan</span>
                        <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>