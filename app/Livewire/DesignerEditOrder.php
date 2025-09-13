<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\OptionValue;
use App\Models\ProductOption;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DesignerEditOrder extends Component
{
    use WithPagination;

    public $appointment;
    public $order;
    public $search = '';
    public $category_filter = '';
    public $selectedProducts = [];
    public $cartItems = [];
    public $showAddProducts = false;
    public $orderNotes = '';
    public $editingItemIndex = null;
    public $editingOptions = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'category_filter' => ['except' => ''],
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Appointment $appointment)
    {
        // Verify the designer has access to this appointment
        $designer = Auth::guard('designer')->user();
        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403, 'You do not have access to this appointment.');
        }

        $this->appointment = $appointment;
        $this->order = $appointment->order;

        if (!$this->order) {
            abort(404, 'No order found for this appointment.');
        }

        $this->orderNotes = $this->order->notes ?? '';

        // Load existing order items into cart format
        $this->loadExistingOrderItems();
    }

    public function loadExistingOrderItems()
    {
        $this->cartItems = [];

        foreach ($this->order->items as $item) {
            // Load option details for display
            $optionDetails = [];
            if (!empty($item->options)) {
                $options = is_array($item->options) ? $item->options : json_decode($item->options, true);
                if (is_array($options)) {
                    foreach ($options as $optionValueId) {
                        $optionValue = OptionValue::with('productOption')->find($optionValueId);
                        if ($optionValue) {
                            $optionDetails[] = [
                                'option_name' => $optionValue->productOption->name,
                                'value' => $optionValue->value,
                                'price_adjustment' => $optionValue->price_adjustment
                            ];
                        }
                    }
                }
            }

            $this->cartItems[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                'options' => $item->options ?? [],
                'option_details' => $optionDetails,
                'notes' => $item->notes ?? '',
                'is_existing' => true
            ];
        }
    }

    public function addToCart($productId)
    {
        $product = Product::with(['options.values'])->find($productId);

        if (!$product) {
            session()->flash('error', 'Product not found.');
            return;
        }

        // Check if product already exists in cart
        $existingIndex = $this->findCartItemIndex($productId, []);

        if ($existingIndex !== false) {
            $this->cartItems[$existingIndex]['quantity']++;
            $this->cartItems[$existingIndex]['total_price'] =
                $this->cartItems[$existingIndex]['quantity'] * $this->cartItems[$existingIndex]['unit_price'];
        } else {
            $this->cartItems[] = [
                'id' => null, // New item
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 1,
                'unit_price' => $product->base_price,
                'total_price' => $product->base_price,
                'options' => [],
                'option_details' => [],
                'notes' => '',
                'is_existing' => false
            ];
        }

        $this->selectedProducts = [];
        session()->flash('success', 'Product added to order.');
    }

    public function removeFromCart($index)
    {
        if (isset($this->cartItems[$index])) {
            unset($this->cartItems[$index]);
            $this->cartItems = array_values($this->cartItems); // Re-index array
            session()->flash('success', 'Item removed from order.');
        }
    }

    public function updateQuantity($index, $quantity)
    {
        if (isset($this->cartItems[$index]) && $quantity > 0) {
            $this->cartItems[$index]['quantity'] = $quantity;
            $this->cartItems[$index]['total_price'] =
                $this->cartItems[$index]['quantity'] * $this->cartItems[$index]['unit_price'];
        }
    }

    public function updatedCartItems($value, $key)
    {
        // Handle live updates to cart items
        if (str_contains($key, '.quantity')) {
            $index = explode('.', $key)[0];
            if (isset($this->cartItems[$index]) && $this->cartItems[$index]['quantity'] > 0) {
                $this->cartItems[$index]['total_price'] =
                    $this->cartItems[$index]['quantity'] * $this->cartItems[$index]['unit_price'];
            }
        }
    }

    public function updateItemNotes($index, $notes)
    {
        if (isset($this->cartItems[$index])) {
            $this->cartItems[$index]['notes'] = $notes;
        }
    }

    public function editItemOptions($index)
    {
        if (isset($this->cartItems[$index])) {
            $this->editingItemIndex = $index;
            $this->editingOptions = [];

            // Load current options
            if (!empty($this->cartItems[$index]['options'])) {
                $options = is_array($this->cartItems[$index]['options']) ?
                    $this->cartItems[$index]['options'] :
                    json_decode($this->cartItems[$index]['options'], true);

                if (is_array($options)) {
                    foreach ($options as $optionValueId) {
                        $optionValue = OptionValue::with('productOption')->find($optionValueId);
                        if ($optionValue) {
                            $this->editingOptions[$optionValue->productOption->id] = $optionValueId;
                        }
                    }
                }
            }

            // Show modal
            $this->dispatch('showModal', 'editOptionsModal');
        }
    }

    public function saveItemOptions()
    {
        if ($this->editingItemIndex !== null && isset($this->cartItems[$this->editingItemIndex])) {
            $product = Product::with(['options.values'])->find($this->cartItems[$this->editingItemIndex]['product_id']);

            if ($product) {
                // Calculate new unit price
                $basePrice = $product->base_price;
                $optionsPrice = 0;
                $selectedOptions = [];

                foreach ($this->editingOptions as $optionId => $valueId) {
                    if ($valueId) {
                        $optionValue = OptionValue::find($valueId);
                        if ($optionValue) {
                            $optionsPrice += $optionValue->price_adjustment;
                            $selectedOptions[] = $valueId;
                        }
                    }
                }

                $newUnitPrice = $basePrice + $optionsPrice;
                $newTotalPrice = $newUnitPrice * $this->cartItems[$this->editingItemIndex]['quantity'];

                // Update cart item
                $this->cartItems[$this->editingItemIndex]['unit_price'] = $newUnitPrice;
                $this->cartItems[$this->editingItemIndex]['total_price'] = $newTotalPrice;
                $this->cartItems[$this->editingItemIndex]['options'] = $selectedOptions;

                // Update option details for display
                $optionDetails = [];
                foreach ($selectedOptions as $optionValueId) {
                    $optionValue = OptionValue::with('productOption')->find($optionValueId);
                    if ($optionValue) {
                        $optionDetails[] = [
                            'option_name' => $optionValue->productOption->name,
                            'value' => $optionValue->value,
                            'price_adjustment' => $optionValue->price_adjustment
                        ];
                    }
                }
                $this->cartItems[$this->editingItemIndex]['option_details'] = $optionDetails;

                session()->flash('success', 'Product options updated successfully!');
            }
        }

        $this->editingItemIndex = null;
        $this->editingOptions = [];
        $this->dispatch('hideModal', 'editOptionsModal');
    }

    public function toggleAddProducts()
    {
        $this->showAddProducts = !$this->showAddProducts;
        $this->resetPage();
    }

    public function saveOrder()
    {
        try {
            DB::transaction(function () {
                // Update order notes
                $this->order->update([
                    'notes' => $this->orderNotes
                ]);

                // Get existing order item IDs
                $existingItemIds = collect($this->cartItems)
                    ->where('is_existing', true)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Remove items that are no longer in the cart
                $this->order->items()
                    ->whereNotIn('id', $existingItemIds)
                    ->delete();

                // Update or create items
                foreach ($this->cartItems as $cartItem) {
                    if ($cartItem['is_existing'] && $cartItem['id']) {
                        // Update existing item
                        OrderItem::where('id', $cartItem['id'])->update([
                            'quantity' => $cartItem['quantity'],
                            'unit_price' => $cartItem['unit_price'],
                            'total_price' => $cartItem['total_price'],
                            'options' => $cartItem['options'],
                            'notes' => $cartItem['notes']
                        ]);
                    } else {
                        // Create new item
                        $this->order->items()->create([
                            'product_id' => $cartItem['product_id'],
                            'quantity' => $cartItem['quantity'],
                            'unit_price' => $cartItem['unit_price'],
                            'total_price' => $cartItem['total_price'],
                            'options' => $cartItem['options'],
                            'notes' => $cartItem['notes']
                        ]);
                    }
                }

                // Recalculate order totals
                $this->order->calculateTotals();
            });

            session()->flash('success', 'Order updated successfully!');

            // Redirect back to appointment details
            return redirect()->route('designer.appointments.show', $this->appointment);
        } catch (\Exception $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            session()->flash('error', 'Failed to update order. Please try again.');
        }
    }

    public function getCartTotalProperty()
    {
        return collect($this->cartItems)->sum('total_price');
    }

    public function getCartItemsCountProperty()
    {
        return collect($this->cartItems)->sum('quantity');
    }

    private function findCartItemIndex($productId, $options)
    {
        foreach ($this->cartItems as $index => $item) {
            if (
                $item['product_id'] == $productId &&
                $item['options'] == $options
            ) {
                return $index;
            }
        }
        return false;
    }

    public function render()
    {
        $products = Product::with(['category', 'options.values'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_filter, function ($query) {
                $query->where('category_id', $this->category_filter);
            })
            ->where('is_active', true)
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        return view('livewire.designer-edit-order', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
