<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\OptionValue;
use Illuminate\Support\Facades\Session;

class GuestCartService
{
    private const CART_SESSION_KEY = 'guest_cart';

    /**
     * Get all items in the guest cart
     */
    public function getCartItems(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }

    /**
     * Add item to guest cart
     */
    public function addToCart(int $productId, int $quantity, array $selectedOptions = [], string $notes = ''): array
    {
        $cartItems = $this->getCartItems();
        $cartKey = $this->generateCartKey($productId, $selectedOptions);

        if (isset($cartItems[$cartKey])) {
            // Update existing item
            $cartItems[$cartKey]['quantity'] += $quantity;
        } else {
            // Add new item
            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception('Product not found');
            }

            $unitPrice = $this->calculateUnitPrice($product, $selectedOptions);

            $cartItems[$cartKey] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'selected_options' => $selectedOptions,
                'notes' => $notes,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $quantity,
                'created_at' => now()->toISOString()
            ];
        }

        // Recalculate total price
        $cartItems[$cartKey]['total_price'] = $cartItems[$cartKey]['unit_price'] * $cartItems[$cartKey]['quantity'];

        Session::put(self::CART_SESSION_KEY, $cartItems);
        return $cartItems[$cartKey];
    }

    /**
     * Update item quantity in guest cart
     */
    public function updateQuantity(string $cartKey, int $quantity): bool
    {
        $cartItems = $this->getCartItems();
        
        if (!isset($cartItems[$cartKey]) || $quantity < 1) {
            return false;
        }

        $cartItems[$cartKey]['quantity'] = $quantity;
        $cartItems[$cartKey]['total_price'] = $cartItems[$cartKey]['unit_price'] * $quantity;

        Session::put(self::CART_SESSION_KEY, $cartItems);
        return true;
    }

    /**
     * Remove item from guest cart
     */
    public function removeFromCart(string $cartKey): bool
    {
        $cartItems = $this->getCartItems();
        
        if (!isset($cartItems[$cartKey])) {
            return false;
        }

        unset($cartItems[$cartKey]);
        Session::put(self::CART_SESSION_KEY, $cartItems);
        return true;
    }

    /**
     * Clear all items from guest cart
     */
    public function clearCart(): void
    {
        Session::forget(self::CART_SESSION_KEY);
    }

    /**
     * Get cart summary
     */
    public function getCartSummary(): array
    {
        $cartItems = $this->getCartItems();
        $itemCount = count($cartItems);
        $totalPrice = array_sum(array_column($cartItems, 'total_price'));

        return [
            'item_count' => $itemCount,
            'total_price' => $totalPrice,
            'items' => $cartItems
        ];
    }

    /**
     * Merge guest cart with user cart
     */
    public function mergeWithUserCart(int $userId): int
    {
        $guestCartItems = $this->getCartItems();
        $mergedCount = 0;

        foreach ($guestCartItems as $cartKey => $guestItem) {
            // Check if user already has this exact item
            $existingItem = CartItem::where('user_id', $userId)
                ->where('product_id', $guestItem['product_id'])
                ->where('selected_options', json_encode($guestItem['selected_options']))
                ->first();

            if ($existingItem) {
                // Update quantity of existing item
                $existingItem->quantity += $guestItem['quantity'];
                $existingItem->updatePrices();
                $existingItem->save();
            } else {
                // Create new cart item
                $cartItem = new CartItem([
                    'user_id' => $userId,
                    'product_id' => $guestItem['product_id'],
                    'quantity' => $guestItem['quantity'],
                    'selected_options' => $guestItem['selected_options'],
                    'notes' => $guestItem['notes'] ?? '',
                    'unit_price' => $guestItem['unit_price'],
                    'total_price' => $guestItem['total_price']
                ]);
                $cartItem->save();
            }
            $mergedCount++;
        }

        // Clear guest cart after merging
        $this->clearCart();
        return $mergedCount;
    }

    /**
     * Generate unique key for cart item based on product and options
     */
    private function generateCartKey(int $productId, array $selectedOptions): string
    {
        return md5($productId . '_' . json_encode($selectedOptions));
    }

    /**
     * Calculate unit price including option adjustments
     */
    private function calculateUnitPrice(Product $product, array $selectedOptions): float
    {
        $price = (float) $product->base_price;

        foreach ($selectedOptions as $optionId => $valueId) {
            if ($valueId) {
                // Handle checkbox arrays
                if (is_array($valueId)) {
                    foreach ($valueId as $singleValueId) {
                        $optionValue = OptionValue::find($singleValueId);
                        if ($optionValue && $optionValue->price_adjustment) {
                            $price += (float) $optionValue->price_adjustment;
                        }
                    }
                } else {
                    // Handle single values (radio/select)
                    $optionValue = OptionValue::find($valueId);
                    if ($optionValue && $optionValue->price_adjustment) {
                        $price += (float) $optionValue->price_adjustment;
                    }
                }
            }
        }

        return $price;
    }

    /**
     * Get cart items with full product data for display
     */
    public function getCartItemsWithProducts(): array
    {
        $cartItems = $this->getCartItems();
        $enhancedItems = [];

        foreach ($cartItems as $cartKey => $item) {
            $product = Product::with('options.values')->find($item['product_id']);
            if (!$product) {
                continue;
            }

            // Build selected options with full option value data for display
            $selectedOptionsDisplay = [];
            if (!empty($item['selected_options'])) {
                foreach ($item['selected_options'] as $optionId => $valueId) {
                    if ($valueId) {
                        $option = $product->options->find($optionId);
                        $optionValue = OptionValue::find($valueId);
                        if ($option && $optionValue) {
                            $optionName = app()->getLocale() === 'ar' ? ($option->name_ar ?? $option->name) : $option->name;
                            $optionValueText = app()->getLocale() === 'ar' ? ($optionValue->value_ar ?? $optionValue->value) : $optionValue->value;
                            
                            $selectedOptionsDisplay[$optionName] = [
                                'value' => $optionValueText,
                                'price_adjustment' => $optionValue->price_adjustment ?? 0
                            ];
                        }
                    }
                }
            }

            $enhancedItems[] = [
                'cart_key' => $cartKey,
                'product_id' => $item['product_id'],
                'product_name' => app()->getLocale() === 'ar' ? ($product->name_ar ?? $product->name) : $product->name,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'name_ar' => $product->name_ar,
                    'base_price' => $product->base_price,
                    'image' => $product->image_url ?? $product->image,
                    'options' => $product->options
                ],
                'quantity' => $item['quantity'],
                'selected_options' => $selectedOptionsDisplay,
                'notes' => $item['notes'] ?? '',
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price']
            ];
        }

        return $enhancedItems;
    }
}
