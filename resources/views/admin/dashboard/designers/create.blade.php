@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Create Designer')
@section('title', trans('designers.create_designer'))

@section('content')
    @push('styles')
        <style>
            .validation-message {
                margin-top: 0.25rem;
                font-size: 0.875rem;
                color: #ef4444;
            }

            .form-label-dark,
            .text-dark {
                color: #454545;
            }
        </style>
    @endpush
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ trans('designers.create_designer') }}
                </h1>
                <p class="text-gray-600">{{ trans('designers.manage_designers') }}</p>
            </div>
            <a href="{{ route('admin.designers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                <span>{{ trans('designers.back_to_designers') }}</span>
            </a>
        </div>

        <!-- Create Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.designers.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <!-- Name -->
                        <div>
                            <label for="name" class="form-label form-label-dark">
                                {{ trans('designers.full_name') }} <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="form-control @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="form-label form-label-dark">
                                {{ trans('designers.email') }} <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="form-control @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="form-label form-label-dark">
                                {{ trans('designers.phone') }}
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Portfolio URL -->
                        <div>
                            <label for="portfolio_url" class="form-label form-label-dark">
                                {{ trans('designers.portfolio_url') }}
                            </label>
                            <input type="url" name="portfolio_url" id="portfolio_url"
                                value="{{ old('portfolio_url') }}"
                                class="form-control @error('portfolio_url') border-red-500 @enderror">
                            @error('portfolio_url')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div style="grid-column: 1 / -1;">
                            <label for="address" class="form-label form-label-dark">
                                {{ trans('designers.address') }}
                            </label>
                            <textarea name="address" id="address" rows="3" class="form-control @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div style="grid-column: 1 / -1;">
                            <label for="bio" class="form-label form-label-dark">
                                {{ trans('designers.bio') }}
                            </label>
                            <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') border-red-500 @enderror">{{ old('bio') }}</textarea>
                            @error('bio')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label form-label-dark">
                                {{ trans('designers.password') }} <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="password" name="password" id="password" required
                                class="form-control @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="form-label form-label-dark">
                                {{ trans('designers.confirm_password') }} <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="form-control">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-3" style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                        <a href="{{ route('admin.designers.index') }}" class="btn btn-secondary">
                            {{ trans('admin.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i>
                            <span>{{ trans('designers.create_designer_button') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
