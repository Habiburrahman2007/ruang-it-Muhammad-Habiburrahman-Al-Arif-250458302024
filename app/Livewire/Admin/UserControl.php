<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class UserControl extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    #[Title('Admin | Kelola pengguna')]

    public $search = '';
    public $filterStatus = 'all';

    protected $listeners = ['toggleUserStatus' => 'toggleBanned'];
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function toggleBanned($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->banned = !$user->banned;
            $user->save();
        }
        $this->dispatch(
            'userStatusUpdated',
            $user->banned
                ? 'Pengguna berhasil diblokir.'
                : 'Pengguna berhasil diaktifkan kembali.'
        );
    }

    public function render()
    {
        $users = User::query()
            ->withCount('articles')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus === 'active', function ($query) {
                $query->where('banned', false);
            })
            ->when($this->filterStatus === 'banned', function ($query) {
                $query->where('banned', true);
            })
            ->orderBy('articles_count', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-control', [
            'users' => $users,
        ]);
    }
}
