// Academic UI System - Modal Handler
document.addEventListener('DOMContentLoaded', function() {
    // Handle modal close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal-custom');
            modals.forEach(modal => {
                if (modal.style.display !== 'none') {
                    const closeBtn = modal.querySelector('.modal-custom-close');
                    if (closeBtn) closeBtn.click();
                }
            });
        }
    });
    
    // Handle click outside modal to close
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-custom')) {
            const closeBtn = e.target.querySelector('.modal-custom-close');
            if (closeBtn) closeBtn.click();
        }
    });
});

// Livewire specific
if (window.Livewire) {
    Livewire.hook('element.updated', (el, component) => {
        // Auto cleanup modal backdrop
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    });
}