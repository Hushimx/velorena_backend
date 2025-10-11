@extends('components.layout')

@section('content')
    <x-navbar />

    <div class="delete-account-page">
        <div class="container">
            <div class="delete-account-container">
                <!-- Header Section -->
                <div class="header-section">
                    <div class="icon-wrapper">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h1 class="main-title">حذف الحساب</h1>
                    <p class="main-subtitle">هذا الإجراء لا يمكن التراجع عنه</p>
                </div>

                <!-- Warning Card -->
                <div class="warning-card">
                    <div class="warning-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="warning-content">
                        <h3>تحذير هام</h3>
                        <p>سيؤدي حذف حسابك إلى إزالة جميع بياناتك بشكل دائم بما في ذلك الطلبات والمواعيد والتصاميم المحفوظة. لا يمكن التراجع عن هذا الإجراء.</p>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="form-card">
                    <form method="POST" action="{{ route('user.account.delete') }}" id="deleteAccountForm">
                        @csrf
                        @method('DELETE')

                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i>
                                تأكيد كلمة المرور
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required
                                class="form-input @error('password') input-error @enderror"
                                placeholder="أدخل كلمة المرور الخاصة بك"
                            >
                            @error('password')
                                <p class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirmation Checkbox -->
                        <div class="form-group">
                            <label class="checkbox-container">
                                <input 
                                    type="checkbox" 
                                    name="confirm_delete" 
                                    value="1"
                                    required
                                    class="checkbox-input @error('confirm_delete') input-error @enderror"
                                >
                                <span class="checkbox-label">
                                    أفهم أن هذا الإجراء دائم ولا يمكن التراجع عنه
                                </span>
                            </label>
                            @error('confirm_delete')
                                <p class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button 
                                type="submit"
                                class="btn btn-delete"
                            >
                                <i class="fas fa-trash-alt"></i>
                                حذف حسابي نهائياً
                            </button>
                            <a 
                                href="{{ route('home') }}"
                                class="btn btn-cancel"
                            >
                                <i class="fas fa-times"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Back Link -->
                <div class="back-link">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-arrow-right"></i>
                        العودة إلى لوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <style>
        .delete-account-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #fff4e6 0%, #ffffff 100%);
            padding: 60px 0;
            font-family: 'Cairo', sans-serif;
        }

        .delete-account-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Section */
        .header-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.15);
            animation: pulse 2s ease-in-out infinite;
        }

        .icon-wrapper i {
            font-size: 48px;
            color: #dc2626;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 8px 24px rgba(220, 38, 38, 0.15);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 12px 32px rgba(220, 38, 38, 0.25);
            }
        }

        .main-title {
            font-size: 36px;
            font-weight: 800;
            color: #2a1e1e;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .main-subtitle {
            font-size: 18px;
            color: #6b5555;
            font-weight: 500;
        }

        /* Warning Card */
        .warning-card {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            display: flex;
            gap: 20px;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.15);
        }

        .warning-icon {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            background: rgba(245, 158, 11, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .warning-icon i {
            font-size: 24px;
            color: #d97706;
        }

        .warning-content h3 {
            font-size: 18px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 8px;
        }

        .warning-content p {
            font-size: 15px;
            color: #78350f;
            line-height: 1.6;
            margin: 0;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 28px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 600;
            color: #2a1e1e;
            margin-bottom: 12px;
        }

        .form-label i {
            color: #6b5555;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            font-size: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-input:focus {
            outline: none;
            border-color: #dc2626;
            background: white;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }

        .form-input.input-error {
            border-color: #ef4444;
            background: #fef2f2;
        }

        /* Checkbox */
        .checkbox-container {
            display: flex;
            align-items: start;
            gap: 12px;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-input {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .checkbox-input:checked {
            accent-color: #dc2626;
        }

        .checkbox-label {
            font-size: 15px;
            color: #4b5563;
            line-height: 1.5;
        }

        .error-message {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            font-size: 14px;
            color: #ef4444;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 16px;
            margin-top: 32px;
        }

        .btn {
            flex: 1;
            padding: 18px 24px;
            font-size: 17px;
            font-weight: 700;
            border-radius: 16px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
        }

        .btn-delete {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.4);
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        /* Back Link */
        .back-link {
            text-align: center;
        }

        .back-link a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            color: #6b5555;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            color: #2a1e1e;
            gap: 12px;
        }

        /* RTL Support */
        [dir="rtl"] .warning-card,
        [dir="rtl"] .checkbox-container,
        [dir="rtl"] .form-label {
            flex-direction: row-reverse;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .delete-account-page {
                padding: 40px 0;
            }

            .main-title {
                font-size: 28px;
            }

            .main-subtitle {
                font-size: 16px;
            }

            .icon-wrapper {
                width: 80px;
                height: 80px;
            }

            .icon-wrapper i {
                font-size: 36px;
            }

            .form-card {
                padding: 28px 20px;
            }

            .warning-card {
                flex-direction: column;
                text-align: center;
            }

            .warning-icon {
                margin: 0 auto;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .delete-account-container {
                padding: 0 16px;
            }

            .main-title {
                font-size: 24px;
            }

            .form-card {
                padding: 24px 16px;
            }

            .warning-card {
                padding: 20px;
            }
        }
    </style>

    <script>
        document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const confirmed = confirm('هل أنت متأكد تماماً من رغبتك في حذف حسابك؟ لا يمكن التراجع عن هذا الإجراء.');
            
            if (confirmed) {
                this.submit();
            }
        });
    </script>
@endsection

