<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $user = auth('sanctum')->user();

        $query = Article::withFullDetails($user)->latest();

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)->orWhere('id', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $articles = $query->paginate((int) $request->get('per_page', 10));

        $articles->getCollection()->transform(function ($article) {
            $article->isLiked = (bool) $article->is_liked;
            return $article;
        });

        return ArticleResource::collection($articles);
    }

    public function show($identifier): ArticleResource
    {
        $user = auth('sanctum')->user();

        $article = Article::withFullDetails($user)
            ->with('comments.user:id,name,photo_profile')
            ->where(function ($q) use ($identifier) {
                $q->where('id', $identifier)->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        $article->isLiked = (bool) $article->is_liked;

        return new ArticleResource($article);
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle(
            $request->validated(),
            $request->file('image'),
            $request->user()
        );

        $article->load(['user', 'category']);

        return response()->json([
            'message' => 'Article created successfully',
            'data' => new ArticleResource($article)
        ], 201);
    }

    public function update(UpdateArticleRequest $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);

        if ($article->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $article = $this->articleService->updateArticle(
            $article,
            $request->only(['title', 'content', 'category_id', 'status']),
            $request->file('image')
        );

        $fresh = $article->fresh()->load(['user', 'category']);

        return response()->json([
            'message' => 'Article updated successfully',
            'data' => new ArticleResource($fresh)
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);

        if ($article->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->articleService->deleteArticle($article);

        return response()->json([
            'message' => 'Article deleted successfully'
        ]);
    }
}