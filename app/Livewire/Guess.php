<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Guess extends Component
{
    #[Layout('components.layouts.guesslyt')]
    #[Title('Halaman tamu')]
    public $articles;
    public $user;
    public $category = 'All';
    public $categories = [];
    public $search = '';
    public $isLoadingMore = false;
    public $perPage = 9;
    public $totalArticles;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadCategories();
        $this->loadArticles();
    }

    public function updatedSearch()
    {
        $this->loadArticles();
    }

    public function searchPost()
    {
        $this->loadArticles();
    }

    public function loadCategories()
    {
        $this->categories = \App\Models\Category::all();
    }

    public function loadArticles()
    {
        $query = Article::with(['user', 'category', 'likes', 'comments'])
            ->where('status', 'active')
            ->whereHas('user', fn($q) => $q->where('banned', false))
            ->when(
                $this->category !== 'All',
                fn($query) =>
                $query->whereHas('category', fn($q) => $q->where('name', $this->category))
            )
            ->when(
                $this->search,
                fn($query) =>
                $query->where(
                    fn($q) =>
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'))
                )
            )
            ->latest();

        // total artikel untuk kontrol "Load More"
        $this->totalArticles = $query->count();

        $this->articles = $query->take($this->perPage)->get()
            ->map(function ($article) {
                $article->isLiked = $article->likes->where('user_id', $this->user->id ?? null)->count() > 0;
                return $article;
            });
    }

    public function loadMore()
    {
        if ($this->perPage < $this->totalArticles) {
            $this->isLoadingMore = true;

            usleep(500000);

            $this->perPage += 9;
            $this->loadArticles();

            $this->isLoadingMore = false;
        }
    }

    public function setCategory($category)
    {
        $this->category = $category;
        $this->loadArticles();
    }

    public function render()
    {
        return view('livewire.guest.guess');
    }
}
