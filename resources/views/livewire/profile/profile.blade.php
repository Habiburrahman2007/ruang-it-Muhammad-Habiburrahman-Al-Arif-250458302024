<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last mb-3">
                <h3>
                    <i class="fa-solid fa-user"></i>
                    Profil Saya
                    <span>
                        @if ($user->banned)
                            <span class="badge bg-danger">Banned</span>
                        @endif
                    </span>
                </h3>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 w-100">
                    <div class="card-body py-4 px-4">

                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <div class="d-flex align-items-center mb-3 mb-md-0">
                                <img src="{{ $user->photo_profile ? asset('storage/' . $user->photo_profile) : asset('img/default-avatar.jpeg') }}"
                                    class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;"
                                    alt="Avatar {{ $user->name }}">
                                <div class="ms-3 text-start">
                                    <h5 class="fw-bold mb-1 text-md-start">{{ $user->name }}</h5>
                                    <p class="text-muted mb-1">{{ $user->profession ?? 'Belum diatur' }}</p>
                                    <div class="small text-secondary">
                                        <span>{{ $user->bio }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('profile-edit') }}" class="btn btn-outline-primary px-4">
                                    <i class="fa-solid fa-user-pen me-1"></i> Edit
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid text-center my-4">
                <div class="row justify-content-center align-items-stretch">

                    <div class="col-12 col-md-4 py-4">
                        <div class="mb-2">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        <h1 class="fw-bold mb-0">{{ number_format($articleCount) }}</h1>
                        <h4 class="text-muted">Artikel</h4>
                    </div>

                    <div class="col-12 col-md-4 py-4">
                        <div class="mb-2">
                            <i class="fa-solid fa-heart"></i>
                        </div>
                        <h1 class="fw-bold mb-0">{{ number_format($likeCount) }}</h1>
                        <h4 class="text-muted">Suka</h4>
                    </div>

                    <div class="col-12 col-md-4 py-4">
                        <div class="mb-2">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <h1 class="fw-bold mb-0">{{ number_format($commentCount) }}</h1>
                        <h4 class="text-muted">Komentar</h4>
                    </div>

                </div>
            </div>

            <h3 class="text-center border-t border-light pt-5">Daftar artikel saya</h3>
            <div class="p-3">
                <div
                    class="input-group w-100 mb-3 d-flex flex-column flex-md-row align-items-start align-items-md-center">
                    <input type="text" class="form-control me-2 w-100 w-md-50 mb-3"
                        placeholder="Cari judul artikel atau penulis..." wire:model.live="search"
                        style="height: 38px;" />
                    <div class="d-flex flex-nowrap gap-2" role="group" aria-label="Filter category">
                        <button type="button" wire:click.prevent="setCategory('All')"
                            class="btn btn-sm {{ $category === 'All' ? 'btn-primary' : 'btn-outline-primary' }}">
                            SEMUA
                        </button>
                        @foreach ($categories as $cat)
                            @php
                                $color = str_replace('bg-', '', $cat->color);
                                $btnClass = $category === $cat->name ? "btn-$color" : "btn-outline-$color";
                            @endphp
                            <button type="button" wire:click.prevent="setCategory('{{ $cat->name }}')"
                                class="btn btn-sm {{ $btnClass }}">
                                {{ $cat->name }}
                            </button>
                        @endforeach
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
            </div>
        @else
            <div class="position-relative">
                <div wire:loading.flex wire:target="search, setCategory"
                    class="position-absolute top-0 start-0 w-100 h-100 justify-content-center align-items-center bg-white bg-opacity-75 z-2"
                    style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="row g-4 transition-opacity" wire:loading.class="opacity-50"
                    wire:target="search, setCategory">
                    @foreach ($articles as $article)
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card blog-card rounded-3 overflow-hidden shadow-sm h-100">
                                <a href="{{ route('detail-article', ['slug' => $article->slug, 'from' => 'profile']) }}"
                                    class="text-decoration-none text-dark">
                                    <img class="card-img-top img-fluid rounded-top-3 object-cover"
                                        style="height: 200px; width: 100%; object-fit: cover;"
                                        src="{{ !empty($article->image) && file_exists(storage_path('app/public/' . $article->image))
                                            ? asset('storage/' . $article->image)
                                            : asset('img/Login.jpg') }}"
                                        alt="{{ $article->title }}">
                                    <div class="card-body">
                                        <h4 class="card-title text-secondary">{{ $article->title }}</h4>
                                        @php
                                            $preview = \App\Helpers\ContentHelper::excerpt($article->content, 120);
                                        @endphp
                                        <p class="card-text text-secondary">{!! $preview !!}</p>
                                        <span class="badge {{ $article->category->color }}">
                                            {{ $article->category->name }}
                                        </span>
                                        @if ($article->status === 'active')
                                            <span class="badge bg-success ms-2">Active</span>
                                        @else
                                            <span class="badge bg-danger ms-2">Banned</span>
                                        @endif
                                    </div>
                                </a>

                                <div
                                    class="card-footer border-0 d-flex justify-content-between align-items-center px-3 pb-3">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-link p-2 text-decoration-none"
                                            wire:click="toggleLike({{ $article->id }})" wire:loading.attr="disabled"
                                            wire:target="toggleLike({{ $article->id }})">
                                            <span wire:loading.remove wire:target="toggleLike({{ $article->id }})">
                                                <i
                                                    class="bi bi-heart{{ $article->isLiked ? '-fill text-danger' : ' text-secondary' }}"></i>
                                            </span>
                                            <span wire:loading wire:target="toggleLike({{ $article->id }})">
                                                <div class="spinner-border spinner-border-sm text-secondary"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </span>
                                            <small class="text-muted">{{ $article->likes->count() }}</small>
                                        </button>

                                        <button type="button" class="btn btn-link p-2 text-decoration-none">
                                            <i class="bi bi-chat text-secondary"></i>
                                            <small class="text-muted">{{ $article->comments->count() }}</small>
                                        </button>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->photo_profile ? asset('storage/' . $user->photo_profile) : asset('img/default-avatar.jpeg') }}"
                                            class="rounded-circle me-2"
                                            style="width: 35px; height: 35px; object-fit: cover;" alt="Author">
                                        <small class="fw-semibold text-secondary">
                                            {{ \Illuminate\Support\Str::limit($article->user->name, 10, '...') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-center mt-4">
                @if ($totalFiltered > count($articles))
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
    </section>
</div>
