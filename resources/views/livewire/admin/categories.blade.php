<div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-3">
                    <i class="fa-solid fa-tags"></i>
                    Daftar Kategori
                </h5>

                <div class="d-flex flex-column align-items-end gap-2">
                    <div class="w-100">
                        <input type="text" class="form-control" placeholder="Cari kategori..." wire:model.live="search">
                    </div>

                    <a href="{{ route('create-category') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">
                        Tambah Kategori
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table table-striped align-middle" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Warna</th>
                                <th class="text-center">Total Artikel</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <span class="badge {{ $category->colorClass }}"
                                            style="{{ $category->colorStyle }}">
                                            {{ $colorOptions[$category->color] ?? $category->color }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $category->active_articles_count }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-decoration-none p-0"
                                                id="dropdownMenuButton{{ $category->id }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical text-secondary fs-5"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                aria-labelledby="dropdownMenuButton{{ $category->id }}">
                                                <li>
                                                    <button wire:click="editCategory({{ $category->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                                        class="dropdown-item text-success">
                                                        <i class="bi bi-pencil-square me-2"></i>Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <button onclick="confirmDeleteCategory({{ $category->id }})"
                                                        class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>Hapus
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <ul class="pagination pagination-primary mb-0 justify-center mt-3">
                    {{-- PREVIOUS --}}
                    <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ $categories->onFirstPage() ? '#' : $categories->previousPageUrl() }}"
                            @if (!$categories->onFirstPage()) wire:navigate @endif>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </li>

                    {{-- NUMBERS --}}
                    @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                        <li class="page-item {{ $categories->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}"
                                @if ($categories->currentPage() != $page) wire:navigate @endif>
                                {{ $page }}
                            </a>
                        </li>
                    @endforeach

                    {{-- NEXT --}}
                    <li class="page-item {{ $categories->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link"
                            href="{{ $categories->hasMorePages() ? $categories->nextPageUrl() : '#' }}"
                            @if ($categories->hasMorePages()) wire:navigate @endif>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </section>

    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama kategori</label>
                        <input type="text" class="form-control" wire:model="name" id="edit-category-name">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Warna</label>
                        <select class="form-select" wire:model="color" id="edit-category-color">
                            <option value="">-- Select Color --</option>
                            @foreach ($colorOptions as $class => $label)
                                <option value="{{ $class }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('color')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" wire:click="updateCategory" class="btn btn-primary btn-sm"
                        data-bs-dismiss="modal">
                        Perbarui
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:init', () => {

        const editModalEl = document.getElementById('editModal');
        const nameInput = document.getElementById('edit-category-name');
        const colorSelect = document.getElementById('edit-category-color');
        const editModal = new bootstrap.Modal(editModalEl);

        // === SHOW MODAL ===
        window.addEventListener('showEditCategoryModal', event => {

            const data = event.detail[0]; // FORMAT SAMA SEPERTI KOMENTAR

            if (data) {
                if (nameInput) {
                    nameInput.value = data.name;
                    nameInput.dispatchEvent(new Event('input'));
                }
                if (colorSelect) {
                    colorSelect.value = data.color;
                    colorSelect.dispatchEvent(new Event('input'));
                }
            }

            editModal.show();
        });

        // === CLOSE MODAL ===
        window.addEventListener('closeEditCategoryModal', () => {
            editModal.hide();
        });

    });
</script>
