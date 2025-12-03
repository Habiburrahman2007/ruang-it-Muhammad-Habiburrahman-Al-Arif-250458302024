<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class EditArticle extends Component
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

        // Authorization: Only article owner can edit
        if ($article->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->articleId = $article->id;
        $this->title = $article->title;
        $this->content = $article->content ?? '';
        $this->category_id = $article->category_id;
        $this->oldImage = $article->image;
        $this->categories = \App\Helpers\CategoryCache::all();
    }

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

            // Step 3: CRITICAL - Validate magic bytes (actual file content)
            $imageInfo = @getimagesize($this->image->getRealPath());
            if (!$imageInfo) {
                $this->addError('image', 'File bukan gambar yang valid atau file rusak.');
                $this->image = null;
                return;
            }

            // Step 4: Validate dimensions
            [$width, $height] = $imageInfo;
            if ($width < 100 || $height < 100 || $width > 4000 || $height > 4000) {
                $this->addError('image', 'Ukuran gambar harus antara 100x100 dan 4000x4000 pixels.');
                $this->image = null;
            }
        }
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $article = Article::findOrFail($this->articleId);

            // Double-check authorization before update
            if ($article->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $slug = Str::slug($this->title);
            $slugCount = Article::where('slug', $slug)
                ->where('id', '!=', $this->articleId)
                ->count();

            if ($slugCount > 0) {
                $slug = $slug . '-' . time();
            }

            if ($this->image) {
                // Secure file upload with random filename
                $extension = $this->image->getClientOriginalExtension();
                $filename = uniqid('article_', true) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $imagePath = $this->image->storeAs('articles', $filename, 'public');

                // Delete old image only after new one is successfully stored
                if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                    try {
                        Storage::disk('public')->delete($this->oldImage);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete old image', [
                            'path' => $this->oldImage,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } else {
                $imagePath = $this->oldImage;
            }

            // Sanitize HTML content to prevent XSS
            $sanitizedContent = \App\Helpers\HtmlSanitizer::sanitize($this->content);

            $article->update([
                'title' => $this->title,
                'content' => $sanitizedContent,
                'category_id' => $this->category_id,
                'slug' => $slug,
                'image' => $imagePath,
            ]);

            session()->flash('article_updated', true);
            return redirect()->route('dashboard');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to update article', [
                'article_id' => $this->articleId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Gagal mengupdate artikel. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error('Unexpected error in EditArticle', [
                'article_id' => $this->articleId,
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Terjadi kesalahan. Silakan hubungi administrator.');
        }
    }

    public function render()
    {
        return view('livewire.article.edit-article');
    }
}
