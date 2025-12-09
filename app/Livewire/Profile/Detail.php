<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Detail extends Component
{
    #[Layout('layouts.app')]
    #[Title('Detail profil')]

    public $user;
    public $articles;
    public $categories = [];
    public $category = 'All';
    public $search = '';

    public $articleCount = 0;
    public $likeCount = 0;
    public $commentCount = 0;

    public $perPage = 9;
    public $totalArticles = 0;
    public $isLoadingMore = false;

    public function mount($slug)
    {
        $this->user = User::where('slug', $slug)->firstOrFail();

        // ambil semua kategori yang digunakan user ini
        $this->categories = Category::whereHas('articles', function ($q) {
            $q->where('user_id', $this->user->id);
        })->get();

        $this->loadArticles();
    }

    // 🔹 Fungsi untuk memuat artikel berdasarkan kategori dan pencarian
    public function loadArticles()
    {
        $query = $this->user->articles()
            ->with(['category'])
            ->withCount(['likes', 'comments']);

        if ($this->category !== 'All') {
            $query->whereHas('category', fn($q) => $q->where('name', $this->category));
        }

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Hitung total artikel terfilter untuk kontrol Load More
        $this->totalArticles = $query->count();

        $currentUserId = Auth::user()->id;

        // hasilnya Collection, bukan array
        $this->articles = $query->latest()
            ->when($currentUserId, function ($q) use ($currentUserId) {
                return $q->selectRaw('articles.*, EXISTS(
                    SELECT 1 FROM likes 
                    WHERE likes.article_id = articles.id 
                    AND likes.user_id = ?
                ) as is_liked', [$currentUserId]);
            })
            ->take($this->perPage)
            ->get();

        // Statistik menggunakan aggregate query yang efisien
        $this->articleCount = $this->user->articles()->count();
        // Gunakan query terpisah yang dikhususkan untuk count relasi
        $this->likeCount = $this->user->articles()->withCount('likes')->get()->sum('likes_count');
        $this->commentCount = $this->user->articles()->withCount('comments')->get()->sum('comments_count');
    }

    public function loadMore()
    {
        if ($this->perPage < $this->totalArticles) {
            $this->isLoadingMore = true;
            $this->perPage += 9;
            $this->loadArticles();
            $this->isLoadingMore = false;
        }
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

    public function render()
    {
        return view('livewire.profile.detail');
    }
}
