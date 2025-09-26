<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-gray-900">{{ trans('products.product_options') }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ trans('products.option_management_description') }}</p>
        </div>
        <button type="button" wire:click="$set('showAddOption', true)" 
                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-medium text-sm text-white hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            {{ trans('products.add_option') }}
        </button>
    </div>

    <!-- Success Message -->
        @if (session()->has('message'))
        <div class="bg-white border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <p class="text-sm text-green-800 font-medium">{{ session('message') }}</p>
            </div>
            </div>
        @endif

        <!-- Add New Option Form -->
        @if($showAddOption)
        <div class="bg-white border border-indigo-200 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">{{ trans('products.add_option') }}</h4>
                <button type="button" wire:click="$set('showAddOption', false)" 
                        class="text-gray-400 hover:text-gray-600 transition duration-200 p-1 rounded-lg hover:bg-gray-100">
                    ×
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">{{ trans('products.option_name') }} *</label>
                        <input type="text" wire:model="newOption.name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                           placeholder="e.g., Size, Color, Material">
                        @error('newOption.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">{{ trans('products.option_name_ar') }}</label>
                        <input type="text" wire:model="newOption.name_ar" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                           placeholder="اسم الخيار">
                </div>
                </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">{{ trans('products.option_type') }}</label>
                        <select wire:model="newOption.type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                        <option value="select">{{ trans('products.select') }}</option>
                        <option value="radio">{{ trans('products.radio') }}</option>
                        <option value="checkbox">{{ trans('products.checkbox') }}</option>
                        <option value="text">{{ trans('products.text') }}</option>
                        <option value="number">{{ trans('products.number') }}</option>
                        </select>
                    </div>
                    
                <div class="flex items-center">
                        <label class="flex items-center">
                        <input type="checkbox" wire:model="newOption.is_required" class="mr-3 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">{{ trans('products.required') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Option Values -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <label class="block text-sm font-semibold text-gray-700">{{ trans('products.option_values') }}</label>
                        <button type="button" wire:click="addValueToNewOption" 
                            class="inline-flex items-center px-3 py-2 text-sm text-indigo-600 hover:text-indigo-800 transition duration-200 bg-indigo-50 hover:bg-indigo-100 rounded-lg">
                        {{ trans('products.add_value') }}
                        </button>
                    </div>
                    
                <div class="space-y-3">
                    @foreach($newOption['values'] as $index => $value)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 p-4 bg-white rounded-lg border border-indigo-200 shadow-sm">
                            <input type="text" wire:model="newOption.values.{{ $index }}.value" 
                                   placeholder="{{ trans('products.value') }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                            <input type="text" wire:model="newOption.values.{{ $index }}.value_ar" 
                                   placeholder="{{ trans('products.value_ar') }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                            <input type="number" wire:model="newOption.values.{{ $index }}.price_adjustment" 
                                   placeholder="{{ trans('products.price_adjustment') }}" step="0.01"
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                            <div class="flex items-center justify-end">
                                <button type="button" wire:click="removeValueFromNewOption({{ $index }})" 
                                        class="text-red-600 hover:text-red-800 transition duration-200 p-2">
                                    ×
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-indigo-200">
                <button type="button" wire:click="$set('showAddOption', false)" 
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200 shadow-sm">
                    {{ trans('products.cancel') }}
                    </button>
                <button type="button" wire:click="saveOption" 
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    {{ trans('products.save_option') }}
                    </button>
                </div>
            </div>
        @endif

        <!-- Existing Options -->
        <div class="space-y-4">
            @forelse($options as $option)
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 hover:border-indigo-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h5 class="text-lg font-semibold text-gray-900">{{ $option['name'] }}</h5>
                        @if($option['name_ar'])
                            <p class="text-sm text-gray-600 mt-1">{{ $option['name_ar'] }}</p>
                        @endif
                        <div class="flex items-center space-x-3 mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ ucfirst($option['type']) }}
                            </span>
                            @if($option['is_required'])
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ trans('products.required') }}
                                </span>
                            @endif
                        </div>
                        </div>
                        <div class="flex space-x-2">
                        <button type="button" wire:click="editOption({{ $option['id'] }})" 
                                class="p-2.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition duration-200 shadow-sm">
                            Edit
                        </button>
                        <button type="button" wire:click="deleteOption({{ $option['id'] }})" 
                                onclick="return confirm('{{ trans('products.confirm_delete_option') }}')"
                                class="p-2.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition duration-200 shadow-sm">
                            Delete
                        </button>
                        </div>
                    </div>
                    
                    @if(!empty($option['values']))
                    <div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($option['values'] as $value)
                                <div class="p-3 bg-white rounded-lg border border-indigo-200 hover:border-indigo-300 transition duration-200 shadow-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ $value['value'] }}
                                    </div>
                                    @if($value['value_ar'])
                                        <div class="text-sm text-gray-600 mt-1">{{ $value['value_ar'] }}</div>
                                    @endif
                                    @if($value['price_adjustment'] != 0)
                                        <div class="text-sm font-medium mt-2 {{ $value['price_adjustment'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $value['price_adjustment'] > 0 ? '+' : '' }}{{ $value['price_adjustment'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        </div>
                    @endif
                </div>
        @empty
            <div class="text-center py-16 bg-white rounded-xl border-2 border-dashed border-indigo-300">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ trans('products.no_options_added') }}</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">{{ trans('products.option_management_description') }}</p>
                <button type="button" wire:click="$set('showAddOption', true)" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-medium text-sm text-white hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    {{ trans('products.add_your_first_option') }}
                </button>
            </div>
        @endforelse
    </div>
</div>

<!-- Edit Option Modal -->
@if($editingOption)
    <div class="fixed inset-0 overflow-y-auto h-full w-full z-50 bg-black bg-opacity-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-xl rounded-xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <h4 class="text-xl font-semibold text-gray-900">{{ trans('products.edit_option') }}</h4>
                    <button type="button" wire:click="cancelEdit" 
                            class="text-gray-400 hover:text-gray-600 transition duration-200 p-1 rounded-lg hover:bg-gray-100">
                        ×
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Option Name *</label>
                        <input type="text" wire:model="editingOption.name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                               placeholder="e.g., Size, Color, Material">
                        @error('editingOption.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Option Name (Arabic)</label>
                        <input type="text" wire:model="editingOption.name_ar" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                               placeholder="اسم الخيار">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Type</label>
                        <select wire:model="editingOption.type" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <option value="select">Select</option>
                            <option value="radio">Radio</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="editingOption.is_required" class="mr-3 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm text-gray-700">Required</span>
                        </label>
                    </div>
                </div>

                <!-- Option Values -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Option Values</label>
                        <button type="button" wire:click="addValueToEditingOption" 
                                class="inline-flex items-center px-3 py-2 text-sm text-green-600 hover:text-green-800 transition duration-200">
                            <i class="fas fa-plus mr-1"></i>Add Value
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                    @foreach($editingOption['values'] as $index => $value)
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <input type="text" wire:model="editingOption.values.{{ $index }}.value" 
                                   placeholder="Value" 
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <input type="text" wire:model="editingOption.values.{{ $index }}.value_ar" 
                                   placeholder="Value (Arabic)" 
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <input type="number" wire:model="editingOption.values.{{ $index }}.price_adjustment" 
                                   placeholder="Price Adjustment" step="0.01"
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <div class="flex items-center justify-end">
                                <button type="button" wire:click="removeValueFromEditingOption({{ $index }})" 
                                            class="text-red-600 hover:text-red-800 transition duration-200 p-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="cancelEdit" 
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200 shadow-sm">
                        {{ trans('products.cancel') }}
                    </button>
                    <button type="button" wire:click="updateOption" 
                            class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        {{ trans('products.update_option') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif