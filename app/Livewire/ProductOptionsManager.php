<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\OptionValue;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductOptionsManager extends Component
{
    use WithFileUploads;

    public $product;
    public $options = [];
    public $newOption = [
        'name' => '',
        'name_ar' => '',
        'type' => 'select',
        'is_required' => false,
        'values' => []
    ];
    public $showAddOption = false;
    public $editingOption = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $rules = [
        'newOption.name' => 'required|string|max:255',
        'newOption.name_ar' => 'nullable|string|max:255',
        'newOption.type' => 'required|in:select,radio,checkbox,text,number',
        'newOption.is_required' => 'boolean',
        'newOption.values.*.value' => 'required_with:newOption.values|string|max:255',
        'newOption.values.*.value_ar' => 'nullable|string|max:255',
        'newOption.values.*.price_adjustment' => 'nullable|numeric'
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->loadOptions();
    }

    public function hydrate()
    {
        $this->loadOptions();
    }

    public function loadOptions()
    {
        $this->options = $this->product->options()
            ->with('values')
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    public function addValueToNewOption()
    {
        $this->newOption['values'][] = [
            'value' => '',
            'value_ar' => '',
            'price_adjustment' => 0,
            'is_active' => true
        ];
    }

    public function removeValueFromNewOption($index)
    {
        unset($this->newOption['values'][$index]);
        $this->newOption['values'] = array_values($this->newOption['values']);
    }

    public function saveOption()
    {
        $this->validate();

        $optionData = $this->newOption;
        $values = $optionData['values'] ?? [];
        unset($optionData['values']);
        
        // Set is_active to true by default
        $optionData['is_active'] = true;

        $option = $this->product->options()->create($optionData);

        foreach ($values as $valueData) {
            if (!empty($valueData['value'])) {
                $valueData['is_active'] = true; // Set is_active to true by default
                $option->values()->create($valueData);
            }
        }

        $this->resetNewOption();
        $this->loadOptions();
        $this->showAddOption = false;
        
        $this->dispatch('option-saved');
        session()->flash('message', trans('products.option_created_successfully'));
    }

    public function editOption($optionId)
    {
        $option = $this->product->options()->with('values')->find($optionId);
        if ($option) {
            $this->editingOption = $option->toArray();
            $this->editingOption['values'] = $option->values->toArray();
        }
    }

    public function updateOption()
    {
        if (!$this->editingOption) return;

        $this->validate([
            'editingOption.name' => 'required|string|max:255',
            'editingOption.name_ar' => 'nullable|string|max:255',
            'editingOption.type' => 'required|in:select,radio,checkbox,text,number',
            'editingOption.is_required' => 'boolean',
            'editingOption.values.*.value' => 'required_with:editingOption.values|string|max:255',
            'editingOption.values.*.value_ar' => 'nullable|string|max:255',
            'editingOption.values.*.price_adjustment' => 'nullable|numeric'
        ]);

        $option = $this->product->options()->find($this->editingOption['id']);
        if ($option) {
            $optionData = $this->editingOption;
            $values = $optionData['values'] ?? [];
            unset($optionData['values'], $optionData['id'], $optionData['product_id'], $optionData['created_at'], $optionData['updated_at']);
            
            // Set is_active to true by default
            $optionData['is_active'] = true;

            $option->update($optionData);

            // Update values
            $option->values()->delete();
            foreach ($values as $valueData) {
                if (!empty($valueData['value'])) {
                    unset($valueData['id'], $valueData['product_option_id'], $valueData['created_at'], $valueData['updated_at']);
                    $valueData['is_active'] = true; // Set is_active to true by default
                    $option->values()->create($valueData);
                }
            }
        }

        $this->editingOption = null;
        $this->loadOptions();
        
        $this->dispatch('option-updated');
        session()->flash('message', trans('products.option_updated_successfully'));
    }

    public function deleteOption($optionId)
    {
        $option = $this->product->options()->find($optionId);
        if ($option) {
            $option->delete();
            $this->loadOptions();
            $this->dispatch('option-deleted');
            session()->flash('message', trans('products.option_deleted_successfully'));
        }
    }

    public function addValueToEditingOption()
    {
        if ($this->editingOption) {
            $this->editingOption['values'][] = [
                'value' => '',
                'value_ar' => '',
                'price_adjustment' => 0,
            ];
        }
    }

    public function removeValueFromEditingOption($index)
    {
        if ($this->editingOption) {
            unset($this->editingOption['values'][$index]);
            $this->editingOption['values'] = array_values($this->editingOption['values']);
        }
    }

    public function resetNewOption()
    {
        $this->newOption = [
            'name' => '',
            'name_ar' => '',
            'type' => 'select',
            'is_required' => false,
            'values' => []
        ];
    }

    public function cancelEdit()
    {
        $this->editingOption = null;
    }

    public function render()
    {
        return view('livewire.product-options-manager');
    }
}