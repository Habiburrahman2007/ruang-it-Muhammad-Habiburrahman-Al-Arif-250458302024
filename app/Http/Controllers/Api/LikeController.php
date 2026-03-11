<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    public function toggle(Request $request, $articleId)
    {
        $article = Article::find($articleId);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $user = $request->user();

        $like = Like::where('user_id', $user->id)
            ->where('article_id', $articleId)
            ->first();

        if ($like) {
            $like->delete();
            return response()->json([
                'message' => 'Article unliked',
                'liked' => false
            ]);
        } else {
            Like::create([
                'user_id' => $user->id,
                'article_id' => $articleId,
            ]);

            return response()->json([
                'message' => 'Article liked',
                'liked' => true
            ]);
        }
    }
}
