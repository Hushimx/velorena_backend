<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200" style="background: var(--brand-yellow-light);">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium" style="color: var(--brand-brown);">Product Options</h3>
            <button wire:click="$set('showAddOption', true)" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150"
                    style="background: var(--brand-brown); hover:background: var(--brand-brown-hover);"
                    onmouseover="this.style.background='var(--brand-brown-hover)'"
                    onmouseout="this.style.background='var(--brand-brown)'">
                <i class="fas fa-plus mr-2"></i>
                Add Option
            </button>
        </div>
    </div>

    <div class="p-6">
        @if (session()->has('message'))
            <div class="mb-4 px-4 py-3 rounded border" style="background: var(--brand-yellow-light); border-color: var(--brand-yellow); color: var(--brand-brown);">
                <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
            </div>
        @endif

        <!-- Add New Option Form -->
        @if($showAddOption)
            <div class="mb-6 p-6 rounded-lg border" style="background: var(--bg-tertiary); border-color: var(--brand-yellow);">
                <h4 class="text-md font-medium mb-4" style="color: var(--brand-brown);">Add New Option</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--brand-brown);">Option Name *</label>
                        <input type="text" wire:model="newOption.name" 
                               class="w-full px-3 py-2 border rounded-md transition duration-200"
                               style="border-color: var(--border-light); focus:border-color: var(--brand-brown); focus:ring-color: var(--brand-yellow);"
                               onfocus="this.style.borderColor='var(--brand-brown)'; this.style.boxShadow='0 0 0 3px var(--brand-yellow-light)'"
                               onblur="this.style.borderColor='var(--border-light)'; this.style.boxShadow='none'">
                        @error('newOption.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--brand-brown);">Option Name (Arabic)</label>
                        <input type="text" wire:model="newOption.name_ar" 
                               class="w-full px-3 py-2 border rounded-md transition duration-200"
                               style="border-color: var(--border-light);"
                               onfocus="this.style.borderColor='var(--brand-brown)'; this.style.boxShadow='0 0 0 3px var(--brand-yellow-light)'"
                               onblur="this.style.borderColor='var(--border-light)'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--brand-brown);">Type</label>
                        <select wire:model="newOption.type" 
                                class="w-full px-3 py-2 border rounded-md transition duration-200"
                                style="border-color: var(--border-light);"
                                onfocus="this.style.borderColor='var(--brand-brown)'; this.style.boxShadow='0 0 0 3px var(--brand-yellow-light)'"
                                onblur="this.style.borderColor='var(--border-light)'; this.style.boxShadow='none'">
                            <option value="select">Select</option>
                            <option value="radio">Radio</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="newOption.is_required" class="mr-2" style="accent-color: var(--brand-brown);">
                            <span class="text-sm" style="color: var(--brand-brown);">Required</span>
                        </label>
                    </div>
                </div>

                <!-- Option Values -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold" style="color: var(--brand-brown);">Option Values</label>
                        <button type="button" wire:click="addValueToNewOption" 
                                class="text-sm transition duration-200"
                                style="color: var(--brand-brown);"
                                onmouseover="this.style.color='var(--brand-brown-hover)'"
                                onmouseout="this.style.color='var(--brand-brown)'">
                            <i class="fas fa-plus mr-1"></i>Add Value
                        </button>
                    </div>
                    
                    @foreach($newOption['values'] as $index => $value)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-2 p-3 rounded border" style="background: var(--bg-primary); border-color: var(--brand-yellow);">
                            <input type="text" wire:model="newOption.values.{{ $index }}.value" 
                                   placeholder="Value" 
                                   class="px-3 py-2 border rounded-md transition duration-200"
                                   style="border-color: var(--border-light);"
                                   onfocus="this.style.borderColor='var(--brand-brown)'; this.style.boxShadow='0 0 0 3px var(--brand-yellow-light)'"
                                   onblur="this.style.borderColor='var(--border-light)'; this.style.boxShadow='none'">
                            <input type="text" wire:model="newOption.values.{{ $index }}.value_ar" 
                                   placeholder="Value (Arabic)" 
                                   class="px-3 py-2 border rounded-md transition duration-200"
                                   style="border-color: var(--border-light);"
                                   onfocus="this.style.borderColor='var(--brand-brown)'; this.style.boxShadow='0 0 0 3px var(--brand-yellow-light)'"
                                   onblur="this.style.borderColor='var(--border-light)'; this.style.boxShadow='none'">
                            <input type="number" wire:model="newOption.values.{{ $index }}.price_adjustment" 
                                   placeholder="Price Adjustment" step="0.01"
                                   class="px-3 py-2 border rounded-md transition duration-200"
                                   style="border-color: var(--border-light);"
                                   onfocus="this.style.borderColor='var(--brand-brown)'; this.style.boxShadow='0 0 0 3px var(--brand-yellow-light)'"
                                   onblur="this.style.borderColor='var(--border-light)'; this.style.boxShadow='none'">
                            <div class="flex items-center justify-end">
                                <button type="button" wire:click="removeValueFromNewOption({{ $index }})" 
                                        class="text-red-600 hover:text-red-800 transition duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-3">
                    <button wire:click="$set('showAddOption', false)" 
                            class="px-4 py-2 border rounded-md transition duration-200"
                            style="border-color: var(--border-light); color: var(--text-secondary);"
                            onmouseover="this.style.backgroundColor='var(--bg-hover)'"
                            onmouseout="this.style.backgroundColor='transparent'">
                        Cancel
                    </button>
                    <button wire:click="saveOption" 
                            class="px-4 py-2 text-white rounded-md transition duration-200"
                            style="background: var(--brand-brown);"
                            onmouseover="this.style.background='var(--brand-brown-hover)'"
                            onmouseout="this.style.background='var(--brand-brown)'">
                        Save Option
                    </button>
                </div>
            </div>
        @endif

        <!-- Existing Options -->
        <div class="space-y-4">
            @forelse($options as $option)
                <div class="border rounded-lg p-4 transition duration-200 hover:shadow-md" style="border-color: var(--brand-yellow); background: var(--bg-primary);">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h5 class="font-medium" style="color: var(--brand-brown);">{{ $option['name'] }}</h5>
                            @if($option['name_ar'])
                                <p class="text-sm" style="color: var(--text-secondary);">{{ $option['name_ar'] }}</p>
                            @endif
                            <div class="flex items-center space-x-4 mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: var(--brand-yellow-light); color: var(--brand-brown);">
                                    {{ ucfirst($option['type']) }}
                                </span>
                                @if($option['is_required'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: var(--brand-yellow); color: var(--brand-brown);">
                                        Required
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button wire:click="editOption({{ $option['id'] }})" 
                                    class="transition duration-200"
                                    style="color: var(--brand-brown);"
                                    onmouseover="this.style.color='var(--brand-brown-hover)'"
                                    onmouseout="this.style.color='var(--brand-brown)'">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="deleteOption({{ $option['id'] }})" 
                                    onclick="return confirm('Are you sure you want to delete this option?')"
                                    class="text-red-600 hover:text-red-800 transition duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    @if(!empty($option['values']))
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach($option['values'] as $value)
                                <div class="p-2 rounded text-sm" style="background: var(--brand-yellow-light);">
                                    <div class="font-medium" style="color: var(--brand-brown);">{{ $value['value'] }}</div>
                                    @if($value['value_ar'])
                                        <div style="color: var(--text-secondary);">{{ $value['value_ar'] }}</div>
                                    @endif
                                    @if($value['price_adjustment'] != 0)
                                        <div class="font-medium" style="color: var(--brand-brown);">
                                            {{ $value['price_adjustment'] > 0 ? '+' : '' }}{{ $value['price_adjustment'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-8" style="color: var(--text-secondary);">
                    <i class="fas fa-cog text-4xl mb-4" style="color: var(--brand-yellow);"></i>
                    <p>No options added yet. Click "Add Option" to get started.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Edit Option Modal -->
@if($editingOption)
    <div class="fixed inset-0 overflow-y-auto h-full w-full z-50" style="background: rgba(42, 30, 30, 0.5);">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md" style="background: var(--bg-primary); border-color: var(--brand-yellow); box-shadow: var(--shadow-brand);">
            <div class="mt-3">
                <h4 class="text-lg font-medium mb-4" style="color: var(--brand-brown);">Edit Option</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--brand-brown);">Option Name *</label>
                        <input type="text" wire:model="editingOption.name" 
                               class="w-full px-3 py-2 border rounded-md transition duration-200"
                               style="border-color: var(--border-light);"
                               onfocus="this.style.borderColor='var(--brand-brown)'; this.style.boxShadow='0 0 0 3px var(--brand-yellow-light)'"
                               onblur="this.style.borderColor='var(--border-light)'; this.style.boxShadow='none'">
                        @error('editingOption.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Option Name (Arabic)</label>
                        <input type="text" wire:model="editingOption.name_ar" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                        <select wire:model="editingOption.type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="select">Select</option>
                            <option value="radio">Radio</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="editingOption.is_required" class="mr-2">
                            <span class="text-sm text-gray-700">Required</span>
                        </label>
                    </div>
                </div>

                <!-- Option Values -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-gray-700">Option Values</label>
                        <button type="button" wire:click="addValueToEditingOption" 
                                class="text-sm text-green-600 hover:text-green-800">
                            <i class="fas fa-plus mr-1"></i>Add Value
                        </button>
                    </div>
                    
                    @foreach($editingOption['values'] as $index => $value)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-2 p-3 bg-gray-50 rounded border">
                            <input type="text" wire:model="editingOption.values.{{ $index }}.value" 
                                   placeholder="Value" 
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <input type="text" wire:model="editingOption.values.{{ $index }}.value_ar" 
                                   placeholder="Value (Arabic)" 
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <input type="number" wire:model="editingOption.values.{{ $index }}.price_adjustment" 
                                   placeholder="Price Adjustment" step="0.01"
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <div class="flex items-center justify-end">
                                <button type="button" wire:click="removeValueFromEditingOption({{ $index }})" 
                                        class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-3">
                    <button wire:click="cancelEdit" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button wire:click="updateOption" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Update Option
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif