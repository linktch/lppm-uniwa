import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

// Event listener untuk SweetAlert
window.addEventListener('swal', (event) => {
    Swal.fire(event.detail);
});

// Livewire event listener
document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal', (data) => {
        Swal.fire(data);
    });
});