<li class="nav-header"
    style="color: #6ab0d6; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
    <i class="fas fa-crown mr-1"></i> Prodi
</li>




{{-- Kegiatan Treeview --}}
@php
    $kegiatanActive = request()->is('prodi/kegiatan/*');
@endphp

<li class="nav-item has-treeview {{ $kegiatanActive ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ $kegiatanActive ? 'active' : '' }}"
        style="border-radius: 8px; margin: 2px 5px;">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>
            Manajemen Kegiatan
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('/prodi/kegiatan/KKN/index') }}"
                class="nav-link {{ request()->is('prodi/kegiatan/KKN*') ? 'active' : '' }}"
                style="border-radius: 8px; margin: 2px 0; ">
                <i class="nav-icon fas fa-users"></i>
                <p>KKN</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/prodi/kegiatan/PAM/index') }}"
                class="nav-link {{ request()->is('prodi/kegiatan/PAM*') ? 'active' : '' }}"
                style="border-radius: 8px; margin: 2px 0; ">
                <i class="nav-icon fas fa-hands-helping"></i>
                <p>PAM</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/prodi/kegiatan/PKM/index') }}"
                class="nav-link {{ request()->is('prodi/kegiatan/PKM*') ? 'active' : '' }}"
                style="border-radius: 8px; margin: 2px 0; ">
                <i class="nav-icon fas fa-lightbulb"></i>
                <p>PKM</p>
            </a>
        </li>
    </ul>
</li>
