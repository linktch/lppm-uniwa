<div>
    <div class="p-4 md:p-6">
        <div class="max-w-full mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-4 md:p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center">
                                <i class="fas fa-chart-bar text-white text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-base md:text-xl font-bold text-gray-800">Rekap Laporan Harian</h4>
                                <p class="text-xs text-gray-500">
                                    {{ $jenisKegiatan }} - {{ $role }}
                                </p>
                                @if($timeline)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i> 
                                    Periode Pelaksanaan: {{ \Carbon\Carbon::parse($timeline->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($timeline->tanggal_selesai)->format('d/m/Y') }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <button wire:click="back" 
                                class="inline-flex items-center gap-1 md:gap-2 px-3 py-1.5 md:px-4 md:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs md:text-sm font-medium transition">
                            <i class="fas fa-arrow-left"></i> <span class="hidden md:inline">Kembali</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Minggu -->
            @if($timeline)
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2 md:gap-3">
                        <button wire:click="setTanggalByMinggu(1)" 
                                class="px-2 py-2 md:px-3 rounded-lg text-xs md:text-sm font-medium transition text-center
                                {{ $mingguKe == 1 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Minggu 1
                            <span class="hidden md:inline">(1-7)</span>
                        </button>
                        <button wire:click="setTanggalByMinggu(2)" 
                                class="px-2 py-2 md:px-3 rounded-lg text-xs md:text-sm font-medium transition text-center
                                {{ $mingguKe == 2 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Minggu 2
                            <span class="hidden md:inline">(8-14)</span>
                        </button>
                        <button wire:click="setTanggalByMinggu(3)" 
                                class="px-2 py-2 md:px-3 rounded-lg text-xs md:text-sm font-medium transition text-center
                                {{ $mingguKe == 3 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Minggu 3
                            <span class="hidden md:inline">(15-21)</span>
                        </button>
                        <button wire:click="setTanggalByMinggu(4)" 
                                class="px-2 py-2 md:px-3 rounded-lg text-xs md:text-sm font-medium transition text-center
                                {{ $mingguKe == 4 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Minggu 4
                            <span class="hidden md:inline">(22-28)</span>
                        </button>
                        <button wire:click="setTanggalByMinggu(5)" 
                                class="px-2 py-2 md:px-3 rounded-lg text-xs md:text-sm font-medium transition text-center
                                {{ $mingguKe == 5 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Minggu 5
                            <span class="hidden md:inline">(29-31)</span>
                        </button>
                    </div>
                    <div class="text-center mt-2 text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_selesai)->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Search -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-4">
                    <div class="flex gap-2 md:gap-4">
                        <div class="flex-1">
                            <input type="text" wire:model.live="search" 
                                   class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Cari mahasiswa...">
                        </div>
                        <div>
                            <button wire:click="$refresh" 
                                    class="px-3 py-2 md:px-4 md:py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Rekap -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-2 md:px-3 md:py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-2 py-2 md:px-3 md:py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mahasiswa</th>
                                <th class="px-2 py-2 md:px-3 md:py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                <th class="px-2 py-2 md:px-3 md:py-3 text-left text-xs font-medium text-gray-500 uppercase">Program Studi</th>
                                @foreach($tanggalRange as $tanggal)
                                <th class="px-1 py-2 md:px-2 md:py-3 text-center text-xs font-medium text-gray-500 uppercase min-w-[60px] md:min-w-[80px]">
                                    {{ \Carbon\Carbon::parse($tanggal)->format('d/m') }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($mahasiswas as $index => $mahasiswa)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 md:px-3 md:py-3 text-xs md:text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-2 py-2 md:px-3 md:py-3 text-xs md:text-sm font-medium text-gray-800">{{ $mahasiswa['nama'] }}</td>
                                <td class="px-2 py-2 md:px-3 md:py-3 text-xs md:text-sm text-gray-600">{{ $mahasiswa['nim'] }}</td>
                                <td class="px-2 py-2 md:px-3 md:py-3 text-xs md:text-sm text-gray-500">{{ $mahasiswa['prodi'] }}</td>
                                @foreach($tanggalRange as $tanggal)
                                <td class="px-1 py-2 md:px-2 md:py-3 text-center">
                                    @php
                                        $status = $mahasiswa[$tanggal]['status'] ?? '-';
                                    @endphp
                                    @if($status == 'approved')
                                        <span class="inline-flex px-1 py-0.5 md:px-2 md:py-1 text-[10px] md:text-xs rounded-full bg-green-100 text-green-700 whitespace-nowrap">
                                            <i class="fas fa-check-circle mr-0 md:mr-1"></i> ACC
                                        </span>
                                    @elseif($status == 'revisi')
                                        <span class="inline-flex px-1 py-0.5 md:px-2 md:py-1 text-[10px] md:text-xs rounded-full bg-yellow-100 text-yellow-700 whitespace-nowrap">
                                            <i class="fas fa-edit mr-0 md:mr-1"></i> Revisi
                                        </span>
                                    @elseif($status == 'submitted')
                                        <span class="inline-flex px-1 py-0.5 md:px-2 md:py-1 text-[10px] md:text-xs rounded-full bg-blue-100 text-blue-700 whitespace-nowrap">
                                            <i class="fas fa-paper-plane mr-0 md:mr-1"></i> Submitted
                                        </span>
                                    @else
                                        <span class="inline-flex px-1 py-0.5 md:px-2 md:py-1 text-[10px] md:text-xs rounded-full bg-gray-100 text-gray-500 whitespace-nowrap">
                                            <i class="fas fa-minus-circle mr-0 md:mr-1"></i> -
                                        </span>
                                    @endif
                                 </td>
                                @endforeach
                             </tr>
                            @empty
                            <tr>
                                <td colspan="{{ 4 + count($tanggalRange) }}" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                    Belum ada data mahasiswa
                                </td>
                             </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mt-4 flex flex-wrap gap-3 md:gap-4 justify-center text-[10px] md:text-xs">
                <span class="inline-flex items-center gap-1"><i class="fas fa-check-circle text-green-500"></i> ACC (Disetujui)</span>
                <span class="inline-flex items-center gap-1"><i class="fas fa-edit text-yellow-500"></i> Revisi (Perlu Perbaikan)</span>
                <span class="inline-flex items-center gap-1"><i class="fas fa-paper-plane text-blue-500"></i> Submitted (Menunggu)</span>
                <span class="inline-flex items-center gap-1"><i class="fas fa-minus-circle text-gray-400"></i> Belum Upload</span>
            </div>

            <!-- Loading Indicator -->
            <div wire:loading class="fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg text-sm">
                <i class="fas fa-spinner fa-pulse mr-2"></i> Memuat data...
            </div>
        </div>
    </div>
</div>