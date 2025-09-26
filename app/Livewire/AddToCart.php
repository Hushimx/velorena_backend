<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\OptionValue;
use App\Models\Design;
// ProductDesign removed - designs are now order-level only
use App\Services\DesignApiService;
use App\Services\GuestCartService;
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
        $this->isLoading = true;

        try {
            // Validate all inputs
            $this->validate();
            $this->validateRequiredOptions();

            if ($this->getErrorBag()->count() > 0) {
                $this->isLoading = false;
                return;
            }

            if (Auth::check()) {
                // Authenticated user - use database cart
                $this->addToAuthenticatedCart();
            } else {
                // Guest user - use session cart
                $this->addToGuestCart();
            }

            // Reset form
            $this->quantity = 1;
            $this->notes = '';

            // Reset options to first values
            foreach ($this->product->options as $option) {
                if ($option->values->count() > 0) {
                    if ($option->type === 'checkbox') {
                        $this->selectedOptions[$option->id] = [];
                    } else {
                        $this->selectedOptions[$option->id] = $option->values->first()->id;
                    }
                }
            }

            $this->isLoading = false;

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

    private function addToAuthenticatedCart()
    {
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

        // Show success toaster notification
        $this->dispatch(
            'showToast',
            message: $message,
            type: 'success',
            title: trans('messages.success'),
            duration: 4000
        );
    }

    private function addToGuestCart()
    {
        $guestCartService = app(GuestCartService::class);
        $guestCartService->addToCart(
            $this->product->id,
            $this->quantity,
            $this->selectedOptions,
            $this->notes
        );

        $message = trans('messages.cart_added');

        // Show success toaster notification
        $this->dispatch(
            'showToast',
            message: $message,
            type: 'success',
            title: trans('messages.success'),
            duration: 4000
        );
    }

    public function buyNow()
    {
        $this->isLoading = true;

        try {
            // First add to cart
            $this->addToCart();

            if ($this->getErrorBag()->count() > 0) {
                $this->isLoading = false;
                return;
            }

            $this->isLoading = false;

            // Use JavaScript redirect instead of PHP redirect
            $this->dispatch('redirectToCart');
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

        // Designs are now managed at order level, not product level
        $this->selectedDesigns = [];
        $this->designNotes = [];
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
            // Designs are now managed at order level, not product level
            // This functionality has been moved to cart designs
            
            $this->closeDesignModal();
            session()->flash('info', 'Design management has been moved to the cart level. Designs will be automatically included when you create an order.');
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
