<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل كمالك - فيلورينا</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        /* Navy Blue and Mint Green Theme - Clean & Focused */
        :root {
            --navy-blue: #000080;
            --mint-green: #98FF98;
            --navy-blue-light: rgba(0, 0, 128, 0.1);
            --mint-green-light: rgba(152, 255, 152, 0.1);
            --navy-blue-dark: #000066;
            --mint-green-dark: #7FFF7F;
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--mint-green) 100%);
        }

        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 128, 0.15), 0 10px 10px -5px rgba(152, 255, 152, 0.1);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 128, 0.2);
            border-color: var(--navy-blue);
        }

        .btn-hover {
            transition: all 0.3s ease;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 128, 0.2);
        }

        .type-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            background: var(--mint-green-light);
        }

        .type-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 128, 0.15);
        }

        .type-card.selected {
            border-color: var(--navy-blue);
            background: linear-gradient(135deg, var(--navy-blue-light) 0%, var(--mint-green-light) 100%);
            box-shadow: 0 0 0 3px rgba(0, 0, 128, 0.1);
        }

        .input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        .input-icon {
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 10;
        }

        .input-focused .input-icon {
            transform: translateX(-10px);
            opacity: 0.7;
        }

        /* Ensure icons stay visible */
        .relative .absolute {
            pointer-events: none;
        }

        .form-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, var(--mint-green-light) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 128, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 128, 0.2);
        }

        .input-focus {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-focus:focus {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 128, 0.1);
            border-color: var(--navy-blue);
        }

        /* Override Tailwind focus states for Navy Blue theme */
        .focus\:ring-blue-500:focus {
            --tw-ring-color: #000080 !important;
        }

        .focus\:border-blue-500:focus {
            border-color: #000080 !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--mint-green) 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 128, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Loading animation */
        .loading-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Additional styling improvements */
        .section-title {
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--mint-green) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-message {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
            opacity: 0;
            pointer-events: none;
        }

        .file-input-label {
            display: block;
            padding: 12px 16px;
            background: var(--mint-green-light);
            border: 2px dashed rgba(0, 0, 128, 0.3);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            border-color: var(--navy-blue);
            background: linear-gradient(135deg, var(--navy-blue-light) 0%, var(--mint-green-light) 100%);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 via-blue-50 to-green-100 min-h-screen font-cairo"
    style="background: linear-gradient(135deg, var(--mint-green-light) 0%, rgba(0, 0, 128, 0.05) 50%, var(--mint-green-light) 100%);">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-5">
        <div class="absolute inset-0"
            style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.4'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
    </div>

    <div class="relative min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Language Switcher -->
        <div class="absolute top-4 right-4 z-10">
            @php
                use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
                $current = LaravelLocalization::getCurrentLocale();
            @endphp

            <div x-data="{ open: false }" class="relative">
                <!-- Trigger Button -->
                <button @click="open = !open" type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-medium shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    aria-haspopup="listbox" :aria-expanded="open">
                    <span class="inline-flex items-center gap-2">
                        <span class="text-xs rounded px-2 py-0.5 border">{{ strtoupper($current) }}</span>
                        <span class="hidden sm:inline">
                            {{ $current === 'ar' ? 'العربية' : 'English' }}
                        </span>
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-cloak x-show="open" @click.outside="open = false"
                    class="absolute z-50 mt-2 w-40 rounded-xl border border-gray-200 bg-white shadow-lg"
                    :class="{
                        'right-0': '{{ LaravelLocalization::getCurrentLocaleDirection() }}'
                        === 'rtl',
                        'left-0': '{{ LaravelLocalization::getCurrentLocaleDirection() }}'
                        === 'ltr'
                    }">
                    <ul class="py-1" role="listbox">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @php
                                $url = LaravelLocalization::getLocalizedURL($localeCode, null, [], true);
                                $active = $localeCode === $current;
                            @endphp

                            <li>
                                <a href="{{ $url }}" hreflang="{{ $localeCode }}" rel="alternate"
                                    class="flex items-center justify-between px-3 py-2 text-sm hover:bg-gray-50 @if ($active) font-semibold @endif">
                                    <span class="flex items-center gap-2">
                                        <span
                                            class="text-xs rounded px-2 py-0.5 border">{{ strtoupper($localeCode) }}</span>
                                        <span>{{ $properties['native'] ?? strtoupper($localeCode) }}</span>
                                    </span>
                                    @if ($active)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-7.364 7.364a1 1 0 01-1.414 0L3.293 10.435a1 1 0 111.414-1.414l3.222 3.222 6.657-6.657a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="text-center">
                    <h1
                        class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-800 to-green-500">
                        {{ __('velorena') }}</h1>
                </div>
            </div>
            <h2 class="mt-8 text-center text-3xl font-extrabold text-gray-900">
                تسجيل حساب جديد
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                أو
                <a href="{{ route('lender.login') }}"
                    class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                    تسجيل الدخول إلى حسابك
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="form-card py-8 px-6 rounded-2xl">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">خطأ:</h3>
                                <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('lender.register') }}" method="POST" id="registrationForm"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Registration Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            نوع التسجيل <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="type-card selected border-2 border-gray-200 rounded-xl p-4 text-center"
                                onclick="selectType('individual')">
                                <input id="individual" name="type" type="radio" value="individual" class="hidden"
                                    {{ old('type') == 'individual' ? 'checked' : 'checked' }}>
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900">فرد</h3>
                                <p class="text-sm text-gray-500 mt-1">حساب شخصي</p>
                            </div>
                            <div class="type-card border-2 border-gray-200 rounded-xl p-4 text-center"
                                onclick="selectType('company')">
                                <input id="company" name="type" type="radio" value="company" class="hidden"
                                    {{ old('type') == 'company' ? 'checked' : '' }}>
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900">شركة</h3>
                                <p class="text-sm text-gray-500 mt-1">حساب تجاري</p>
                            </div>
                        </div>
                        <div class="error-message" id="type-error">يرجى اختيار نوع التسجيل</div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="space-y-4">
                        <h3 class="section-title text-lg font-bold border-b border-gray-200 pb-2">المعلومات الشخصية
                        </h3>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                الاسم الكامل <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="name" name="name" type="text" required
                                    value="{{ old('name') }}"
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                    onblur="validateField('name')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)">
                                <div class="error-message" id="name-error">يرجى إدخال الاسم الكامل</div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    value="{{ old('email') }}"
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                    onblur="validateField('email')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)">
                                <div class="error-message" id="email-error">يرجى إدخال بريد إلكتروني صحيح</div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الهاتف <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="phone" name="phone" type="tel" required
                                    value="{{ old('phone') }}"
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                    onblur="validateField('phone')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)">
                                <div class="error-message" id="phone-error">يرجى إدخال رقم هاتف صحيح</div>
                            </div>
                        </div>

                        <!-- City Selection -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                المدينة <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <select id="city" name="city" required
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right appearance-none bg-white"
                                    onblur="validateField('city')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)">
                                    <option value="">اختر المدينة</option>
                                    @foreach (\App\Models\City::all() as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city') == $city->id ? 'selected' : '' }}>{{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <div class="error-message" id="city-error">يرجى اختيار المدينة</div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                العنوان التفصيلي <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 pt-3 flex items-start pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <textarea id="address" name="address" rows="3" required
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right resize-none"
                                    onblur="validateField('address')" onfocus="handleInputFocus(this)" onblur="handleInputBlur(this)"
                                    placeholder="أدخل العنوان التفصيلي (الحي، الشارع، رقم المنزل)">{{ old('address') }}</textarea>
                                <div class="error-message" id="address-error">يرجى إدخال العنوان التفصيلي</div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Information Section (Conditional) -->
                    <div id="company-fields" class="hidden space-y-4">
                        <h3 class="section-title text-lg font-bold border-b border-gray-200 pb-2">معلومات الشركة</h3>

                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم الشركة <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <input id="business_name" name="business_name" type="text"
                                    value="{{ old('business_name') }}"
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                    onblur="validateField('business_name')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)">
                                <div class="error-message" id="business_name-error">يرجى إدخال اسم الشركة</div>
                            </div>
                        </div>

                        <!-- Business Category -->
                        <div>
                            <label for="business_category" class="block text-sm font-medium text-gray-700 mb-2">
                                النشاط التجاري <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <select id="business_category" name="business_category" required
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right appearance-none bg-white"
                                    onblur="validateField('business_category')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)" onchange="handleCategoryChange()">
                                    <option value="">اختر النشاط التجاري</option>
                                    @foreach (\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('business_category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                    <option value="other"
                                        {{ old('business_category') == 'other' ? 'selected' : '' }}>أخرى</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <div class="error-message" id="business_category-error">يرجى اختيار النشاط التجاري
                                </div>
                            </div>
                        </div>

                        <!-- Custom Activity (Conditional) -->
                        <div id="custom-activity-field" class="hidden">
                            <label for="custom_activity" class="block text-sm font-medium text-gray-700 mb-2">
                                النشاط التجاري المخصص <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="custom_activity" name="custom_activity" type="text"
                                    value="{{ old('custom_activity') }}"
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                    onblur="validateField('custom_activity')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)" placeholder="أدخل النشاط التجاري المخصص">
                                <div class="error-message" id="custom_activity-error">يرجى إدخال النشاط التجاري المخصص
                                </div>
                            </div>
                        </div>

                        <!-- Commercial Record Upload -->
                        <div>
                            <label for="commercial_record" class="block text-sm font-medium text-gray-700 mb-2">
                                السجل التجاري (PDF) <span class="text-red-500">*</span>
                            </label>
                            <div class="file-input-wrapper">
                                <label for="commercial_record" class="file-input-label">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <span class="text-sm text-gray-600">اضغط لرفع ملف السجل التجاري</span>
                                    <span class="text-xs text-gray-500 block mt-1">PDF فقط - الحد الأقصى 10
                                        ميجابايت</span>
                                </label>
                                <input id="commercial_record" name="commercial_record" type="file" accept=".pdf"
                                    required onblur="validateField('commercial_record')"
                                    onchange="updateFileLabel(this, 'commercial_record')">
                                <div class="error-message" id="commercial_record-error">يرجى رفع السجل التجاري</div>
                            </div>
                        </div>

                        <!-- Tax Record Upload (Optional) -->
                        <div>
                            <label for="tax_record" class="block text-sm font-medium text-gray-700 mb-2">
                                السجل الضريبي (PDF) <span class="text-gray-500">(اختياري)</span>
                            </label>
                            <div class="file-input-wrapper">
                                <label for="tax_record" class="file-input-label">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <span class="text-sm text-gray-600">اضغط لرفع ملف السجل الضريبي</span>
                                    <span class="text-xs text-gray-500 block mt-1">PDF فقط - الحد الأقصى 10
                                        ميجابايت</span>
                                </label>
                                <input id="tax_record" name="tax_record" type="file" accept=".pdf"
                                    onchange="updateFileLabel(this, 'tax_record')">
                            </div>
                        </div>

                        <!-- Business Description -->
                        <div>
                            <label for="business_description" class="block text-sm font-medium text-gray-700 mb-2">
                                وصف النشاط التجاري
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 pt-3 flex items-start pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <textarea id="business_description" name="business_description" rows="3"
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right resize-none"
                                    placeholder="وصف مختصر للنشاط التجاري" onfocus="handleInputFocus(this)" onblur="handleInputBlur(this)">{{ old('business_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">كلمة المرور</h3>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="new-password"
                                    required
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                    onblur="validateField('password')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)">
                                <div class="error-message" id="password-error">كلمة المرور يجب أن تكون 8 أحرف على
                                    الأقل</div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                تأكيد كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none input-icon">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    autocomplete="new-password" required
                                    class="input-focus block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                    onblur="validateField('password_confirmation')" onfocus="handleInputFocus(this)"
                                    onblur="handleInputBlur(this)">
                                <div class="error-message" id="password_confirmation-error">كلمة المرور غير متطابقة
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                            class="btn-primary w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            إنشاء الحساب
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">لديك حساب بالفعل؟</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('lender.login') }}"
                            class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                </path>
                            </svg>
                            تسجيل الدخول
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Input focus/blur handlers
        function handleInputFocus(element) {
            const parent = element.parentElement;
            parent.classList.add('input-focused');
        }

        function handleInputBlur(element) {
            const parent = element.parentElement;
            parent.classList.remove('input-focused');
        }

        // Category change handler
        function handleCategoryChange() {
            const categorySelect = document.getElementById('business_category');
            const customActivityField = document.getElementById('custom-activity-field');
            const customActivityInput = document.getElementById('custom_activity');

            if (categorySelect.value === 'other') {
                customActivityField.classList.remove('hidden');
                customActivityInput.required = true;
            } else {
                customActivityField.classList.add('hidden');
                customActivityInput.required = false;
                customActivityInput.value = '';
            }
        }

        // File label updater
        function updateFileLabel(input, fieldName) {
            const label = input.previousElementSibling;
            const fileName = input.files[0]?.name;

            if (fileName) {
                label.innerHTML = `
                     <svg class="w-6 h-6 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                     </svg>
                     <span class="text-sm text-gray-700 font-medium">${fileName}</span>
                     <span class="text-xs text-gray-500 block mt-1">تم اختيار الملف بنجاح</span>
                 `;
                label.style.borderColor = '#10b981';
                label.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)';

                // Remove error styling if file is selected
                input.classList.remove('input-error');
                const errorElement = document.getElementById(fieldName + '-error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            } else {
                // Reset to default
                if (fieldName === 'commercial_record') {
                    label.innerHTML = `
                         <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                         </svg>
                         <span class="text-sm text-gray-600">اضغط لرفع ملف السجل التجاري</span>
                         <span class="text-xs text-gray-500 block mt-1">PDF فقط - الحد الأقصى 10 ميجابايت</span>
                     `;
                } else {
                    label.innerHTML = `
                         <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                         </svg>
                         <span class="text-sm text-gray-600">اضغط لرفع ملف السجل الضريبي</span>
                         <span class="text-xs text-gray-500 block mt-1">PDF فقط - الحد الأقصى 10 ميجابايت</span>
                     `;
                }
                label.style.borderColor = '#cbd5e1';
                label.style.background = 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)';
            }
        }

        // Validation functions
        function validateField(fieldName) {
            const field = document.getElementById(fieldName);
            const errorElement = document.getElementById(fieldName + '-error');

            // Remove previous validation classes
            field.classList.remove('input-error');
            errorElement.style.display = 'none';

            let isValid = false;
            let errorMessage = '';

            switch (fieldName) {
                case 'name':
                    isValid = field.value.trim().length >= 3;
                    errorMessage = 'الاسم يجب أن يكون 3 أحرف على الأقل';
                    break;

                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    isValid = emailRegex.test(field.value);
                    errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
                    break;

                case 'phone':
                    const phoneRegex = /^[0-9+\-\s()]{10,}$/;
                    isValid = phoneRegex.test(field.value);
                    errorMessage = 'يرجى إدخال رقم هاتف صحيح';
                    break;

                case 'city':
                    isValid = field.value !== '';
                    errorMessage = 'يرجى اختيار المدينة';
                    break;

                case 'address':
                    isValid = field.value.trim().length >= 10;
                    errorMessage = 'العنوان يجب أن يكون 10 أحرف على الأقل';
                    break;

                case 'business_name':
                    if (document.getElementById('company').checked) {
                        isValid = field.value.trim().length >= 2;
                        errorMessage = 'اسم الشركة يجب أن يكون حرفين على الأقل';
                    } else {
                        isValid = true; // Not required for individual
                    }
                    break;

                case 'business_category':
                    if (document.getElementById('company').checked) {
                        isValid = field.value !== '';
                        errorMessage = 'يرجى اختيار النشاط التجاري';
                    } else {
                        isValid = true; // Not required for individual
                    }
                    break;

                case 'custom_activity':
                    if (document.getElementById('company').checked && document.getElementById('business_category').value ===
                        'other') {
                        isValid = field.value.trim().length >= 2;
                        errorMessage = 'يرجى إدخال النشاط التجاري المخصص';
                    } else {
                        isValid = true; // Not required
                    }
                    break;

                case 'commercial_record':
                    if (document.getElementById('company').checked) {
                        isValid = field.files.length > 0;
                        errorMessage = 'يرجى رفع ملف PDF للسجل التجاري';
                    } else {
                        isValid = true;
                    }
                    break;

                case 'password':
                    isValid = field.value.length >= 8;
                    errorMessage = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
                    break;

                case 'password_confirmation':
                    const password = document.getElementById('password').value;
                    isValid = field.value === password && field.value.length >= 8;
                    errorMessage = 'كلمة المرور غير متطابقة';
                    break;
            }

            // Apply validation styling for errors
            if (!isValid) {
                field.classList.add('input-error');
                errorElement.style.display = 'block';
            } else {
                field.classList.remove('input-error');
                errorElement.style.display = 'none';
            }
        }

        // Show/hide company fields based on registration type
        function selectType(type) {
            const individualCard = document.querySelector('[onclick="selectType(\'individual\')"]');
            const companyCard = document.querySelector('[onclick="selectType(\'company\')"]');
            const individualRadio = document.getElementById('individual');
            const companyRadio = document.getElementById('company');
            const companyFields = document.getElementById('company-fields');

            // Remove selected class from both cards
            individualCard.classList.remove('selected');
            companyCard.classList.remove('selected');

            if (type === 'company') {
                companyCard.classList.add('selected');
                companyRadio.checked = true;
                companyFields.classList.remove('hidden');
                // Make company fields required
                document.getElementById('business_name').required = true;
                document.getElementById('business_category').required = true;
                document.getElementById('commercial_record').required = true;
            } else {
                individualCard.classList.add('selected');
                individualRadio.checked = true;
                companyFields.classList.add('hidden');
                // Remove required from company fields
                document.getElementById('business_name').required = false;
                document.getElementById('business_category').required = false;
                document.getElementById('commercial_record').required = false;
            }

            // Validate type selection
            validateTypeSelection();
        }

        function validateTypeSelection() {
            const typeError = document.getElementById('type-error');
            const selectedType = document.querySelector('input[name="type"]:checked');

            if (selectedType) {
                typeError.style.display = 'none';
            } else {
                typeError.style.display = 'block';
            }
        }

        // Form submission validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            let isValid = true;

            // Validate all required fields
            const requiredFields = ['name', 'email', 'phone', 'city', 'address', 'password',
                'password_confirmation'
            ];
            requiredFields.forEach(field => {
                validateField(field);
                const fieldElement = document.getElementById(field);
                if (fieldElement.classList.contains('input-error') || fieldElement.value.trim() === '') {
                    isValid = false;
                }
            });

            // Validate type selection
            validateTypeSelection();
            if (!document.querySelector('input[name="type"]:checked')) {
                isValid = false;
            }

            // Validate company fields if company is selected
            if (document.getElementById('company').checked) {
                const companyFields = ['business_name', 'business_category', 'commercial_record'];
                companyFields.forEach(field => {
                    validateField(field);
                    const fieldElement = document.getElementById(field);
                    if (fieldElement.classList.contains('input-error') || fieldElement.value.trim() ===
                        '') {
                        isValid = false;
                    }
                });

                // Validate custom activity if "other" is selected
                if (document.getElementById('business_category').value === 'other') {
                    validateField('custom_activity');
                    const customActivityElement = document.getElementById('custom_activity');
                    if (customActivityElement.classList.contains('input-error') || customActivityElement.value
                        .trim() === '') {
                        isValid = false;
                    }
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('يرجى تصحيح الأخطاء قبل إرسال النموذج');
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                جاري التسجيل...
            `;
            submitBtn.disabled = true;
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const selectedType = document.querySelector('input[name="type"]:checked');
            if (selectedType) {
                selectType(selectedType.value);
            }

            // Add real-time validation for password confirmation
            document.getElementById('password').addEventListener('input', function() {
                const confirmPassword = document.getElementById('password_confirmation');
                if (confirmPassword.value) {
                    validateField('password_confirmation');
                }
            });
        });
    </script>
</body>

</html>
