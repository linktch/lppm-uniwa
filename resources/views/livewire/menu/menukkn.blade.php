<div class="p-6">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4 md:p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center">
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

    <!-- Grid Cards - Teks menyesuaikan role -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

        <!-- Card 1: Timeline (Hanya Super Admin) -->
        @if(auth()->user()->role == 'superadmin')
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
                    Kelola jadwal dan tenggat waktu kegiatan KKN.
                </p>
                <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/timeline") }}"
                    class="block w-full text-center bg-gradient-to-r from-cyan-500 to-cyan-400 hover:from-cyan-600 hover:to-cyan-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Atur Timeline
                </a>
            </div>
        </div>
        @endif

        <!-- Card 2: Kelola Kelompok (Hanya Super Admin) -->
        @if(auth()->user()->role == 'superadmin')
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
                    Kelola data kelompok dan anggota KKN.
                </p>
                <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/kelompok") }}"
                    class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Kelompok
                </a>
            </div>
        </div>
        @endif

        <!-- Card 3: Screening KKN (Admin, Dosen, Prodi) -->
        @if(in_array(auth()->user()->role, ['superadmin', 'dosen', 'prodi', 'kemahasiswaan']))
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
                    @if(auth()->user()->role == 'dosen')
                    Screening mahasiswa bimbingan Anda.
                    @elseif(auth()->user()->role == 'prodi')
                    Screening mahasiswa prodi Anda.
                    @else
                    Screening dan seleksi peserta KKN.
                    @endif
                </p>
                <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/screening") }}"
                    class="block w-full text-center bg-gradient-to-r from-teal-500 to-teal-400 hover:from-teal-600 hover:to-teal-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Screening
                </a>
            </div>
        </div>
        @endif

        <!-- Card 4: Screening Hafalan (Admin, Dosen, Prodi) -->
        @if(in_array(auth()->user()->role, ['superadmin', 'dosen', 'prodi', 'kemahasiswaan']))
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-book-quran text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Screening Hafalan</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    @if(auth()->user()->role == 'dosen')
                    Penilaian hafalan mahasiswa bimbingan.
                    @elseif(auth()->user()->role == 'prodi')
                    Penilaian hafalan mahasiswa prodi Anda.
                    @else
                    Kelola penilaian hafalan Al-Qur'an peserta KKN.
                    @endif
                </p>
                <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/screening/hafalan") }}"
                    class="block w-full text-center bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Hafalan
                </a>
            </div>
        </div>
        @endif

        <!-- Card 5: Laporan Harian / Laporan Mahasiswa (Semua Role) -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">
                        @if(auth()->user()->role == 'mahasiswa') Laporan Harian
                        @else Laporan Mahasiswa
                        @endif
                    </h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    @if(auth()->user()->role == 'mahasiswa')
                    Buat dan kirim laporan harian kegiatan KKN Anda.
                    @elseif(auth()->user()->role == 'dosen')
                    Review laporan harian mahasiswa bimbingan Anda.
                    @elseif(auth()->user()->role == 'prodi')
                    Review laporan harian mahasiswa prodi Anda.
                    @else
                    Review laporan harian seluruh mahasiswa KKN.
                    @endif
                </p>
                <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/laporan-harian") }}"
                    class="block w-full text-center bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i>
                    @if(auth()->user()->role == 'mahasiswa') Buat Laporan
                    @else Review Laporan
                    @endif
                </a>
            </div>
        </div>

        <!-- Card 6: Dokumen / Dokumen Saya (Mahasiswa & Admin) -->
        @if(in_array(auth()->user()->role, ['superadmin', 'mahasiswa']))
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-folder-open text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">
                        @if(auth()->user()->role == 'mahasiswa') Dokumen Saya
                        @else Dokumen KKN
                        @endif
                    </h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    @if(auth()->user()->role == 'mahasiswa')
                    Kelola dokumen persyaratan KKN Anda.
                    @else
                    Kelola dokumen persyaratan seluruh mahasiswa KKN.
                    @endif
                </p>
                <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/dokumen") }}"
                    class="block w-full text-center bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Dokumen
                </a>
            </div>
        </div>
        @endif

        <!-- Card 7: Pendaftaran KKN (Mahasiswa & Admin) -->
        @if(in_array(auth()->user()->role, ['superadmin', 'mahasiswa']))
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">
                        @if(auth()->user()->role == 'mahasiswa') Pendaftaran KKN
                        @else Manajemen Pendaftaran
                        @endif
                    </h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    @if(auth()->user()->role == 'mahasiswa')
                    Daftarkan diri Anda untuk mengikuti program KKN.
                    @else
                    Kelola pendaftaran mahasiswa peserta KKN.
                    @endif
                </p>
                <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/pendaftaran") }}"
                    class="block w-full text-center bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i>
                    @if(auth()->user()->role == 'mahasiswa') Daftar KKN
                    @else Kelola Pendaftaran
                    @endif
                </a>
            </div>
        </div>
        @endif

        <!-- Card 8: Rekap Laporan (Semua Role) -->
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
                    @if(auth()->user()->role == 'mahasiswa')
                    Lihat rekap laporan harian yang telah Anda buat.
                    @elseif(auth()->user()->role == 'dosen')
                    Lihat rekap laporan mahasiswa bimbingan Anda.
                    @elseif(auth()->user()->role == 'prodi')
                    Lihat rekap laporan mahasiswa prodi Anda.
                    @else
                    Lihat rekap laporan seluruh mahasiswa KKN.
                    @endif
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Lihat Rekap
                </a>
            </div>
        </div>

        <!-- Card 9: Score / Nilai (Admin, Dosen, Prodi) -->
        @if(in_array(auth()->user()->role, ['superadmin', 'dosen', 'prodi', 'kemahasiswaan']))
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-star text-white text-lg"></i>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Score / Nilai</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                    @if(auth()->user()->role == 'dosen')
                    Kelola penilaian mahasiswa bimbingan Anda.
                    @elseif(auth()->user()->role == 'prodi')
                    Kelola penilaian mahasiswa prodi Anda.
                    @else
                    Kelola penilaian akhir seluruh mahasiswa KKN.
                    @endif
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-600 hover:to-yellow-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Nilai
                </a>
            </div>
        </div>
        @endif

        <!-- Card 10: Kesehatan Mahasiswa (Semua Role) -->
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
                    @if(auth()->user()->role == 'mahasiswa')
                    Input dan pantau data kesehatan Anda selama KKN.
                    @elseif(auth()->user()->role == 'dosen')
                    Pantau data kesehatan mahasiswa bimbingan Anda.
                    @elseif(auth()->user()->role == 'prodi')
                    Pantau data kesehatan mahasiswa prodi Anda.
                    @else
                    Pantau data kesehatan seluruh mahasiswa peserta KKN.
                    @endif
                </p>
                <a href="#"
                    class="block w-full text-center bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                    wire:navigate>
                    <i class="fas fa-arrow-right mr-2"></i>
                    @if(auth()->user()->role == 'mahasiswa') Kelola Kesehatan
                    @else Pantau Kesehatan
                    @endif
                </a>
            </div>
        </div>

    </div>
</div>