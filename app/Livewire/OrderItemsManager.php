<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderItemsManager extends Component
{
    public $order;
    public $orderItems;
    public $deleteItemId = null;
    public $showDeleteModal = false;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->loadOrderItems();
    }

    public function loadOrderItems()
    {
        $this->orderItems = $this->order->items()->with('product')->get();
    }

    public function confirmDelete($itemId)
    {
        $this->deleteItemId = $itemId;
        $this->showDeleteModal = true;
    }

    public function deleteOrderItem()
    {
        try {
            if (!$this->deleteItemId) {
                session()->flash('error', trans('orders.order_item_not_found'));
                $this->closeModal();
                return;
            }



            // Check if the order belongs to the current user
            if ($this->order->user_id !== Auth::id()) {
                session()->flash('error', trans('orders.unauthorized_action'));
                $this->closeModal();
                return;
            }

            // Check if the order is pending (only allow deletion for pending orders)
            if ($this->order->status !== 'pending') {
                session()->flash('error', trans('orders.cannot_delete_items_non_pending_order'));
                $this->closeModal();
                return;
            }

            $orderItem = OrderItem::where('order_id', $this->order->id)
                ->where('id', $this->deleteItemId)
                ->first();

            if (!$orderItem) {
                session()->flash('error', trans('orders.order_item_not_found'));
                $this->closeModal();
                return;
            }



            // Delete the order item
            $orderItem->delete();

            // Recalculate order totals
            $this->recalculateOrderTotals();

            // Reload order items
            $this->loadOrderItems();

            // Refresh the order model
            $this->order->refresh();

            session()->flash('message', trans('orders.order_item_deleted_successfully'));

            // Dispatch event to refresh parent components
            $this->dispatch('order-updated');
        } catch (\Exception $e) {
            Log::error('Error deleting order item', [
                'order_id' => $this->order->id,
                'item_id' => $this->deleteItemId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            session()->flash('error', trans('orders.order_item_delete_failed'));
        }

        $this->closeModal();
    }

    public function cancelDelete()
    {
        $this->closeModal();
    }

    private function closeModal()
    {
        $this->deleteItemId = null;
        $this->showDeleteModal = false;
        $this->dispatch('modal-closed');
    }

    private function recalculateOrderTotals()
    {
        // Get all remaining items for this order
        $items = $this->order->items;

        // Calculate new totals
        $subtotal = $items->sum('total_price');
        $tax = $subtotal * 0.15; // Assuming 15% tax rate
        $total = $subtotal + $tax;

        // Update the order
        $this->order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);

        // Refresh the order model
        $this->order->refresh();
    }

    public function render()
    {
        // Reload order items to ensure we have the latest data
        $this->loadOrderItems();

        return view('livewire.order-items-manager');
    }
}
