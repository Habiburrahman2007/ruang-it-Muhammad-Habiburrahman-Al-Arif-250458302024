<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['user:id,name,slug,photo_profile', 'category:id,name,color'])
            ->withCount(['comments', 'likes'])
            ->latest();

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)->orWhere('id', $request->category);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
        }

        $articles = $query->paginate($request->get('per_page', 10));

        // Pastikan accessor image_url & photo_profile_url ikut ter-include
        $articles->getCollection()->transform(function ($article) {
            if ($article->user) {
                $article->user->append('photo_profile_url');
            }
            return $article;
        });

        return response()->json($articles);
    }

    public function show($identifier)
    {
        $article = Article::with([
            'user:id,name,slug,photo_profile,bio',
            'category:id,name,color',
            'comments.user:id,name,photo_profile',
            'likes'
        ])
        ->withCount(['comments', 'likes'])
        ->where('id', $identifier)
        ->orWhere('slug', $identifier)
        ->first();

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        // Pastikan accessor photo_profile_url ikut ter-include di user & comments
        if ($article->user) {
            $article->user->append('photo_profile_url');
        }
        $article->comments->each(function ($comment) {
            if ($comment->user) {
                $comment->user->append('photo_profile_url');
            }
        });

        // Check if current user liked it
        if (auth('sanctum')->check()) {
            $article->isLiked = $article->isLikedBy(auth('sanctum')->user());
        }

        return response()->json(['data' => $article]);
    }

    public function store(Request $request)
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

        return response()->json([
            'message' => 'Article created successfully',
            'data' => $article->load(['user', 'category'])
        ], 201);
    }

    public function update(Request $request, $id)
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

        return response()->json([
            'message' => 'Article updated successfully',
            'data' => $article->fresh()->load(['user', 'category'])
        ]);
    }

    public function destroy(Request $request, $id)
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
