<script data-navigate-once src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script data-navigate-once src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script data-navigate-once src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script data-navigate-once src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script data-navigate-once>
    document.addEventListener('livewire:init', () => {
        console.log('Livewire aktif ✅');
    });
</script>
<script data-navigate-once>
    function copyToClipboard() {
        const rawData = document.getElementById('rawData');
        const range = document.createRange();
        range.selectNode(rawData);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();

        // Alert copy success
        alert('Raw data copied to clipboard!');
    }
</script>

<script data-navigate-once>
    window.addEventListener('swal', event => {
        let data = event.detail[0]; // 🔥 INI KUNCINYA

        Swal.fire({
            icon: data.icon,
            title: data.title,
            text: data.text,
        });
    });
</script>

<script data-navigate-once>
    window.addEventListener('confirmDelete', event => {
        let data = event.detail;

        console.log('CLICK DELETE', data);

        Swal.fire({
            title: 'Yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                // ✅ LIVEWIRE V3 WAY
                Livewire.dispatch('deleteAction', {
                    type: data.type,
                    id: data.id
                });

            }
        });
    });
</script>


