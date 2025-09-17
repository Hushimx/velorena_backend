@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Edit Designer')
@section('title', trans('designers.edit_designer'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ trans('designers.edit_designer') }}</h1>
                <p class="text-gray-600">{{ trans('designers.manage_designers') }}</p>
            </div>
            <a href="{{ route('admin.designers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                <span>{{ trans('designers.back_to_designers') }}</span>
            </a>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <div class="card-body">
            <form action="{{ route('admin.designers.update', $designer) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Name -->
                    <div>
                        <label for="name" class="form-label">
                            {{ trans('designers.full_name') }} <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $designer->name) }}" required
                            class="form-control @error('name') border-red-500 @enderror">
                        @error('name')
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="form-label">
                            {{ trans('designers.email') }} <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $designer->email) }}" required
                            class="form-control @error('email') border-red-500 @enderror">
                        @error('email')
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="form-label">
                            {{ trans('designers.phone') }}
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $designer->phone) }}"
                            class="form-control @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Portfolio URL -->
                    <div>
                        <label for="portfolio_url" class="form-label">
                            {{ trans('designers.portfolio_url') }}
                        </label>
                        <input type="url" name="portfolio_url" id="portfolio_url"
                            value="{{ old('portfolio_url', $designer->portfolio_url) }}"
                            class="form-control @error('portfolio_url') border-red-500 @enderror">
                        @error('portfolio_url')
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div style="grid-column: 1 / -1;">
                        <label for="address" class="form-label">
                            {{ trans('designers.address') }}
                        </label>
                        <textarea name="address" id="address" rows="3"
                            class="form-control @error('address') border-red-500 @enderror">{{ old('address', $designer->address) }}</textarea>
                        @error('address')
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div style="grid-column: 1 / -1;">
                        <label for="bio" class="form-label">
                            {{ trans('designers.bio') }}
                        </label>
                        <textarea name="bio" id="bio" rows="4"
                            class="form-control @error('bio') border-red-500 @enderror">{{ old('bio', $designer->bio) }}</textarea>
                        @error('bio')
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="form-label">
                            {{ trans('designers.password') }}
                        </label>
                        <input type="password" name="password" id="password"
                            placeholder="{{ trans('designers.new_password_placeholder') }}"
                            class="form-control @error('password') border-red-500 @enderror">
                        @error('password')
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #ef4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="form-label">
                            {{ trans('designers.confirm_password') }}
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control">
                    </div>
                </div>

                <!-- Submit Button -->
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <a href="{{ route('admin.designers.index') }}" class="btn btn-secondary">
                        {{ trans('admin.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        <span>{{ trans('designers.update_designer_button') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
