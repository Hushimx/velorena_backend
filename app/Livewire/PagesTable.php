<?php

namespace App\Livewire;

use App\Models\ProtectedPage;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class PagesTable extends Component
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

    public $deletePageId = null;
    public $showDeleteModal = false;

    public function confirmDelete($pageId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deletePageId = $pageId;
        $this->showDeleteModal = true;
    }

    public function deletePage()
    {
        try {
            if ($this->deletePageId) {
                $page = ProtectedPage::findOrFail($this->deletePageId);
                $page->delete();

                session()->flash('message', trans('admin.page_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('admin.error_occurred'));
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
        $this->deletePageId = null;
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
        $pages = ProtectedPage::when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('title_ar', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('content_ar', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pages-table', [
            'pages' => $pages,
        ]);
    }
}
