<?php

namespace App\Livewire;

use App\Models\Like;
use App\Models\User;
use App\Models\Article;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class DetailProfile extends Component
{
    #[Layout('layouts.app')]
    #[Title('Detail Profil')]

    public $user;
    public $articles;
    public $categories = [];
    public $category = 'All';
    public $search = '';
    public $perPage = 9;
    public $totalFiltered = 0;
    public $articleCount = 0;
    public $likeCount = 0;
    public $commentCount = 0;

    public function mount($slug)
    {
        $this->user = User::with(['articles.category', 'articles.likes', 'articles.comments'])
            ->where('slug', $slug)
            ->firstOrFail();

        $this->categories = Category::whereHas('articles', function ($q) {
            $q->where('user_id', $this->user->id);
        })->pluck('name')->toArray();

        $this->loadArticles();
    }

    public function loadArticles()
    {
        $authUserId = Auth::id();

        $baseQuery = $this->user->articles()
            ->with(['category'])
            ->withCount(['likes', 'comments'])
            ->when($authUserId, function ($q) use ($authUserId) {
                return $q->selectRaw('articles.*, EXISTS(
                    SELECT 1 FROM likes 
                    WHERE likes.article_id = articles.id 
                    AND likes.user_id = ?
                ) as is_liked', [$authUserId]);
            });

        if ($this->category !== 'All') {
            $baseQuery->whereHas('category', fn($q) => $q->where('name', $this->category));
        }
        if ($this->search) {
            $baseQuery->where('title', 'like', '%' . $this->search . '%');
        }
        $this->totalFiltered = $baseQuery->count();
        $this->articles = $baseQuery->latest()->take($this->perPage)->get();
        $this->articleCount = $this->user->articles()->count();
        $this->likeCount = $this->user->articles()->withCount('likes')->get()->sum('likes_count');
        $this->commentCount = $this->user->articles()->withCount('comments')->get()->sum('comments_count');
    }

    public function updatedSearch()
    {
        $this->resetPagination();
        $this->loadArticles();
    }

    public function setCategory($category)
    {
        $this->category = $category;
        $this->resetPagination();
        $this->loadArticles();
    }

    protected function resetPagination()
    {
        $this->perPage = 9;
    }

    public function loadMore()
    {
        if ($this->perPage < $this->totalFiltered) {
            $this->perPage += 9;
            $this->loadArticles();
        }
    }

    public function toggleLike($articleId)
    {
        $authUser = Auth::user();
        $article = Article::find($articleId);

        if (!$article || !$authUser) {
            return;
        }

        $existingLike = Like::where('user_id', $authUser->id)
            ->where('article_id', $articleId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Like::create([
                'user_id' => $authUser->id,
                'article_id' => $articleId,
            ]);
        }

        $this->loadArticles();
    }

    public function render()
    {
        return view('livewire.profile.detail-profile');
    }
}
