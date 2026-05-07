<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Edit extends Component
{
    use WithFileUploads;

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

        // Update photo if new one uploaded
        if ($this->new_photo) {
            // Delete old photo if exists
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }

            // Store new photo
            $extension = $this->new_photo->getClientOriginalExtension();
            $filename = uniqid('profile_', true) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $path = $this->new_photo->storeAs('profile-photos', $filename, 'public');
            $user->photo_profile = $path;
        }

        // Update user data
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
