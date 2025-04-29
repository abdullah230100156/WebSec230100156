{{-- resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .verify-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .verify-container h1 {
            margin-bottom: 1rem;
        }
        .verify-container form {
            margin-top: 1.5rem;
        }
        .verify-container button {
            background-color: #3490dc;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        .verify-container button:hover {
            background-color: #2779bd;
        }
        .success-message {
            color: green;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <h1>Verify Your Email Address</h1>

        <p>Before continuing, please check your email for a verification link.</p>
        <p>If you didn't receive the email, click the button below to request another.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="success-message">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">Resend Verification Email</button>
        </form>
    </div>
</body>
</html>
