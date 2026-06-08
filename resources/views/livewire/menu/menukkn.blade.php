<div class="rounded-xl min-h-screen bg-gray-300 pb-20">
    <!-- Header dengan Gradient -->
    <div class="rounded-xl bg-gradient-to-r from-blue-700 to-blue-500 text-white sticky top-0 z-10 shadow-lg">
        <div class="px-4 py-5 md:px-8 md:py-6">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-users text-2xl md:text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold">Manajemen KKN</h1>
                    <p class="text-xs md:text-sm text-white/80 mt-0.5">
                        @if(auth()->user()->role == 'mahasiswa')
                        Kelola partisipasi KKN Anda
                        @elseif(auth()->user()->role == 'dosen')
                        Kelola mahasiswa bimbingan
                        @else
                        Kelola seluruh kegiatan KKN
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Menu - Responsive: 2 kolom (mobile) / 4 kolom (desktop) -->
    <div class="px-4 py-6 md:px-8 md:py-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5">

            <!-- Menu 1: Timeline (Super Admin Only) -->
            @if(auth()->user()->role == 'superadmin')
            <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/timeline") }}" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center mb-3 group-hover:bg-cyan-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-calendar-alt text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Timeline</h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">Atur jadwal</p>
            </a>
            @endif

            <!-- Menu 2: Kelola Kelompok (Super Admin Only) -->
            @if(auth()->user()->role == 'superadmin')
            <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/kelompok") }}" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-users text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Kelompok</h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">Kelola kelompok</p>
            </a>
            @endif

            <!-- Menu 3: Screening -->
            @if(in_array(auth()->user()->role, ['superadmin',  'prodi', 'kemahasiswaan']))
            <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/screening") }}" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center mb-3 group-hover:bg-teal-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-clipboard-list text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Screening</h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">Seleksi peserta</p>
            </a>
            @endif

            <!-- Menu 4: Screening Hafalan -->
            @if(in_array(auth()->user()->role, ['superadmin']))
            <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/screening/hafalan") }}" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-book-quran text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Hafalan</h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">Penilaian hafalan</p>
            </a>
            @endif

            <!-- Menu 5: Laporan Harian -->
            <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/laporan-harian") }}" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-file-alt text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">
                    @if(auth()->user()->role == 'mahasiswa') Laporan Saya
                    @else Review Laporan
                    @endif
                </h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">
                    @if(auth()->user()->role == 'mahasiswa') Buat laporan
                    @else Review mahasiswa
                    @endif
                </p>
            </a>

            <!-- Menu 6: Dokumen -->
            @if(in_array(auth()->user()->role, ['mahasiswa']))
            <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/berkas-saya") }}" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-folder-open text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">
                    @if(auth()->user()->role == 'mahasiswa') Dokumen Saya
                    @else Dokumen
                    @endif
                </h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">Kelola berkas</p>
            </a>
            @endif

            <!-- Menu 7: Pendaftaran -->
            @if(in_array(auth()->user()->role, ['superadmin', 'mahasiswa']))
            @if($jenisKegiatan=='PKM')
            <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/pendaftaran") }}" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-green-100 text-green-600 flex items-center justify-center mb-3 group-hover:bg-green-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-edit text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">
                    @if(auth()->user()->role == 'mahasiswa') Pendaftaran
                    @else Manajemen
                    @endif
                </h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">
                    @if(auth()->user()->role == 'mahasiswa') Daftar KKN
                    @else Kelola pendaftaran
                    @endif
                </p>
            </a>
            @endif
            @endif

            <!-- Menu 8: Rekap Laporan -->
            <a href="{{ route('kegiatan.rekap-laporan.index', ['role' => auth()->user()->role, 'jenisKegiatan' => $jenisKegiatan]) }}"
                wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-green-100 text-green-600 flex items-center justify-center mb-3 group-hover:bg-green-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-chart-bar text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Rekap Laporan</h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">Lihat statistik</p>
            </a>

            <!-- Menu 9: Score / Nilai -->
            @if(in_array(auth()->user()->role, ['superadmin', 'dosen', 'prodi', 'kemahasiswaan']))
            <a href="#" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center mb-3 group-hover:bg-yellow-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-star text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Score / Nilai</h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">Kelola nilai</p>
            </a>
            @else

            <!-- Menu 10: Kesehatan -->
            <a href="#" wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-red-100 text-red-600 flex items-center justify-center mb-3 group-hover:bg-red-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-heartbeat text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Kesehatan</h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">
                    @if(auth()->user()->role == 'mahasiswa') Input data
                    @else Pantau
                    @endif
                </p>
            </a>
            @endif

            <!-- Menu 11: Dokumen Kelompok -->
            <a href="{{ route('kegiatan.berkas.index', ['role' => $role, 'jenisKegiatan' => $jenisKegiatan]) }}"
                wire:navigate
                class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 active:scale-95 hover:scale-105">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center mb-3 group-hover:bg-pink-600 group-hover:text-white transition mx-auto md:mx-0">
                    <i class="fas fa-file-pdf text-xl md:text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm md:text-base text-center md:text-left">Dokumen Kelompok
                </h3>
                <p class="text-xs text-gray-400 mt-1 text-center md:text-left hidden md:block">Berkas kelompok</p>
            </a>
        </div>
    </div>
</div>

@push('style')
<style>
/* Touch-friendly untuk mobile */
@media (max-width: 768px) {
    .group {
        min-height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .active\:scale-95:active {
        transform: scale(0.95);
    }
}

/* Hover effect untuk desktop */
@media (min-width: 769px) {
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
}

/* Smooth transition */
.group {
    transition: all 0.2s ease;
}
</style>
@endpush