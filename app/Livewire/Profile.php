<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Profile extends Component
{
    #[Layout('layouts.app')]
    #[Title('Halaman Profil')]

    public $user;
    public $articles;
    public $articleCount;
    public $likeCount;
    public $commentCount;
    public $category = 'All';
    public $categories = [];
    public $search = '';
    public $perPage = 9;
    public $totalFiltered = 0;

    public function mount()
    {
        $this->user = Auth::user();

        $this->articleCount = $this->user->articles()->count();
        $this->likeCount = Like::whereIn('article_id', $this->user->articles()->pluck('id'))->count();
        $this->commentCount = Comment::whereIn('article_id', $this->user->articles()->pluck('id'))->count();
        $this->loadCategories();
        $this->loadArticles();
    }

    public function updatedSearch()
    {
        $this->loadArticles();
        $this->resetPagination();
    }

    public function loadCategories()
    {
        $this->categories = Category::pluck('name')->toArray();
    }

    public function searchPost()
    {
        $this->loadArticles();
    }

    public function loadArticles()
    {
        $userId = $this->user->id;

        $query = Article::with(['category'])
            ->withCount(['likes', 'comments']) // ✅ Only count
            ->selectRaw('articles.*, EXISTS(
                SELECT 1 FROM likes 
                WHERE likes.article_id = articles.id 
                AND likes.user_id = ?
            ) as is_liked', [$userId])
            ->where('user_id', $this->user->id)
            ->when($this->category !== 'All', function ($query) {
                $query->whereHas('category', fn($q) => $q->where('name', $this->category));
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            });
        $this->totalFiltered = $query->count();
        $this->articles = $query
            ->latest()
            ->take($this->perPage)
            ->get();
    }

    public function loadMore()
    {
        if ($this->perPage < $this->totalFiltered) {
            $this->perPage += 6;
            $this->loadArticles();
        }
    }

    protected function resetPagination()
    {
        $this->perPage = 6;
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
            Like::firstOrCreate([
                'user_id' => $user->id,
                'article_id' => $articleId,
            ]);
        }

        $this->loadArticles();
    }

    public function setCategory($category)
    {
        $this->category = $category;
        $this->loadArticles();
        $this->resetPagination();
    }

    #[On('logoutConfirmed')]

    public function logout()
    {
        \Illuminate\Support\Facades\Log::info('Logout method called in Profile component');
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/auth/login');
    }

    public function render()
    {
        return view('livewire.profile.profile');
    }
}
