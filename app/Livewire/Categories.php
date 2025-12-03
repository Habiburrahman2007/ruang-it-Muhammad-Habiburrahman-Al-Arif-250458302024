<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Categories extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    #[Title('Admin | Kelola Kategori')]

    public $color;
    public $name;
    public $categoryId;
    public $search = '';
    //public $categories;

    protected $listeners = ['deleteCategoryConfirmed' => 'deleteCategory'];
    protected $paginationTheme = 'bootstrap';

    public $colorOptions = [
        'bg-danger' => 'Merah',
        'bg-success' => 'Hijau',
        'bg-warning' => 'Kuning',
        'bg-primary' => 'Biru',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editCategory($id)
    {
        $category = Category::find($id);
        if (!$category) return;

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->color = $category->color;

        $this->dispatch('showEditCategoryModal', [
            'name' => $this->name,
            'color' => $this->color
        ]);
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string',
        ]);

        $category = Category::find($this->categoryId);
        if (!$category) return;

        $category->update([
            'name' => $this->name,
            'color' => $this->color,
        ]);

        $this->dispatch('closeEditCategoryModal');
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if (!$category) return;
        $category->delete();
    }


    public function render()
    {
        $categories = Category::withCount([
            'articles as active_articles_count' => function ($query) {
                $query->where('status', 'active');
            }
        ])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(4);

        return view('livewire.admin.categories', [
            'categories' => $categories,
            'colorOptions' => $this->colorOptions,
        ]);
    }
}
