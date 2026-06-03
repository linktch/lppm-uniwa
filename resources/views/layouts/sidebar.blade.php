@php
$role = auth()->user()->role ?? 'superadmin';
$roleMap = ['Super Admin' => 'superadmin'];
$role = $roleMap[$role] ?? strtolower($role);
$baseUrl = "/$role/kegiatan";
$kegiatanActive = request()->is("$role/kegiatan/*") ?? false;
@endphp

<aside class="w-64 min-h-screen flex flex-col shadow-xl"
       style="background: linear-gradient(180deg, #1a3a5c 0%, #0f2b45 100%);">
    
    <!-- Brand Logo -->
    <a href="{{ route('superadmin.user.index') }}" 
       class="flex items-center gap-3 px-4 py-4 border-b border-white/10">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-lg"
             style="background: linear-gradient(135deg, #2c7da0, #1e3a5f);">
            <i class="fas fa-graduation-cap text-white text-sm"></i>
        </div>
        <span class="font-bold text-base tracking-wide text-white">
            LPPM <span style="color: #6ab0d6;">Uniwa</span>
        </span>
    </a>

    <!-- Sidebar Content -->
    <div class="flex-1 flex flex-col py-4 px-3">
        
        <!-- User Panel -->
        <div class="flex items-center gap-3 pb-4 mb-3 border-b border-white/10">
            <div class="flex-shrink-0">
                <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" 
                     class="w-9 h-9 rounded-full object-cover ring-2 ring-[#2c7da0]"
                     alt="User Image">
            </div>
            <div class="flex-1 min-w-0">
                <a href="#" class="text-white font-medium text-sm truncate hover:text-white/90 transition">
                    {{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}
                </a>
                <p class="text-[#6ab0d6] text-xs flex items-center gap-1 mt-0.5">
                    <i class="fas fa-user-shield text-[10px]"></i> 
                    {{ auth()->user()->role ?? 'Super Admin' }}
                </p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1">
            <ul class="space-y-1">
                
                <!-- Dashboard -->
                <li>
                    <a href="#" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->is('dashboard*') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </li>

                <!-- SUPER ADMIN MENU -->
                @if($role === 'superadmin')
                <li class="pt-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-[#6ab0d6] px-3 py-1 flex items-center gap-1">
                        <i class="fas fa-crown text-[10px]"></i> SUPER ADMIN
                    </div>
                </li>

                <li>
                    <a wire:navigate href="{{ route('superadmin.user.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('superadmin.user.index') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-users-cog w-5"></i>
                        <span class="text-sm">Manajemen User</span>
                    </a>
                </li>

                <li>
                    <a wire:navigate href="{{ route('superadmin.periode.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('superadmin.periode.index') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span class="text-sm">Periode Kegiatan</span>
                    </a>
                </li>

                <li>
                    <a wire:navigate href="{{ route('superadmin.pejabat.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('superadmin.pejabat.index') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-signature w-5"></i>
                        <span class="text-sm">Pejabat Signatur</span>
                    </a>
                </li>
                @endif

                <!-- KEGIATAN MENU -->
                <li class="pt-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-[#6ab0d6] px-3 py-1 flex items-center gap-1">
                        <i class="fas fa-briefcase text-[10px]"></i> KEGIATAN
                    </div>
                </li>

                <!-- Treeview Menu Kegiatan (Manual Toggle) -->
                <li class="relative">
                    <button onclick="toggleSubmenu(this)"
                            class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ $kegiatanActive ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-briefcase w-5"></i>
                            <span class="text-sm">Manajemen Kegiatan</span>
                        </div>
                        <i class="fas fa-chevron-right transition-transform duration-200 submenu-icon {{ $kegiatanActive ? 'rotate-90' : '' }}"></i>
                    </button>
                    
                    <ul class="submenu pl-7 mt-1 space-y-1 {{ $kegiatanActive ? '' : 'hidden' }}">
                        @foreach (['KKN'] as $item)
                        <li>
                            <a wire:navigate href="{{ url("$baseUrl/$item/index") }}"
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition-all duration-200 text-white/70 hover:bg-white/5 hover:text-white text-sm {{ request()->is("$role/kegiatan/$item*") ? 'bg-white/10 text-white' : '' }}">
                                <i class="w-4 {{ $item=='KKN' ? 'fas fa-users' : 'fas fa-lightbulb' }}"></i>
                                <span>{{ $item }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>

                <!-- Divider -->
                <li class="pt-3">
                    <div class="border-t border-white/10"></div>
                </li>

                <!-- Logout -->
                <li>
                    <a href="#" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-red-400 hover:bg-red-500/10 hover:text-red-300"
                       onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="text-sm">Logout</span>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<script>
function toggleSubmenu(button) {
    const submenu = button.nextElementSibling;
    const icon = button.querySelector('.submenu-icon');
    submenu.classList.toggle('hidden');
    icon.classList.toggle('rotate-90');
}
</script>

<style>
.submenu {
    transition: all 0.2s ease;
}
.rotate-90 {
    transform: rotate(90deg);
}
</style>