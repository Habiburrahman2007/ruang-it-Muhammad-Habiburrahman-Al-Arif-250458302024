<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Article;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class LandingPage extends Component
{
    #[Layout('layouts.lp')]
    #[Title('Ruang IT | Landing Page')]

    public $articles;
    public $stats = [];

    public function mount()
    {
        $this->articles = Article::latest()->take(9)->get();
        $this->stats = [
            [
                'title' => Article::where('status', 'active')->count() . ' Artikel',
                'desc'  => 'Artikel IT berkualitas tinggi diperbarui secara berkala',
            ],
            [
                'title' => Category::count() . ' Kategori',
                'desc'  => 'Berbagai topik mulai dari coding hingga AI',
            ],
            [
                'title' => User::count() . ' Penulis',
                'desc'  => 'Penulis aktif dari berbagai bidang teknologi',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.landing-page');
    }
}
