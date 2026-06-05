<div class="p-6">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4 md:p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-600 to-cyan-400 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg md:text-xl font-semibold text-gray-800">Timeline Kegiatan KKN</h4>
                    <p class="text-xs md:text-sm text-gray-500">Kelola jadwal dan tenggat waktu kegiatan Kuliah Kerja Nyata</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Button -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Search -->
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                               class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent w-48 md:w-56" 
                               placeholder="Cari periode atau kegiatan...">
                    </div>
                    <!-- Status Filter -->
                    <select wire:model.live="statusFilter" 
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="AKTIF">AKTIF</option>
                        <option value="berakhir">Berakhir</option>
                    </select>
                    <!-- Per Page -->
                    <select wire:model.live="perPage" 
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="5">5 data</option>
                        <option value="10">10 data</option>
                        <option value="25">25 data</option>
                        <option value="50">50 data</option>
                    </select>
                </div>
                <button wire:click="openCreateModal" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-cyan-600 to-cyan-400 hover:from-cyan-700 hover:to-cyan-500 text-white rounded-full text-sm font-medium transition">
                    <i class="fas fa-plus"></i> Tambah Timeline
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session()->has('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-green-700">{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-500 hover:text-green-700" 
                    onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session()->has('error'))
    <div class="mb-4 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            <span class="text-red-700">{{ session('error') }}</span>
            <button type="button" class="ml-auto text-red-500 hover:text-red-700" 
                    onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Timeline Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($timelines as $timeline)
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 {{ $timeline->status == 'AKTIF' ? 'border-l-green-500' : 'border-l-red-500' }}">
            <div class="p-5">
                <!-- Header Badges -->
                <div class="flex justify-between items-start gap-2 mb-3">
                    <div>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                            <i class="fas fa-tag text-xs"></i>
                            {{ $timeline->periode?->nama_periode ?? 'Periode Tidak Diketahui' }}
                        </span>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $timeline->status == 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        <i class="fas {{ $timeline->status == 'AKTIF' ? 'fa-play-circle' : 'fa-flag-checkered' }}"></i>
                        {{ $timeline->status_label }}
                    </span>
                </div>
                
                <!-- Title -->
                <h5 class="font-semibold text-gray-800 text-base mb-1">
                    {{ $timeline->kegiatan?->nama_kegiatan ?? 'Kegiatan Tidak Diketahui' }}
                </h5>
                
                <!-- Jenis Badge -->
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium 
                    {{ $timeline->jenis_color == 'primary' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $timeline->jenis_color == 'info' ? 'bg-cyan-100 text-cyan-700' : '' }}
                    {{ $timeline->jenis_color == 'success' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $timeline->jenis_color == 'warning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $timeline->jenis_color == 'danger' ? 'bg-red-100 text-red-700' : '' }}">
                    <i class="fas {{ $timeline->jenis_icon }}"></i>
                    {{ $timeline->jenis_label }}
                </span>

                <!-- Description -->
                <p class="text-gray-500 text-xs mt-3 mb-4 line-clamp-2">
                    {{ $timeline->kegiatan?->deskripsi ?: 'Tidak ada deskripsi kegiatan' }}
                </p>

                <!-- Timeline Info -->
                <div class="bg-gray-50 rounded-xl p-3 mb-4">
                    <div class="flex items-center gap-2 text-xs text-gray-600 py-1">
                        <i class="fas fa-calendar-day w-4 text-cyan-600"></i>
                        <span>{{ $timeline->tanggal_mulai_formatted }} <i class="fas fa-arrow-right mx-1 text-gray-400"></i> {{ $timeline->tanggal_selesai_formatted }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-600 py-1">
                        <i class="fas fa-clock w-4 text-cyan-600"></i>
                        <span>Durasi: {{ $timeline->duration_text }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button wire:click="openEditModal({{ $timeline->id }})" 
                            class="w-9 h-9 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition group relative">
                        <i class="fas fa-edit"></i>
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                            Edit Timeline
                        </span>
                    </button>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="w-9 h-9 rounded-lg bg-cyan-50 hover:bg-cyan-100 text-cyan-600 transition">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute left-0 mt-1 w-36 bg-white rounded-lg shadow-lg border z-50 py-1">
                            <button wire:click="updateStatus({{ $timeline->id }}, 'AKTIF')" 
                                    class="w-full px-3 py-2 text-left text-sm text-green-600 hover:bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-play-circle"></i> AKTIF
                            </button>
                            <button wire:click="updateStatus({{ $timeline->id }}, 'berakhir')" 
                                    class="w-full px-3 py-2 text-left text-sm text-red-600 hover:bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-flag-checkered"></i> Berakhir
                            </button>
                        </div>
                    </div>

                    <button wire:click="delete({{ $timeline->id }})" 
                            wire:confirm="Apakah Anda yakin ingin menghapus timeline ini?"
                            class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition group relative">
                        <i class="fas fa-trash-alt"></i>
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                            Hapus Timeline
                        </span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-md text-center py-12">
                <div class="flex flex-col items-center gap-3">
                    <i class="fas fa-calendar-times text-gray-300 text-6xl"></i>
                    <p class="text-gray-500">Belum ada timeline kegiatan</p>
                    <small class="text-gray-400">Klik tombol "Tambah Timeline" untuk membuat jadwal kegiatan KKN</small>
                    <button wire:click="openCreateModal" class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-cyan-600 to-cyan-400 text-white rounded-full text-sm font-medium transition">
                        <i class="fas fa-plus"></i> Tambah Timeline
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($timelines->hasPages())
    <div class="mt-6">
        {{ $timelines->links() }}
    </div>
    @endif

    <!-- Modal Tambah/Edit Timeline -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-5 bg-gradient-to-r from-cyan-600 to-cyan-400">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas {{ $isEdit ? 'fa-edit' : 'fa-plus-circle' }} text-white"></i>
                        </div>
                        <div>
                            <h5 class="text-white font-semibold">{{ $isEdit ? 'Edit Timeline' : 'Tambah Timeline Baru' }}</h5>
                            <p class="text-white/80 text-xs">{{ $isEdit ? 'Perbarui tanggal dan status timeline' : 'Tambahkan tanggal pelaksanaan kegiatan KKN' }}</p>
                        </div>
                    </div>
                    <button type="button" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition" wire:click="closeModal">
                        <i class="fas fa-times text-white text-sm"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5 space-y-4">
                    <!-- Periode -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-calendar-alt text-gray-400 mr-1"></i> Periode
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select wire:model="selectedPeriodeId" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="">Pilih Periode</option>
                                @foreach($periodeList as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('selectedPeriodeId')
                        <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kegiatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-tasks text-gray-400 mr-1"></i> Kegiatan
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-briefcase absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select wire:model="selectedKegiatanId" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="">Pilih Kegiatan</option>
                                @foreach($kegiatanList as $kegiatan)
                                <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('selectedKegiatanId')
                        <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Kegiatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-tag text-gray-400 mr-1"></i> Jenis Kegiatan
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-list absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select wire:model="jenisTimeline" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="">Pilih Jenis Kegiatan</option>
                                <option value="Pendaftaran">📝 Pendaftaran</option>
                                <option value="Pembekalan">📚 Pembekalan</option>
                                <option value="Pelaksanaan">🏃 Pelaksanaan</option>
                                <option value="Pelaporan">📊 Pelaporan</option>
                                <option value="Evaluasi">⭐ Evaluasi</option>
                            </select>
                        </div>
                        @error('jenis')
                        <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai dan Selesai -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-calendar-plus"></i> Tanggal Mulai
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model="tanggal_mulai" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            @error('tanggal_mulai')
                            <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-calendar-minus"></i> Tanggal Selesai
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model="tanggal_selesai" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            @error('tanggal_selesai')
                            <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-flag-checkered text-gray-400 mr-1"></i> Status
                        </label>
                        <div class="relative">
                            <i class="fas fa-toggle-on absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select wire:model="status" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="AKTIF">AKTIF</option>
                                <option value="berakhir">Berakhir</option>
                            </select>
                        </div>
                        @error('status')
                        <div class="text-xs text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium transition" wire:click="closeModal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-cyan-400 hover:from-cyan-700 hover:to-cyan-500 text-white rounded-full text-sm font-medium transition" wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update' : 'Simpan' }}</span>
                        <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>