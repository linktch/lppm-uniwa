<div>
    <div class="p-6">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
           <!-- Tombol Aksi -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="p-5">
        <div class="flex justify-between items-center gap-3">
            <!-- Bagian Kiri: Tombol Kembali -->
            <button wire:click="back"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            
            <!-- Bagian Kanan: Tombol Aksi -->
            <div class="flex gap-3">
                @if(Auth::user()->role != 'mahasiswa')
                    <button wire:click="toggleReviewForm"
                            class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white rounded-lg text-sm font-medium transition">
                        <i class="fas fa-star mr-1"></i> Review Laporan
                    </button>
                @endif
                
                @if(Auth::user()->role == 'mahasiswa' && $data['status'] == 'revisi')
                    <a href="{{ route('kegiatan.kkn.laporanharian.update', ['role' => auth()->user()->role, 'id' => $data['id']]) }}" 
                       wire:navigate
                       class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white rounded-lg text-sm font-medium transition">
                        <i class="fas fa-undo mr-1"></i> Revisi Laporan
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

            <!-- Data Laporan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-700 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        <h5 class="font-semibold text-gray-800">Data Laporan</h5>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Informasi Dasar -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Tanggal Laporan</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ isset($data['tanggal']) ? Carbon\Carbon::parse($data['tanggal'])->format('d/m/Y H:i') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Mahasiswa</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $data['mahasiswa']['nama_mahasiswa'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">NIM</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $data['mahasiswa']['nim'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Status</p>
                            @php
                            $status = $data['status'] ?? 'draft';
                            $statusClass = '';
                            $statusIcon = '';
                            $statusText = '';

                            switch ($status) {
                            case 'draft':
                            $statusClass = 'bg-gray-100 text-gray-700';
                            $statusIcon = 'fa-pen-fancy';
                            $statusText = 'Draft';
                            break;
                            case 'submitted':
                            $statusClass = 'bg-blue-100 text-blue-700';
                            $statusIcon = 'fa-paper-plane';
                            $statusText = 'Submitted';
                            break;
                            case 'revisi':
                            $statusClass = 'bg-yellow-100 text-yellow-700';
                            $statusIcon = 'fa-undo-alt';
                            $statusText = 'Revisi';
                            break;
                            case 'approved':
                            $statusClass = 'bg-green-100 text-green-700';
                            $statusIcon = 'fa-check-circle';
                            $statusText = 'Approved';
                            break;
                            case 'rejected':
                            $statusClass = 'bg-red-100 text-red-700';
                            $statusIcon = 'fa-times-circle';
                            $statusText = 'Rejected';
                            break;
                            default:
                            $statusClass = 'bg-gray-100 text-gray-700';
                            $statusIcon = 'fa-pen-fancy';
                            $statusText = ucfirst($status);
                            }
                            @endphp
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }}"></i> {{ $statusText }}
                            </span>
                        </div>
                    </div>

                    <!-- Foto & Narasi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                <i class="fas fa-camera mr-1"></i> Dokumentasi Foto
                            </p>
                            <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-200">
                                @if($data['foto'])
                                <img src="{{ Storage::url($data['foto']) }}" alt="Foto Kegiatan"
                                    class="rounded-lg max-w-full max-h-64 mx-auto object-cover">
                                <a href="{{ Storage::url($data['foto']) }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs text-blue-500 hover:underline mt-2">
                                    <i class="fas fa-expand"></i> Lihat Fullsize
                                </a>
                                @else
                                <div class="py-8">
                                    <i class="fas fa-image text-gray-300 text-4xl mb-2 block"></i>
                                    <p class="text-gray-400 text-sm">Belum ada foto</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                <i class="fas fa-align-left mr-1"></i> Narasi Kegiatan
                            </p>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 max-h-64 overflow-y-auto">
                                <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">
                                    {!! $data['aktivitas'] ?? '-'!!}
                                </p>
                                <p class="text-right text-xs text-gray-400 mt-2">
                                    <i class="fas fa-keyboard"></i> {{ strlen($data['aktivitas'] ?? '') }} karakter
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Section -->
            @if($reviews && $reviews->count() > 0)
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-white">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-r from-orange-500 to-orange-400 flex items-center justify-center">
                            <i class="fas fa-star text-white text-sm"></i>
                        </div>
                        <h5 class="font-semibold text-gray-800">Review Laporan</h5>
                        <span
                            class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">{{ $reviews->count() }}
                            Review</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($reviews as $review)
                    <div class="bg-gray-50 rounded-xl p-5">
                        <!-- Header Review -->
                        <div
                            class="flex flex-wrap items-center justify-between gap-3 mb-3 pb-3 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white
                        {{ $review->role == 'mitra' ? 'bg-gradient-to-r from-cyan-600 to-cyan-400' : '' }}
                        {{ $review->role == 'prodi' ? 'bg-gradient-to-r from-blue-700 to-blue-500' : '' }}
                        {{ $review->role == 'kemahasiswaan' ? 'bg-gradient-to-r from-orange-500 to-orange-400' : '' }}
                        {{ $review->role == 'superadmin' ? 'bg-gradient-to-r from-red-600 to-red-500' : '' }}
                        {{ $review->role == 'dosen' ? 'bg-gradient-to-r from-purple-600 to-purple-400' : '' }}
                        {{ $review->role == 'admin' ? 'bg-gradient-to-r from-gray-600 to-gray-500' : '' }}
                        {{ $review->role == 'mahasiswa' ? 'bg-gradient-to-r from-green-600 to-green-400' : '' }}">
                                    <i class="fas 
                            {{ $review->role == 'mitra' ? 'fa-handshake' : '' }}
                            {{ $review->role == 'prodi' ? 'fa-university' : '' }}
                            {{ $review->role == 'kemahasiswaan' ? 'fa-user-friends' : '' }}
                            {{ $review->role == 'superadmin' ? 'fa-crown' : '' }}
                            {{ $review->role == 'dosen' ? 'fa-chalkboard-user' : '' }}
                            {{ $review->role == 'admin' ? 'fa-user-shield' : '' }}
                            {{ $review->role == 'mahasiswa' ? 'fa-user-graduate' : '' }}"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">Review dari
                                        {{ ucfirst($review->role) }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->user->first_name ?? '-' }}</p>
                                </div>
                            </div>
                            @if($review->status)
                            <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                    {{ $review->status == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $review->status == 'revisi' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $review->status == 'submitted' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $review->status == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                                <i class="fas 
                        {{ $review->status == 'approved' ? 'fa-check-circle' : '' }}
                        {{ $review->status == 'revisi' ? 'fa-undo-alt' : '' }}
                        {{ $review->status == 'submitted' ? 'fa-paper-plane' : '' }}
                        {{ $review->status == 'ditolak' ? 'fa-times-circle' : '' }}"></i>
                                {{ ucfirst($review->status) }}
                            </div>
                            @endif
                        </div>

                        <!-- Komentar Review -->
                        <div class="mb-3">
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $review->komentar }}</p>
                        </div>

                        <!-- Tanggal -->
                        <div class="text-right">
                            <p class="text-xs text-gray-400">
                                <i class="fas fa-clock mr-1"></i> {{ $review->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <!-- Balasan (Replies) -->
                        @if($review->replies && $review->replies->count() > 0)
                        @foreach($review->replies as $reply)
                        <div class="mt-3 ml-8 pl-3 border-l-2 border-yellow-400">
                            <div class="flex items-center gap-2 mb-2">
                                <div
                                    class="w-7 h-7 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                                    <i class="fas fa-reply text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700 text-xs">Balasan dari
                                        {{ ucfirst($reply->role) }}</p>
                                    <p class="text-xs text-gray-500">{{ $reply->user->name ?? '-' }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">{{ $reply->komentar }}</p>
                            <p class="text-right text-xs text-gray-400 mt-1">
                                <i class="fas fa-clock mr-1"></i> {{ $reply->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        @endforeach
                        @endif

                        <!-- Form Balasan untuk SEMUA ROLE KECUALI MAHASISWA -->
                        @if(Auth::user()->role != 'mahasiswa')
                        @if($replyReviewId == $review->id)
                        <div class="mt-3 ml-8">
                            <textarea wire:model="replyText" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Tulis balasan Anda..."></textarea>
                            <div class="flex justify-end gap-2 mt-2">
                                <button wire:click="setReply(null)"
                                    class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs transition">
                                    Batal
                                </button>
                                <button wire:click="sendReply({{ $review->id }})"
                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs transition">
                                    <i class="fas fa-paper-plane"></i> Kirim
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="text-right mt-2">
                            <button wire:click="setReply({{ $review->id }})"
                                class="text-xs text-blue-500 hover:text-blue-700">
                                <i class="fas fa-reply"></i> Balas Review
                            </button>
                        </div>
                        @endif
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tombol Aksi untuk Role Selain Mahasiswa -->
            @if(Auth::user()->role != 'mahasiswa')
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-5">
                    <div class="flex justify-end gap-3">
                        <button wire:click="back"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </button>
                        <button wire:click="toggleReviewForm"
                            class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white rounded-lg text-sm font-medium transition">
                            <i class="fas fa-star mr-1"></i> Review Laporan
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tombol Revisi untuk Mahasiswa -->
            @if(Auth::user()->role == 'mahasiswa' && $data['status'] == 'revisi')
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-5">
                    <div class="flex justify-end">
                        <a href="{{ route('kegiatan.kkn.laporanharian.update', ['role' => auth()->user()->role, 'id' => $data['id']]) }}"
                            wire:navigate
                            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition">
                            <i class="fas fa-undo mr-1"></i> Revisi Laporan
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Review Laporan -->
            @if($showReviewForm)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-500 to-orange-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fas fa-star text-white text-sm"></i>
                            </div>
                            <h5 class="font-semibold text-white">Form Review Laporan</h5>
                        </div>
                        <button wire:click="toggleReviewForm" class="text-white hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Status Review (hanya untuk review utama, bukan balasan) -->
                    @if(!$parentId)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Status Review <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="reviewStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Pilih Status</option>
                            <option value="approved">✅ Disetujui</option>
                            <option value="revisi">🔄 Revisi</option>
                            <option value="ditolak">❌ Ditolak</option>
                        </select>
                        @error('reviewStatus')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Komentar Review -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Komentar / Catatan Review <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="reviewKomentar" rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Tuliskan komentar, masukan, atau catatan review untuk mahasiswa..."></textarea>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fas fa-info-circle"></i>
                            @if($reviewStatus == 'approved')
                            Berikan apresiasi dan masukan untuk kebaikan kedepannya
                            @elseif($reviewStatus == 'revisi')
                            Jelaskan bagian mana yang perlu direvisi secara spesifik
                            @elseif($reviewStatus == 'ditolak')
                            Berikan alasan yang jelas mengapa laporan ditolak
                            @else
                            Berikan komentar yang konstruktif untuk mahasiswa
                            @endif
                        </p>
                        @error('reviewKomentar')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end gap-3">
                        <button wire:click="toggleReviewForm"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button wire:click="submitReview" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white rounded-lg text-sm font-medium transition">
                            <span wire:loading.remove><i class="fas fa-paper-plane mr-1"></i> Kirim Review</span>
                            <span wire:loading><i class="fas fa-spinner fa-pulse mr-1"></i> Mengirim...</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>