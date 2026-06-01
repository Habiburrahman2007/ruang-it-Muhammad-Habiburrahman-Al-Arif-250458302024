<?php

namespace App\Services;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Traits\UploadsFiles;
use Illuminate\Support\Facades\DB;

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

        try {
            return DB::transaction(function () use ($data, $user, $imagePath) {
                return Article::create([
                    'user_id' => $user->id,
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']) . '-' . Str::random(6),
                    'content' => $data['content'],
                    'status' => $data['status'] ?? Article::STATUS_ACTIVE,
                    'image' => $imagePath,
                    'category_id' => $data['category_id'],
                ]);
            });
        } catch (\Exception $e) {
            if ($imagePath) {
                $this->deleteFile($imagePath);
            }
            throw $e;
        }
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
        $oldImage = $article->image;
        $newImagePath = null;

        if ($imageFile) {
            $newImagePath = $this->uploadFile($imageFile, Article::IMAGE_PATH);
            $data['image'] = $newImagePath;
        }

        try {
            DB::transaction(function () use ($article, $data) {
                $article->update($data);
            });

            if ($newImagePath && $oldImage) {
                $this->deleteFile($oldImage);
            }

            return $article;
        } catch (\Exception $e) {
            if ($newImagePath) {
                $this->deleteFile($newImagePath);
            }
            throw $e;
        }
    }

    /**
     * Delete an article.
     *
     * @param Article $article
     * @return void
     */
    public function deleteArticle(Article $article): void
    {
        DB::transaction(function () use ($article) {
            $this->deleteFile($article->image);
            $article->delete();
        });
    }
}
