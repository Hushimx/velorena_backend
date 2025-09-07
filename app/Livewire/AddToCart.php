<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\CartItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add items to cart');
            return;
        }

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

        \Log::info('Validation passed, proceeding to add to cart');

        $user = Auth::user();

        // Check if item already exists with same options
        $existingItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $this->product->id)
            ->where('selected_options', json_encode($this->selectedOptions))
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $this->quantity;
            $existingItem->updatePrices();

            \Log::info('Updated existing cart item', ['cart_item_id' => $existingItem->id]);
            session()->flash('success', 'Item quantity updated in cart');
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $this->product->id,
                'quantity' => $this->quantity,
                'selected_options' => $this->selectedOptions,
                'notes' => $this->notes
            ]);

            $cartItem->updatePrices();

            \Log::info('Created new cart item', ['cart_item_id' => $cartItem->id]);
        }

        // Reset form
        $this->quantity = 1;
        $this->notes = '';

        // Close modal immediately
        $this->showModal = false;

        // Show success toast message
        $this->dispatch('showSuccessToast', message: 'Item added to cart successfully!');

        // Reset selected options
        foreach ($this->product->options as $option) {
            $this->selectedOptions[$option->id] = null;
        }

        // Dispatch event to update cart count
        $this->dispatch('cartUpdated');

        // Also dispatch a browser event
        $this->dispatch('cartUpdated');
    }

    public function validateRequiredOptions()
    {
        foreach ($this->product->options as $option) {
            if ($option->is_required && empty($this->selectedOptions[$option->id])) {
                $this->addError('selectedOptions.' . $option->id, 'Please select ' . $option->name);
            }
        }
    }

    public function calculateTotalPrice()
    {
        $price = $this->product->base_price;

        // Add option prices
        foreach ($this->selectedOptions as $optionId => $valueId) {
            if ($valueId) {
                $optionValue = \App\Models\OptionValue::find($valueId);
                if ($optionValue && $optionValue->price_adjustment) {
                    $price += $optionValue->price_adjustment;
                }
            }
        }

        return $price * $this->quantity;
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function showAddToCartModal()
    {
        $this->showModal = true;
    }

    public function hideAddToCartModal()
    {
        $this->showModal = false;
        $this->quantity = 1;
        $this->notes = '';

        // Reset selected options
        foreach ($this->product->options as $option) {
            $this->selectedOptions[$option->id] = null;
        }
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
