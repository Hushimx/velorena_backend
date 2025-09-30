<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Services\GuestCartService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartIndicator extends Component
{
    public $itemCount = 0;
    public $totalPrice = 0;

    protected $listeners = [
        'cartUpdated' => 'loadCartData',
        'getCartPreviewData' => 'emitCartPreviewData'
    ];

    public function mount()
    {
        $this->loadCartData();
    }

    public function loadCartData()
    {
        if (Auth::check()) {
            // Authenticated user - get from database
            $user = Auth::user();
            $cartItems = CartItem::where('user_id', $user->id)->get();

            $this->itemCount = $cartItems->count();
            $this->totalPrice = $cartItems->sum('total_price');
        } else {
            // Guest user - get from session
            $guestCartService = app(GuestCartService::class);
            $cartSummary = $guestCartService->getCartSummary();

            $this->itemCount = $cartSummary['item_count'];
            $this->totalPrice = $cartSummary['total_price'];
        }
    }

    public function emitCartPreviewData()
    {
        if (Auth::check()) {
            // Authenticated user - get from database
            $user = Auth::user();
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
            
            $items = $cartItems->map(function($item) {
                return [
                    'product_name' => app()->getLocale() === 'ar' ? ($item->product->name_ar ?? $item->product->name) : $item->product->name,
                    'product_image' => $item->product->image_url ?? $item->product->image,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price
                ];
            });
            
            $data = [
                'items' => $items,
                'item_count' => $cartItems->count(),
                'total_price' => $cartItems->sum('total_price')
            ];
        } else {
            // Guest user - get from session
            $guestCartService = app(GuestCartService::class);
            $cartSummary = $guestCartService->getCartSummary();
            $cartItems = $guestCartService->getCartItemsWithProducts();
            
            $items = collect($cartItems)->map(function($item) {
                return [
                    'product_name' => app()->getLocale() === 'ar' ? ($item['product']['name_ar'] ?? $item['product']['name']) : $item['product']['name'],
                    'product_image' => $item['product']['image_url'] ?? $item['product']['image'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price']
                ];
            });
            
            $data = [
                'items' => $items,
                'item_count' => $cartSummary['item_count'],
                'total_price' => $cartSummary['total_price']
            ];
        }
        
        $this->emit('cartPreviewData', $data);
    }

    public function render()
    {
        return view('livewire.cart-indicator');
    }
}
