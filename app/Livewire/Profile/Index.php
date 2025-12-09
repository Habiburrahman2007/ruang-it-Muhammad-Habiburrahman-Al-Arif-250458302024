<?php

namespace App\Livewire\Profile;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    #[Layout('layouts.app')]
    #[Title('Profil Saya')]

    public $user;
    public $articles = [];
    public $categories = [];
    public $category = 'All';
    public $search = '';

    public $articleCount = 0;
    public $likeCount = 0;
    public $commentCount = 0;

    public $perPage = 9;
    public $totalFiltered = 0;

    public function mount()
    {
        $this->user = Auth::user();
        
        // Get all categories used by this user
        $this->categories = Category::whereHas('articles', function ($q) {
            $q->where('user_id', $this->user->id);
        })->get();

        $this->loadArticles();
    }

    public function loadArticles()
    {
        $query = $this->user->articles()
            ->with(['category', 'user']) // Eager load user just in case, though it's auth user
            ->withCount(['likes', 'comments']);

        if ($this->category !== 'All') {
            $query->whereHas('category', fn($q) => $q->where('name', $this->category));
        }

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $this->totalFiltered = $query->count();

        // Optimized query with is_liked subquery
        $this->articles = $query->latest()
            ->selectRaw('articles.*, EXISTS(
                SELECT 1 FROM likes 
                WHERE likes.article_id = articles.id 
                AND likes.user_id = ?
            ) as is_liked', [$this->user->id])
            ->take($this->perPage)
            ->get();

        // Statistics using aggregate queries instead of fetching all models
        $this->articleCount = $this->user->articles()->count();
        $this->likeCount = $this->user->articles()->withCount('likes')->get()->sum('likes_count');
        $this->commentCount = $this->user->articles()->withCount('comments')->get()->sum('comments_count');
    }

    public function loadMore()
    {
        $this->perPage += 9;
        $this->loadArticles();
    }

    public function setCategory($category)
    {
        $this->category = $category;
        $this->perPage = 9;
        $this->loadArticles();
    }

    public function updatedSearch()
    {
        $this->perPage = 9;
        $this->loadArticles();
    }

    public function toggleLike($articleId)
    {
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
        return view('livewire.profile.profile');
    }
}
