<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jarak - منصة الإيجار</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8fafc;
        }

        /* Minimal Professional Theme */
        :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            --success: #10b981;
            --error: #ef4444;
        }

        .form-card {
            background: white;
            border: 1px solid var(--gray-200);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
        }

        .form-input {
            border: 1.5px solid var(--gray-300);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1), inset 0 1px 2px rgba(0, 0, 0, 0.05);
            background: white;
            transform: translateY(-1px);
        }

        .form-input:hover {
            border-color: var(--gray-400);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.08);
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .phone-input-container {
            display: flex;
            align-items: center;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            background: white;
            transition: all 0.2s ease;
        }

        .phone-input-container:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .phone-input-container:hover {
            border-color: var(--gray-600);
        }

        .phone-country-code {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            background: var(--gray-50);
            border-right: 1px solid var(--gray-200);
            color: var(--gray-600);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .phone-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            background: transparent;
        }

        .account-type-card {
            border: 2px solid var(--gray-200);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .account-type-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
        }

        .account-type-card.selected {
            border-color: var(--primary);
            background: linear-gradient(145deg, var(--primary-light) 0%, #dbeafe 100%);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.2);
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            transform: translateY(-2px);
        }

        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .btn-primary:disabled::before {
            display: none;
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .btn-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.05), transparent);
            transition: left 0.5s;
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }

        .btn-secondary:hover::before {
            left: 100%;
        }

        .btn-secondary:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .step-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
            gap: 0.5rem;
        }

        .step-dot {
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            background: var(--gray-300);
            transition: all 0.2s ease;
        }

        .step-dot.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transform: scale(1.3);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .step-dot.completed {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .loading {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid currentColor;
            border-right: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
        }

        .loading-dots {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .loading-dot {
            width: 0.375rem;
            height: 0.375rem;
            background: currentColor;
            border-radius: 50%;
            animation: loading-dots 1.4s ease-in-out infinite both;
        }

        .loading-dot:nth-child(1) { animation-delay: -0.32s; }
        .loading-dot:nth-child(2) { animation-delay: -0.16s; }
        .loading-dot:nth-child(3) { animation-delay: 0; }

        @keyframes loading-dots {
            0%, 80%, 100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .input-icon {
            color: var(--gray-400);
        }

        .form-input:focus + .input-icon {
            color: var(--primary);
        }

        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 0.5rem;
            padding: 1rem;
            color: #166534;
            font-size: 0.875rem;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            padding: 1rem;
            color: #dc2626;
            font-size: 0.875rem;
        }

        .otp-input {
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 0.5rem;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mb-2">Jarak</h1>
            <p class="text-sm text-gray-600">منصة الإيجار الرائدة</p>
        </div>

        <!-- Register Card -->
        <div class="form-card rounded-xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">إنشاء حساب جديد</h2>
                <p class="text-sm text-gray-600 mt-2">انضم إلى منصة Jarak</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-message mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="error-message mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div>{{ session('error') }}</div>
                    </div>
                </div>
            @endif

            <!-- Step Progress Indicator -->
            <div class="step-progress">
                <div class="step-dot active" id="step1-dot"></div>
                <div class="step-dot" id="step2-dot"></div>
            </div>

            <!-- Step 1: Registration Form -->
            <div id="step1" class="space-y-6">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">معلومات الحساب</h3>
                    <p class="text-sm text-gray-600 mt-1">أدخل معلوماتك الشخصية</p>
                </div>

                <form id="registration-form" class="space-y-6">
                    <!-- Account Type Selection -->
                    <div>
                        <label class="form-label">نوع الحساب <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="individual" class="hidden" {{ old('type', 'individual') == 'individual' ? 'checked' : '' }} onchange="toggleAccountType()" required>
                                <div class="account-type-card {{ old('type', 'individual') == 'individual' ? 'selected' : '' }}" id="individual-type">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">فرد</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="company" class="hidden" {{ old('type') == 'company' ? 'checked' : '' }} onchange="toggleAccountType()">
                                <div class="account-type-card {{ old('type') == 'company' ? 'selected' : '' }}" id="company-type">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h10M7 15h10"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">شركة</span>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   class="form-input w-full pr-12 text-right"
                                   placeholder="أدخل بريدك الإلكتروني"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="full_name" class="form-label">الاسم الكامل <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" 
                                   name="full_name" 
                                   id="full_name" 
                                   value="{{ old('full_name') }}"
                                   class="form-input w-full pr-12 text-right"
                                   placeholder="أدخل اسمك الكامل"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('full_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name (Only for company account type) -->
                    <div id="company-name-section" class="{{ old('type') == 'company' ? '' : 'hidden' }}">
                        <label for="business_name" class="form-label">اسم الشركة <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" 
                                   name="business_name" 
                                   id="business_name" 
                                   value="{{ old('business_name') }}"
                                   class="form-input w-full pr-12 text-right"
                                   placeholder="أدخل اسم الشركة">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h10M7 15h10"></path>
                                </svg>
                            </div>
                        </div>
                        @error('business_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Input -->
                    <div>
                        <label for="phone" class="form-label">رقم الهاتف <span class="text-red-500">*</span></label>
                        <div class="phone-input-container">
                            <div class="phone-country-code">+966</div>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   maxlength="9"
                                   value="{{ old('phone') }}"
                                   class="phone-input"
                                   placeholder="5XXXXXXXX"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)"
                                   required>
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="form-label">كلمة المرور <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-input w-full pr-12 text-right"
                                   placeholder="أدخل كلمة المرور"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-input w-full pr-12 text-right"
                                   placeholder="أعد إدخال كلمة المرور"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none input-icon">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Next Button -->
                    <button type="button" onclick="validateAndProceed()" id="proceed-btn" class="w-full flex justify-center items-center py-4 px-6 rounded-xl text-sm font-medium text-white btn-primary">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        <span id="proceed-text">إرسال رمز التحقق</span>
                    </button>
                </form>
            </div>

            <!-- Step 2: OTP Verification -->
            <div id="step2" class="space-y-6 hidden">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">التحقق من الرمز</h3>
                    <p class="text-sm text-gray-600 mt-1">تم إرسال رمز التحقق إلى <span id="contact-display" class="font-medium text-blue-600"></span></p>
                </div>

                <!-- Success Message -->
                <div class="success-message">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm">تم إرسال رمز التحقق بنجاح!</span>
                    </div>
                </div>

                <!-- OTP Input -->
                <div>
                    <label for="otp" class="form-label">رمز التحقق <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" 
                               name="otp" 
                               id="otp" 
                               maxlength="6"
                               class="form-input w-full text-center otp-input"
                               placeholder="000000"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                    </div>
                    <p class="text-sm text-gray-500 text-center mt-2">رمز التحقق هو: <span class="font-bold text-primary">123456</span></p>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-3 space-x-reverse">
                    <button type="button" onclick="backToStep1()" class="flex-1 py-4 px-6 rounded-xl text-sm font-medium text-gray-700 btn-secondary">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        رجوع
                    </button>
                    <button type="button" onclick="verifyOTP()" id="verify-otp-btn" class="flex-1 py-4 px-6 rounded-xl text-sm font-medium text-white btn-primary">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="verify-otp-text">التحقق</span>
                    </button>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    تملك حساب بالفعل؟
                    <a href="{{ route('lender.login') }}" class="font-bold text-primary hover:text-blue-700 transition-colors">
                        تسجيل الدخول
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let accountType = 'individual';

        // Toggle account type
        function toggleAccountType() {
            const individualType = document.querySelector('input[name="type"][value="individual"]');
            const companyType = document.querySelector('input[name="type"][value="company"]');
            const companySection = document.getElementById('company-name-section');
            const individualCard = document.getElementById('individual-type');
            const companyCard = document.getElementById('company-type');
            const businessNameInput = document.getElementById('business_name');
            
            if (individualType.checked) {
                accountType = 'individual';
                individualCard.classList.add('selected');
                companyCard.classList.remove('selected');
                companySection.classList.add('hidden');
                businessNameInput.required = false;
            } else {
                accountType = 'company';
                companyCard.classList.add('selected');
                individualCard.classList.remove('selected');
                companySection.classList.remove('hidden');
                businessNameInput.required = true;
            }
        }

        // Validate and proceed to OTP step
        function validateAndProceed() {
            const form = document.getElementById('registration-form');
            const formData = new FormData(form);
            
            // Basic validation
            const email = formData.get('email');
            const fullName = formData.get('full_name');
            const phone = formData.get('phone');
            const password = formData.get('password');
            const passwordConfirmation = formData.get('password_confirmation');
            const type = formData.get('type');
            const businessName = formData.get('business_name');
            
            if (!email || !fullName || !phone || !password || !passwordConfirmation || !type) {
                alert('يرجى ملء جميع الحقول المطلوبة');
                return;
            }
            
            if (phone.length !== 9) {
                alert('يرجى إدخال رقم هاتف صحيح مكون من 9 أرقام');
                return;
            }
            
            if (password !== passwordConfirmation) {
                alert('كلمة المرور غير متطابقة');
                return;
            }
            
            if (password.length < 8) {
                alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل');
                return;
            }
            
            if (type === 'company' && !businessName) {
                alert('يرجى إدخال اسم الشركة');
                return;
            }
            
            // Check availability
            Promise.all([
                checkAvailability('email', email),
                checkAvailability('phone', phone)
            ]).then(([emailAvailable, phoneAvailable]) => {
                if (!emailAvailable || !phoneAvailable) {
                    return;
                }
                
                // Show loading state
                const button = document.getElementById('proceed-btn');
                const buttonText = document.getElementById('proceed-text');
                const originalText = buttonText.textContent;
                
                button.disabled = true;
                buttonText.innerHTML = '<div class="loading-dots"><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div></div><span class="mr-2">جاري الإرسال</span>';

                // Simulate API call
                setTimeout(() => {
                    // Move to step 2
                    document.getElementById('step1').classList.add('hidden');
                    document.getElementById('step2').classList.remove('hidden');
                    
                    // Update step indicator
                    document.getElementById('step1-dot').classList.remove('active');
                    document.getElementById('step1-dot').classList.add('completed');
                    document.getElementById('step2-dot').classList.add('active');
                    
                    // Update contact display
                    document.getElementById('contact-display').textContent = `+966${phone}`;
                    
                    currentStep = 2;
                    
                    // Reset button
                    button.disabled = false;
                    buttonText.textContent = originalText;
                }, 1500);
            });
        }

        // Check availability
        function checkAvailability(method, value) {
            return fetch('{{ route("lender.register.check-availability") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    contact_method: method,
                    contact_value: value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    alert(data.message);
                    return false;
                }
                return true;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في التحقق من البيانات');
                return false;
            });
        }

        // Verify OTP
        function verifyOTP() {
            const otp = document.getElementById('otp').value.trim();
            
            if (!otp || otp.length !== 6) {
                alert('يرجى إدخال رمز التحقق المكون من 6 أرقام');
                return;
            }

            if (otp !== '123456') {
                alert('رمز التحقق غير صحيح');
                return;
            }
            
            // Show loading state
            const button = document.getElementById('verify-otp-btn');
            const buttonText = document.getElementById('verify-otp-text');
            const originalText = buttonText.textContent;
            
            button.disabled = true;
            buttonText.innerHTML = '<div class="loading-dots"><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div></div><span class="mr-2">جاري التحقق</span>';

            // Submit the form
            setTimeout(() => {
                const form = document.getElementById('registration-form');
                form.action = '{{ route("lender.register.post") }}';
                form.method = 'POST';
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                form.submit();
            }, 1000);
        }

        // Back to step 1
        function backToStep1() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            
            // Update step indicator
            document.getElementById('step2-dot').classList.remove('active');
            document.getElementById('step1-dot').classList.remove('completed');
            document.getElementById('step1-dot').classList.add('active');
            
            currentStep = 1;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            toggleAccountType();
        });
    </script>
</body>
</html>