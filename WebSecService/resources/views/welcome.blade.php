@extends('layouts.master')

@section('title', 'Welcome')

@section('content')
    <!-- Fullscreen Content Section -->
    <div class="text-center my-5">
        <!-- Dynamic Greeting Message -->
        <h1 class="display-4 text-light mb-4" id="greeting-message"></h1>
        
        <div class="card m-4 bg-dark text-light shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="card-body p-5">
                <h2 class="text-light">Welcome to the Home Page!</h2>
                <p class="lead">We're glad you're here. Explore, learn, and enjoy the content!</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Dynamic greeting based on time of day
            function updateGreeting() {
                const hour = new Date().getHours();
                let greeting;

                if (hour < 12) {
                    greeting = "Good Morning!";
                } else if (hour < 18) {
                    greeting = "Good Afternoon!";
                } else {
                    greeting = "Good Evening!";
                }

                document.getElementById('greeting-message').textContent = greeting;
            }

            // Call the updateGreeting function when the page loads
            window.onload = updateGreeting;
        </script>
    @endpush

    <style>
        /* Fullscreen Dark Theme */
        body {
            background-color: #121212;  /* Solid dark background */
            color: #e0e0e0;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Fullscreen greeting and card without container */
        .text-center {
            padding: 30px;
        }

        /* Card styling */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
            background-color: #1f1f1f;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }

        .card-body {
            padding: 40px;
            font-size: 1.2rem;
            color: #d1d1d1;
        }

        /* Text and button enhancements */
        h1, h2 {
            color: #f0f0f0;
        }

        p.lead {
            color: #c0c0c0;
            font-size: 1.1rem;
        }

        /* Buttons */
        .btn-dark {
            background-color: #444;
            color: #fff;
            transition: background-color 0.3s ease;
            border-radius: 20px;
            padding: 10px 20px;
            border: none;
        }

        .btn-dark:hover {
            background-color: #666;
            color: #fff;
        }
    </style>
@endsection
