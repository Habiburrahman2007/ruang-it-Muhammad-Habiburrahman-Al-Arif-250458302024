<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::where('banned', false)
            ->withCount('articles');

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

        return UserResource::collection($users);
    }

    public function show($identifier): JsonResponse
    {
        $user = User::where(function ($q) use ($identifier) {
                $q->where('id', $identifier)
                  ->orWhere('slug', $identifier);
            })
            ->where('banned', false)
            ->withCount('articles')
            ->firstOrFail();

        $articleIds = Article::where('user_id', $user->id)->pluck('id');

        $totalLikesReceived = Like::whereIn('article_id', $articleIds)->count();
        $totalCommentsReceived = \App\Models\Comment::whereIn('article_id', $articleIds)->count();

        $articles = Article::with(['category:id,name,color'])
            ->withCount(['comments', 'likes'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'banned'])
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
                'comments_count'       => $totalCommentsReceived,
                'total_likes'          => $totalLikesReceived,
                'created_at'           => $user->created_at,
                'articles'             => $articles,
            ]
        ]);
    }
}
