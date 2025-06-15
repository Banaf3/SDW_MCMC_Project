<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - VeriTrack</title>
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
            background: linear-gradient(135deg, #45c649 0%, #4c6ef5 50%, #f7971e 100%);
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
            color: #45c649;
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
            border-color: #45c649;
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
            background: linear-gradient(90deg, #45c649 0%, #4c6ef5 100%);
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
            color: #45c649;
            text-decoration: underline;
        }
        .alert-danger {
            background: #ffe3e3;
            color: #c92a2a;
            padding: 0.7rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
        }
        .role-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
            display: none;
        }
        .role-admin {
            background: #4c6ef5;
            display: none;
        }
        .role-agency {
            background: #45c649;
            display: none;
        }
        .role-public {
            background: #f7971e;
            display: none;
        }
        .role-info {
            text-align: center;
            margin-bottom: 1.5rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">            <h2 class="auth-title">Register as Public User</h2>
            <div style="text-align: center; margin-bottom: 1.5rem; padding: 1rem; background: #e3f2fd; border-radius: 8px; color: #1565c0;">
                <strong>📝 Public User Registration</strong><br>
                <small>This page is for public user registration only. Admin and agency staff accounts are created through internal processes.</small>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left: 1.2rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="Enter your email address">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Enter a secure password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Confirm your password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register as Public User</button>
            </form>
            <p class="auth-link">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>    </div>
</body>
</html>
