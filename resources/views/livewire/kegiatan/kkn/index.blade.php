<div class="p-6">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4 md:p-5">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg md:text-xl font-semibold text-gray-800">Manajemen KKN</h4>
                    <p class="text-xs md:text-sm text-gray-500">
                        @if(auth()->user()->role == 'mahasiswa')
                        Dashboard Mahasiswa - Kelola partisipasi KKN Anda
                        @elseif(auth()->user()->role == 'dosen')
                        Dashboard Dosen - Kelola penilaian dan laporan mahasiswa bimbingan
                        @elseif(auth()->user()->role == 'prodi')
                        Dashboard Program Studi - Kelola KKN mahasiswa prodi Anda
                        @else
                        Dashboard Administrator - Kelola seluruh kegiatan Kuliah Kerja Nyata
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

        @if(auth()->user()->role == 'mahasiswa')
        {{-- TAMPILAN UNTUK MAHASISWA --}}

        <!-- Card Pendaftaran KKN -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Pendaftaran KKN</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Daftarkan diri anda untuk mengikuti program KKN.
                </p>
                <a href="{{ route('kegiatan.kkn.registration.index', auth()->user()->role) }}"
                    class="block w-full text-center bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Daftar KKN
                </a>
            </div>
        </div>

        <!-- Card Laporan Harian -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Laporan Harian</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Buat dan kirim laporan harian kegiatan KKN anda.
                </p>
                <a href="{{ route('kegiatan.kkn.laporanharian.index', ['role' => auth()->user()->role]) }}"
                    class="block w-full text-center bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Buat Laporan
                </a>
            </div>
        </div>

        <!-- Card Rekap Laporan -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Rekap Laporan</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Lihat rekap laporan harian yang telah anda buat.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Lihat Rekap
                </a>
            </div>
        </div>

        <!-- Card Kesehatan Mahasiswa -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Kesehatan Mahasiswa</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Input dan pantau data kesehatan anda selama KKN.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Kesehatan
                </a>
            </div>
        </div>

        @elseif(auth()->user()->role == 'dosen')
        {{-- TAMPILAN UNTUK DOSEN --}}

        <!-- Card Laporan Mahasiswa -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Laporan Mahasiswa</h3>
                </div>
            </div>
            @php
            $role = auth()->user()->role;
            @endphp

            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Review laporan harian kegiatan KKN mahasiswa bimbingan Anda.
                </p>
                <a href="{{ route('kegiatan.kkn.laporanharian.index', $role) }}" wire:navigate
                    class="block w-full text-center bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105">
                    <i class="fas fa-arrow-right mr-2"></i> Review Laporan
                </a>
            </div>
        </div>

        <!-- Card Rekap Laporan -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Rekap Laporan</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Lihat rekap laporan harian, mingguan, dan bulanan mahasiswa bimbingan.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Lihat Rekap
                </a>
            </div>
        </div>

        <!-- Card Score / Nilai -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-star text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Score / Nilai</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Kelola penilaian dan scoring akhir mahasiswa bimbingan Anda.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Nilai
                </a>
            </div>
        </div>

        <!-- Card Kesehatan Mahasiswa -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Kesehatan Mahasiswa</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Pantau data kesehatan mahasiswa bimbingan Anda.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Pantau Kesehatan
                </a>
            </div>
        </div>

        @elseif(auth()->user()->role == 'prodi')
        {{-- TAMPILAN UNTUK PRODI --}}

        <!-- Card Screening KKN -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Screening KKN</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Lakukan screening dan seleksi mahasiswa prodi Anda sebelum pemberangkatan.
                </p>
                <a href="{{ route('kegiatan.kkn.screening.index', auth()->user()->role) }}"
                    class="block w-full text-center bg-gradient-to-r from-blue-500 to-blue-400 hover:from-blue-600 hover:to-blue-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Screening
                </a>
            </div>
        </div>

        <!-- Card Laporan Mahasiswa -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Laporan Mahasiswa</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Review laporan harian kegiatan KKN mahasiswa prodi Anda.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Review Laporan
                </a>
            </div>
        </div>

        <!-- Card Rekap Laporan -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Rekap Laporan</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Lihat rekap laporan harian, mingguan, dan bulanan mahasiswa prodi Anda.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Lihat Rekap
                </a>
            </div>
        </div>

        <!-- Card Score / Nilai -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-star text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Score / Nilai</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Kelola penilaian dan scoring akhir mahasiswa prodi Anda.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Nilai
                </a>
            </div>
        </div>

        <!-- Card Kesehatan Mahasiswa -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Kesehatan Mahasiswa</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Pantau data kesehatan mahasiswa prodi Anda.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Pantau Kesehatan
                </a>
            </div>
        </div>

        @else
        {{-- TAMPILAN UNTUK ADMIN / SUPERADMIN --}}

        <!-- Card Pengaturan Timeline -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Pengaturan Timeline</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Kelola jadwal, tenggat waktu pendaftaran, pelaksanaan, dan laporan KKN.
                </p>
                <a href="{{ route('kegiatan.kkn.timeline.index', auth()->user()->role) }}"
                    class="block w-full text-center bg-gradient-to-r from-cyan-500 to-cyan-400 hover:from-cyan-600 hover:to-cyan-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Atur Timeline
                </a>
            </div>
        </div>

        <!-- Card Kelola Kelompok -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Kelola Kelompok</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Kelola data kelompok KKN, anggota kelompok, dan pembagian tugas.
                </p>
                <a href="{{ route('kegiatan.kkn.kelompok.index', auth()->user()->role) }}"
                    class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Kelompok
                </a>
            </div>
        </div>

        <!-- Card Screening KKN -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Screening KKN</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Lakukan screening dan seleksi peserta KKN sebelum pemberangkatan.
                </p>
                <a href="{{ route('kegiatan.kkn.screening.index', auth()->user()->role) }}"
                    class="block w-full text-center bg-gradient-to-r from-teal-500 to-teal-400 hover:from-teal-600 hover:to-teal-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Screening
                </a>
            </div>
        </div>

        <!-- Card Laporan Mahasiswa -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Laporan Mahasiswa</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Review laporan harian kegiatan KKN mahasiswa.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Review Laporan
                </a>
            </div>
        </div>

        <!-- Card Rekap Laporan -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Rekap Laporan</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Lihat rekap laporan harian, mingguan, dan bulanan mahasiswa.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Lihat Rekap
                </a>
            </div>
        </div>

        <!-- Card Score / Nilai -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-star text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Score / Nilai</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Kelola penilaian dan scoring akhir mahasiswa KKN.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Nilai
                </a>
            </div>
        </div>

        <!-- Card Kesehatan Mahasiswa -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Kesehatan Mahasiswa</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    Pantau data kesehatan dan riwayat kesehatan mahasiswa peserta KKN.
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Kesehatan
                </a>
            </div>
        </div>

        @endif
    </div>
</div>