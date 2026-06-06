<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LPPM Uniwa')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @livewireStyles

    <style>
    .sidebar-transition {
        transition: transform 0.3s ease-in-out;
    }

    @media (max-width: 1023px) {
        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-visible {
            transform: translateX(0);
        }
    }

    .overlay-transition {
        transition: opacity 0.3s ease-in-out;
    }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <!-- Overlay untuk mobile -->
    <div id="sidebarOverlay"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden opacity-0 pointer-events-none overlay-transition">
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-[#1a3a5c] to-[#0f2b45] shadow-xl sidebar-transition lg:relative lg:translate-x-0 -translate-x-full lg:translate-x-0">
            @include('layouts.sidebar')
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">

            <!-- NAVBAR -->
            <nav class="bg-white shadow-md px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button id="sidebarToggle"
                            class="text-gray-600 hover:text-gray-900 focus:outline-none p-2 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <a href="{{ route('superadmin.user.index') }}" class="text-gray-800 font-semibold text-lg">
                            <i class="fas fa-graduation-cap text-blue-600"></i>
                            <span class="hidden sm:inline">LPPM Uniwa</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Notifikasi -->
                        <div class="relative">
                            <button id="notifButton"
                                class="relative text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                                <i class="far fa-bell text-xl"></i>
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5">3</span>
                            </button>
                            <div id="notifDropdown"
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border z-50 hidden">
                                <div class="p-3 border-b"><span class="font-semibold">Notifikasi</span></div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="flex items-start gap-3 p-3 hover:bg-gray-50 border-b">
                                        <i class="fas fa-file-alt text-blue-500"></i>
                                        <div>
                                            <p class="text-sm">Proposal baru diajukan</p><span
                                                class="text-xs text-gray-400">2 menit lalu</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2 border-t text-center"><a href="#" class="text-sm text-blue-600">Lihat
                                        semua</a></div>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative">
                            <button id="userButton"
                                class="flex items-center gap-2 text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-user-circle text-2xl"></i>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="userDropdown"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border py-2 z-50 hidden">
                                <div class="px-4 py-3 border-b bg-gray-50">
                                    <p class="text-sm font-semibold">{{ auth()->user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Admin' }}</p>
                                </div>
                                <a href="{{ route('profile') }}" 
   wire:navigate
   class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
    <i class="fas fa-user w-4"></i> Profile
</a>
                                <a href="#"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i
                                        class="fas fa-cog"></i> Settings</a>
                                <hr class="my-1">
                                <a href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-gray-100"><i
                                        class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                <div class="container mx-auto">
                    {{ $slot }}
                </div>
            </main>

            <!-- FOOTER -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6 text-center text-gray-500 text-sm">
                © {{ date('Y') }} LPPM Uniwa. All rights reserved.
            </footer>
        </div>
    </div>

    <!-- Form Logout -->
    <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- SweetAlert Script -->
    <script data-navigate-once src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
    @livewireScripts


    <script data-navigate-once>
    (function() {

        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (!sidebar) return;

            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');

            if (window.innerWidth < 1024 && overlay) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (!sidebar) return;

            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');

            if (window.innerWidth < 1024 && overlay) {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
            }
        }

        function resetSidebarState() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (!sidebar) return;

            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full', 'translate-x-0');

                if (overlay) {
                    overlay.classList.add('opacity-0', 'pointer-events-none');
                    overlay.classList.remove('opacity-100', 'pointer-events-auto');
                }
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
            }
        }

        // Toggle sidebar (event delegation)
        document.addEventListener('click', function(e) {

            const toggleBtn = e.target.closest('#sidebarToggle');

            if (toggleBtn) {
                e.preventDefault();

                const sidebar = document.getElementById('sidebar');

                if (!sidebar) return;

                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }

                return;
            }

            const overlay = e.target.closest('#sidebarOverlay');

            if (overlay) {
                closeSidebar();
            }
        });

        // Dropdown Notifikasi & User
        document.addEventListener('click', function(e) {

            const notifButton = document.getElementById('notifButton');
            const notifDropdown = document.getElementById('notifDropdown');

            const userButton = document.getElementById('userButton');
            const userDropdown = document.getElementById('userDropdown');

            if (e.target.closest('#notifButton')) {

                notifDropdown?.classList.toggle('hidden');
                userDropdown?.classList.add('hidden');

                return;
            }

            if (e.target.closest('#userButton')) {

                userDropdown?.classList.toggle('hidden');
                notifDropdown?.classList.add('hidden');

                return;
            }

            notifDropdown?.classList.add('hidden');
            userDropdown?.classList.add('hidden');
        });

        // Initial load
        document.addEventListener('DOMContentLoaded', resetSidebarState);

        // Saat Livewire navigate selesai
        document.addEventListener('livewire:navigated', resetSidebarState);

        // Resize browser
        window.addEventListener('resize', resetSidebarState);

    })();
    </script>
    <script data-navigate-once>
    document.addEventListener('livewire:initialized', () => {

        Livewire.on('show-delete-confirm', () => {
            console.log('show-delete-confirm diterima');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Laporan yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                console.log('Hasil Swal:', result);

                if (result.isConfirmed) {
                    console.log('Kirim event deleteConfirmed');

                    Livewire.dispatch('deleteConfirmed');
                }
            });
        });

        Livewire.on('swal', (event) => {
            console.log('Event swal diterima:', event);

            Swal.fire(event[0]);
        });

        Livewire.on('swal-and-redirect', (event) => {

            const data = event[0];

            Swal.fire({
                icon: data.icon,
                title: data.title,
                text: data.text,
                confirmButtonText: 'Selesai!'
            }).then(() => {
                window.location.href = data.url;
            });

        });

    });
    </script>
</body>

</html>