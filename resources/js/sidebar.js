document.addEventListener('alpine:init', () => {
    Alpine.data('sidebarState', () => ({
        sidebarOpen: true,
        
        initSidebar() {
            const savedState = localStorage.getItem('sidebarOpen');
            
            if (savedState !== null) {
                this.sidebarOpen = JSON.parse(savedState);
            } else {
                this.sidebarOpen = window.innerWidth >= 1024;
            }
            
            this.$watch('sidebarOpen', (value) => {
                localStorage.setItem('sidebarOpen', JSON.stringify(value));
            });
            
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    this.sidebarOpen = true;
                    localStorage.setItem('sidebarOpen', JSON.stringify(true));
                }
            });
        }
    }));
});