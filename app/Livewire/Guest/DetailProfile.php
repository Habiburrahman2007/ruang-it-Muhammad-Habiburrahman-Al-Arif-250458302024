<?php

namespace App\Livewire\Guest;

use App\Models\User;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class DetailProfile extends Component
{
    #[Layout('layouts.guesslyt')]
    #[Title('Detail profil')]

    public $user;
    public $articles;
    public $categories = [];
    public $category = 'All';
    public $search = '';

    public $articleCount = 0;
    public $likeCount = 0;
    public $commentCount = 0;

    public function mount($slug)
    {
        $this->user = User::with(['articles.category', 'articles.likes', 'articles.comments'])
            ->where('slug', $slug)
            ->firstOrFail();

        // ambil semua kategori yang digunakan user ini
        $this->categories = Category::whereHas('articles', function ($q) {
            $q->where('user_id', $this->user->id);
        })->get();

        $this->loadArticles();
    }

    // 🔹 Fungsi untuk memuat artikel berdasarkan kategori dan pencarian
    public function loadArticles()
    {
        $query = $this->user->articles()->with(['category', 'likes', 'comments']);

        if ($this->category !== 'All') {
            $query->whereHas('category', fn($q) => $q->where('name', $this->category));
        }

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // hasilnya Collection, bukan array
        $currentUserId = Auth::id();
        $this->articles = $query->latest()
            ->when($currentUserId, function ($q) use ($currentUserId) {
                return $q->selectRaw('articles.*, EXISTS(
                    SELECT 1 FROM likes 
                    WHERE likes.article_id = articles.id 
                    AND likes.user_id = ?
                ) as is_liked', [$currentUserId]);
            })
            ->get();

        // bisa pakai count() dan sum()
        $this->articleCount = $this->articles->count();
        $this->likeCount = $this->articles->sum(fn($a) => $a->likes->count());
        $this->commentCount = $this->articles->sum(fn($a) => $a->comments->count());
    }


    // 🔹 Ketika kategori diganti
    public function setCategory($category)
    {
        $this->category = $category;
        $this->loadArticles();
    }

    // 🔹 Ketika search berubah secara live
    public function updatedSearch()
    {
        $this->loadArticles();
    }

    public function toggleLike($articleId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $article = \App\Models\Article::findOrFail($articleId);
        $like = $article->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
        } else {
            $article->likes()->create(['user_id' => Auth::id()]);
        }

        $this->loadArticles();
    }

    public function render()
    {
        return view('livewire.guest.detail-profile');
    }
}
