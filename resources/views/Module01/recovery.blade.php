<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery - VeriTrack</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            min-height: 100vh;
            height: 100%;
            width: 100vw;
            overflow-x: hidden;
        }
        .auth-container {
            min-height: 100vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f7971e 0%, #4c6ef5 50%, #45c649 100%);
            background-size: 200% 200%;
            animation: gradientBG 6s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .auth-card {
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(76,110,245,0.08), 0 1.5px 6px rgba(69,198,73,0.08);
            width: 100%;
            max-width: 400px;
        }
        .auth-title {
            text-align: center;
            color: #f7971e;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 1.7rem;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            min-width: 280px;
            max-width: 350px;
            padding: 0.9rem 1.2rem;
            border: 1.5px solid #ced4da;
            border-radius: 8px;
            font-size: 1.1rem;
            background: #f8f9fa;
            transition: border-color 0.2s;
            margin: 0 auto;
            display: block;
        }
        .form-control:focus {
            border-color: #f7971e;
            outline: none;
        }
        .btn {
            display: inline-block;
            padding: 0.9rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary {
            background: linear-gradient(90deg, #f7971e 0%, #4c6ef5 100%);
            color: #fff;
        }
        .btn-block {
            width: 100%;
        }
        .auth-link {
            text-align: center;
            margin-top: 1.2rem;
        }
        .auth-link a {
            color: #f7971e;
            text-decoration: underline;
        }
        .alert-success {
            background: #d3f9d8;
            color: #37b24d;
            padding: 0.7rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
        }
        .alert-danger {
            background: #ffe3e3;
            color: #c92a2a;
            padding: 0.7rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">Password Recovery</h2>            @if(session('status'))
                <div class="alert alert-success" style="background: #d3f9d8; color: #37b24d; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; text-align: center; font-weight: 600; box-shadow: 0 2px 10px rgba(55,178,77,0.1); border-left: 4px solid #37b24d;">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left: 1.2rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="identifier">Enter your email address</label>
                    <input type="email" name="email" id="identifier" class="form-control" placeholder="Email address" required autofocus>
                    <small style="color: #666; font-size: 0.9rem; margin-top: 0.5rem; display: block;">
                        Note: Password recovery is only available for accounts with email addresses.
                    </small>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
            </form>
            <p class="auth-link"><a href="{{ route('login') }}">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
