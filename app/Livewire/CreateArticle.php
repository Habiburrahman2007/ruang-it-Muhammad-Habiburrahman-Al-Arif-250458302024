<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateArticle extends Component
{
    use WithFileUploads;

    #[Layout('components.layouts.app')]
    #[Title('Tambah artikel')]

    public $title;
    public $content;
    public $category_id;
    public $image;
    public $categories;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
    ];

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:2048',
        ]);
    }

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function save()
    {
        $this->validate();
        $imagePath = $this->image ? $this->image->store('articles', 'public') : null;
        Article::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . uniqid(),
            'content' => $this->content,
            'status' => 'active',
            'image' => $imagePath,
            'category_id' => $this->category_id,
        ]);
        session()->flash('article_created', true);
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.article.create-article');
    }
}
