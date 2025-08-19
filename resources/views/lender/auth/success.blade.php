<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>تم التسجيل بنجاح - فيلورينا</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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

        .success-animation {
            animation: successPulse 2s ease-in-out;
        }

        @keyframes successPulse {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .btn-hover {
            transition: all 0.3s ease;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 128, 0.2);
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
        <!-- Header -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="text-center">
                    <h1
                        class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-800 to-green-500">
                        {{ __('velorena') }}</h1>
                </div>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="bg-white py-8 px-6 rounded-2xl shadow-xl border border-gray-100">
                <!-- Success Icon -->
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto success-animation">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">تم التسجيل بنجاح!</h2>
                    <p class="text-gray-600 mb-4">مرحباً بك في منصة فيلورينا</p>
                    <div class="rounded-lg p-4 text-right"
                        style="background: var(--mint-green-light); border: 1px solid var(--navy-blue-light);">
                        <h3 class="font-medium mb-2" style="color: var(--navy-blue);">حالة الحساب:</h3>
                        <p class="text-sm" style="color: var(--navy-blue-dark);">حسابك قيد المراجعة والتفعيل</p>
                        <p class="text-xs mt-2" style="color: var(--navy-blue);">سيتم إشعارك عبر البريد الإلكتروني عند
                            تفعيل الحساب</p>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-gray-900 mb-3">الخطوات التالية:</h3>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <span class="w-2 h-2 rounded-full mt-2 ml-2 flex-shrink-0"
                                style="background: var(--navy-blue);"></span>
                            <span>سيتم مراجعة بياناتك من قبل فريقنا</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 rounded-full mt-2 ml-2 flex-shrink-0"
                                style="background: var(--navy-blue);"></span>
                            <span>ستتلقى إشعاراً عبر البريد الإلكتروني عند الموافقة</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 rounded-full mt-2 ml-2 flex-shrink-0"
                                style="background: var(--navy-blue);"></span>
                            <span>يمكنك تسجيل الدخول بعد تفعيل الحساب</span>
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('lender.login') }}"
                        class="btn-hover w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white"
                        style="background: linear-gradient(135deg, var(--navy-blue) 0%, var(--mint-green) 100%);">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        تسجيل الدخول
                    </a>

                    <a href="{{ route('welcome') }}"
                        class="btn-hover w-full flex justify-center py-3 px-4 border rounded-lg shadow-sm text-sm font-medium text-white"
                        style="background: var(--mint-green-light); border-color: var(--navy-blue); color: var(--navy-blue);">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        العودة للصفحة الرئيسية
                    </a>
                </div>

                <!-- Contact Info -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-500">للمساعدة، يمكنك التواصل معنا عبر:</p>
                    <p class="text-sm font-medium" style="color: var(--navy-blue);">support@velorena.com</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
