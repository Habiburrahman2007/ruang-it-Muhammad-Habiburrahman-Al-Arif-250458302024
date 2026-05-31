<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Mencari daftar user (Bug Fix #3: search user yang belum pernah menulis artikel sekalipun)
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::where('banned', false)
            ->withCount('articles');

        // Filter by search query (name atau profession)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('profession', 'like', '%' . $search . '%');
            });
        }

        $users = $query->select(['id', 'name', 'slug', 'photo_profile', 'profession', 'bio', 'created_at'])
            ->orderBy('name')
            ->paginate((int) $request->get('per_page', 15));

        // Tambah photo_profile_url ke setiap user
        $users->getCollection()->transform(function ($user) {
            return [
                'id'                => $user->id,
                'name'              => $user->name,
                'slug'              => $user->slug,
                'profession'        => $user->profession,
                'bio'               => $user->bio,
                'photo_profile_url' => $user->photo_profile_url,
                'articles_count'    => $user->articles_count,
                'created_at'        => $user->created_at,
            ];
        });

        return response()->json($users);
    }

    /**
     * Menampilkan profil publik user lain berdasarkan slug
     * (Bug Fix #2: endpoint profil publik + pekerjaan + statistik)
     */
    public function show(string $slug): JsonResponse
    {
        $user = User::where('slug', $slug)
            ->where('banned', false)
            ->withCount('articles')
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Hitung total likes yang diterima oleh user ini
        $totalLikesReceived = Like::whereIn(
            'article_id',
            Article::where('user_id', $user->id)->pluck('id')
        )->count();

        // Ambil artikel terbaru milik user (hanya artikel aktif)
        $articles = Article::with(['category:id,name,color'])
            ->withCount(['comments', 'likes'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => [
                'id'                   => $user->id,
                'name'                 => $user->name,
                'slug'                 => $user->slug,
                'profession'           => $user->profession,
                'bio'                  => $user->bio,
                'photo_profile_url'    => $user->photo_profile_url,
                'articles_count'       => $user->articles_count,
                'total_likes_received' => $totalLikesReceived,
                'created_at'           => $user->created_at,
                'articles'             => $articles,
            ]
        ]);
    }
}
