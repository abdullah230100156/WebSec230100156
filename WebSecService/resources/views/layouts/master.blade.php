<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Welcome to My Website">
    <meta name="author" content="Your Name">
    <title>Basic Website - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hide success and error messages after 3 seconds
            var successMessage = document.querySelector('.alert-success');
            var errorMessage = document.querySelector('.alert-danger');
            
            if(successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 3000);
            }

            if(errorMessage) {
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</head>
<body>
    @include('layouts.menu')

    <div class="container mt-4">

        <!-- Success and Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif



        <!-- Main Content -->
        @yield('content')

        <!-- Custom Footer -->
        <footer class="mt-4">
            <div class="text-center">
                <p>&copy; 2025 My Website. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
