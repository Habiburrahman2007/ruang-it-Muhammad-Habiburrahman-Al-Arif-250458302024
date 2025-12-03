<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Comments extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    #[Title('Admin | Kelola komentar')]

    public $search = '';
    public $filterStatus = 'all';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    #[On('toggleCommentConfirmed')]
    public function toggleHidden($commentId)
    {
        $comment = Comment::find($commentId);
        if (!$comment) return;

        $comment->update([
            'is_hidden' => !$comment->is_hidden,
        ]);

        $this->dispatch('commentToggled', $comment->is_hidden
            ? 'Komentar disembunyikan.'
            : 'Komentar ditampilkan kembali.');
    }

    public function render()
    {
        $comments = Comment::with(['user', 'article'])
            ->when($this->search, function ($query) {
                $query->where('content', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('article', fn($q) => $q->where('title', 'like', '%' . $this->search . '%'));
            })
            ->when($this->filterStatus !== 'all', function ($query) {
                if ($this->filterStatus === 'hidden') {
                    $query->where('is_hidden', true);
                } elseif ($this->filterStatus === 'visible') {
                    $query->where('is_hidden', false);
                }
            })
            ->latest()
            ->paginate(5);

        return view('livewire.admin.comments', [
            'comments' => $comments,
        ]);
    }
}
