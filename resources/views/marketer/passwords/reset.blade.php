<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إعادة تعيين كلمة المرور - المسوق</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100">
                <i class="fas fa-lock text-blue-600 text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                إعادة تعيين كلمة المرور
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                أدخل كلمة المرور الجديدة
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('marketer.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="أدخل بريدك الإلكتروني" value="{{ $email ?? old('email') }}">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور الجديدة</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="أدخل كلمة المرور الجديدة">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="أعد إدخال كلمة المرور الجديدة">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-save text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    إعادة تعيين كلمة المرور
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('marketer.login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    العودة لتسجيل الدخول
                </a>
            </div>
        </form>
    </div>
</body>

</html>
