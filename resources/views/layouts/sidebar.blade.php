<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex flex-column align-items-start gap-0">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('landing-page') }}" class="d-flex justify-around items-center">
                        <img src="{{ asset('img/logo_fp-removebg-preview.png') }}" alt="Logo"
                            class="w-10 h-auto me-2" />
                        <h3 class="mb-0">Ruang IT</h3>
                    </a>
                </div>

                <div class="d-flex align-items-center justify-content-start ms-4 mt-2 w-100 pe-3"
                    style="gap: 0.25rem;">
                    <div class="theme-toggle d-flex align-items-center me-2" style="gap: 0.25rem;">
                        <div class="form-check form-switch fs-6 d-flex align-items-center" style="gap: 0.25rem;">
                            <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                                style="cursor: pointer">
                            <label class="form-check-label" for="toggle-dark"></label>
                            <i id="theme-icon" class="fas fa-sun" style="width: 20px; text-align: center;"></i>
                        </div>
                    </div>

                    <button onclick="window.confirmLogout()"
                        class="btn btn-sm btn-outline-danger d-flex align-items-center"
                        style="cursor: pointer; font-size: 0.875rem; gap: 0.25rem; padding: 0.25rem 0.5rem;">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </div>

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
                    class="sidebar-item {{ request()->routeIs('dashboard') || ((request()->routeIs('detail-article') || request()->routeIs('detail-profile') || request()->routeIs('guidelines')) && request('from') === 'dashboard') ? 'active bg-primary text-white rounded' : '' }}">
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

                <li
                    class="sidebar-item {{ request()->routeIs('profile', 'profile-edit') || ((request()->routeIs('detail-article') || request()->routeIs('guidelines')) && request('from') === 'profile') ? 'active bg-primary text-white rounded' : '' }}">
                    <a wire:navigate href="{{ route('profile') }}" class="sidebar-link d-flex align-items-center gap-2">
                        <i class="bi bi-person-fill"></i>
                        <span>Profil</span>
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
                        class="sidebar-item {{ request()->routeIs('blog-control') || ((request()->routeIs('detail-article') || request()->routeIs('guidelines')) && request('from') === 'blog-control') ? 'active bg-primary text-white rounded' : '' }}">
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
