<div>
    <div class="card position-relative border-0 shadow-sm">
        <div class="card-body position-relative p-5">

            <span class="badge {{ $article->category->color }} position-absolute top-0 start-10 mt-4 me-4">
                {{ $article->category->name }}
            </span>

            <div class="position-absolute top-0 end-0 d-none d-md-flex flex-column align-items-end me-4"
                style="margin-top: 4rem;">
                <div class="btn-group mb-2">
                    <button type="button" class="btn btn-link p-2 text-decoration-none"
                        wire:click="toggleLike({{ $article->id }})">
                        <i class="bi bi-heart{{ $article->isLiked ? '-fill text-danger' : ' text-secondary' }}"></i>
                        <small class="text-muted">{{ $article->likes->count() }}</small>
                    </button>
                    <button type="button" class="btn btn-link p-2 text-decoration-none">
                        <a href="#comments"><i class="bi bi-chat text-secondary"></i></a>
                        <small class="text-muted">{{ $article->comments->count() }}</small>
                    </button>
                </div>

                @if (Auth::id() === $article->user_id)
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none p-0" type="button"
                            id="dropdownMenuButton{{ $article->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical text-secondary fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                            aria-labelledby="dropdownMenuButton{{ $article->id }}">
                            <li>
                                <a class="dropdown-item" href="{{ route('edit-article', $article->slug) }}">
                                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit
                                </a>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger" onclick="confirmDelete({{ $article->id }})">
                                    <i class="bi bi-trash me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            <h1 class="fw-bold display-5 my-4" style="line-height: 1.2;">
                {{ $article->title }}
            </h1>

            <div class="d-flex align-items-center mt-4 mb-2">
                <div class="avatar avatar-md">
                    <img src="{{ $article->user->photo_profile ? asset('storage/' . $article->user->photo_profile) : asset('img/default-avatar.jpeg') }}"
                        alt="Foto Profil {{ $article->user->name }}">
                </div>
                <div class="ms-3">
                    <h6 class="mb-0 fw-bold text-md">{{ $article->user->name }}</h6>
                    <p class="mb-0 small text-muted">{{ $article->user->profession }}</p>
                </div>
            </div>

            <div class="d-flex d-md-none justify-content-end align-items-center gap-3 mb-3">
                <button type="button" class="btn btn-link p-0 text-decoration-none"
                    wire:click="toggleLike({{ $article->id }})">
                    <i class="bi bi-heart{{ $article->isLiked ? '-fill text-danger' : ' text-secondary' }}"></i>
                    <small class="text-muted ms-1">{{ $article->likes->count() }}</small>
                </button>

                <a href="#comments" class="btn btn-link p-0 text-decoration-none">
                    <i class="bi bi-chat text-secondary"></i>
                    <small class="text-muted ms-1">{{ $article->comments->count() }}</small>
                </a>

                @if (Auth::id() === $article->user_id)
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none p-0" type="button"
                            id="dropdownMenuButtonMobile{{ $article->id }}" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-three-dots-vertical text-secondary fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                            aria-labelledby="dropdownMenuButtonMobile{{ $article->id }}">
                            <li>
                                <a class="dropdown-item" href="{{ route('edit-article', $article->slug) }}">
                                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit
                                </a>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger" onclick="confirmDelete({{ $article->id }})">
                                    <i class="bi bi-trash me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            @if ($article->status === 'banned')
                <div class="text-center my-3">
                    <h6>
                        <span class="text-danger">Artikel ini sedang diblokir sehingga tidak bisa diakses oleh para
                            pembaca.</span> <br>
                        Silahkan mengikuti panduan penulisan artikel <a
                            href="{{ route('guidelines', ['from' => request('from')]) }}"
                            class="text-primary">disini</a>
                        dan kembali mengedit artikel ini agar statusnya kembali aktif. <br>
                        Hubungi <a href="https://wa.link/zjo1b2" class="text-primary" target="blank">admin</a> untuk
                        konsultasi.
                    </h6>
                </div>
            @endif

            @if ($article->image)
                <div class="d-flex justify-content-center my-4">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
                        class="img-fluid rounded shadow-sm" style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
            @endif

            <div class="card-text mt-4" style="font-size: 1.1rem; line-height: 1.8;">
                {!! $article->content !!}
            </div>
        </div>
    </div>

    <div class="col-12">

        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-comments me-2"></i> Tambahkan Komentar
                </h5>
            </div>

            <div class="card-body">
                @auth
                    <div class="card-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form wire:submit.prevent="postComment">
                            <div class="mb-3">
                                <textarea wire:model.defer="newComment" id="comment" class="form-control @error('newComment') is-invalid @enderror"
                                    rows="3" placeholder="Tulis komentar kamu di sini..." wire:loading.attr="disabled"></textarea>
                                @error('newComment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        Kirim
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-1" role="status"
                                            aria-hidden="true"></span>
                                        Mengirim...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Silakan <a href="{{ route('login') }}" class="alert-link">login</a> untuk berkomentar.
                    </div>
                @endauth
            </div>
        </div>

        <div>
            @if ($comments->isEmpty())
                <div class="card mt-4 shadow-sm">
                    <div class="card-body text-center text-muted fst-italic my-3">
                        Belum ada komentar. Jadilah yang pertama!
                    </div>
                </div>
            @else
                @foreach ($comments as $comment)
                    <div class="card mt-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-start">
                                    <div class="avatar avatar-sm me-3"
                                        style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                        <img src="{{ $comment->user->photo_profile ? asset('storage/' . $comment->user->photo_profile) : asset('img/default-avatar.jpeg') }}"
                                            alt="{{ $comment->user->name }}" class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $comment->user->name }}</h6>
                                        <small class="text-muted d-block mb-2">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </small>

                                        @if ($comment->is_hidden)
                                            <p class="mb-0 fst-italic text-muted">
                                                Komentar disembunyikan. <br>
                                                @if (auth()->id() === $comment->user_id)
                                                    Silahkan mengikuti panduan penulisan komentar <a
                                                        href="{{ route('guidelines', ['from' => request('from')]) }}"
                                                        class="text-primary">disini</a>
                                                    dan kembali mengedit komentar ini agar bisa kembali muncul. <br>
                                                    Hubungi <a href="https://wa.link/zjo1b2" class="text-primary"
                                                        target="blank">admin</a> untuk
                                                    konsultasi.
                                                @endif
                                            </p>
                                        @else
                                            <p class="mb-0">{{ $comment->content }}</p>
                                        @endif
                                    </div>
                                </div>

                                @if (auth()->id() === $comment->user_id)
                                    <div class="dropdown">
                                        <button class="btn btn-link text-decoration-none p-0" type="button"
                                            id="commentMenuButton{{ $comment->id }}" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical text-secondary fs-6"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                            aria-labelledby="commentMenuButton{{ $comment->id }}">
                                            <li>
                                                <button class="dropdown-item text-success"
                                                    wire:click="editComment({{ $comment->id }})"
                                                    data-bs-toggle="modal" data-bs-target="#editCommentModal">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button wire:click="deleteComment({{ $comment->id }})"
                                                    class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i>Hapus
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (\App\Models\Comment::where('article_id', $article->id)->count() > $comments->count())
                    <div x-intersect.full="$wire.loadMore()" class="d-flex justify-content-center py-4">
                        <div wire:loading wire:target="loadMore" class="text-primary">
                            <i class="fa fa-spinner fa-spin fa-2x"></i>
                            <span class="ms-2 fw-bold">Sedang memuat komentar lainnya...</span>
                        </div>

                        <div wire:loading.remove wire:target="loadMore" class="text-muted small">
                            Scroll untuk memuat lebih banyak...
                        </div>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <p>Semua komentar sudah ditampilkan.</p>
                    </div>
                @endif

            @endif
        </div>

        <div wire:ignore.self class="modal fade" id="editCommentModal" tabindex="-1"
            aria-labelledby="editCommentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCommentModalLabel">Edit Komentar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body form-label">
                        <textarea wire:model="editedCommentContent" id="edit-comment-textarea" class="form-control" rows="3"></textarea>
                        @error('editedCommentContent')
                            <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updateComment">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
