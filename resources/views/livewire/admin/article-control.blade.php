<div>
    <div class="p-3 mb-3">
        <div class="input-group w-100 mb-3">
            <input type="text" class="form-control" placeholder="Cari artikel atau penulis..."
                wire:model.live="search" />
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-filter"></i> {{ $category }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" wire:click.prevent="setCategory('All')">Semua</a></li>
                @foreach ($categories as $cat)
                    <li>
                        <a class="dropdown-item" href="#" wire:click.prevent="setCategory('{{ $cat }}')">
                            {{ $cat }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-toggle-on"></i> {{ ucfirst($status) }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" wire:click.prevent="setStatus('All')">Semua</a></li>
                <li><a class="dropdown-item" href="#" wire:click.prevent="setStatus('active')">Aktif</a></li>
                <li><a class="dropdown-item" href="#" wire:click.prevent="setStatus('banned')">Terblokir</a></li>
            </ul>
        </div>
    </div>

    @if ($articles->isEmpty())
        <div class="card">
            <div class="card-body">
                @if ($category !== 'All')
                    Tidak ada artikel di kategori "{{ $category }}" yang sesuai pencarian.
                @elseif($search)
                    Tidak ada artikel yang sesuai dengan kata kunci "{{ $search }}".
                @else
                    Belum ada artikel yang ditulis.
                @endif
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach ($articles as $article)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card blog-card rounded-3 overflow-hidden shadow-sm h-100">
                        <a href="{{ route('detail-article', ['slug' => $article->slug, 'from' => 'blog-control']) }}"
                            class="text-decoration-none text-dark">
                            <img class="card-img-top img-fluid rounded-top-3 object-cover"
                                style="height: 200px; width: 100%; object-fit: cover;"
                                src="{{ !empty($article->image) && file_exists(storage_path('app/public/' . $article->image))
                                    ? asset('storage/' . $article->image)
                                    : asset('img/Login.jpg') }}"
                                alt="{{ $article->title }}">
                            <div class="card-body">
                                <h4 class="card-title text-secondary">{{ $article->title }}</h4>
                                <p class="card-text text-secondary">
                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                </p>
                                <span class="badge {{ $article->category->color }}">
                                    {{ $article->category->name }}
                                </span>
                                @if ($article->status === 'active')
                                    <span class="badge bg-success ms-2">Aktif</span>
                                @else
                                    <span class="badge bg-danger ms-2">Terblokir</span>
                                @endif
                            </div>
                        </a>

                        <div class="card-footer border-0 d-flex justify-content-between align-items-center px-3 pb-3">
                            <div class="btn-group">
                                <button type="button" class="btn btn-link p-2 text-decoration-none"
                                    wire:click.stop="toggleLike({{ $article->id }})">
                                    <i
                                        class="bi bi-heart{{ $article->isLiked ? '-fill text-danger' : ' text-secondary' }}"></i>
                                    <small class="text-muted">{{ $article->likes->count() }}</small>
                                </button>

                                <button type="button" class="btn btn-link p-2 text-decoration-none">
                                    <i class="bi bi-chat text-secondary"></i>
                                    <small class="text-muted">{{ $article->comments->count() }}</small>
                                </button>

                                <div class="dropdown">
                                    <button class="btn btn-link p-2 text-decoration-none" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical text-secondary"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if ($article->status === 'active')
                                            <li>
                                                <a class="dropdown-item text-danger" href="#"
                                                    onclick="confirmToggleStatus({{ $article->id }}, 'banned')">
                                                    <i class="bi bi-slash-circle me-2"></i>Blokir
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item text-success" href="#"
                                                    onclick="confirmToggleStatus({{ $article->id }}, 'active')">
                                                    <i class="bi bi-check-circle me-2"></i>Aktifkan
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <a href="{{ route('detail-profile', $article->user->slug) }}"
                                    class="d-flex align-items-center text-decoration-none">
                                    <img src="{{ $article->user->photo_profile ? asset('storage/' . $article->user->photo_profile) : asset('img/default-avatar.jpeg') }}"
                                        class="rounded-circle me-2"
                                        style="width: 35px; height: 35px; object-fit: cover;" alt="Author">
                                    <small class="fw-semibold text-secondary">
                                        {{ \Illuminate\Support\Str::limit($article->user->name, 10, '...') }}
                                    </small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            @if ($totalArticles > count($articles))
                <div x-intersect.full="$wire.loadMore()" class="d-flex justify-content-center py-4">
                    <div wire:loading wire:target="loadMore" class="text-primary">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                        <span class="ms-2 fw-bold">Sedang memuat artikel lainnya...</span>
                    </div>

                    <div wire:loading.remove wire:target="loadMore" class="text-muted small">
                        Scroll untuk memuat lebih banyak...
                    </div>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <p>Semua artikel sudah ditampilkan.</p>
                </div>
            @endif
        </div>
    @endif
</div>
