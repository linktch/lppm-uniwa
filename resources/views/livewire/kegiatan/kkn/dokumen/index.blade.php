<div>
    <div class="p-6">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-indigo-400 flex items-center justify-center">
                                <i class="fas fa-folder-open text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">Dokumen Saya</h4>
                                <p class="text-sm text-gray-500">Lihat dokumen persyaratan KKN</p>
                            </div>
                        </div>
                        <button wire:click="back" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    <p class="text-sm text-blue-800">Berikut adalah dokumen persyaratan KKN yang telah Anda upload.</p>
                </div>
            </div>

            <!-- Tabel Dokumen -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-500 flex items-center justify-center">
                            <i class="fas fa-list text-white text-sm"></i>
                        </div>
                        <h5 class="font-semibold text-gray-800">Daftar Dokumen Persyaratan</h5>
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded-full">3 Dokumen</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Dokumen</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            
                            <!-- Baris 1: Surat -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-500">1</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-envelope text-purple-600 text-sm"></i>
                                        </div>
                                        <span class="font-medium text-gray-800">Surat Pengantar</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($screeningFile && $screeningFile->status_surat)
                                        @if($screeningFile->status_surat == 'valid')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                                <i class="fas fa-check-circle"></i> Valid
                                            </span>
                                        @elseif($screeningFile->status_surat == 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                                <i class="fas fa-clock"></i> Menunggu
                                            </span>
                                        @endif
                                    @elseif($existing_surat)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                            <i class="fas fa-clock"></i> Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-500">
                                            <i class="fas fa-minus-circle"></i> Belum Upload
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    @if($screeningFile && $screeningFile->keterangan_surat)
                                        {{ $screeningFile->keterangan_surat }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($existing_surat)
                                        <button wire:click="downloadFile('{{ $existing_surat }}', 'surat')" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Baris 2: KTP -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-500">2</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-id-card text-indigo-600 text-sm"></i>
                                        </div>
                                        <span class="font-medium text-gray-800">KTP / Identitas Diri</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($screeningFile && $screeningFile->status_ktp)
                                        @if($screeningFile->status_ktp == 'valid')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                                <i class="fas fa-check-circle"></i> Valid
                                            </span>
                                        @elseif($screeningFile->status_ktp == 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                                <i class="fas fa-clock"></i> Menunggu
                                            </span>
                                        @endif
                                    @elseif($existing_ktp)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                            <i class="fas fa-clock"></i> Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-500">
                                            <i class="fas fa-minus-circle"></i> Belum Upload
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    @if($screeningFile && $screeningFile->keterangan_ktp)
                                        {{ $screeningFile->keterangan_ktp }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($existing_ktp)
                                        <button wire:click="downloadFile('{{ $existing_ktp }}', 'ktp')" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                             </tr>

                            <!-- Baris 3: SPP / Pembayaran -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-500">3</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-money-bill-wave text-green-600 text-sm"></i>
                                        </div>
                                        <span class="font-medium text-gray-800">Bukti Pembayaran (SPP)</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($screeningFile && $screeningFile->status_spp)
                                        @if($screeningFile->status_spp == 'valid')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                                <i class="fas fa-check-circle"></i> Valid
                                            </span>
                                        @elseif($screeningFile->status_spp == 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                                <i class="fas fa-clock"></i> Menunggu
                                            </span>
                                        @endif
                                    @elseif($existing_spp)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                            <i class="fas fa-clock"></i> Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-500">
                                            <i class="fas fa-minus-circle"></i> Belum Upload
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    @if($screeningFile && $screeningFile->keterangan_spp)
                                        {{ $screeningFile->keterangan_spp }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($existing_spp)
                                        <button wire:click="downloadFile('{{ $existing_spp }}', 'spp')" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                             </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Ringkasan Status -->
                @if($screeningFile)
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-4 text-xs">
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-check-circle text-green-500"></i> Valid
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-clock text-yellow-500"></i> Menunggu Verifikasi
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-times-circle text-red-500"></i> Ditolak
                            </span>
                        </div>
                        <div class="text-xs text-gray-400">
                            @if($screeningFile->isAllValid())
                                <span class="text-green-600 font-medium">✅ Semua dokumen valid</span>
                            @elseif($screeningFile->hasRejected())
                                <span class="text-red-600 font-medium">⚠️ Ada dokumen yang ditolak, silakan perbaiki</span>
                            @else
                                <span class="text-yellow-600 font-medium">⏳ Menunggu verifikasi dokumen</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>