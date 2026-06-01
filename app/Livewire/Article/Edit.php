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
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Services\ArticleService;

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


    public function update(ArticleService $articleService)
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

            $data = [
                'title'       => $this->title,
                'content'     => clean($this->content),
                'category_id' => $this->category_id,
            ];

            // Only update slug if title changed, to avoid unnecessary changes
            if ($article->title !== $this->title) {
                $slug = Str::slug($this->title);
                if (Article::where('slug', $slug)->where('id', '!=', $this->articleId)->exists()) {
                    $slug .= '-' . time();
                }
                $data['slug'] = $slug;
            }

            $articleService->updateArticle($article, $data, $this->image);

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
