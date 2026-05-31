<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse; // Senior Standard: Import JsonResponse type hint

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar artikel (Feed Dashboard)
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Ambil user dari token Sanctum secara opsional (bisa guest / logged-in user)
        $user = auth('sanctum')->user();

        // 2. Bangun Query Utama dengan Eager Loading untuk mencegah N+1 Query Problem
        $query = Article::with(['user:id,name,slug,photo_profile,profession', 'category:id,name,color'])
            ->withCount(['comments', 'likes'])
            // SENIOR OPTIMIZATION: Subquery untuk mengecek status 'like' user saat ini langsung dari database
            ->withExists(['likes as is_liked' => function ($q) use ($user) {
                $q->where('user_id', $user ? $user->id : null);
            }])
            // BUG FIX #1: Exclude artikel milik user yang di-ban
            ->whereHas('user', function ($q) {
                $q->where('banned', false);
            })
            ->latest();

        // 3. Jalankan Filter Kategori jika ada request
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)->orWhere('id', $request->category);
            });
        }

        // 4. CRITICAL SECURE FIX: Bungkus orWhere dalam Logical Grouping Closure
        // Agar pencarian tidak merusak query filter kategori di atasnya.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // 5. Eksekusi Pagination data
        $articles = $query->paginate((int) $request->get('per_page', 10));

        // 6. SINKRONISASI FLUTTER: Transformasikan collection agar memiliki field 'isLiked' (camelCase)
        // Agar klop dengan model data `.isLiked` yang diekspektasikan oleh GetX Controller di mobile.
        $articles->getCollection()->transform(function ($article) {
            $article->isLiked = (bool) $article->is_liked;
            return $article;
        });

        return response()->json($articles);
    }

    /**
     * Menampilkan detail single artikel
     */
    public function show($identifier): JsonResponse
    {
        $user = auth('sanctum')->user();

        // Refactor query detail dengan withExists agar seirama dengan method index
        $article = Article::with([
            'user:id,name,slug,photo_profile,bio,profession,banned',
            'category:id,name,color',
            'comments.user:id,name,photo_profile',
        ])
        ->withCount(['comments', 'likes'])
        ->withExists(['likes as is_liked' => function ($q) use ($user) {
            $q->where('user_id', $user ? $user->id : null);
        }])
        // BUG FIX #1: Exclude artikel milik user yang di-ban
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

        // Set properti isLiked agar bisa dibaca dua rute format json (snake & camel) di mobile
        $article->isLiked = (bool) $article->is_liked;

        return response()->json(['data' => $article]);
    }

    /**
     * Membuat artikel baru
     */
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

    /**
     * Memperbarui artikel
     */
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

    /**
     * Menghapus artikel
     */
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