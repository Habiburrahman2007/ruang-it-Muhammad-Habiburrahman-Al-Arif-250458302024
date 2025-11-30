<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <link rel="shortcut icon" href="{{ asset('dist/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" crossorigin href="{{ asset('dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('dist/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('dist/assets/compiled/css/iconly.css') }}">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const root = document.documentElement;

            function applySystemTheme() {
                const systemDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
                root.setAttribute("data-bs-theme", systemDark ? "dark" : "light");
            }
            applySystemTheme();
            window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", applySystemTheme);
        });
    </script>

    <style>
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            transition: all 0.3s ease;
        }

        #navbar a,
        #navbar p,
        #navbar h6 {
            color: #ffffff;
        }

        .logo {
            width: 5%;
        }

        @media (max-width: 768px) {
            .logo {
                width: 15%;
            }
        }


        .blog-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .blog-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.15) !important;
        }
    </style>
</head>

<body style="padding-top: 70px;">
    <script src="{{ asset('dist/assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                <div id="navbar" class="header-top">
                    <div class="container-fluid px-3 px-md-5 d-flex justify-content-between align-items-center py-2">
                        <a href="{{ route('landing-page') }}">
                            <h6 class="d-flex align-items-center mb-0">
                                <img src="{{ asset('img/logo_fp-removebg-preview.png') }}" alt="Logo Ruang IT"
                                    class="logo">
                                <p class="text-2xl ms-3 mb-0">Ruang IT</p>
                            </h6>
                        </a>
                        <div class="header-top-right">
                            <!-- Desktop Buttons -->
                            <div class="d-none d-md-flex gap-3">
                                <a href="{{ route('login') }}" wire:navigate
                                    class="btn btn-primary hover:!bg-transparent hover:!border-[var(--bs-primary)]">Masuk</a>
                                <a href="{{ route('register') }}" wire:navigate
                                    class="btn btn-outline-primary">Daftar</a>
                            </div>

                            <!-- Mobile Menu -->
                            <div class="d-md-none dropdown">
                                <a href="#" id="mobileMenuDropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false" class="text-white">
                                    <i class="fa-solid fa-bars fs-2"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                    aria-labelledby="mobileMenuDropdown">
                                    <li><a class="dropdown-item" wire:navigate href="{{ route('login') }}">Login</a>
                                    </li>
                                    <li><a class="dropdown-item" wire:navigate
                                            href="{{ route('register') }}">Register</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content-wrapper container">
                {{ $slot }}
            </div>

        </div>
    </div>
    <script src="{{ asset('dist/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('dist/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('dist/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/assets/static/js/pages/dashboard.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const navbar = document.getElementById("navbar");

            function updateNavbar() {
                if (window.scrollY > 10) {
                    navbar.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
                    navbar.style.backdropFilter = "blur(10px)";
                    navbar.style.webkitBackdropFilter = "blur(10px)";
                } else {
                    navbar.style.backgroundColor = "transparent";
                    navbar.style.backdropFilter = "none";
                    navbar.style.boxShadow = "none";
                }
            }
            updateNavbar();
            window.addEventListener("scroll", updateNavbar);
        });
    </script>

</body>

</html>
