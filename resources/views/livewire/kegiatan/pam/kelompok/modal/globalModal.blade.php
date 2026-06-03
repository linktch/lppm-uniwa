@if ($modalActive)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(2px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content academic-modal">

                <!-- HEADER -->
                <div class="modal-header academic-modal-header">
                    <h5 class="modal-title">Tambah {{ ucfirst($modalActive) }}</h5>
                    <button type="button" class="btn-close-modal" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <input type="text" wire:model.live="search" wire:focus="$set('showDropdown', true)"
                        class="form-control-modern" placeholder="🔍 Cari {{ $modalActive }}">

                    <!-- DROPDOWN -->
                    @if ($showDropdown && $search)
                        <div class="list-group position-relative mt-1"
                            style="max-height: 250px; overflow-y:auto; z-index: 999;">
                            @forelse($listItems as $item)
                                @php
                                    // Mahasiswa → ambil data dari JSON
                                    $dataMahasiswa =
                                        $modalActive === 'mahasiswa' ? json_decode($item->data_mahasiswa, true) : null;

                                    if ($modalActive === 'mahasiswa') {
                                        $displayName = $dataMahasiswa['nama_mahasiswa'] ?? $item->name;
                                        $subInfo =
                                            ($dataMahasiswa['nim'] ?? '-') .
                                            ' - ' .
                                            ($dataMahasiswa['nama_program_studi'] ?? '-');
                                    } elseif ($modalActive === 'prodi') {
                                        // Kalau modal aktif adalah prodi, misal tampilkan nama program studi
                                        // Nama lengkap + fallback
                                        $displayName = trim(($item->first_name ?? '') . ' ' . ($item->last_name ?? ''));
                                        $displayName = $displayName ?: '-';

                                        // Username
                                        $subInfo = $item->username ?? '-';

                                        // Ambil data prodi dari tabel prodi_fakultas
                                        $prodi = \App\Models\ProdiFakultas::where('id_prodi', $item->id_prodi)->first();
                                        $prodiName = $prodi->nama_program_studi ?? '-';

                                        // Bisa gabungkan info tambahan
                                        $subInfo .= ' - ' . $prodiName;
                                    } else {
                                        // Bukan mahasiswa → tampilkan Nama - Username - Role
                                        $displayName = ($item->first_name ?? '-') . ' ' . ($item->last_name ?? '-');
                                        $subInfo = ($item->username ?? '-') . ' - ' . ($item->role ?? '-');
                                    }
                                @endphp

                                <button type="button" class="list-group-item list-group-item-action text-start"
                                    wire:click="pilihItem({{ $item->id }}, '{{ $modalActive }}', @js($item))">
                                    <strong>{{ $displayName }}</strong><br>
                                    <small>{{ $subInfo }}</small>
                                </button>
                            @empty
                                <div class="list-group-item text-muted">Tidak ditemukan</div>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- FOOTER -->
                <div class="modal-footer academic-modal-footer">
                    <button class="btn-cancel" wire:click="closeModal">Batal</button>
                    <button class="btn-save" wire:click="tambahItem">Simpan</button>
                </div>

            </div>
        </div>
    </div>
@endif
