<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('livewire.users-table', [
            'users' => $users,
        ]);
    }
}
