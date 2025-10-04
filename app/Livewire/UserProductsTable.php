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
    public $selectedCategories = [];
    public $sortBy = 'recommended';
    protected $queryString = ['search', 'selectedCategories', 'sortBy'];
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

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function sortBy($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategories = [];
        $this->sortBy = 'recommended';
        $this->resetPage();
    }


    public function mount()
    {
        // Initialize selectedCategories from URL if present
        if (request()->has('selectedCategories')) {
            $categories = request()->get('selectedCategories');
            $this->selectedCategories = is_array($categories) ? $categories : [$categories];
        }
        if (request()->has('search')) {
            $this->search = request()->get('search');
        }
        if (request()->has('sortBy')) {
            $this->sortBy = request()->get('sortBy');
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
            ->when($this->selectedCategories, function ($query, $selectedCategories) {
                $query->whereIn('category_id', $selectedCategories);
            })
            ->when($this->sortBy, function ($query, $sortBy) {
                switch ($sortBy) {
                    case 'name':
                        $query->orderBy('name_ar', 'asc')->orderBy('name', 'asc');
                        break;
                    case 'price_low':
                        $query->orderBy('base_price', 'asc');
                        break;
                    case 'price_high':
                        $query->orderBy('base_price', 'desc');
                        break;
                    case 'recommended':
                    default:
                        $query->orderBy('sort_order')->orderBy('name_ar', 'asc')->orderBy('name', 'asc');
                        break;
                }
            })
            ->paginate(12); // Show 12 products per page for better grid layout

        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('name_ar')
            ->orderBy('name')
            ->get();

        // Debug information
        Log::info('UserProductsTable Debug', [
            'search' => $this->search,
            'selectedCategories' => $this->selectedCategories,
            'sortBy' => $this->sortBy,
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
