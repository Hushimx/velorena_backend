<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class UsersTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];
    protected string $paginationTheme = 'tailwind';

    // reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public $deleteUserId = null;
    public $showDeleteModal = false;

    public function confirmDelete($userId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteUserId = $userId;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        try {
            if ($this->deleteUserId) {
                $user = User::findOrFail($this->deleteUserId);
                $user->delete();

                session()->flash('message', trans('users.user_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('users.delete_error'));
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
        $this->deleteUserId = null;
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
        $users = User::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.users-table', [
            'users' => $users,
        ]);
    }
}
