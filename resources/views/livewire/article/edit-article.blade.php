<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        Edit artikel
                    </h2>
                    <span class="text-center">Silahkan mengikuti panduan penulisan artikel <a
                            href="{{ route('guidelines', ['from' => 'edit-article']) }}"
                            class="text-primary">disini</a></span>
                </div>

                <div class="card-content">
                    <div class="card-body">
                        <form wire:submit.prevent="update" class="form">
                            <div class="row">

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="formFileSm" class="form-label">Gambar sampul</label>
                                        <input class="form-control form-control-sm" id="formFileSm" type="file"
                                            wire:model="image" accept="image/*">

                                        @if ($image)
                                            <div class="mt-3">
                                                <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                                    class="img-thumbnail" style="max-height: 200px;">
                                            </div>
                                        @elseif($oldImage)
                                            <div class="mt-3">
                                                <img src="{{ asset('storage/' . $oldImage) }}" alt="Current Image"
                                                    class="img-thumbnail" style="max-height: 200px;">
                                            </div>
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
                                            placeholder="Edit article title" />
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label">Pilih kategori</label>
                                    <select wire:model="category_id" class="form-select">
                                        <option value="">-- Choose Category --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div wire:ignore>
                                    <label class="form-label">Isi artikel</label>
                                    <input id="content" name="content" type="hidden" wire:model="content"
                                        value="{{ old('content', $content) }}">
                                    <trix-editor input="content"></trix-editor>
                                </div>

                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="btn btn-primary me-1 mt-3 mb-1">
                                        <span wire:loading.remove wire:target="update">Perbarui</span>
                                        <span wire:loading wire:target="update"><i
                                                class="fas fa-spinner fa-spin me-2"></i>Memperbarui</span>
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
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initTrix();
    });

    document.addEventListener('livewire:navigated', function() {
        initTrix();
    });

    function initTrix() {
        const trixInput = document.getElementById('content');
        const trixEditor = document.querySelector('trix-editor');

        if (!trixInput || !trixEditor) {
            setTimeout(initTrix, 100);
            return;
        }

        // Remove existing listener to prevent duplicates
        trixEditor.removeEventListener('trix-change', handleTrixChange);

        // Add new listener
        trixEditor.addEventListener('trix-change', handleTrixChange);

        // Initial load
        trixEditor.editor.loadHTML(trixInput.value);
    }

    function handleTrixChange(e) {
        const content = e.target.value;
        @this.set('content', content);
    }
</script>
