<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إعادة تعيين كلمة المرور - Jarak</title>
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Jarak</h1>
            <p class="text-sm text-gray-600">منصة جارك الرائدة</p>
        </div>

        <!-- Reset Password Card -->
        <div class="form-card rounded-xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">إعادة تعيين كلمة المرور</h2>
                <p class="text-sm text-gray-600 mt-1">أدخل كلمة المرور الجديدة</p>
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

            <!-- Form -->
            <form action="{{ route('lender.password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- New Password -->
                <div>
                    <label for="password" class="form-label">كلمة المرور الجديدة <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               class="form-input w-full pr-12 text-right"
                               placeholder="أدخل كلمة المرور الجديدة">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none input-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required
                               class="form-input w-full pr-12 text-right"
                               placeholder="أعد إدخال كلمة المرور الجديدة">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none input-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full flex justify-center items-center py-4 px-6 rounded-xl text-sm font-medium text-white btn-primary">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    إعادة تعيين كلمة المرور
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
</body>
</html>
