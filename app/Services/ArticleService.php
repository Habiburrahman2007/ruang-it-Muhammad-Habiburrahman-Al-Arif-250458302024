<?php

namespace App\Services;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Traits\UploadsFiles;

class ArticleService
{
    use UploadsFiles;

    /**
     * Create a new article.
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $imageFile
     * @param User $user
     * @return Article
     */
    public function createArticle(array $data, $imageFile, User $user): Article
    {
        $imagePath = null;
        if ($imageFile) {
            $imagePath = $this->uploadFile($imageFile, Article::IMAGE_PATH);
        }

        $article = Article::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'slug' => Str::slug($data['title']) . '-' . Str::random(6),
            'content' => $data['content'],
            'status' => $data['status'] ?? Article::STATUS_ACTIVE,
            'image' => $imagePath,
            'category_id' => $data['category_id'],
        ]);

        return $article;
    }

    /**
     * Update an existing article.
     *
     * @param Article $article
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $imageFile
     * @return Article
     */
    public function updateArticle(Article $article, array $data, $imageFile = null): Article
    {
        if ($imageFile) {
            $this->deleteFile($article->image);
            $data['image'] = $this->uploadFile($imageFile, Article::IMAGE_PATH);
        }

        $article->update($data);

        return $article;
    }

    /**
     * Delete an article.
     *
     * @param Article $article
     * @return void
     */
    public function deleteArticle(Article $article): void
    {
        $this->deleteFile($article->image);
        $article->delete();
    }
}
