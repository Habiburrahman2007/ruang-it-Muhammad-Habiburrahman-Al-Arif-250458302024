<div id="dashboard">
    <div class="p-3">
        <div class="input-group w-100 mb-3 d-flex flex-column flex-md-row align-items-start align-items-md-center">
            <input type="text" class="form-control me-2 w-100 w-md-50" placeholder="Cari judul atau penulis..."
                id="search-input" wire:model.live.debounce.300ms="search" style="height: 38px;" />
            <nav class="w-100 mt-2 overflow-x-auto pb-2">
                <div class="d-flex flex-nowrap gap-2" role="group" aria-label="Filter category">
                    <button type="button" wire:click.prevent="setCategory('All')"
                        class="btn btn-sm {{ $category === 'All' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua
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
            </nav>
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
                        <a href="{{ route('detail-article', ['slug' => $article->slug, 'from' => 'dashboard']) }}"
                            class="text-decoration-none text-dark">
                            <img class="card-img-top img-fluid rounded-top-3 object-cover"
                                style="height: 200px; width: 100%; object-fit: cover;"
                                src="{{ !empty($article->image) && file_exists(storage_path('app/public/' . $article->image))
                                    ? asset('storage/' . $article->image) //Laravel membangun URL publik untuk gambar
                                    : asset('img/Login.jpg') }}"
                                alt="{{ $article->title }}">
                            <div class="card-body" wire:key="article-{{ $article->id }}">
                                <h4 class="card-title text-secondary">{{ $article->title }}</h4>
                                @php
                                    $preview = \App\Helpers\ContentHelper::preview($article->content, 120);
                                @endphp
                                <p class="card-text text-secondary">{{ $preview }}</p>
                                <span class="badge {{ $article->category->color }}">
                                    {{ $article->category->name }}
                                </span>
                            </div>
                        </a>

                        <div class="card-footer border-0 d-flex justify-content-between align-items-center px-3 pb-3">
                            <div class="btn-group">
                                <button type="button" class="btn btn-link p-2 text-decoration-none"
                                    wire:click="toggleLike({{ $article->id }})">
                                    <i
                                        class="bi bi-heart{{ $article->is_liked ? '-fill text-danger' : ' text-secondary' }}"></i>
                                    <small class="text-muted">{{ $article->likes_count }}</small>
                                </button>

                                <button type="button" class="btn btn-link p-2 text-decoration-none">
                                    <i class="bi bi-chat text-secondary"></i>
                                    <small class="text-muted">{{ $article->comments_count }}</small>
                                </button>
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
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('post-created', (event) => {});
    });
</script>
