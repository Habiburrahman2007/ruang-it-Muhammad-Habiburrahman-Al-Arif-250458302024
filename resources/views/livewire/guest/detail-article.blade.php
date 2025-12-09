<div>
    <div class="card position-relative">
        <div class="card-body position-relative">

            {{-- Badge Kategori --}}
            <span class="badge {{ $article->category->color }} position-absolute top-0 end-0 mt-3 me-3">
                {{ $article->category->name }}
            </span>

            {{-- Like, Comment, & Dropdown --}}
            <div class="position-absolute top-0 end-0 d-flex flex-row align-items-center me-3" style="margin-top: 3rem;">

                {{-- Like & Comment --}}
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-link p-2 text-decoration-none"
                        wire:click.stop="toggleLike({{ $article->id }})" wire:loading.attr="disabled"
                        wire:target="toggleLike({{ $article->id }})">
                        <span wire:loading.remove wire:target="toggleLike({{ $article->id }})">
                            <i class="bi bi-heart{{ $article->isLiked ? '-fill text-danger' : ' text-secondary' }}"></i>
                        </span>
                        <span wire:loading wire:target="toggleLike({{ $article->id }})">
                            <div class="spinner-border spinner-border-sm text-secondary" role="status">
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
            </div>



            {{-- Profil Penulis --}}
            <div class="d-flex align-items-center">
                <div class="avatar avatar-xl">
                    <img src="{{ $article->user->photo_profile ? asset('storage/' . $article->user->photo_profile) : asset('img/default-avatar.jpeg') }}"
                        alt="Foto Profil {{ $article->user->name }}">
                </div>
                <div class="ms-3">
                    <h6 class="mb-0 fw-bold">{{ $article->user->name }}</h6>
                    <p class="mb-0">{{ $article->user->profession }}</p>
                    <small class="text-muted">{{ $article->created_at->format('d F Y') }}</small>
                </div>
            </div>

            <hr>

            <div class="mt-4">
                <h3 class="card-title">{{ $article->title }}</h3>
                @if ($article->image)
                    <div class="d-flex justify-content-center my-3">
                        <img src="{{ !empty($article->image) && file_exists(storage_path('app/public/' . $article->image))
                            ? asset('storage/' . $article->image)
                            : asset('img/Login.jpg') }}"
                            alt="{{ $article->title }}" class="img-fluid rounded"
                            style="width: 50%; height: auto; object-fit: cover;">
                    </div>
                @endif
                <div class="card-text mt-3">
                    {!! $article->content !!}
                </div>
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
                                        <i class="bi bi-send me-1"></i> Kirim
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

                                    {{-- Cek status komentar --}}
                                    @if ($comment->is_hidden)
                                        <p class="mb-0 fst-italic text-muted">Komentar disembunyikan</p>
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
                                                wire:click="editComment({{ $comment->id }})" data-bs-toggle="modal"
                                                data-bs-target="#editCommentModal">
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
