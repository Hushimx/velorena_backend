<div class="max-w-4xl mx-auto p-8 bg-white rounded-xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-semibold text-gray-900 mb-8">{{ trans('users.edit_user') }}</h2>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="block text-sm font-medium text-gray-700 mb-3">{{ trans('users.client_type') }}</label>
            <div class="flex space-x-6">
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input type="radio" wire:model="client_type" value="individual"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 ml-2">
                    <span class="text-gray-700">{{ trans('users.individual') }}</span>
                </label>
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input type="radio" wire:model="client_type" value="company"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 ml-2">
                    <span class="text-gray-700">{{ trans('users.company') }}</span>
                </label>
            </div>
        </div>

        <!-- Dynamic Fields -->
        <div class="space-y-6">
            @if ($client_type === 'individual')
                <!-- Individual Fields -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="full_name"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.full_name') }}*</label>
                        <input type="text" id="full_name" wire:model="full_name"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @else
                <!-- Company Fields -->
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_name"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.company_name') }}*</label>
                            <input type="text" id="company_name" wire:model="company_name"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_person"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.contact_person') }}*</label>
                            <input type="text" id="contact_person" wire:model="contact_person"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="vat_number"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.vat_number') }}</label>
                            <input type="text" id="vat_number" wire:model="vat_number"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                        </div>
                        <div>
                            <label for="cr_number"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.cr_number') }}</label>
                            <input type="text" id="cr_number" wire:model="cr_number"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="cr_document"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.cr_document') }}</label>
                            <input type="file" id="cr_document" wire:model="cr_document"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('cr_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="vat_document"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.vat_document') }}</label>
                            <input type="file" id="vat_document" wire:model="vat_document"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('vat_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Common Fields -->
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.email') }}*</label>
                    <input type="email" id="email" wire:model="email"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.phone') }}</label>
                    <input type="text" id="phone" wire:model="phone"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="address"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.address') }}</label>
                    <input type="text" id="address" wire:model="address"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                </div>
                <div>
                    <label for="city"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.city') }}</label>
                    <input type="text" id="city" wire:model="city"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                </div>
                <div>
                    <label for="country"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.country') }}</label>
                    <input type="text" id="country" wire:model="country"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                </div>
            </div>

            <div>
                <label for="notes"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.notes') }}</label>
                <textarea id="notes" wire:model="notes" rows="3"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.new_password_placeholder') }}</label>
                    <input type="password" id="password" wire:model="password"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ trans('users.confirm_password') }}</label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" wire:loading.attr="disabled"
                class="w-full flex justify-center items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 disabled:opacity-70 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save">
                    {{ trans('users.update_user_button') }}
                </span>
                <span wire:loading wire:target="save" class="inline-flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    {{ trans('users.updating') }}
                </span>
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mt-4 p-4 bg-green-50 border border-green-100 text-green-700 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="px-2">{{ session('message') }}</span>
            </div>
        @endif
    </form>
</div>
