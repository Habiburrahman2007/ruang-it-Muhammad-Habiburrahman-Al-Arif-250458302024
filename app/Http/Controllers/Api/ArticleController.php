<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse; 

class ArticleController extends Controller
{
    
    public function index(Request $request): JsonResponse
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

        return response()->json($articles);
    }

    
    public function show($identifier): JsonResponse
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
        ->first();

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        
        $article->isLiked = (bool) $article->is_liked;

        return response()->json(['data' => $article]);
    }

    
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

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
            'data' => $article
        ], 201);
    }

    
    public function update(Request $request, $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        if ($article->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
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
            'data' => $fresh
        ]);
    }

    
    public function destroy(Request $request, $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

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