<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Traits\UploadsFiles;

class Edit extends Component
{
    use WithFileUploads, UploadsFiles;

    #[Layout('layouts.app')]
    #[Title('Edit Profil')]

    public $name;
    public $profession;
    public $bio;
    public $photo_profile;
    public $new_photo;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->profession = $user->profession;
        $this->bio = $user->bio;
        $this->photo_profile = $user->photo_profile;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'new_photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        
        if ($this->new_photo) {
            $this->deleteFile($user->photo_profile);
            $user->photo_profile = $this->uploadFile($this->new_photo, \App\Models\User::PROFILE_PHOTO_PATH);
        }

        
        $user->name = $this->name;
        $user->profession = $this->profession;
        $user->bio = $this->bio;
        $user->save();

        session()->flash('success', 'Profil berhasil diperbarui!');

        return redirect()->route('profile');
    }

    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}
