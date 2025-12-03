<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CreateCategory extends Component
{
    public $name;
    public $color;

    #[Layout('layouts.app')]
    #[Title('Admin | Tambah Kategori')]
    public function render()
    {
        return view('livewire.admin.create-category');
    }

    public $colorOptions = [
        'bg-danger' => 'Merah',
        'bg-success' => 'Hijau',
        'bg-warning' => 'Kuning',
        'bg-primary' => 'Biru',
    ];

    public function save()
    {
        $this->validate([
            'name' => 'required|min:2|unique:categories,name',
            'color' => 'required|in:' . implode(',', array_keys($this->colorOptions)),
        ]);

        try {
            Category::create([
                'name' => $this->name,
                'color' => $this->color,
            ]);

            // Clear category cache so new category appears immediately
            \App\Helpers\CategoryCache::flush();

            session()->flash('category_created', true);
            return redirect()->to('/admin/category');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to create category', [
                'name' => $this->name,
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Gagal membuat kategori. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error('Unexpected error in CreateCategory', [
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Terjadi kesalahan. Silakan hubungi administrator.');
        }
    }
}
