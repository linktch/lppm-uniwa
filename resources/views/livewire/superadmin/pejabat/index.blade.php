<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-signature mr-2 text-blue-600"></i> Pejabat Signatur
                </h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ url('/dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-700">Pejabat Signatur</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center">
                        <i class="fas fa-signature text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Manajemen Pejabat Signatur</h3>
                        <p class="text-xs text-gray-500">Kelola data pejabat penanda tangan dokumen</p>
                    </div>
                </div>
                <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2" 
                        wire:click="openModal">
                    <i class="fas fa-plus-circle"></i> Tambah Pejabat
                </button>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <!-- Alert Success -->
            @if (session()->has('message'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-green-700">{{ session('message') }}</span>
                        <button type="button" class="ml-auto text-green-500 hover:text-green-700" 
                                onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" 
                               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Cari nama atau jabatan..." 
                               wire:model.live.debounce.300ms="search">
                    </div>
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tampilkan</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            wire:model.live="perPage">
                        <option value="10">10 data per halaman</option>
                        <option value="25">25 data per halaman</option>
                        <option value="50">50 data per halaman</option>
                        <option value="100">100 data per halaman</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodi / Fakultas</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pejabat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tanda Tangan</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pejabats as $index => $pejabat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $pejabats->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    @if($pejabat->user)
                                        <p class="text-sm font-medium text-gray-900">{{ $pejabat->user->name ?? $pejabat->user->first_name }}</p>
                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">
                                            {{ ucfirst($pejabat->user->role ?? '-') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $pejabat->prodiFakultas->nama_program_studi ?? '-' }}</td>
                                <td class="px-4 py-3"><span class="text-sm font-medium text-gray-900">{{ $pejabat->nama }}</span></td>
                                <td class="px-4 py-3"><span class="inline-flex px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">{{ $pejabat->jabatan }}</span></td>
                                <td class="px-4 py-3 text-center">
                                    @if($pejabat->signatur_path)
                                        <img src="{{ Storage::url($pejabat->signatur_path) }}" alt="Tanda Tangan" class="h-10 w-auto cursor-pointer rounded-lg mx-auto" onclick="window.open(this.src, '_blank')">
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <button wire:click="edit({{ $pejabat->id }})" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-3 py-1 rounded-lg text-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button onclick="confirmDelete({{ $pejabat->id }})" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded-lg text-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pejabats->hasPages())
                <div class="mt-6 pt-4 border-t border-gray-200">
                    {{ $pejabats->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Form SEDERHANA -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold">{{ $pejabatId ? 'Edit' : 'Tambah' }} Pejabat Signatur</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User Pejabat</label>
                    <select wire:model="user_id" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }} - {{ ucfirst($user->role ?? 'User') }}</option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pejabat</label>
                    <input type="text" wire:model="nama" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Nama pejabat">
                    @error('nama') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                    <select wire:model="prodi_fakultas_id" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodiFakultas as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama_program_studi }}</option>
                        @endforeach
                    </select>
                    @error('prodi_fakultas_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <input type="text" wire:model="jabatan" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Jabatan">
                    @error('jabatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanda Tangan (PNG)</label>
                    @if($signatur_path && !$signatur_file)
                        <div class="mb-2 p-2 bg-gray-100 rounded text-center">
                            <img src="{{ Storage::url($signatur_path) }}" class="h-12 mx-auto">
                        </div>
                    @endif
                    <input type="file" accept="image/png" wire:model="signatur_file" class="w-full px-3 py-2 border rounded-lg text-sm">
                    @error('signatur_file') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    @if($signatur_file)
                        <div class="mt-2 p-2 bg-green-50 rounded text-center">
                            <img src="{{ $signatur_file->temporaryUrl() }}" class="h-12 mx-auto">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 p-4 border-t bg-gray-50">
                <button wire:click="closeModal" class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Batal</button>
                <button wire:click="{{ $pejabatId ? 'update' : 'store' }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    {{ $pejabatId ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            @this.call('delete', id);
        }
    }
</script>
@endpush