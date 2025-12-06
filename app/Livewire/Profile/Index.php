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
        $this->user = Auth::user()->load(['articles.category', 'articles.likes', 'articles.comments']);

        // Get all categories used by this user
        $this->categories = Category::whereHas('articles', function ($q) {
            $q->where('user_id', $this->user->id);
        })->get();

        $this->loadArticles();
    }

    public function loadArticles()
    {
        $query = $this->user->articles()->with(['category', 'likes', 'comments', 'user']);

        if ($this->category !== 'All') {
            $query->whereHas('category', fn($q) => $q->where('name', $this->category));
        }

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $this->totalFiltered = $query->count();

        $this->articles = $query->latest()->take($this->perPage)->get();

        // Calculate statistics
        $allArticles = $this->user->articles()->with(['likes', 'comments'])->get();
        $this->articleCount = $allArticles->count();
        $this->likeCount = $allArticles->sum(fn($a) => $a->likes->count());
        $this->commentCount = $allArticles->sum(fn($a) => $a->comments->count());

        // Mark articles as liked by current user
        foreach ($this->articles as $article) {
            $article->isLiked = $article->likes->contains('user_id', Auth::id());
        }
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
