<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mt-3">
                        <i class="fa-solid fa-pen"></i> Buat artikel baru
                    </h4>
                </div>

                <div class="card-content">
                    <div class="card-body">
                        <form wire:submit.prevent="save" class="form" enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="formFileSm" class="form-label">Gambar sampul</label>
                                        <input class="form-control form-control-sm" id="formFileSm" type="file"
                                            wire:model="image"
                                            accept="image/png, image/jpeg, image/jpg, image/webp, image/gif">
                                        @if ($image && !$errors->has('image'))
                                            @try
                                                <div class="mt-3">
                                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                                        class="img-thumbnail" style="max-height: 200px;">
                                                </div>
                                                @catch(\Exception $e)
                                                <div class="mt-2 text-muted"><small>Preview tidak tersedia</small></div>
                                            @endtry
                                        @endif
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Judul</label>
                                        <input type="text" wire:model="title" class="form-control"
                                            placeholder="Ketik judul disini" />
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label">Pilih kategori</label>
                                    <select wire:model="category_id" class="form-select">
                                        <option value="">-- Pilih disini --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4" wire:ignore>
                                    <label class="form-label">Isi artikel</label>
                                    <input id="x" type="hidden" wire:model="content">
                                    <div class="bg-white">
                                        <trix-editor input="x" class="form-control"></trix-editor>
                                    </div>
                                </div>
                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="btn btn-primary me-1 mb-1">
                                        <span wire:loading.remove wire:target="save">Terbitkan</span>
                                        <span wire:loading wire:target="save"><i
                                                class="fas fa-spinner fa-spin me-2"></i>Menerbitkan</span>
                                    </button>
                                </div>

                            </div>
                        </form>
                        @if (session()->has('message'))
                            <div class="alert alert-success mt-3">
                                {{ session('message') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script wire:ignore>
            document.addEventListener('trix-change', e => {
                @this.set('content', e.target.value);
            });
        </script>

    </div>
</section>
