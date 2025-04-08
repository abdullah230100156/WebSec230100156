<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Basic Website - @yield('title')</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // التأكد من وجود الرسائل وعرضها بشكل مؤقت
            var successMessage = document.querySelector('.alert-success');
            var errorMessage = document.querySelector('.alert-danger');
            
            // إخفاء الرسائل بعد 3 ثواني
            if(successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 3000); // إخفاء الرسالة بعد 3 ثواني
            }

            if(errorMessage) {
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 3000); // إخفاء الرسالة بعد 3 ثواني
            }
        });
    </script>
</head>
<body>
    @include('layouts.menu')
    <div class="container mt-4">

        {{-- هنا المكان الصحيح لعرض الرسائل --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- محتوى الصفحة --}}
        @yield('content')
    </div>
</body>
</html>
