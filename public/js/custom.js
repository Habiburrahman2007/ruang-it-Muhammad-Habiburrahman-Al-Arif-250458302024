
function initializeHamburger() {
    const burgerBtn = document.querySelector('.burger-btn');
    const sidebar = document.getElementById('sidebar');
    const sidebarHide = document.querySelector('.sidebar-hide');

    if (burgerBtn && sidebar) {
        const newBurgerBtn = burgerBtn.cloneNode(true);
        burgerBtn.parentNode.replaceChild(newBurgerBtn, burgerBtn);

        newBurgerBtn.addEventListener('click', function (e) {
            e.preventDefault();
            sidebar.classList.toggle('active');

            let backdrop = document.querySelector('.sidebar-backdrop');
            if (sidebar.classList.contains('active')) {
                if (!backdrop) {
                    backdrop = document.createElement('div');
                    backdrop.classList.add('sidebar-backdrop');
                    backdrop.addEventListener('click', function () {
                        sidebar.classList.remove('active');
                        this.remove();
                    });
                    document.body.appendChild(backdrop);
                }
            } else {
                if (backdrop) {
                    backdrop.remove();
                }
            }
        });
    }

    if (sidebarHide && sidebar) {
        const newSidebarHide = sidebarHide.cloneNode(true);
        sidebarHide.parentNode.replaceChild(newSidebarHide, sidebarHide);

        newSidebarHide.addEventListener('click', function (e) {
            e.preventDefault();
            sidebar.classList.remove('active');
            const backdrop = document.querySelector('.sidebar-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', initializeHamburger);

document.addEventListener('livewire:navigated', initializeHamburger);

document.addEventListener('livewire:navigated', () => {
    Livewire.dispatch('refreshComponent');
});

document.addEventListener('livewire:navigate', () => {
    const loader = document.getElementById('global-loader');
    if (loader) loader.style.display = 'flex';
});

document.addEventListener('livewire:navigated', () => {
    const loader = document.getElementById('global-loader');
    if (loader) loader.style.display = 'none';
});

function initDarkMode() {
    const toggle = document.getElementById('toggle-dark-btn');
    const html = document.documentElement;
    const body = document.body;

    const isDark = localStorage.getItem('theme') === 'dark';

    if (isDark) {
        html.classList.add('dark');
        body.classList.add('dark');
        html.setAttribute('data-bs-theme', 'dark');
    } else {
        html.classList.remove('dark');
        body.classList.remove('dark');
        html.setAttribute('data-bs-theme', 'light');
    }

    const icon = document.getElementById('theme-icon');
    if (icon) {
        if (isDark) {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        } else {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    }

    if (toggle) {
        const newToggle = toggle.cloneNode(true);
        toggle.parentNode.replaceChild(newToggle, toggle);

        newToggle.addEventListener('click', function (e) {
            e.preventDefault();

            const isDarkNow = html.classList.contains('dark');
            const currentIcon = newToggle.querySelector('#theme-icon');

            if (!isDarkNow) {
                localStorage.setItem('theme', 'dark');
                html.classList.add('dark');
                body.classList.add('dark');
                html.setAttribute('data-bs-theme', 'dark');

                if (currentIcon) {
                    currentIcon.classList.remove('fa-sun');
                    currentIcon.classList.add('fa-moon');
                }
            } else {
                localStorage.setItem('theme', 'light');
                html.classList.remove('dark');
                body.classList.remove('dark');
                html.setAttribute('data-bs-theme', 'light');

                if (currentIcon) {
                    currentIcon.classList.remove('fa-moon');
                    currentIcon.classList.add('fa-sun');
                }
            }
        });
    }
}
initDarkMode();

document.addEventListener('livewire:navigated', () => {
    initDarkMode();
});

document.addEventListener('DOMContentLoaded', function () {
    if (window.article_updated) {
        Swal.fire({
            title: 'Artikel berhasil diperbarui!',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
        window.article_updated = null;
    }

    if (window.profile_updated) {
        Swal.fire({
            title: 'Profil berhasil diperbarui!',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
        window.profile_updated = null;
    }

    if (window.comment_updated) {
        Swal.fire({
            title: 'Komentar berhasil diperbarui!',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
        window.comment_updated = null;
    }
});

document.addEventListener('livewire:load', function () {
    Livewire.on('openEditModal', () => {
        var myModal = new bootstrap.Modal(document.getElementById('editCommentModal'));
        myModal.show();
    });

    Livewire.on('closeEditModal', () => {
        var myModalEl = document.getElementById('editCommentModal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        if (modal) {
            modal.hide();
        }
    });
});

document.addEventListener('livewire:init', () => {
    const editModalEl = document.getElementById('editCommentModal');
    if (editModalEl) {
        const editModal = new bootstrap.Modal(editModalEl);
        const editTextArea = document.getElementById('edit-comment-textarea');
        window.addEventListener('showEditCommentModal', event => {
            if (editTextArea) {
                editTextArea.value = event.detail.content;
                editTextArea.dispatchEvent(new Event('input'));
            }
            editModal.show();
        });
        window.addEventListener('closeEditModal', () => {
            editModal.hide();
        });
    }
});

document.addEventListener('livewire:init', () => {
    Livewire.on('comment-posted', () => {
        const textarea = document.getElementById('comment');
        if (textarea) {
            textarea.focus();
        }
    });
});

document.addEventListener('trix-change', function (e) {
    const xInput = document.getElementById('x');
    if (xInput) {
        xInput.value = e.target.value;
    }
});

document.addEventListener("trix-file-accept", function (event) {
    event.preventDefault();
});
