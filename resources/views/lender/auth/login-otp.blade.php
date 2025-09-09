<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>التحقق من الرمز - Jarak</title>
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
            direction: ltr;
        }

        .field-error {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
        }

        .field-error svg {
            width: 1rem;
            height: 1rem;
            margin-left: 0.25rem;
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

        <!-- OTP Verification Card -->
        <div class="form-card rounded-xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">التحقق من الرمز</h2>
                <p class="text-sm text-gray-600 mt-1">تم إرسال رمز التحقق إلى <span class="font-medium text-blue-600">{{ session('login_phone') }}</span></p>
            </div>

            <!-- Success Messages -->
            @if (session('success'))
                <div class="success-message mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            <!-- OTP Form -->
            <form action="{{ route('lender.login.otp.verify') }}" method="POST" class="space-y-6" id="otp-form">
                @csrf
                
                <!-- OTP Input -->
                <div>
                    <label for="otp" class="form-label">رمز التحقق <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" 
                               name="otp" 
                               id="otp" 
                               maxlength="6"
                               class="form-input w-full text-center otp-input @error('otp') border-red-500 @enderror"
                               placeholder="000000"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                               required>
                    </div>
                    @error('otp')
                        <div class="field-error">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                    <p class="text-sm text-gray-500 text-center mt-2">رمز التحقق هو: <span class="font-bold text-primary">123456</span></p>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('lender.login') }}" class="flex-1 py-4 px-6 rounded-xl text-sm font-medium text-gray-700 btn-secondary text-center">
                        <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        رجوع
                    </a>
                    <button type="submit" id="verify-otp-btn" class="flex-1 py-4 px-6 rounded-xl text-sm font-medium text-white btn-primary">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="verify-otp-text">تسجيل الدخول</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Form validation and submission
        document.getElementById('otp-form').addEventListener('submit', function(e) {
            const otp = document.getElementById('otp').value.trim();
            const button = document.getElementById('verify-otp-btn');
            const buttonText = document.getElementById('verify-otp-text');
            
            // Clear previous errors
            clearErrors();
            
            // Validate OTP
            if (!otp || otp.length !== 6) {
                showFieldError('otp', 'يرجى إدخال رمز التحقق المكون من 6 أرقام');
                e.preventDefault();
                return;
            }

            if (otp !== '123456') {
                showFieldError('otp', 'رمز التحقق غير صحيح');
                e.preventDefault();
                return;
            }
            
            // Show loading state
            const originalText = buttonText.textContent;
            button.disabled = true;
            buttonText.innerHTML = '<div class="loading-dots"><div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div></div><span class="mr-2">جاري التحقق</span>';
        });

        function showFieldError(fieldName, message) {
            const field = document.getElementById(fieldName);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.innerHTML = `
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                ${message}
            `;
            
            field.classList.add('border-red-500');
            field.parentNode.parentNode.appendChild(errorDiv);
        }

        function clearErrors() {
            // Remove all field errors
            const errors = document.querySelectorAll('.field-error');
            errors.forEach(error => error.remove());
            
            // Remove error styling from inputs
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => input.classList.remove('border-red-500'));
        }
    </script>
</body>
</html>
