<nav class="bg-white shadow-md px-4 py-3">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button id="sidebarToggle" 
                    class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <a href="{{ route('superadmin.user.index') }}" class="text-gray-800 font-semibold text-lg">
                <i class="fas fa-graduation-cap text-blue-600"></i>
                <span class="hidden sm:inline">LPPM Uniwa</span>
            </a>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Notifikasi - dengan JavaScript murni -->
            <div class="relative">
                <button id="notifButton" 
                        class="relative text-gray-600 hover:text-gray-900 focus:outline-none p-2 rounded-lg hover:bg-gray-100">
                    <i class="far fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 min-w-[18px]">3</span>
                </button>
                
                <!-- Dropdown Notifikasi -->
                <div id="notifDropdown" 
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border z-50 hidden">
                    <div class="p-3 border-b">
                        <span class="font-semibold">Notifikasi</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="flex items-start gap-3 p-3 hover:bg-gray-50 border-b">
                            <i class="fas fa-file-alt text-blue-500 mt-0.5"></i>
                            <div>
                                <p class="text-sm">Proposal baru diajukan</p>
                                <span class="text-xs text-gray-400">2 menit lalu</span>
                            </div>
                        </a>
                        <a href="#" class="flex items-start gap-3 p-3 hover:bg-gray-50 border-b">
                            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                            <div>
                                <p class="text-sm">Pengabdian disetujui</p>
                                <span class="text-xs text-gray-400">1 jam lalu</span>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-t text-center">
                        <a href="#" class="text-sm text-blue-600">Lihat semua</a>
                    </div>
                </div>
            </div>

            <!-- User Dropdown - dengan JavaScript murni -->
            <div class="relative">
                <button id="userButton" 
                        class="flex items-center gap-2 text-gray-600 hover:text-gray-900 focus:outline-none p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-user-circle text-2xl"></i>
                    <i class="fas fa-chevron-down text-xs hidden sm:inline"></i>
                </button>
                
                <!-- Dropdown User -->
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
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog w-4"></i> Settings
                    </a>
                    <hr class="my-1">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt w-4"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<script>
    // Notifikasi Dropdown
    const notifButton = document.getElementById('notifButton');
    const notifDropdown = document.getElementById('notifDropdown');
    
    if (notifButton && notifDropdown) {
        notifButton.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
            // Tutup dropdown user jika terbuka
            if (userDropdown) userDropdown.classList.add('hidden');
        });
    }
    
    // User Dropdown
    const userButton = document.getElementById('userButton');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userButton && userDropdown) {
        userButton.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
            // Tutup notifikasi dropdown jika terbuka
            if (notifDropdown) notifDropdown.classList.add('hidden');
        });
    }
    
    // Tutup semua dropdown saat klik di luar
    document.addEventListener('click', (e) => {
        if (notifDropdown && !notifButton.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
        if (userDropdown && !userButton.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });
</script>