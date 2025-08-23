<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class OrdersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    protected $queryString = ['search', 'status_filter'];
    protected string $paginationTheme = 'tailwind';

    // reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public $deleteOrderId = null;
    public $showDeleteModal = false;

    public function confirmDelete($orderId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteOrderId = $orderId;
        $this->showDeleteModal = true;
    }

    public function deleteOrder()
    {
        try {
            if ($this->deleteOrderId) {
                $order = Order::findOrFail($this->deleteOrderId);

                // Check if order can be deleted (only pending orders)
                if (!$order->isPending()) {
                    session()->flash('error', trans('orders.cannot_delete_non_pending_order'));
                    $this->closeModal();
                    return;
                }

                $order->delete();

                session()->flash('message', trans('orders.order_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('orders.delete_error'));
        }

        $this->closeModal();
        $this->resetPage(); // Reset pagination after deletion
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

    public function refreshComponent()
    {
        $this->resetPage();
        $this->closeModal();
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['user', 'items'])
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('order_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->status_filter, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.orders-table', [
            'orders' => $orders,
        ]);
    }
}
