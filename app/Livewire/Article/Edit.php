<?php

namespace App\Livewire\Article;

use App\Models\Article;
use Illuminate\Database\QueryException;
use App\Helpers\CategoryCache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Edit extends Component
{
    use WithFileUploads;

    public $articleId;
    public $title;
    public $content = '';
    public $category_id;
    public $image;
    public $oldImage;
    public $categories;

    #[Layout('layouts.app')]
    #[Title('Edit artikel')]

    public function mount($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        if ($article->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit artikel ini.');
        }

        $this->articleId = $article->id;
        $this->title = $article->title;
        $this->content = $article->content ?? '';
        $this->category_id = $article->category_id;
        $this->oldImage = $article->image;
        $this->categories = CategoryCache::all();
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }


    public function update()
    {
        $this->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $article = Article::findOrFail($this->articleId);

            if ($article->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit artikel ini.');
            }
            $slug = Str::slug($this->title);
            if (Article::where('slug', $slug)->where('id', '!=', $this->articleId)->exists()) {
                $slug .= '-' . time();
            }

            $imagePath = $this->oldImage;
            if ($this->image) {
                $filename  = uniqid('article_', true) . '.' . $this->image->getClientOriginalExtension();
                $imagePath = $this->image->storeAs('articles', $filename, 'public');

                if ($this->oldImage) {
                    Storage::disk('public')->delete($this->oldImage);
                }
            }

            $article->update([
                'title'       => $this->title,
                'content'     => clean($this->content),
                'category_id' => $this->category_id,
                'slug'        => $slug,
                'image'       => $imagePath,
            ]);

            session()->flash('article_updated', true);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $type = $e instanceof QueryException
                ? 'DB Error'
                : 'General Error';

            Log::error("$type saat update article", [
                'article_id' => $this->articleId,
                'user_id'    => Auth::id(),
                'error'      => $e->getMessage(),
            ]);

            session()->flash('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }


    public function render()
    {
        return view('livewire.article.edit-article');
    }
}
