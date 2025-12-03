<?php

namespace App\Livewire\Guest;

use App\Models\Like;
use App\Models\Article;
use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DetailArticle extends Component
{
    #[Layout('layouts.guesslyt')]
    #[Title('Detail profil')]

    public $article;
    public $slug;
    public $user;
    public $comments = [];
    public $newComment;
    public $perPage = 3;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->article = Article::with(['user', 'category'])->where('slug', $slug)->firstOrFail();
        $this->user = Auth::user();

        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = $this->article->comments()
            ->with('user')
            ->latest()
            ->take($this->perPage)
            ->get();
    }

    public function loadMore()
    {
        $this->perPage += 3;
        $this->loadComments();
    }

    public function postComment()
    {
        if (!$this->user) {
            session()->flash('error', 'Kamu harus login untuk berkomentar.');
            return;
        }

        $this->validate([
            'newComment' => 'required|string|max:500',
        ]);

        Comment::create([
            'user_id' => $this->user->id,
            'article_id' => $this->article->id,
            'content' => $this->newComment,
        ]);

        $this->newComment = ''; // reset input
        $this->loadComments(); // refresh komentar
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);

        if ($comment && $comment->user_id === Auth::id()) {
            $comment->delete();
            $this->loadComments();
        }
    }

    public function deleteArticle($id)
    {
        $article = Article::find($id);

        if (!$article || $article->user_id !== Auth::id()) {
            session()->flash('error', 'Kamu tidak punya izin untuk menghapus artikel ini.');
            return;
        }

        if ($article->image_id && Storage::exists('public/' . $article->image_id)) {
            Storage::delete('public/' . $article->image_id);
        }

        $article->delete();

        session()->flash('success', 'Artikel berhasil dihapus.');
        return redirect()->route('dashboard');
    }

    public function toggleLike($articleId)
    {
        $user = $this->user;
        if (!$user) return;

        $like = Like::where('user_id', $user->id)
            ->where('article_id', $articleId)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'article_id' => $articleId,
            ]);
        }

        $this->loadArticles(); // pastikan method ini memang ada
    }

    public function render()
    {
        return view('livewire.guest.detail-article');
    }
}
