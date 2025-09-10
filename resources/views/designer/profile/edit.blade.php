@extends('designer.layouts.app')

@section('title', __('dashboard.edit_profile'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('dashboard.edit_profile') }}</h1>
                <p class="mt-1" style="color: #ffde9f;">{{ __('dashboard.manage_your_profile_information') }}</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-user-edit text-4xl" style="color: #ffde9f;"></i>
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <form method="POST" action="#" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('dashboard.name') }}
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ Auth::guard('designer')->user()->name }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-2a1e1e focus:border-transparent"
                           required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('dashboard.email') }}
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ Auth::guard('designer')->user()->email }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-2a1e1e focus:border-transparent"
                           required>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('dashboard.phone') }}
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ Auth::guard('designer')->user()->phone }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-2a1e1e focus:border-transparent">
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('dashboard.address') }}
                    </label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ Auth::guard('designer')->user()->address }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-2a1e1e focus:border-transparent">
                </div>
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('dashboard.bio') }}
                </label>
                <textarea id="bio" 
                          name="bio" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-2a1e1e focus:border-transparent"
                          placeholder="{{ __('dashboard.tell_us_about_yourself') }}">{{ Auth::guard('designer')->user()->bio }}</textarea>
            </div>

            <!-- Portfolio URL -->
            <div>
                <label for="portfolio_url" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('dashboard.portfolio_url') }}
                </label>
                <input type="url" 
                       id="portfolio_url" 
                       name="portfolio_url" 
                       value="{{ Auth::guard('designer')->user()->portfolio_url }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-2a1e1e focus:border-transparent"
                       placeholder="https://example.com/portfolio">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg"
                        style="background-color: #2a1e1e;">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('dashboard.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
