<li class="nav-header"
    style="color: #6ab0d6; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
    <i class="fas fa-crown mr-1"></i> Mahasiswa
</li>




{{-- Kegiatan Treeview --}}
@php
    $kegiatanActive = request()->is('mahasiswa/kegiatan/*');
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
            <a href="{{ url('/mahasiswa/kegiatan/KKN/index') }}"
                class="nav-link {{ request()->is('mahasiswa/kegiatan/KKN*') ? 'active' : '' }}"
                style="border-radius: 8px; margin: 2px 0; ">
                <i class="nav-icon fas fa-users"></i>
                <p>KKN</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/mahasiswa/kegiatan/PAM/index') }}"
                class="nav-link {{ request()->is('mahasiswa/kegiatan/PAM*') ? 'active' : '' }}"
                style="border-radius: 8px; margin: 2px 0; ">
                <i class="nav-icon fas fa-hands-helping"></i>
                <p>PAM</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/mahasiswa/kegiatan/PKM/index') }}"
                class="nav-link {{ request()->is('mahasiswa/kegiatan/PKM*') ? 'active' : '' }}"
                style="border-radius: 8px; margin: 2px 0; ">
                <i class="nav-icon fas fa-lightbulb"></i>
                <p>PKM</p>
            </a>
        </li>
    </ul>
</li>
