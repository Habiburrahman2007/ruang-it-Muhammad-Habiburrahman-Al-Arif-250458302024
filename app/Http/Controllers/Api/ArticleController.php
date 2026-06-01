<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = auth('sanctum')->user();

        $query = Article::with(['user:id,name,slug,photo_profile,profession', 'category:id,name,color'])
            ->withCount(['comments', 'likes'])
            ->withExists(['likes as is_liked' => function ($q) use ($user) {
                $q->where('user_id', $user ? $user->id : null);
            }])
            ->whereHas('user', function ($q) {
                $q->where('banned', false);
            })
            ->latest();

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

        $article = Article::with([
            'user:id,name,slug,photo_profile,bio,profession,banned',
            'category:id,name,color',
            'comments.user:id,name,photo_profile',
        ])
        ->withCount(['comments', 'likes'])
        ->withExists(['likes as is_liked' => function ($q) use ($user) {
            $q->where('user_id', $user ? $user->id : null);
        }])
        ->whereHas('user', function ($q) {
            $q->where('banned', false);
        })
        ->where(function ($q) use ($identifier) {
            $q->where('id', $identifier)->orWhere('slug', $identifier);
        })
        ->firstOrFail();

        $article->isLiked = (bool) $article->is_liked;

        return new ArticleResource($article);
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = uniqid('article_', true) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $imagePath = $request->file('image')->storeAs('articles', $filename, 'public');
        }

        $article = Article::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
            'status' => 'active',
            'image' => $imagePath,
            'category_id' => $request->category_id,
        ]);

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

        $data = $request->only(['title', 'content', 'category_id', 'status']);

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = uniqid('article_', true) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $data['image'] = $request->file('image')->storeAs('articles', $filename, 'public');
        }

        $article->update($data);
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

        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return response()->json([
            'message' => 'Article deleted successfully'
        ]);
    }
}