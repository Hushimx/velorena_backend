<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ProductsTable extends Component
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

    public $deleteProductId = null;
    public $showDeleteModal = false;

    public function confirmDelete($productId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteProductId = $productId;
        $this->showDeleteModal = true;
    }

    public function deleteProduct()
    {
        try {
            if ($this->deleteProductId) {
                $product = Product::findOrFail($this->deleteProductId);
                $product->delete();

                session()->flash('message', trans('products.product_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('products.delete_error'));
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
        $this->deleteProductId = null;
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
        $products = Product::with('category')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('name_ar', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('description_ar', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('name_ar', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.products-table', [
            'products' => $products,
        ]);
    }
}
