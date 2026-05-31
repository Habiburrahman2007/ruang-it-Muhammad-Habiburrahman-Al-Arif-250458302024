<section class="section">
    <div class="card">
        <div class="card-header d-flex gap-3">
            <i class="fa-solid fa-comments"></i>
            <h5 class="card-title mb-0">Daftar Komentar</h5>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="w-100 w-md-50">
                    <input type="text" class="form-control" placeholder="Cari komentar..." wire:model.live="search">
                </div>

                <div class="d-flex gap-2">
                    <button type="button" wire:click="$set('filterStatus', 'all')"
                        class="btn btn-sm {{ $filterStatus === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua
                    </button>
                    <button type="button" wire:click="$set('filterStatus', 'visible')"
                        class="btn btn-sm {{ $filterStatus === 'visible' ? 'btn-success' : 'btn-outline-success' }}">
                        Tampil
                    </button>
                    <button type="button" wire:click="$set('filterStatus', 'hidden')"
                        class="btn btn-sm {{ $filterStatus === 'hidden' ? 'btn-danger' : 'btn-outline-danger' }}">
                        Disembunyikan
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Komentator</th>
                            <th>Artikel</th>
                            <th>Komentar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $comment)
                            <tr @if ($comment->is_hidden)  @endif>
                                <td>{{ $comment->user->name ?? '-' }}</td>
                                <td>{{ Str::limit($comment->article->title ?? '-', 20) }}</td>
                                <td>{{ Str::limit($comment->content, 40) }}</td>
                                <td>
                                    @if ($comment->is_hidden)
                                        <span class="badge bg-danger">disembunyikan</span>
                                    @else
                                        <span class="badge bg-success">Tampil</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-decoration-none p-0" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical text-secondary fs-5"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if ($comment->is_hidden)
                                                <li>
                                                    <button class="dropdown-item text-primary"
                                                        onclick="confirmToggle({{ $comment->id }}, false)">
                                                        <i class="bi bi-eye me-1 text-primary"></i> Tampilkan
                                                    </button>
                                                </li>
                                            @else
                                                <li>
                                                    <button class="dropdown-item text-danger"
                                                        onclick="confirmToggle({{ $comment->id }}, true)">
                                                        <i class="bi bi-eye-slash me-1"></i> Sembunyikan
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada komentar yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
            <ul class="pagination pagination-primary mb-0 justify-content-center mt-3">
                
                <li class="page-item {{ $comments->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $comments->onFirstPage() ? '#' : $comments->previousPageUrl() }}"
                        @if (!$comments->onFirstPage()) wire:navigate @endif>
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </li>

                
                @foreach ($comments->getUrlRange(1, $comments->lastPage()) as $page => $url)
                    <li class="page-item {{ $comments->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}"
                            @if ($comments->currentPage() != $page) wire:navigate @endif>
                            {{ $page }}
                        </a>
                    </li>
                @endforeach

                
                <li class="page-item {{ $comments->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $comments->hasMorePages() ? $comments->nextPageUrl() : '#' }}"
                        @if ($comments->hasMorePages()) wire:navigate @endif>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </li>
            </ul>

        </div>
    </div>
</section>
