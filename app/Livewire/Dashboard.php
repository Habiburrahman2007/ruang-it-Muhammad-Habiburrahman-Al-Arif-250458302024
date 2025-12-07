<?php

namespace App\Livewire;

use App\Models\Like;
use App\Models\Article;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    #[Title('Dashboard')]

    #[Url(history: true)]
    public $articles = [];
    public $user;
    public $category = 'All';
    public $categories = [];
    public $search = '';
    public $isLoadingMore = false;
    public $perPage = 9;
    public $totalArticles;
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadCategories();
        $this->loadArticles();
    }

    public function loadCategories()
    {
        $this->categories = \App\Helpers\CategoryCache::all();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadArticles();
    }

    public function searchPost()
    {
        $this->resetPage();
        $this->loadArticles();
    }

    public function resetPage()
    {
        $this->perPage = 9;
        $this->page = 1;
    }

    public function loadArticles()
    {
        $userId = $this->user->id ?? null;

        $query = Article::with(['user', 'category'])
            ->withCount(['likes', 'comments']) // ✅ Only count, not load all records
            ->when($userId, function ($q) use ($userId) {
                // ✅ Add isLiked as computed column
                return $q->selectRaw('articles.*, EXISTS(
                    SELECT 1 FROM likes 
                    WHERE likes.article_id = articles.id 
                    AND likes.user_id = ?
                ) as is_liked', [$userId]);
            })
            ->where('status', 'active')
            ->whereHas('user', fn($q) => $q->where('banned', false))
            ->when(
                $this->category !== 'All',
                fn($q) => $q->whereHas('category', fn($q2) => $q2->where('name', $this->category))
            )
            ->when(
                $this->search,
                fn($q) =>
                $q->where(
                    fn($q2) =>
                    $q2->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', fn($q3) => $q3->where('name', 'like', '%' . $this->search . '%'))
                )
            )
            ->latest();

        $this->totalArticles = $query->count();

        $this->articles = $query->take($this->perPage)->get();
    }

    public function loadMore()
    {
        if ($this->perPage < $this->totalArticles) {
            $this->isLoadingMore = true;
            usleep(300000);
            $this->perPage += 9;
            $this->loadArticles();
            $this->isLoadingMore = false;
        }
    }

    public function toggleLike($articleId)
    {
        $user = $this->user;
        if (!$user) return;

        try {
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

            $this->loadArticles();
        } catch (QueryException $e) {
            Log::error('Failed to toggle like', [
                'user_id' => $user->id,
                'article_id' => $articleId,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function setCategory($category)
    {
        $this->category = $category;
        $this->resetPage();
        $this->loadArticles();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
