<section class="section">
    <div class="col-12 pt-3">
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
    <div class="mt-5">
        <div class="mb-4 text-center border-top pt-4">
            <h2 class="fw-bold mb-1">Daftar artikel anda</h2>
        </div>

        <div class="p-3">
            <div class="input-group w-100 mb-3 d-flex flex-column flex-md-row align-items-start align-items-md-center">
                <input type="text" class="form-control me-2 w-100 w-md-50 mb-3"
                    placeholder="Cari judul artikel atau penulis..." wire:model.live="search" style="height: 38px;" />
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

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 transition-opacity"
                wire:loading.class="opacity-50" wire:target="search, setCategory">
                @foreach ($articles as $article)
                    <div class="col d-flex">
                        <div class="card flex-fill blog-card rounded-3 overflow-hidden shadow-sm h-100">
                            <a href="{{ route('detail-article', $article->slug) }}"
                                class="text-decoration-none text-dark">
                                <img class="card-img-top img-fluid rounded-top-3 object-cover"
                                    style="height: 200px; width: 100%; object-fit: cover;"
                                    src="{{ !empty($article->image) && file_exists(storage_path('app/public/' . $article->image))
                                        ? asset('storage/' . $article->image)
                                        : asset('img/Login.jpg') }}"
                                    alt="{{ $article->title }}">
                                <div class="card-body">
                                    <h5 class="card-title text-secondary">{{ $article->title }}</h5>
                                    @php
                                        $preview = \App\Helpers\ContentHelper::excerpt($article->content, 120);
                                    @endphp
                                    <p class="card-text text-secondary">{!! $preview !!}</p>
                                    <span class="badge {{ $article->category->color }}">
                                        {{ $article->category->name }}
                                    </span>
                                </div>
                            </a>
                            <div
                                class="card-footer border-0 d-flex justify-content-between align-items-center px-3 pb-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-link p-0 text-decoration-none"
                                        wire:click="toggleLike({{ $article->id }})" wire:loading.attr="disabled"
                                        wire:target="toggleLike({{ $article->id }})">
                                        <span wire:loading.remove wire:target="toggleLike({{ $article->id }})">
                                            <i
                                                class="bi bi-heart{{ $article->isLiked ? '-fill text-danger' : ' text-secondary' }}"></i>
                                        </span>
                                        <span wire:loading wire:target="toggleLike({{ $article->id }})">
                                            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </span>
                                        <small class="text-muted ms-2">{{ $article->likes->count() }}</small>
                                    </button>
                                    <button type="button" class="btn btn-link p-0 text-decoration-none ms-3">
                                        <i class="bi bi-chat text-secondary"></i>
                                        <small class="text-muted ms-2">{{ $article->comments->count() }}</small>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $article->user->photo_profile ? asset('storage/' . $article->user->photo_profile) : asset('img/default-avatar.jpeg') }}"
                                        class="rounded-circle me-2" style="width:35px; height:35px; object-fit:cover;"
                                        alt="Author">
                                    <small
                                        class="fw-semibold text-secondary">{{ \Illuminate\Support\Str::limit($article->user->name, 10, '...') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
