<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Buat Category</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form wire:submit.prevent="save" class="form">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mandatory">
                                        <label for="name" class="form-label mb-3">Nama kategori</label>
                                        <input type="text" id="name" class="form-control mb-3"
                                            placeholder="Ketik disini" wire:model="name" />
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mandatory">
                                        <label for="color" class="form-label mb-3">Warna kategori</label>
                                        <select class="form-select" wire:model="color">
                                            <option value="">-- Pilih warna --</option>
                                            @foreach ($colorOptions as $class => $label)
                                                <option value="{{ $class }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('color')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="btn btn-primary me-1 mb-1">
                                        <span wire:loading.remove wire:target="save">Buat</span>
                                        <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin me-2"></i>Membuat</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
