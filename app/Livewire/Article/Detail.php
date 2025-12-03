<?php

namespace App\Livewire\Article;

use App\Models\Like;
use App\Models\Article;
use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Detail extends Component
{
    #[Layout('layouts.app')]
    #[Title('Detail Artikel')]

    public $article;
    public $slug;
    public $user;
    public $likesCount;
    public $commentsCount;
    public $comments = [];
    public $newComment = '';
    public $editingCommentId;
    public $editedCommentContent;
    public $perPage = 3;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->user = Auth::user();
        $this->loadArticle();
        $this->loadComments();
    }

    public function loadArticle()
    {
        $this->article = Article::with([
            'user',
            'category',
            'likes',
            'comments.user'
        ])
            ->where('slug', $this->slug)
            ->firstOrFail();

        if ($this->user) {
            $this->article->isLiked = $this->article->likes
                ->where('user_id', $this->user->id)
                ->count() > 0;
        } else {
            $this->article->isLiked = false;
        }

        $this->likesCount = $this->article->likes->count();
        $this->commentsCount = $this->article->comments->count();
    }

    public function loadComments()
    {
        $this->comments = Comment::with('user')
            ->where('article_id', $this->article->id)
            ->latest()
            ->take($this->perPage)
            ->get();
    }

    public function loadMore()
    {
        $this->perPage += 3;
        $this->loadComments();
    }


    public function postComment()
    {
        if (!$this->user) {
            session()->flash('error', 'Kamu harus login untuk berkomentar.');
            return;
        }

        $this->validate([
            'newComment' => 'required|min:2|max:500',
        ]);

        Comment::create([
            'article_id' => $this->article->id,
            'user_id' => $this->user->id,
            'content' => $this->newComment,
        ]);

        $this->loadComments();
        $this->reset('newComment');
        $this->js("document.getElementById('comment').value = ''");
    }

    public function editComment($id)
    {
        $comment = Comment::find($id);

        if (!$comment || $comment->user_id !== Auth::id()) {
            session()->flash('error', 'Kamu tidak bisa mengedit komentar ini.');
            return;
        }

        $this->editingCommentId = $id;
        $this->editedCommentContent = $comment->content;

        $this->dispatch('showEditCommentModal', content: $this->editedCommentContent);
    }

    public function updateComment()
    {
        $this->validate([
            'editedCommentContent' => 'required|string|min:3|max:500'
        ]);

        $comment = \App\Models\Comment::find($this->editingCommentId);

        if (!$comment || $comment->user_id !== Auth::id()) {
            session()->flash('error', 'Kamu tidak bisa mengedit komentar ini.');
            return;
        }

        $comment->update([
            'content' => $this->editedCommentContent,
        ]);

        $this->editingCommentId = null;
        $this->editedCommentContent = '';

        $this->loadComments();
        session()->flash('comment_updated', true);
        $this->dispatch('closeEditModal');
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) return;

        if ($comment->user_id !== Auth::id()) {
            session()->flash('error', 'Kamu tidak bisa menghapus komentar orang lain.');
            return;
        }

        $comment->delete();
        $this->loadComments();
    }

    public function toggleLike($articleId)
    {
        $user = $this->user;

        if (!$user) return;

        $like = Like::where('user_id', $user->id)
            ->where('article_id', $articleId)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'article_id' => $articleId,
            ]);
        }

        $this->loadArticle();
    }

    #[On('deleteArticleConfirmed')]
    public function deleteArticle($id)
    {
        $article = Article::find($id);

        if (!$article) {
            $this->dispatch('showToast', message: 'Error: Artikel tidak ditemukan.', type: 'error');
            return;
        }

        // Authorization: Only article owner or admin can delete
        $user = Auth::user();
        if (!$user || ($article->user_id !== $user->id && $user->role !== 'admin')) {
            $this->dispatch('showToast', message: 'Error: Anda tidak memiliki akses untuk menghapus artikel ini.', type: 'error');
            return;
        }

        try {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $article->delete();
            session()->flash('article_deleted', true);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $this->dispatch('showToast', message: 'Terjadi kesalahan: ' . $e->getMessage(), type: 'error');
        }
    }


    public function render()
    {
        return view('livewire.article.detail-article', [
            'comments' => $this->comments,
        ]);
    }
}
