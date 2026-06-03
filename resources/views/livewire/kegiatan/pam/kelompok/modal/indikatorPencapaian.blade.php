@if ($modalActivePAM)
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
