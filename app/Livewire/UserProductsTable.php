<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class UserProductsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    protected $queryString = ['search', 'categoryFilter'];
    protected string $paginationTheme = 'bootstrap';

    public function paginationView()
    {
        return 'livewire.custom-pagination';
    }

    // reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }


    public function mount()
    {
        // Initialize categoryFilter from URL if present
        if (request()->has('categoryFilter')) {
            $this->categoryFilter = request()->get('categoryFilter');
        }
        if (request()->has('search')) {
            $this->search = request()->get('search');
        }
    }

    public function render()
    {
        $products = Product::with('category')
            ->where('is_active', true) // Only show active products to users
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
            ->when($this->categoryFilter, function ($query, $categoryFilter) {
                $query->where('category_id', $categoryFilter);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12); // Show 12 products per page for better grid layout

        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Debug information
        Log::info('UserProductsTable Debug', [
            'search' => $this->search,
            'categoryFilter' => $this->categoryFilter,
            'total_products' => $products->total(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'last_page' => $products->lastPage(),
            'has_pages' => $products->hasPages(),
            'count' => $products->count(),
        ]);

        return view('livewire.user-products-table', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
