<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="shortcut icon" href="{{ asset('dist/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/table-datatable.css') }}">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <style>
        .card-text ul {
            list-style-type: disc !important;
            margin-left: 1.5rem !important;
        }

        .card-text ol {
            list-style-type: decimal !important;
            margin-left: 1.5rem !important;
        }

        .card-text li {
            display: list-item !important;
            margin-bottom: 0.5rem;
        }

        trix-editor ul,
        trix-editor ol {
            list-style-position: inside !important;
            margin-left: 1.5rem !important;
            padding-left: 1rem !important;
        }

        trix-editor ul {
            list-style-type: disc !important;
        }

        trix-editor ol {
            list-style-type: decimal !important;
        }

        trix-editor li {
            display: list-item !important;
            margin-bottom: 0.4rem !important;
        }

        .preview-content {
            max-height: 80px;
            overflow: hidden;
        }

        .preview-content ul {
            list-style-type: disc;
            padding-left: 1.2em;
        }

        .preview-content ol {
            list-style-type: decimal;
            padding-left: 1.2em;
        }


        #sidebar.active~#main header .burger-btn {
            display: none !important;
        }

        header.mb-3 {
            position: sticky;
            top: 0;
            z-index: 999;
            background-color: var(--bs-body-bg);
        }

        .blog-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .blog-card:hover {
            transform: scale(1.03);
            box-shadow: 8px 8px 20px rgba(0, 0, 0, 0.15);
        }


        .dark .trix-button {
            background-color: white !important;
            border-radius: 4px;
            margin: 1px;
        }

        .dark .trix-button.trix-active {
            background-color: #e5e7eb !important;
            /* gray-200 */
        }
    </style>
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-navigate-once="true"></script>
    <script src="{{ asset('js/alerts.js') }}" data-navigate-once="true"></script>
</head>

<body>

    <script src="{{ asset('dist/assets/static/js/initTheme.js') }}" data-navigate-once="true"></script>
    <div id="app">

        @include('layouts.sidebar')

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="content-wrapper container">
                {{ $slot }}
            </div>

        </div>
    </div>


    @stack('scripts')
    {{-- Hamburger Menu Script --}}
    <script>
        function initializeHamburger() {
            const burgerBtn = document.querySelector('.burger-btn');
            const sidebar = document.getElementById('sidebar');
            const sidebarHide = document.querySelector('.sidebar-hide');

            if (burgerBtn && sidebar) {
                // Remove old event listeners by cloning
                const newBurgerBtn = burgerBtn.cloneNode(true);
                burgerBtn.parentNode.replaceChild(newBurgerBtn, burgerBtn);

                // Add new event listener
                newBurgerBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('active');

                    // Create or remove backdrop
                    let backdrop = document.querySelector('.sidebar-backdrop');
                    if (sidebar.classList.contains('active')) {
                        if (!backdrop) {
                            backdrop = document.createElement('div');
                            backdrop.classList.add('sidebar-backdrop');
                            backdrop.addEventListener('click', function() {
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
                // Remove old event listeners by cloning
                const newSidebarHide = sidebarHide.cloneNode(true);
                sidebarHide.parentNode.replaceChild(newSidebarHide, sidebarHide);

                // Add new event listener
                newSidebarHide.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.remove('active');
                    const backdrop = document.querySelector('.sidebar-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializeHamburger);

        // Reinitialize after Livewire navigation
        document.addEventListener('livewire:navigated', initializeHamburger);
    </script>
    {{-- navigate --}}
    <script>
        document.addEventListener('livewire:navigated', () => {
            Livewire.dispatch('refreshComponent');
        });
    </script>
    {{-- dark mode --}}
    <script>
        function initDarkMode() {
            const toggle = document.getElementById('toggle-dark');
            const icon = document.getElementById('theme-icon');
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
            if (toggle) {
                toggle.checked = isDark;

                const newToggle = toggle.cloneNode(true);
                toggle.parentNode.replaceChild(newToggle, toggle);

                newToggle.addEventListener('change', function() {
                    if (this.checked) {
                        localStorage.setItem('theme', 'dark');
                        html.classList.add('dark');
                        html.setAttribute('data-bs-theme', 'dark');

                        if (icon) {
                            icon.classList.remove('fa-sun');
                            icon.classList.add('fa-moon');
                        }
                    } else {
                        localStorage.setItem('theme', 'light');
                        html.classList.remove('dark');
                        html.setAttribute('data-bs-theme', 'light');

                        if (icon) {
                            icon.classList.remove('fa-moon');
                            icon.classList.add('fa-sun');
                        }
                    }
                });
            }
            if (icon) {
                if (isDark) {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                } else {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            }
        }
        initDarkMode();

        document.addEventListener('livewire:navigated', () => {
            initDarkMode();
        });
    </script>

    {{-- Confirm alert --}}

    <script>
        window.article_created = @json(session('article_created'));
        window.category_created = @json(session('category_created'));
        window.article_deleted = @json(session('article_deleted'));
        window.sessionSuccess = @json(session('success'));
    </script>

    {{-- Create Success alert --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @if (session('article_created'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Artikel berhasil diterbitkan.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                });
            @endif

            @if (session('category_created'))
                Swal.fire({
                    title: 'Kategori dibuat!',
                    text: 'Kategori berhasil ditambahkan.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    timer: 1500,
                    showConfirmButton: false
                });
            @endif

        });
    </script>

    {{-- Update Success alert --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @if (session('article_updated'))
                Swal.fire({
                    title: 'Artikel berhasil diperbarui!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            @endif

            @if (session('profile_updated'))
                Swal.fire({
                    title: 'Profil berhasil diperbarui!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            @endif

            @if (session('comment_updated'))
                Swal.fire({
                    title: 'Komentar berhasil diperbarui!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            @endif

        });
    </script>

    {{-- Modal --}}
    <script>
        document.addEventListener('livewire:load', function() {
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
    </script>

    <script>
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
    </script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('comment-posted', () => {
                const textarea = document.getElementById('comment');
                if (textarea) {
                    textarea.focus();
                }
            });
        });
    </script>

    {{-- Link JS Mazer --}}
    <script src="{{ asset('dist/assets/static/js/components/dark.js') }}" data-navigate-once="true"></script>
    <script src="{{ asset('dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"
        data-navigate-once="true"></script>
    <script src="{{ asset('dist/assets/compiled/js/app.js') }}" data-navigate-once="true"></script>
    <script src="{{ asset('dist/assets/extensions/apexcharts/apexcharts.min.js') }}" data-navigate-once="true"></script>
    <script src="{{ asset('dist/assets/static/js/pages/dashboard.js') }}?v={{ time() }}" data-navigate-once="true">
    </script>
    <script src="{{ asset('dist/assets/extensions/simple-datatables/umd/simple-datatables.js') }}"
        data-navigate-once="true"></script>
    <script src="{{ asset('dist/assets/extensions/simple-datatables/umd/simple-datatables.js') }}"
        data-navigate-once="true"></script>
    {{-- Link JS text editor --}}
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <script>
        document.addEventListener('trix-change', function(e) {
            document.getElementById('x').value = e.target.value;
        });
    </script>
    @livewireScripts

</body>

</html>
