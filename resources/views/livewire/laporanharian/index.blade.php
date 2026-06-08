<div class="min-h-screen bg-gray-300 pb-20">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white sticky top-0 z-10 shadow-lg">
        <div class="px-4 py-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Laporan Harian KKN</h1>
                    <p class="text-xs text-white/80 mt-0.5">Laporan kegiatan harian mahasiswa KKN</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Bar: Sertifikat (Kiri) + Timeline (Kanan) -->
    <div class="mx-4 mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            
            <!-- Kiri: Status Sertifikat -->
            @if(auth()->user()->role == 'mahasiswa')
                @if(!$hasSertifikat)
                <div class="bg-red-100 rounded-xl p-3 flex items-center gap-2 border-l-4 border-red-500">
                    <i class="fas fa-certificate text-red-500"></i>
                    <span class="text-xs text-red-700">Sertifikat: Belum tersedia</span>
                </div>
                @else
                <div class="bg-green-100 rounded-xl p-3 flex items-center gap-2 border-l-4 border-green-500">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-xs text-green-700">Sertifikat: Tersedia ✓</span>
                </div>
                @endif
            @endif

            <!-- Kanan: Info Timeline -->
            <div class="{{ $canCreateLaporan && $hasSertifikat ? 'bg-green-50 border-l-4 border-green-500' : 'bg-yellow-50 border-l-4 border-yellow-500' }} rounded-xl p-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-blue-500"></i>
                    <span class="text-xs font-medium text-gray-700">
                        {{ $timelineMessage }}
                    </span>
                </div>
                @if($canCreateLaporan && $hasSertifikat && $sisaHari > 0)
                <div class="flex items-center gap-1">
                    <i class="fas fa-hourglass-half text-gray-500 text-xs"></i>
                    <span class="text-xs font-semibold text-gray-700">{{ round($sisaHari) }} hari</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm mx-4 mt-4 overflow-hidden">
        <div class="p-4 space-y-3">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Periode</label>
                    <select wire:model.live="periodeFilter" class="w-full px-3 py-2 border rounded-lg text-sm bg-white">
                        <option value="">Semua Periode</option>
                        @foreach($periodes as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-40 flex items-end">
                    @if(auth()->user()->role == 'mahasiswa')
                        @if($canCreateLaporan && $hasSertifikat)
                        <a href="{{ route('kegiatan.laporanharian.create', ['role' => $role, 'jenisKegiatan' => $jenisKegiatan]) }}" 
                           wire:navigate 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition text-center">
                            <i class="fas fa-plus mr-2"></i>Tambah
                        </a>
                        @else
                        <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                            <i class="fas fa-plus mr-2"></i>Tambah
                        </button>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Filter untuk admin -->
            @if(in_array(auth()->user()->role, ['superadmin', 'dosen', 'kemahasiswaan']))
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Program Studi</label>
                    <select wire:model.live="filterProdi" class="w-full px-3 py-2 border rounded-lg text-sm bg-white">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama_program_studi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cari</label>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Nama atau NIM...">
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- List Laporan -->
    <div class="px-4 mt-4 space-y-3">
        @forelse($laporans as $index => $laporan)
        @php
        $dataMahasiswa = is_string($laporan->user?->data_mahasiswa)
            ? json_decode($laporan->user->data_mahasiswa, true)
            : ($laporan->user?->data_mahasiswa ?? []);
            
        $statusConfig = [
            'draft' => ['class' => 'bg-gray-100 text-gray-700', 'icon' => 'fa-pen-fancy', 'label' => 'Draft'],
            'submitted' => ['class' => 'bg-blue-100 text-blue-700', 'icon' => 'fa-paper-plane', 'label' => 'Submitted'],
            'revisi' => ['class' => 'bg-yellow-100 text-yellow-700', 'icon' => 'fa-undo-alt', 'label' => 'Revisi'],
            'approved' => ['class' => 'bg-green-100 text-green-700', 'icon' => 'fa-check-circle', 'label' => 'Approved'],
        ];
        $config = $statusConfig[$laporan->status] ?? $statusConfig['draft'];
        @endphp
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user-graduate text-xs text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $dataMahasiswa['nama_mahasiswa'] ?? $laporan->user->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $dataMahasiswa['nim'] ?? '-' }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full {{ $config['class'] }}">
                    <i class="fas {{ $config['icon'] }}"></i> {{ $config['label'] }}
                </span>
            </div>
            
            <div class="px-4 py-3 space-y-2">
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-calendar-alt text-gray-400 w-4"></i>
                    <span class="text-gray-600">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-users text-gray-400 w-4"></i>
                    <span class="text-gray-600">Kelompok: {{ $laporan->kelompok->nama_kelompok ?? '-' }}</span>
                </div>
            </div>
            
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button wire:click="view({{ $laporan->id }})"
                        class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-sm transition">
                    <i class="fas fa-eye"></i> Detail
                </button>
                
                @if(auth()->user()->role == 'mahasiswa' && $laporan->status == 'revisi' && $laporan->user_id == auth()->id())
                <button wire:click="edit({{ $laporan->id }})"
                        class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg text-sm transition">
                    <i class="fas fa-edit"></i> Edit
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-3 block"></i>
            <p class="text-gray-500">Belum ada data laporan harian</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($laporans->hasPages())
    <div class="px-4 mt-4">
        {{ $laporans->links() }}
    </div>
    @endif
</div>