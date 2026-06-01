<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function index($articleId): AnonymousResourceCollection
    {
        $comments = Comment::with('user:id,name,photo_profile')
            ->where('article_id', $articleId)
            ->latest()
            ->paginate(10);

        return CommentResource::collection($comments);
    }

    public function store(StoreCommentRequest $request, $articleId): JsonResponse
    {
        $article = Article::findOrFail($articleId);

        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment posted successfully',
            'data' => new CommentResource($comment->load('user:id,name,photo_profile'))
        ], 201);
    }

    public function update(StoreCommentRequest $request, $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => new CommentResource($comment->load('user:id,name,photo_profile'))
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
