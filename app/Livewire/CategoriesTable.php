<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class CategoriesTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];
    protected string $paginationTheme = 'tailwind';

    // reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public $deleteCategoryId = null;
    public $showDeleteModal = false;

    public function confirmDelete($categoryId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteCategoryId = $categoryId;
        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        try {
            if ($this->deleteCategoryId) {
                $category = Category::findOrFail($this->deleteCategoryId);

                // Check if category has products
                if ($category->products()->count() > 0) {
                    session()->flash('error', trans('categories.cannot_delete_with_products'));
                    $this->closeModal();
                    return;
                }

                $category->delete();

                session()->flash('message', trans('categories.category_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('categories.delete_error'));
        }

        $this->closeModal();
        $this->resetPage(); // Reset pagination after deletion
    }

    public function cancelDelete()
    {
        $this->closeModal();
    }

    private function closeModal()
    {
        $this->deleteCategoryId = null;
        $this->showDeleteModal = false;
        $this->dispatch('modal-closed');
    }

    public function refreshComponent()
    {
        $this->resetPage();
        $this->closeModal();
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('name_ar', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('description_ar', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.categories-table', [
            'categories' => $categories,
        ]);
    }
}
