<div class="p-6">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="p-4 md:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold text-gray-800">Verifikasi Dokumen Pendaftaran</h4>
                        <p class="text-xs md:text-sm text-gray-500">Kelola verifikasi dokumen dan pertanyaan screening KKN</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 Card Screening -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Card Screening Kesehatan -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-base"></i>
                    </div>
                    <h3 class="text-white font-semibold text-base md:text-lg">Screening Kesehatan</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                    Pertanyaan tentang kesehatan peserta KKN
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                        <i class="fas fa-question-circle"></i> {{ $kesehatanCount ?? 0 }} Pertanyaan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                        <i class="fas fa-check-circle"></i> {{ $kesehatanAnswered ?? 0 }} Terjawab
                    </span>
                </div>
                <button class="w-full bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105">
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Screening
                </button>
            </div>
        </div>

        <!-- Card Screening Dokumen (Only for Superadmin) -->
        @if(Auth()->user()->role == 'superadmin')
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-base"></i>
                    </div>
                    <h3 class="text-white font-semibold text-base md:text-lg">Screening Dokumen</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                    Pertanyaan tentang kelengkapan dokumen KKN
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                        <i class="fas fa-question-circle"></i> {{ $dokumenCount ?? 0 }} Pertanyaan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                        <i class="fas fa-check-circle"></i> {{ $dokumenAnswered ?? 0 }} Terjawab
                    </span>
                </div>
                <a wire:navigate href="{{ route('kegiatan.kkn.screening.validasidoc', auth()->user()->role) }}" 
                   class="block w-full bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105 text-center">
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Screening
                </a>
            </div>
        </div>
        @endif

        <!-- Card Screening Hafalan -->
        <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-400 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-quran text-white text-base"></i>
                    </div>
                    <h3 class="text-white font-semibold text-base md:text-lg">Screening Hafalan</h3>
                </div>
            </div>
            <div class="p-5">
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                    Pertanyaan tentang hafalan Al-Qur'an
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                        <i class="fas fa-question-circle"></i> {{ $hafalanCount ?? 0 }} Pertanyaan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                        <i class="fas fa-check-circle"></i> {{ $hafalanAnswered ?? 0 }} Terjawab
                    </span>
                </div>
                <a wire:navigate href="{{ route('kegiatan.kkn.screening.hafalan.index', auth()->user()->role) }}" 
                   class="block w-full bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white font-medium py-2.5 rounded-full transition-all duration-200 hover:scale-105 text-center">
                    <i class="fas fa-arrow-right mr-2"></i> Kelola Screening
                </a>
            </div>
        </div>
    </div>
</div>