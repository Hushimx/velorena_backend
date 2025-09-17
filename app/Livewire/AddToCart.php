<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\OptionValue;
use App\Models\Design;
use App\Models\ProductDesign;
use App\Services\DesignApiService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddToCart extends Component
{
    public Product $product;
    public $quantity = 1;
    public $selectedOptions = [];
    public $notes = '';
    public $isLoading = false;
    public $showDesignModal = false;
    public $selectedDesigns = [];
    public $designNotes = [];

    protected $rules = [
        'quantity' => 'required|integer|min:1|max:100',
        'notes' => 'nullable|string|max:500',
    ];

    protected $listeners = [
        'design-added' => 'handleDesignAdded',
        'design-removed' => 'handleDesignRemoved',
        'design-note-updated' => 'handleDesignNoteUpdated'
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        // Initialize selectedOptions with first value for each option
        foreach ($this->product->options as $option) {
            if ($option->values->count() > 0) {
                if ($option->type === 'checkbox') {
                    // For checkboxes, initialize as empty array
                    $this->selectedOptions[$option->id] = [];
                } else {
                    // For radio/select, use first value
                    $this->selectedOptions[$option->id] = $option->values->first()->id;
                }
            }
        }
    }

    public function updatedSelectedOptions()
    {
        // Validate that all required options are selected
        $this->validateRequiredOptions();
    }

    public function updatedQuantity()
    {
        $this->validate(['quantity' => 'required|integer|min:1|max:100']);
    }

    public function updatedNotes()
    {
        $this->validate(['notes' => 'nullable|string|max:500']);
    }

    protected function validateRequiredOptions()
    {
        foreach ($this->product->options as $option) {
            if ($option->is_required && !isset($this->selectedOptions[$option->id])) {
                $this->addError('selectedOptions.' . $option->id, 'This option is required.');
            }
        }
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            // Store the current URL as intended URL for redirect after login
            session(['url.intended' => request()->fullUrl()]);

            // Redirect to login page
            return redirect()->route('login');
        }

        $this->isLoading = true;

        try {
            // Validate all inputs
            $this->validate();
            $this->validateRequiredOptions();

            if ($this->getErrorBag()->count() > 0) {
                $this->isLoading = false;
                return;
            }

            $user = Auth::user();

            // Check if this exact product with these options already exists in cart
            $existingCartItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $this->product->id)
                ->where('selected_options', json_encode($this->selectedOptions))
                ->first();

            if ($existingCartItem) {
                // Update quantity of existing item
                $existingCartItem->quantity += $this->quantity;
                $existingCartItem->updatePrices();
                $existingCartItem->save();

                $message = trans('messages.cart_updated');
            } else {
                // Create new cart item
                $cartItem = new CartItem([
                    'user_id' => $user->id,
                    'product_id' => $this->product->id,
                    'quantity' => $this->quantity,
                    'selected_options' => $this->selectedOptions,
                    'notes' => $this->notes,
                ]);

                $cartItem->updatePrices();
                $cartItem->save();

                $message = trans('messages.cart_added');
            }

            // Reset form
            $this->quantity = 1;
            $this->notes = '';

            // Reset options to first values
            foreach ($this->product->options as $option) {
                if ($option->values->count() > 0) {
                    $this->selectedOptions[$option->id] = $option->values->first()->id;
                }
            }

            $this->isLoading = false;

            // Show success toaster notification
            $this->dispatch(
                'showToast',
                message: $message,
                type: 'success',
                title: trans('messages.success'),
                duration: 4000
            );

            // Dispatch event to update cart count in other components
            $this->dispatch('cartUpdated');
        } catch (\Exception $e) {
            $this->isLoading = false;
            Log::error('Add to cart error: ' . $e->getMessage());

            // Show error toaster notification
            $this->dispatch(
                'showToast',
                message: trans('messages.cart_add_error'),
                type: 'error',
                title: trans('messages.error'),
                duration: 5000
            );
        }
    }

    public function buyNow()
    {
        if (!Auth::check()) {
            // Store the current URL as intended URL for redirect after login
            session(['url.intended' => request()->fullUrl()]);

            // Redirect to login page
            return redirect()->route('login');
        }

        $this->isLoading = true;

        try {
            // First add to cart
            $this->addToCart();

            if ($this->getErrorBag()->count() > 0) {
                $this->isLoading = false;
                return;
            }

            $this->isLoading = false;

            // Redirect to cart page
            return redirect()->route('cart.index');
        } catch (\Exception $e) {
            $this->isLoading = false;
            Log::error('Buy now error: ' . $e->getMessage());

            // Show error toaster notification
            $this->dispatch(
                'showToast',
                message: trans('messages.purchase_error'),
                type: 'error',
                title: trans('messages.error'),
                duration: 5000
            );
        }
    }

    public function incrementQuantity()
    {
        if ($this->quantity < 100) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function getTotalPriceProperty()
    {
        $basePrice = (float) $this->product->base_price;
        $totalPrice = $basePrice;

        // Add option price adjustments
        foreach ($this->selectedOptions as $optionId => $valueId) {
            if ($valueId) {
                // Handle checkbox arrays
                if (is_array($valueId)) {
                    foreach ($valueId as $singleValueId) {
                        $optionValue = OptionValue::find($singleValueId);
                        if ($optionValue && $optionValue->price_adjustment) {
                            $totalPrice += (float) $optionValue->price_adjustment;
                        }
                    }
                } else {
                    // Handle single values (radio/select)
                    $optionValue = OptionValue::find($valueId);
                    if ($optionValue && $optionValue->price_adjustment) {
                        $totalPrice += (float) $optionValue->price_adjustment;
                    }
                }
            }
        }

        return $totalPrice * $this->quantity;
    }

    public function calculateTotalPrice()
    {
        return $this->totalPrice;
    }

    public function openDesignModal()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to select designs');
            return;
        }

        $this->showDesignModal = true;

        // Load existing designs for this product
        $user = Auth::user();
        $existingDesigns = ProductDesign::where('user_id', $user->id)
            ->where('product_id', $this->product->id)
            ->get();

        $this->selectedDesigns = $existingDesigns->pluck('design_id')->toArray();
        $this->designNotes = $existingDesigns->pluck('notes', 'design_id')->toArray();
    }

    public function closeDesignModal()
    {
        $this->showDesignModal = false;
        $this->selectedDesigns = [];
        $this->designNotes = [];
    }

    public function handleDesignAdded($designId, $notes = '')
    {
        if (!in_array($designId, $this->selectedDesigns)) {
            $this->selectedDesigns[] = $designId;
        }
        $this->designNotes[$designId] = $notes;
    }

    public function handleDesignRemoved($designId)
    {
        $this->selectedDesigns = array_diff($this->selectedDesigns, [$designId]);
        unset($this->designNotes[$designId]);
    }

    public function handleDesignNoteUpdated($designId, $notes)
    {
        $this->designNotes[$designId] = $notes;
    }

    public function saveSelectedDesigns()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to save designs');
            return;
        }

        $user = Auth::user();

        try {
            // Remove existing designs for this product
            ProductDesign::where('user_id', $user->id)
                ->where('product_id', $this->product->id)
                ->delete();

            // Add new designs
            foreach ($this->selectedDesigns as $index => $designId) {
                ProductDesign::create([
                    'user_id' => $user->id,
                    'product_id' => $this->product->id,
                    'design_id' => $designId,
                    'notes' => $this->designNotes[$designId] ?? '',
                    'priority' => $index + 1
                ]);
            }

            $this->closeDesignModal();
            session()->flash('success', 'Designs saved successfully! ' . count($this->selectedDesigns) . ' designs saved.');
        } catch (\Exception $e) {
            Log::error('Failed to save designs', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to save designs: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
