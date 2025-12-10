window.showSuccess = function (message, config = {}) {
    Swal.fire({
        title: message.title || 'Berhasil!',
        text: message.text || '',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        ...config
    });
}

window.showLoading = function (title = 'Memproses...', text = 'Mohon tunggu sebentar.') {
    Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

window.confirmLogout = function () {
    Swal.fire({
        title: 'Yakin mau logout?',
        text: "Kamu akan keluar dari akun ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, logout',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            window.showLoading('Sedang Logout...', 'Sampai jumpa lagi!');

            // Create and submit logout form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

window.confirmDelete = function (articleId) {
    console.log('confirmDelete called with id:', articleId);
    Swal.fire({
        title: 'Yakin ingin menghapus artikel ini?',
        text: 'Artikel yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            window.showLoading('Menghapus...', 'Sedang menghapus artikel.');
            console.log('Dispatching deleteArticleConfirmed for id:', articleId);
            Livewire.dispatch('deleteArticleConfirmed', articleId);
        }
    });
}

window.confirmToggleUser = function (userId, action) {
    const isBan = action === 'ban';

    Swal.fire({
        title: isBan ? 'Blokir pengguna ini?' : 'Aktifkan pengguna ini?',
        text: "Tindakan ini akan mengubah status akun pengguna.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: isBan ? 'Ya, Blokir!' : 'Ya, Aktifkan!',
        confirmButtonColor: isBan ? '#d33' : '#28a745',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            window.showLoading(isBan ? 'Memblokir...' : 'engaktifkan...', 'Sedang memperbarui status pengguna.');
            Livewire.dispatch('toggleUserStatus', { id: userId });
        }
    });
}

window.confirmDeleteCategory = function (categoryId) {
    Swal.fire({
        title: 'Yakin mau hapus kategori ini?',
        text: "Kategori yang sudah dihapus tidak bisa dipulihkan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            window.showLoading('Menghapus...', 'Sedang menghapus kategori.');
            Livewire.dispatch('deleteCategoryConfirmed', { id: categoryId });
        }
    });
}

window.confirmToggleStatus = function (articleId, action) {
    Swal.fire({
        title: action === 'banned' ? 'Blokir artikel ini?' : 'Aktifkan artikel ini?',
        text: "Tindakan ini akan mengubah status artikel.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: action === 'banned' ? 'Ya, Blokir!' : 'Ya, Aktifkan!',
        confirmButtonColor: action === 'banned' ? '#d33' : '#28a745',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            window.showLoading('Memproses...', 'Sedang memperbarui status artikel.');
            Livewire.dispatch('toggleStatus', { id: articleId });
        }
    });
}

window.confirmToggle = function (commentId, hide) {
    Swal.fire({
        title: hide ? 'Sembunyikan komentar?' : 'Tampilkan komentar?',
        text: hide
            ? 'Komentar tidak akan tampil di publik'
            : 'Komentar akan ditampilkan kembali ke publik',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: hide ? '#d33' : '#2563EB',
        cancelButtonColor: '#6c757d',
        confirmButtonText: hide ? 'Ya, sembunyikan!' : 'Ya, tampilkan!',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            window.showLoading('Memproses...', 'Sedang memperbarui status komentar.');
            Livewire.dispatch('toggleCommentConfirmed', { commentId });
        }
    });
}

window.initAlerts = function () {
    if (typeof Livewire === 'undefined') {
        console.warn('Livewire is not defined yet.');
        return;
    }

    Livewire.on('userStatusUpdated', msg => showSuccess({ text: msg }));
    Livewire.on('categoryDeleted', msg => showSuccess({ text: msg }));
    Livewire.on('statusUpdated', msg => showSuccess({ text: msg }));

    // Sukses based on session
    const events = {
        article_created: { title: 'Berhasil!', text: 'Artikel berhasil diterbitkan.' },
        category_created: { title: 'Kategori dibuat!', text: 'Kategori berhasil ditambahkan.' },
        success: { title: 'Berhasil', text: window.sessionSuccess },
        article_deleted: { title: 'Berhasil!', text: 'Artikel berhasil dihapus.' }
    };

    Object.keys(events).forEach(key => {
        if (window[key]) {
            showSuccess(events[key]);
            window[key] = null;
        }
    });
};

// Init saat pertama kali load (jika Livewire sudah siap)
document.addEventListener('livewire:init', () => {
    console.log('Livewire initialized, initializing alerts...');
    window.initAlerts();
});

// Init ulang saat navigasi selesai
document.addEventListener('livewire:navigated', () => {
    console.log('Livewire navigated.');
    console.log('Checking window.confirmLogout:', typeof window.confirmLogout);
    console.log('Checking Swal:', typeof Swal);
    window.initAlerts();
});

// Jalankan langsung jika Livewire sudah siap (misalnya script di-load belakangan)
if (window.Livewire) {
    console.log('Livewire already loaded, initializing alerts immediately...');
    window.initAlerts();
}
