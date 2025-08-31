<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserOrdersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    protected $queryString = ['search', 'status_filter'];
    protected string $paginationTheme = 'tailwind';

    protected $listeners = ['order-updated' => 'refreshOrders'];

    public $deleteOrderId = null;
    public $showDeleteModal = false;

    // reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function refreshOrders()
    {
        $this->resetPage();
        $this->render();
    }

    public function confirmDelete($orderId)
    {
        $this->deleteOrderId = $orderId;
        $this->showDeleteModal = true;
    }

    public function deleteOrder()
    {
        try {
            if (!$this->deleteOrderId) {
                session()->flash('error', trans('orders.order_not_found'));
                $this->closeModal();
                return;
            }

            $order = Order::where('user_id', Auth::id())->findOrFail($this->deleteOrderId);

            // Delete the order (this will cascade delete order items due to foreign key constraints)
            $order->delete();

            session()->flash('message', trans('orders.order_deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Error deleting order', [
                'order_id' => $this->deleteOrderId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            session()->flash('error', trans('orders.order_delete_failed'));
        }

        $this->closeModal();
    }

    public function cancelDelete()
    {
        $this->closeModal();
    }

    private function closeModal()
    {
        $this->deleteOrderId = null;
        $this->showDeleteModal = false;
        $this->dispatch('modal-closed');
    }

    public function render()
    {
        $userId = Auth::id();

        $orders = Order::query()
            ->where('user_id', $userId)
            ->with(['items'])
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('order_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($this->status_filter, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.user-orders-table', [
            'orders' => $orders,
        ]);
    }
}
