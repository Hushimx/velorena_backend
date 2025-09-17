<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddToOrder extends Component
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

    public function addToOrder()
    {
        if (!Auth::check()) {
            // Store the current URL as intended URL for redirect after login
            session(['url.intended' => request()->fullUrl()]);

            // Redirect to login page
            return redirect()->route('login');
        }

        // Simple test to see if method is called
        session()->flash('debug', 'addToOrder method called! User ID: ' . Auth::id());

        Log::info('addToOrder method called', [
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
            'selectedOptions' => $this->selectedOptions
        ]);

        try {
            Log::info('Starting validation...');
            $this->validate();
            Log::info('Basic validation passed');

            // Validate required options
            Log::info('Validating required options...');
            $this->validateRequiredOptions();
            Log::info('Required options validation completed');

            // Check if there are any validation errors
            if ($this->getErrorBag()->count() > 0) {
                Log::error('Validation errors in addToOrder', [
                    'errors' => $this->getErrorBag()->toArray()
                ]);
                return;
            }
            Log::info('No validation errors found');

            // Get or create pending order for the user
            $order = Order::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'status' => 'pending'
                ],
                [
                    'order_number' => Order::generateOrderNumber(),
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0
                ]
            );

            Log::info('Order found/created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            // Filter out null values from selectedOptions
            $filteredOptions = array_filter($this->selectedOptions, function ($value) {
                return $value !== null;
            });

            // Debug: Log the options being sent
            Log::info('Adding product to order', [
                'product_id' => $this->product->id,
                'quantity' => $this->quantity,
                'selectedOptions' => $this->selectedOptions,
                'filteredOptions' => $filteredOptions
            ]);

            // Add product to order
            $orderItem = $order->addProduct($this->product, $this->quantity, $filteredOptions);

            Log::info('Product added to order', [
                'order_item_id' => $orderItem->id,
                'order_item_total' => $orderItem->total_price
            ]);

            // Add notes if provided
            if (!empty($this->notes)) {
                $orderItem->update(['notes' => $this->notes]);
            }

            $this->showModal = false;
            $this->reset(['quantity', 'selectedOptions', 'notes']);

            session()->flash('message', trans('orders.product_added_to_order'));

            $this->dispatch('order-updated');

            Log::info('addToOrder completed successfully');
        } catch (\Exception $e) {
            Log::error('Error in addToOrder', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Failed to add product to order: ' . $e->getMessage());
        }
    }

    public function openModal()
    {
        $this->showModal = true;
        // Reset selected options when opening modal
        foreach ($this->product->options as $option) {
            $this->selectedOptions[$option->id] = null;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['quantity', 'selectedOptions', 'notes']);
    }

    private function validateRequiredOptions()
    {
        foreach ($this->product->options as $option) {
            if ($option->is_required && empty($this->selectedOptions[$option->id])) {
                $this->addError('selectedOptions.' . $option->id, 'This option is required.');
            }
        }
    }

    public function updatedSelectedOptions($value, $key)
    {
        // Debug: Log when options are updated
        Log::info('Option updated', [
            'key' => $key,
            'value' => $value,
            'allOptions' => $this->selectedOptions
        ]);
    }

    public function render()
    {
        return view('livewire.add-to-order');
    }
}
