<div class="rounded-xl min-h-screen bg-gray-300 pb-20">
    <!-- Header dengan Gradient -->
    <div class="rounded-xl bg-gradient-to-r from-blue-700 to-blue-500 text-white sticky top-0 z-10 shadow-lg">
        <div class="px-4 py-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Screening KKN</h1>
                    <p class="text-xs text-white/80 mt-0.5">Kelola verifikasi dokumen dan pertanyaan screening</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 Card Screening -->
    <div class="px-4 mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            
            <!-- Card Screening Kesehatan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="bg-gradient-to-r from-red-500 to-red-400 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fas fa-heartbeat text-white text-base"></i>
                        </div>
                        <h3 class="text-white font-semibold text-sm md:text-base">Screening Kesehatan</h3>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 text-sm mb-3 leading-relaxed">
                        Pertanyaan tentang kesehatan peserta KKN
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                            <i class="fas fa-question-circle"></i> {{ $kesehatanCount ?? 0 }} Pertanyaan
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                            <i class="fas fa-check-circle"></i> {{ $kesehatanAnswered ?? 0 }} Terjawab
                        </span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-medium py-2 rounded-full text-sm transition">
                        <i class="fas fa-arrow-right mr-2"></i> Kelola
                    </button>
                </div>
            </div>

            <!-- Card Screening Dokumen (Only for Superadmin) -->
            @if(Auth()->user()->role == 'superadmin')
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fas fa-file-alt text-white text-base"></i>
                        </div>
                        <h3 class="text-white font-semibold text-sm md:text-base">Screening Dokumen</h3>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 text-sm mb-3 leading-relaxed">
                        Pertanyaan tentang kelengkapan dokumen KKN
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                            <i class="fas fa-question-circle"></i> {{ $dokumenCount ?? 0 }} Pertanyaan
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                            <i class="fas fa-check-circle"></i> {{ $dokumenAnswered ?? 0 }} Terjawab
                        </span>
                    </div>
                    <a wire:navigate href="{{ route('kegiatan.kkn.screening.validasidoc', auth()->user()->role) }}"
                       class="block w-full bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-medium py-2 rounded-full text-sm transition text-center">
                        <i class="fas fa-arrow-right mr-2"></i> Kelola
                    </a>
                </div>
            </div>
            @endif

            <!-- Card Screening Hafalan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="bg-gradient-to-r from-purple-500 to-purple-400 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fas fa-quran text-white text-base"></i>
                        </div>
                        <h3 class="text-white font-semibold text-sm md:text-base">Screening Hafalan</h3>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 text-sm mb-3 leading-relaxed">
                        Pertanyaan tentang hafalan Al-Qur'an
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                            <i class="fas fa-question-circle"></i> {{ $hafalanCount ?? 0 }} Pertanyaan
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                            <i class="fas fa-check-circle"></i> {{ $hafalanAnswered ?? 0 }} Terjawab
                        </span>
                    </div>
                    <a wire:navigate href="{{ route('kegiatan.screening.hafalan.index', [
                        'role' => auth()->user()->role,
                        'jenisKegiatan' => $jenisKegiatan ?? 'KKN'
                    ]) }}" 
                       class="block w-full bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white font-medium py-2 rounded-full text-sm transition text-center">
                        <i class="fas fa-arrow-right mr-2"></i> Kelola
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Info -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2">
        <div class="flex items-center justify-between text-xs text-gray-400">
            <span>© {{ date('Y') }} LPPM Uniwa</span>
            <span>v1.0</span>
        </div>
    </div>
</div>