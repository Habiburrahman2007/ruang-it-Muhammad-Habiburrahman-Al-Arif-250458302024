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
        // Step 1: Basic Laravel validation
        $this->validate([
            'image' => 'image|max:2048',
        ]);

        if ($this->image) {
            // Step 2: Validate server-detected MIME type
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

    public function save()
    {
        $this->validate();

        try {
            if ($this->image) {
                $extension = $this->image->getClientOriginalExtension();
                $filename = uniqid('article_', true) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $imagePath = $this->image->storeAs('articles', $filename, 'public');
            } else {
                $imagePath = null;
            }

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
