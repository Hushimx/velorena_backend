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
  protected string $paginationTheme = 'tailwind';

  // reset pagination when search changes
  public function updatedSearch()
  {
    $this->resetPage();
  }

  public function updatedCategoryFilter()
  {
    $this->resetPage();
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

    return view('livewire.user-products-table', [
      'products' => $products,
      'categories' => $categories,
    ]);
  }
}

