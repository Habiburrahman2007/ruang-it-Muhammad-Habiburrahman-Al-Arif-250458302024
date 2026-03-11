<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($articleId)
    {
        $comments = Comment::with('user:id,name,photo_profile')
            ->where('article_id', $articleId)
            ->latest()
            ->paginate(10);

        return response()->json($comments);
    }

    public function store(Request $request, $articleId)
    {
        $article = Article::find($articleId);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:2|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment posted successfully',
            'data' => $comment->load('user:id,name,photo_profile')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:3|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $comment->load('user:id,name,photo_profile')
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
