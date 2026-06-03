<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i> Manajemen Periode
                </h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="#" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-700">Periode</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Data Periode</h2>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2" 
                       wire:click.prevent="openModal" style="text-decoration: none;">
                        <i class="fas fa-plus"></i> Tambah Periode
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <!-- Filter Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-gray-400 text-sm"></i>
                    <span class="text-sm text-gray-600">Filter Data</span>
                </div>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" 
                           class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64" 
                           placeholder="Cari periode berdasarkan nama..." 
                           wire:model.live="search">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-tag mr-1"></i> Nama Periode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-calendar-day mr-1"></i> Tanggal Mulai
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-calendar-check mr-1"></i> Tanggal Selesai
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                <i class="fas fa-cogs mr-1"></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($periodes as $index => $periode)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $periodes->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tag text-blue-500 text-sm"></i>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $periode->nama_periode }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs">
                                        <i class="fas fa-calendar-day text-xs"></i>
                                        {{ $periode->tanggal_mulai ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs">
                                        <i class="fas fa-calendar-check text-xs"></i>
                                        {{ $periode->tanggal_selesai ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <button wire:click="edit({{ $periode->id }})" 
                                                class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 p-2 rounded-lg transition group relative">
                                            <i class="fas fa-edit"></i>
                                            <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                                Edit
                                            </span>
                                        </button>
                                        <button wire:click="delete({{ $periode->id }})" 
                                                wire:confirm="Apakah Anda yakin ingin menghapus periode ini?"
                                                class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition group relative">
                                            <i class="fas fa-trash"></i>
                                            <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                                Hapus
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-calendar-times text-gray-400 text-5xl"></i>
                                        <p class="text-gray-500">Tidak ada data periode</p>
                                        <small class="text-gray-400">Silakan tambah periode baru dengan klik tombol "Tambah Periode"</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-chart-bar mr-1"></i> Total: <strong class="text-gray-700">{{ $periodes->total() }}</strong> Periode
                    </div>
                    <div>
                        {{ $periodes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @include('livewire.superadmin.periode.createModal')
</div>