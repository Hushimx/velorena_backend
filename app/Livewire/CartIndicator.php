<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartIndicator extends Component
{
    public $itemCount = 0;
    public $totalPrice = 0;

    protected $listeners = [
        'cartUpdated' => 'loadCartData'
    ];

    public function mount()
    {
        $this->loadCartData();
    }

    public function loadCartData()
    {
        if (!Auth::check()) {
            $this->itemCount = 0;
            $this->totalPrice = 0;
            return;
        }

        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->get();

        $this->itemCount = $cartItems->count();
        $this->totalPrice = $cartItems->sum('total_price');
    }

    public function render()
    {
        return view('livewire.cart-indicator');
    }
}
