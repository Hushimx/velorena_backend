<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
    }

    public function addToOrder()
    {
        $this->validate();

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

        // Add product to order
        $order->addProduct($this->product, $this->quantity, $this->selectedOptions);

        // Add notes if provided
        if (!empty($this->notes)) {
            $orderItem = $order->items()->latest()->first();
            $orderItem->update(['notes' => $this->notes]);
        }

        $this->showModal = false;
        $this->reset(['quantity', 'selectedOptions', 'notes']);

        session()->flash('message', trans('orders.product_added_to_order'));

        $this->dispatch('order-updated');
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['quantity', 'selectedOptions', 'notes']);
    }

    public function render()
    {
        return view('livewire.add-to-order');
    }
}
