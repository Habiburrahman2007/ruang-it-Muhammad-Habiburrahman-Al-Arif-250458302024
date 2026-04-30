<section id="edit-profile" class="container-fluid mt-4">
    <div class="card w-100">
        <div class="card-header">
            <h4 class="card-title mb-0">
                <i class="fa-solid fa-user"></i> Edit Profil
            </h4>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="updateProfile" enctype="multipart/form-data">
                <div class="row">
                    <!-- Foto Profil -->
                    <div class="col-12 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="{{ $new_photo ? $new_photo->temporaryUrl() : ($photo_profile ? asset('storage/' . $photo_profile) : asset('img/default-avatar.jpeg')) }}"
                                class="rounded-circle mb-3 shadow-sm" alt="Profile Photo" width="120" height="120">

                            <div class="mb-2 w-50">
                                <input type="file" wire:model="new_photo" class="form-control" />
                            </div>
                            @error('new_photo')
                                <p class="text-danger small text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" wire:model="name" class="form-control" />
                        @error('name')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profesi</label>
                        <input type="text" wire:model="profession" class="form-control" />
                        @error('profession')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Bio</label>
                        <textarea wire:model="bio" class="form-control" rows="4" placeholder="Tell us about yourself..."></textarea>
                        @error('bio')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <span wire:loading.remove wire:target="updateProfile">
                            Simpan perubahan
                        </span>

                        <span wire:loading wire:target="updateProfile">
                            <i class="fas fa-spinner fa-spin me-2"></i> Menyimpan
                        </span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
