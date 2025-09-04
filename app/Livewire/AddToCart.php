<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;
    public $quantity = 1;
    public $selectedOptions = [];
    public $notes = '';
    public $showModal = false;

    protected $rules = [
        'quantity' => 'required|integer|min:1|max:100',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        // Initialize selectedOptions with empty values for each option
        foreach ($this->product->options as $option) {
            $this->selectedOptions[$option->id] = null;
        }
    }

    public function addToCart()
    {
        \Log::info('addToCart called', [
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
            'selectedOptions' => $this->selectedOptions,
            'notes' => $this->notes
        ]);

        $this->validate();
        $this->validateRequiredOptions();

        // Check if there are any validation errors
        if ($this->getErrorBag()->count() > 0) {
            \Log::error('Validation errors in addToCart', ['errors' => $this->getErrorBag()->toArray()]);
            return;
        }

        // Calculate total price including options
        $totalPrice = $this->calculateTotalPrice();

        // Prepare cart item data
        $cartItem = [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_image' => $this->product->image_url ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2Y3ZjdmNyIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjEyIiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UHJvZHVjdDwvdGV4dD48L3N2Zz4=',
            'base_price' => $this->product->base_price,
            'unit_price' => $totalPrice,
            'quantity' => $this->quantity,
            'total_price' => $totalPrice * $this->quantity,
            'selected_options' => $this->getSelectedOptionsData(),
            'notes' => $this->notes
        ];

        // Add to cart via simple JavaScript
        \Log::info('AddToCart adding to cart directly', $cartItem);

        $this->js("
            console.log('Adding to cart:', " . json_encode($cartItem) . ");

            // Get current cart data
            let cartData = JSON.parse(localStorage.getItem('shopping_cart') || '{\"items\": [], \"total\": 0, \"itemCount\": 0}');

            // Add new item
            cartData.items.push(" . json_encode($cartItem) . ");

            // Update totals
            cartData.itemCount = cartData.items.reduce((sum, item) => sum + (item.quantity || 0), 0);
            cartData.total = cartData.items.reduce((sum, item) => sum + (item.total_price || 0), 0);

            // Save to localStorage
            localStorage.setItem('shopping_cart', JSON.stringify(cartData));

            console.log('Cart updated:', cartData);

            // Show success message
            Swal.fire({
                title: 'Added to Cart!',
                text: 'Product added to cart successfully!',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        ");

        $this->showModal = false;

        // Reset form data
        $this->quantity = 1;
        $this->notes = '';

        // Reinitialize selectedOptions properly
        $this->selectedOptions = [];
        foreach ($this->product->options as $option) {
            $this->selectedOptions[$option->id] = null;
        }
    }

    public function openModal()
    {
        \Log::info('Opening modal for product', ['product_id' => $this->product->id, 'current_options' => $this->selectedOptions]);

        $this->showModal = true;

        // Reset form data
        $this->quantity = 1;
        $this->notes = '';

        // Reset selected options when opening modal
        $this->selectedOptions = [];
        foreach ($this->product->options as $option) {
            $this->selectedOptions[$option->id] = null;
        }

        \Log::info('Modal opened with reset options', ['new_options' => $this->selectedOptions]);
    }

    public function closeModal()
    {
        $this->showModal = false;

        // Reset form data
        $this->quantity = 1;
        $this->notes = '';

        // Reinitialize selectedOptions properly
        $this->selectedOptions = [];
        foreach ($this->product->options as $option) {
            $this->selectedOptions[$option->id] = null;
        }
    }

    private function validateRequiredOptions()
    {
        foreach ($this->product->options as $option) {
            if ($option->is_required && empty($this->selectedOptions[$option->id])) {
                $this->addError('selectedOptions.' . $option->id, 'This option is required.');
            }
        }
    }

    private function calculateTotalPrice()
    {
        $totalPrice = $this->product->base_price;

        foreach ($this->selectedOptions as $optionId => $valueId) {
            if ($valueId) {
                $optionValue = \App\Models\OptionValue::find($valueId);
                if ($optionValue) {
                    $totalPrice += $optionValue->price_adjustment;
                }
            }
        }

        return $totalPrice;
    }

    private function getSelectedOptionsData()
    {
        $optionsData = [];

        foreach ($this->selectedOptions as $optionId => $valueId) {
            if ($valueId) {
                $option = $this->product->options->find($optionId);
                $optionValue = \App\Models\OptionValue::find($valueId);

                if ($option && $optionValue) {
                    $optionsData[$option->name] = [
                        'value' => $optionValue->value,
                        'price_adjustment' => $optionValue->price_adjustment
                    ];
                }
            }
        }

        return $optionsData;
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
