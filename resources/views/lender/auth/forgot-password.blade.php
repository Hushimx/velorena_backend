<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نسيت كلمة المرور - Jarak</title>
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
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .form-input {
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input:hover {
            border-color: var(--gray-600);
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

        .contact-method-card {
            border: 2px solid var(--gray-200);
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
            background: white;
        }

        .contact-method-card:hover {
            border-color: var(--primary);
            transform: translateY(-1px);
        }

        .contact-method-card.selected {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-primary:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
        }

        .loading {
            width: 1rem;
            height: 1rem;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Jarak</h1>
            <p class="text-sm text-gray-600">منصة جارك الرائدة</p>
        </div>

        <!-- Forgot Password Card -->
        <div class="form-card rounded-xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">نسيت كلمة المرور</h2>
                <p class="text-sm text-gray-600 mt-1">اختر طريقة استعادة كلمة المرور</p>
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

            <!-- Contact Method Selection -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <label class="cursor-pointer">
                    <input type="radio" name="contact_method" value="email" class="hidden" checked onchange="toggleContactMethod()">
                    <div class="contact-method-card" id="email-card">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900">البريد الإلكتروني</span>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="contact_method" value="phone" class="hidden" onchange="toggleContactMethod()">
                    <div class="contact-method-card" id="phone-card">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900">الهاتف</span>
                    </div>
                </label>
            </div>

            <!-- Form -->
            <form action="{{ route('lender.password.email') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="contact_method" id="form-contact-method" value="email">

                <!-- Email Input -->
                <div id="email-input-section">
                    <label for="email" class="form-label">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="email" 
                               name="contact_value" 
                               id="email" 
                               required
                               class="form-input w-full pr-12 text-right"
                               placeholder="أدخل بريدك الإلكتروني">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none input-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Phone Input -->
                <div id="phone-input-section" class="hidden">
                    <label for="phone" class="form-label">رقم الهاتف <span class="text-red-500">*</span></label>
                    <div class="phone-input-container">
                        <div class="phone-country-code">+966</div>
                        <input type="text" 
                               name="contact_value" 
                               id="phone" 
                               maxlength="9"
                               class="phone-input"
                               placeholder="5XXXXXXXX"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full flex justify-center items-center py-4 px-6 rounded-xl text-sm font-medium text-white btn-primary">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    إرسال رمز التحقق
                </button>
            </form>

            <!-- Back to Login -->
            <div class="text-center mt-6">
                <a href="{{ route('lender.login') }}" class="text-sm text-primary hover:text-blue-700 transition-colors">
                    العودة لتسجيل الدخول
                </a>
            </div>
        </div>
    </div>

    <script>
        let contactMethod = 'email';

        // Toggle contact method
        function toggleContactMethod() {
            const emailMethod = document.querySelector('input[name="contact_method"][value="email"]');
            const phoneMethod = document.querySelector('input[name="contact_method"][value="phone"]');
            const emailSection = document.getElementById('email-input-section');
            const phoneSection = document.getElementById('phone-input-section');
            const emailCard = document.getElementById('email-card');
            const phoneCard = document.getElementById('phone-card');
            const formContactMethod = document.getElementById('form-contact-method');
            
            if (emailMethod.checked) {
                contactMethod = 'email';
                emailCard.classList.add('selected');
                phoneCard.classList.remove('selected');
                emailSection.classList.remove('hidden');
                phoneSection.classList.add('hidden');
                formContactMethod.value = 'email';
                
                // Update form fields
                document.getElementById('email').required = true;
                document.getElementById('phone').required = false;
                document.getElementById('phone').name = '';
                document.getElementById('email').name = 'contact_value';
            } else {
                contactMethod = 'phone';
                phoneCard.classList.add('selected');
                emailCard.classList.remove('selected');
                phoneSection.classList.remove('hidden');
                emailSection.classList.add('hidden');
                formContactMethod.value = 'phone';
                
                // Update form fields
                document.getElementById('phone').required = true;
                document.getElementById('email').required = false;
                document.getElementById('email').name = '';
                document.getElementById('phone').name = 'contact_value';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            toggleContactMethod();
        });
    </script>
</body>
</html>
