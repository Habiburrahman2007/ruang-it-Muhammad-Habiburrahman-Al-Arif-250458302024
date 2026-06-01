<?php

namespace App\Livewire\Article;

use App\Models\Article;
use Illuminate\Database\QueryException;
use App\Helpers\CategoryCache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\ArticleService;

class Create extends Component
{
    use WithFileUploads;

    #[Layout('layouts.app')]
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
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ];

    public function updatedImage()
    {
        
        $this->validate([
            'image' => 'image|max:2048',
        ]);

        if ($this->image) {
            
            $mimeType = $this->image->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($mimeType, $allowedMimes)) {
                $this->addError('image', 'Tipe file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.');
                $this->image = null;
                return;
            }
        }
    }

    public function mount()
    {
        $this->categories = CategoryCache::all();
    }

    public function save(ArticleService $articleService)
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'content' => clean($this->content),
                'category_id' => $this->category_id,
            ];

            $articleService->createArticle($data, $this->image, Auth::user());

            session()->flash('article_created', true);
            return redirect()->route('dashboard');
        } catch (QueryException $e) {
            Log::error('Failed to create article', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Gagal menyimpan artikel. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error('Unexpected error in CreateArticle', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Terjadi kesalahan. Silakan hubungi administrator.');
        }
    }

    public function render()
    {
        return view('livewire.article.create-article');
    }
}
