<div>
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-5">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-file-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">Laporan Harian KKN</h4>
                            <p class="text-sm text-gray-500">Laporan kegiatan harian mahasiswa KKN</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Info Card -->
            <div
                class="rounded-xl p-5 mb-6 flex items-start gap-4 shadow-sm {{ $canCreateLaporan ? 'bg-green-50 border-l-4 border-green-500' : 'bg-yellow-50 border-l-4 border-yellow-500' }}">
                <i class="fas fa-calendar-alt text-2xl text-blue-500"></i>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Informasi Timeline Pelaksanaan</p>
                    <p class="text-sm text-gray-600">{{ $timelineMessage }}</p>
                    @if($canCreateLaporan && $sisaHari > 0)
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-hourglass-half mr-1"></i> Sisa waktu: {{ $sisaHari }} hari
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Periode</label>
                            <select wire:model.live="periodeFilter" class="w-full px-3 py-2 border rounded-lg text-sm">
                                <option value="">Semua Periode</option>
                                @foreach($periodes as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Program Studi</label>
                            <select wire:model.live="filterProdi" class="w-full px-3 py-2 border rounded-lg text-sm">
                                <option value="">Semua Prodi</option>
                                @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama_program_studi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Mahasiswa</label>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Nama atau NIM...">
                        </div>
                        <div class="flex items-end">
                            <button wire:click="create" {{ !$canCreateLaporan ? 'disabled' : '' }}
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50">
                                <i class="fas fa-plus mr-2"></i>Tambah Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">No</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">NIM</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Nama Mahasiswa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Kelompok</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($laporans as $index => $laporan)
                            @php
                            $dataMahasiswa = is_string($laporan->user?->data_mahasiswa)
                            ? json_decode($laporan->user->data_mahasiswa, true)
                            : ($laporan->user?->data_mahasiswa ?? []);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $laporans->firstItem() + $index }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 text-center">
                                    {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 font-mono text-sm">{{ $dataMahasiswa['nim'] ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user-graduate text-xs text-blue-600"></i>
                                        </div>
                                        <span
                                            class="text-sm">{{ $dataMahasiswa['nama_mahasiswa'] ?? $laporan->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">
                                        {{ $laporan->kelompok->nama_kelompok ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                    $statusConfig = [
                                    'draft' => ['class' => 'bg-gray-100 text-gray-700', 'icon' => 'fa-pen-fancy',
                                    'label' => 'Draft'],
                                    'submitted' => ['class' => 'bg-blue-100 text-blue-700', 'icon' => 'fa-paper-plane',
                                    'label' => 'Submitted'],
                                    'revisi' => ['class' => 'bg-yellow-100 text-yellow-700', 'icon' => 'fa-undo-alt',
                                    'label' => 'Revisi'],
                                    'approved' => ['class' => 'bg-green-100 text-green-700', 'icon' =>
                                    'fa-check-circle', 'label' => 'Approved'],
                                    ];
                                    $config = $statusConfig[$laporan->status] ?? $statusConfig['draft'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full {{ $config['class'] }}">
                                        <i class="fas {{ $config['icon'] }}"></i> {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <!-- View -->
                                        <button wire:click="view({{ $laporan->id }})"
                                            class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 inline-flex items-center justify-center transition"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Edit -->
                                        @if(auth()->user()->role == 'mahasiswa' && $laporan->status == 'revisi' &&
                                        $laporan->user_id == auth()->id())
                                        <button wire:click="edit({{ $laporan->id }})"
                                            class="w-8 h-8 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 inline-flex items-center justify-center transition"
                                            title="Edit Laporan">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif

                                        <!-- Delete dengan SweetAlert -->
                                        @if($laporan->user_id == auth()->id())
                                        <button wire:click="confirmDelete({{ $laporan->id }})"
                                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 inline-flex items-center justify-center transition"
                                            title="Hapus Laporan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada data laporan harian
                    </table>
                    </tr>
                    @endforelse
                    </tbody>
                    </table>
                </div>
                @if($laporans->hasPages())
                <div class="px-5 py-3 bg-gray-50 border-t">
                    {{ $laporans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert Script -->
