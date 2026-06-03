<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-users mr-2 text-blue-600"></i> Manajemen User
                </h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="#" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-700">User</span>
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
                        <i class="fas fa-database text-white"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Data User</h2>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search Bar -->
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Cari user..." 
                               class="pl-9 pr-8 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64">
                        @if($search)
                            <button wire:click="resetSearch" 
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Sync Button -->
                    <button wire:click="syncUser" wire:loading.attr="disabled"
                            class="bg-gradient-to-r from-blue-600 to-blue-400 text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition flex items-center gap-2">
                        <span wire:loading.remove wire:target="syncUser">
                            <i class="fas fa-sync-alt"></i> Sync User API
                        </span>
                        <span wire:loading wire:target="syncUser">
                            <i class="fas fa-spinner fa-pulse"></i> Menyinkronkan...
                        </span>
                    </button>

                    <!-- Add Button -->
                    <button wire:click="openModal" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-1"></i> No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-user mr-1"></i> Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-envelope mr-1"></i> Username
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-tag mr-1"></i> Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-1"></i> Tanggal Dibuat
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-1"></i> Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($user->role == 'admin')
                                        bg-red-100 text-red-700
                                    @elseif($user->role == 'superadmin')
                                        bg-yellow-100 text-yellow-700
                                    @else
                                        bg-blue-100 text-blue-700
                                    @endif">
                                    {{ ucfirst($user->role ?? 'User') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button wire:click="openModal({{ $user->id }})" 
                                            class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-3 py-1 rounded-lg text-sm transition flex items-center gap-1">
                                        <i class="fas fa-edit text-xs"></i> Edit
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus user ini?"
                                            class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded-lg text-sm transition flex items-center gap-1">
                                        <i class="fas fa-trash text-xs"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-5xl mb-3 block"></i>
                                @if($search)
                                    Tidak ada user ditemukan untuk pencarian "{{ $search }}"
                                @else
                                    Tidak ada data user
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
               
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi Sukses -->
    @if (session()->has('sync_success'))
        <div class="mt-4 bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-green-700">{{ session('sync_success') }}</span>
                <button type="button" class="ml-auto text-green-500 hover:text-green-700" 
                        onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Notifikasi Gagal -->
    @if (session()->has('sync_error'))
        <div class="mt-4 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                <span class="text-red-700">{{ session('sync_error') }}</span>
                <button type="button" class="ml-auto text-red-500 hover:text-red-700"
                        onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Debug API Data -->
    @if (!empty($apiData))
        <div class="mt-4 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-3 bg-gray-800 border-b border-gray-700">
                <div class="flex items-center gap-2">
                    <i class="fas fa-code text-blue-400"></i>
                    <span class="font-semibold text-white">Response API</span>
                </div>
            </div>
            <div class="p-4 bg-gray-900">
                <pre class="text-gray-300 text-xs overflow-x-auto">{{ json_encode($apiData, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    @endif

    <!-- Modal -->
    @include('livewire.superadmin.user.createModal')
</div>