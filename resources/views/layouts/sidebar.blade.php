<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-evenly align-items-center w-100">
                <a href="{{ route('landing-page') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                    <img src="{{ asset('img/logo_fp-removebg-preview.png') }}" alt="Logo" class="w-10 h-auto" />
                    <h3 class="mb-0">Ruang IT</h3>
                </a>

                <button id="toggle-dark-btn"
                    class="btn d-flex align-items-center justify-content-center border-0 bg-transparent text-secondary"
                    style="width: 35px; height: 35px;">
                    <i id="theme-icon" class="fas fa-sun fs-5"></i>
                </button>
            </div>
            <div class="sidebar-toggler position-absolute top-0 end-0 me-3 mt-2">
                <a href="#" class="sidebar-hide d-xl-none d-block">
                    <span class="badge bg-primary p-0">
                        <i class="bi bi-x text-white"></i>
                    </span>
                </a>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                <li
                    class="sidebar-item {{ request()->routeIs('dashboard') || ((request()->routeIs('detail-article') || request()->routeIs('edit-article') || request()->routeIs('detail-profile') || request()->routeIs('guidelines')) && request('from') === 'dashboard') ? 'active bg-primary text-white rounded' : '' }}">
                    <a wire:navigate href="{{ route('dashboard') }}"
                        class="sidebar-link d-flex align-items-center gap-2">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li
                    class="sidebar-item {{ request()->routeIs('create-article') || (request()->routeIs('guidelines') && request('from') === 'create-article') ? 'active bg-primary text-white rounded' : '' }}">
                    <a wire:navigate href="{{ route('create-article') }}"
                        class="sidebar-link d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Artikel</span>
                    </a>
                </li>

                <li class="sidebar-title">Akun</li>
                <li
                    class="sidebar-item {{ request()->routeIs('profile', 'profile-edit') || ((request()->routeIs('detail-article') || request()->routeIs('edit-article') || request()->routeIs('guidelines')) && request('from') === 'profile') ? 'active bg-primary text-white rounded' : '' }}">
                    <a wire:navigate href="{{ route('profile') }}" class="sidebar-link d-flex align-items-center gap-2">
                        <i class="bi bi-person-fill"></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a onclick="window.confirmLogout()" class="sidebar-link d-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-right text-danger"></i>
                        <span class="text-danger">Logout</span>
                    </a>
                </li>

                @if (auth()->user()?->role === 'admin')
                    <li class="sidebar-title">Menu Admin</li>
                    <li
                        class="sidebar-item {{ request()->routeIs('statistic') || ((request()->routeIs('detail-article') || request()->routeIs('guidelines')) && request('from') === 'statistic') ? 'active bg-primary text-white rounded' : '' }}">
                        <a wire:navigate href="{{ route('statistic') }}"
                            class="sidebar-link d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Statistik</span>
                        </a>
                    </li>

                    <li
                        class="sidebar-item {{ request()->routeIs('category', 'create-category') ? 'active bg-primary text-white rounded' : '' }}">
                        <a wire:navigate href="{{ route('category') }}"
                            class="sidebar-link d-flex align-items-center gap-2">
                            <i class="fa-solid fa-list"></i>
                            <span>Kelola Kategori</span>
                        </a>
                    </li>

                    <li
                        class="sidebar-item {{ request()->routeIs('blog-control') || ((request()->routeIs('detail-article') || request()->routeIs('edit-article') || request()->routeIs('guidelines')) && request('from') === 'blog-control') ? 'active bg-primary text-white rounded' : '' }}">
                        <a wire:navigate href="{{ route('blog-control') }}"
                            class="sidebar-link d-flex align-items-center gap-2">
                            <i class="bi bi-journal-check"></i>
                            <span>Kelola Artikel</span>
                        </a>
                    </li>

                    <li
                        class="sidebar-item {{ request()->routeIs('user-control') ? 'active bg-primary text-white rounded' : '' }}">
                        <a wire:navigate href="{{ route('user-control') }}"
                            class="sidebar-link d-flex align-items-center gap-2">
                            <i class="fa-solid fa-users"></i>
                            <span>Kelola Pengguna</span>
                        </a>
                    </li>

                    <li
                        class="sidebar-item {{ request()->routeIs('comment-control') ? 'active bg-primary text-white rounded' : '' }}">
                        <a wire:navigate.hover href="{{ route('comment-control') }}"
                            class="sidebar-link d-flex align-items-center gap-2">
                            <i class="fa-solid fa-comments"></i>
                            <span>Kelola Komentar</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
