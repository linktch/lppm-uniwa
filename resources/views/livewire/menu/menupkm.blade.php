<div>
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-600 to-green-400 flex items-center justify-center">
                            <i class="fas fa-flask text-white text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">Manajemen PKM</h4>
                            <p class="text-sm text-gray-500">
                                @if(auth()->user()->role == 'mahasiswa')
                                Dashboard Mahasiswa - Kelola partisipasi PKM Anda
                                @elseif(auth()->user()->role == 'dosen')
                                Dashboard Dosen - Kelola penilaian dan laporan mahasiswa bimbingan
                                @elseif(auth()->user()->role == 'prodi')
                                Dashboard Program Studi - Kelola PKM mahasiswa prodi Anda
                                @else
                                Dashboard Administrator - Kelola seluruh kegiatan Program Kreativitas Mahasiswa
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid 3 Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                
                <!-- Card 1: Timeline -->
                <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-500 to-cyan-400 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-lg"></i>
                            </div>
                            <h3 class="text-white font-semibold text-lg">Timeline PKM</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                            Kelola jadwal dan tenggat waktu kegiatan PKM.
                        </p>
                        <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/timeline") }}"
                            class="block w-full text-center bg-gradient-to-r from-cyan-500 to-cyan-400 hover:from-cyan-600 hover:to-cyan-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                            wire:navigate>
                            <i class="fas fa-arrow-right mr-2"></i> Atur Timeline
                        </a>
                    </div>
                </div>

                <!-- Card 2: Kelola Kelompok -->
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
                            Kelola data kelompok dan anggota PKM.
                        </p>
                         <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/kelompok") }}"
                            class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                            wire:navigate>
                            <i class="fas fa-arrow-right mr-2"></i> Kelola Kelompok
                        </a>
                    </div>
                </div>

                <!-- Card 3: Pendaftaran PKM -->
                <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-teal-500 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fas fa-edit text-white text-lg"></i>
                            </div>
                            <h3 class="text-white font-semibold text-lg">Pendaftaran PKM</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                            Daftarkan diri anda untuk mengikuti program PKM.
                        </p>
                         <a href="{{ url("/{$role}/kegiatan/{$jenisKegiatan}/pendaftaran") }}"
                            class="block w-full text-center bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105"
                            wire:navigate>
                            <i class="fas fa-arrow-right mr-2"></i> Daftar PKM
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>