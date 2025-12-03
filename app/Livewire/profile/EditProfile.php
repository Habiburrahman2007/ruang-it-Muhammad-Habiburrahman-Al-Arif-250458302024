<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EditProfile extends Component
{
    use WithFileUploads;

    #[Layout('layouts.app')]
    #[Title('Edit Profile')]

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

    public function updatedNewPhoto()
    {
        // Step 1: Basic Laravel validation
        $this->validate([
            'new_photo' => 'image|max:2048',
        ]);

        if ($this->new_photo) {
            // Step 2: Validate server-detected MIME type
            $mimeType = $this->new_photo->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($mimeType, $allowedMimes)) {
                $this->addError('new_photo', 'Tipe file tidak valid. Hanya JPG, PNG, dan WebP yang diperbolehkan.');
                $this->new_photo = null;
                return;
            }

            // Step 3: CRITICAL - Validate magic bytes (actual file content)
            $imageInfo = @getimagesize($this->new_photo->getRealPath());
            if (!$imageInfo) {
                $this->addError('new_photo', 'File bukan gambar yang valid atau file rusak.');
                $this->new_photo = null;
                return;
            }

            // Step 4: Validate dimensions (profile photos should be smaller)
            [$width, $height] = $imageInfo;
            if ($width < 100 || $height < 100 || $width > 2000 || $height > 2000) {
                $this->addError('new_photo', 'Ukuran foto profil harus antara 100x100 dan 2000x2000 pixels.');
                $this->new_photo = null;
            }
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'new_photo' => 'nullable|image|max:2048',
        ]);

        try {
            if ($this->new_photo) {
                // Secure file upload with random filename
                $extension = $this->new_photo->getClientOriginalExtension();
                $filename = uniqid('profile_', true) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $path = $this->new_photo->storeAs('profiles', $filename, 'public');
                $user->photo_profile = $path;
            }

            $user->update([
                'name' => $this->name,
                'profession' => $this->profession,
                'bio' => $this->bio,
                'photo_profile' => $user->photo_profile,
            ]);

            session()->flash('profile_updated', true);
            return redirect()->route('profile');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to update profile', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Gagal mengupdate profil. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error('Unexpected error in EditProfile', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Terjadi kesalahan. Silakan hubungi administrator.');
        }
    }

    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}
