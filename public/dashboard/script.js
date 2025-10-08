document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('.sidebar__link');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            // Cek jika yang diklik bukan link logout
            if (!this.parentElement.parentElement.classList.contains('sidebar__footer')) {
                event.preventDefault(); // Mencegah pindah halaman untuk demo
                
                // Hapus class 'active' dari semua link
                sidebarLinks.forEach(l => l.classList.remove('active'));
                
                // Tambahkan class 'active' ke link yang diklik
                this.classList.add('active');
            }
        });
    });
});