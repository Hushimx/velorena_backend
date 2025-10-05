@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Site Settings')
@section('title', 'Site Settings')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Site Settings</h1>
                <p class="text-gray-600">Manage your website's header, footer, and general settings</p>
            </div>
            <a href="{{ route('admin.site-settings.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-3 transition-colors">
                <i class="fas fa-plus"></i>
                <span>Add New Setting</span>
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Settings by Type -->
        @foreach ($settings as $type => $typeSettings)
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 capitalize">{{ $type }} Settings</h3>
                    <p class="text-sm text-gray-600">Manage {{ $type }} related settings</p>
                </div>

                @if ($typeSettings->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($typeSettings as $setting)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-1">
                                                <h4 class="text-md font-medium text-gray-900">
                                                    {{ $setting->key }}
                                                    @if ($setting->description)
                                                        <span class="text-sm text-gray-500">-
                                                            {{ $setting->description }}</span>
                                                    @endif
                                                </h4>
                                                <div class="mt-1 flex items-center space-x-2">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if ($setting->is_active) bg-green-100 text-green-800
                                                        @else bg-red-100 text-red-800 @endif">
                                                        {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">Sort:
                                                        {{ $setting->sort_order }}</span>
                                                </div>

                                                @if ($setting->localized_value)
                                                    <div class="mt-2">
                                                        @if (is_array($setting->localized_value))
                                                            <div class="text-sm text-gray-600">
                                                                @foreach ($setting->localized_value as $key => $value)
                                                                    @if ($key === 'logo' && $value)
                                                                        <div class="flex items-center space-x-2">
                                                                            <span
                                                                                class="font-medium">{{ ucfirst($key) }}:</span>
                                                                            <img src="{{ Storage::url($value) }}"
                                                                                alt="Logo"
                                                                                class="h-8 w-8 object-cover rounded">
                                                                        </div>
                                                                    @elseif(is_string($value))
                                                                        <div><span
                                                                                class="font-medium">{{ ucfirst($key) }}:</span>
                                                                            {{ Str::limit($value, 100) }}</div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <p class="text-sm text-gray-600">
                                                                {{ Str::limit($setting->localized_value, 100) }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.site-settings.show', $setting) }}"
                                            class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            View
                                        </a>
                                        <a href="{{ route('admin.site-settings.edit', $setting) }}"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.site-settings.destroy', $setting) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <i class="fas fa-cog text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No {{ $type }} settings found</h3>
                        <p class="text-gray-500">Get started by creating your first {{ $type }} setting.</p>
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Quick Settings Form -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Quick Settings Update</h3>
                <p class="text-sm text-gray-600">Update common settings quickly</p>
            </div>
            <form action="{{ route('admin.site-settings.bulk-update') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Site Name -->
                    <div>
                        <label for="settings[site_name][value]" class="block text-sm font-medium text-gray-700">Site
                            Name</label>
                        <input type="text" name="settings[site_name][value]" id="site_name"
                            value="{{ \App\Models\StoreContent::getSetting('site_name.value') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Site Description -->
                    <div>
                        <label for="settings[site_description][value]" class="block text-sm font-medium text-gray-700">Site
                            Description</label>
                        <input type="text" name="settings[site_description][value]" id="site_description"
                            value="{{ \App\Models\StoreContent::getSetting('site_description.value') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Contact Email -->
                    <div>
                        <label for="settings[contact_email][value]" class="block text-sm font-medium text-gray-700">Contact
                            Email</label>
                        <input type="email" name="settings[contact_email][value]" id="contact_email"
                            value="{{ \App\Models\StoreContent::getSetting('contact_email.value') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label for="settings[contact_phone][value]" class="block text-sm font-medium text-gray-700">Contact
                            Phone</label>
                        <input type="text" name="settings[contact_phone][value]" id="contact_phone"
                            value="{{ \App\Models\StoreContent::getSetting('contact_phone.value') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Facebook URL -->
                    <div>
                        <label for="settings[facebook_url][value]" class="block text-sm font-medium text-gray-700">Facebook
                            URL</label>
                        <input type="url" name="settings[facebook_url][value]" id="facebook_url"
                            value="{{ \App\Models\StoreContent::getSetting('facebook_url.value') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Instagram URL -->
                    <div>
                        <label for="settings[instagram_url][value]"
                            class="block text-sm font-medium text-gray-700">Instagram URL</label>
                        <input type="url" name="settings[instagram_url][value]" id="instagram_url"
                            value="{{ \App\Models\StoreContent::getSetting('instagram_url.value') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Twitter URL -->
                    <div>
                        <label for="settings[twitter_url][value]" class="block text-sm font-medium text-gray-700">Twitter
                            URL</label>
                        <input type="url" name="settings[twitter_url][value]" id="twitter_url"
                            value="{{ \App\Models\StoreContent::getSetting('twitter_url.value') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- LinkedIn URL -->
                    <div>
                        <label for="settings[linkedin_url][value]"
                            class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                        <input type="url" name="settings[linkedin_url][value]" id="linkedin_url"
                            value="{{ \App\Models\StoreContent::getSetting('linkedin_url.value') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
