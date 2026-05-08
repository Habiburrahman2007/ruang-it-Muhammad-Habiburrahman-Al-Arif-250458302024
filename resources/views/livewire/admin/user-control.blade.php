<div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fa-solid fa-users"></i>
                    Daftar Pengguna
                </h5>
            </div>

            <div class="card-body">
                <div class="d-flex flex-column mb-3 gap-2">
                    <div class="w-100">
                        <input type="text" wire:model.live="search" class="form-control"
                            placeholder="Cari pengguna">
                    </div>

                    <nav class="w-100">
                        <div class="d-flex flex-wrap gap-2" role="group" aria-label="Filter user">
                            <button type="button" wire:click="$set('filterStatus', 'all')"
                                class="btn btn-sm {{ $filterStatus === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Semua
                            </button>
                            <button type="button" wire:click="$set('filterStatus', 'active')"
                                class="btn btn-sm {{ $filterStatus === 'active' ? 'btn-success' : 'btn-outline-success' }}">
                                Aktif
                            </button>
                            <button type="button" wire:click="$set('filterStatus', 'banned')"
                                class="btn btn-sm {{ $filterStatus === 'banned' ? 'btn-danger' : 'btn-outline-danger' }}">
                                Terblokir
                            </button>
                        </div>
                    </nav>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto profil</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jumlah Artikel</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="avatar mx-auto mb-3">
                                            <img src="{{ $user->photo_profile ? asset('storage/' . $user->photo_profile) : asset('img/default-avatar.jpeg') }}"
                                                alt="Avatar" class="rounded-circle"
                                                style="width:50px; height:50px; object-fit:cover;">
                                        </div>
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->articles_count }}</td>
                                    <td>
                                        @if ($user->banned)
                                            <span class="badge bg-danger">Banned</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button"
                                                id="dropdownMenu{{ $user->id }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>

                                             <ul class="dropdown-menu" aria-labelledby="dropdownMenu{{ $user->id }}">
                                                @if ($user->banned)
                                                    <li>
                                                        <button class="dropdown-item text-success"
                                                            onclick="confirmToggleUser('{{ $user->id }}', 'unban')">
                                                            <i class="fa-solid fa-unlock me-2"></i>Aktifkan Akun
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button class="dropdown-item text-danger"
                                                            onclick="confirmToggleUser('{{ $user->id }}', 'ban')">
                                                            <i class="fa-solid fa-ban me-2"></i>Blokir Akun
                                                        </button>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item text-primary" href="{{ route('detail-profile', $user->slug) }}" wire:navigate>
                                                        <i class="fa-solid fa-user me-2"></i>Lihat Profil
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ditemukan pengguna yang dimaksud</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <ul class="pagination pagination-primary mb-0 justify-center">

                    <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $users->onFirstPage() ? '#' : $users->previousPageUrl() }}"
                            @if (!$users->onFirstPage()) wire:navigate @endif>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </li>

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}"
                                @if ($users->currentPage() != $page) wire:navigate @endif>
                                {{ $page }}
                            </a>
                        </li>
                    @endforeach

                    <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $users->hasMorePages() ? $users->nextPageUrl() : '#' }}"
                            @if ($users->hasMorePages()) wire:navigate @endif>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </section>
</div>
