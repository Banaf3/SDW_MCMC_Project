<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VeriTrack</title>
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
        }        .auth-container {
            min-height: 100vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4c6ef5 0%, #45c649 50%, #f7971e 100%);
            background-size: 200% 200%;
            animation: gradientBG 12s ease infinite;
            position: relative;
            overflow: hidden;
        }
        
        .auth-container:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .auth-card {
            animation: cardAppear 0.6s ease-out;
        }
        
        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }.auth-card {
            background: #fff;
            padding: 2.8rem 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(76,110,245,0.12), 0 2px 8px rgba(69,198,73,0.08);
            width: 100%;
            max-width: 440px;
            border-top: 5px solid #4c6ef5;
        }
        .auth-title {
            text-align: center;
            background: linear-gradient(90deg, #4c6ef5, #45c649);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 2rem;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 0.5px;
            position: relative;
        }
        .auth-title:after {
            content: '';
            position: absolute;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #4c6ef5, #45c649);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 3px;
        }        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            color: #4a5568;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            background: #f8fafc;
            transition: all 0.3s ease;
            margin: 0 auto;
            display: block;
            height: 3.2rem; /* Fixed height for consistency */
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: #4c6ef5;
            outline: none;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(76,110,245,0.15);
        }        .btn {
            display: inline-block;
            padding: 0.9rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            letter-spacing: 0.4px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4c6ef5 0%, #45c649 100%);
            color: #fff;
            height: 3.4rem;
            box-shadow: 0 4px 12px rgba(76,110,245,0.25);
            position: relative;
            overflow: hidden;
        }
        .btn-primary:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
            transition: all 0.5s ease;
        }
        .btn-primary:hover:before {
            left: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(76,110,245,0.3);
        }        .btn-block {
            width: 100%;
            margin-bottom: 1.5rem;
        }
        
        .auth-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            width: 100%;
            gap: 15px;
        }
          .register-btn, .forgot-btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            width: 48%;
            box-sizing: border-box;
            position: relative;
            z-index: 10;
            pointer-events: auto;
        }
        
        .register-btn {
            background: #f8fafc;
            color: #4a5568;
            border: 1.5px solid #e2e8f0;
        }
          .register-btn:hover {
            background: #fff !important;
            color: #4c6ef5 !important;
            border-color: #4c6ef5 !important;
            box-shadow: 0 2px 8px rgba(76,110,245,0.15) !important;
            text-decoration: none !important;
            pointer-events: auto !important;
            cursor: pointer !important;
        }
        
        .forgot-btn {
            background: rgba(247, 151, 30, 0.08);
            color: #f7971e;
            border: 1.5px solid #f7971e;
        }
          .forgot-btn:hover {
            background: rgba(247, 151, 30, 0.15) !important;
            box-shadow: 0 2px 8px rgba(247, 151, 30, 0.2) !important;
            transform: translateY(-1px) !important;
            text-decoration: none !important;
            pointer-events: auto !important;
            cursor: pointer !important;
        }.alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 500;
            position: relative;
            animation: fadeInUp 0.4s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-danger {
            background: #fff5f5;
            color: #c53030;
            border: 1px solid #feb2b2;
            box-shadow: 0 4px 12px rgba(197, 48, 48, 0.08);
        }
        
        .alert-success {
            background: #f0fff4;
            color: #2f855a;
            border: 1px solid #9ae6b4;
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.08);
        }
        
        .alert:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            border-radius: 4px 0 0 4px;
        }
        
        .alert-danger:before {
            background: linear-gradient(to bottom, #c53030, #e53e3e);
        }
        
        .alert-success:before {
            background: linear-gradient(to bottom, #2f855a, #48bb78);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">Login to VeriTrack</h2>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email or Username</label>
                    <input type="text" name="email" id="email" class="form-control" 
                           placeholder="Enter your email or username" required autofocus>
                </div>                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" class="form-control" 
                               placeholder="Enter your password" required style="padding-right: 50px;">
                        <button type="button" id="togglePassword" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); 
                                       background: none; border: none; cursor: pointer; color: #666; font-size: 18px;"
                                title="Show/Hide Password">
                            👁️
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            
            <div class="auth-buttons">
                <a href="{{ route('register') }}" class="register-btn">Register</a>
                <a href="{{ route('password.request') }}" class="forgot-btn">Forgot Password?</a>
            </div>
        </div>
    </div>    <script>
        // Ensure buttons remain clickable
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const registerBtn = document.querySelector('.register-btn');
                const forgotBtn = document.querySelector('.forgot-btn');
                
                if (registerBtn) {
                    registerBtn.style.pointerEvents = 'auto';
                    registerBtn.style.cursor = 'pointer';
                    registerBtn.style.zIndex = '999';
                    registerBtn.style.position = 'relative';
                    
                    // Add click event listener as backup
                    registerBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        window.location.href = '{{ route("register") }}';
                    });
                }
                
                if (forgotBtn) {
                    forgotBtn.style.pointerEvents = 'auto';
                    forgotBtn.style.cursor = 'pointer';
                    forgotBtn.style.zIndex = '999';
                    forgotBtn.style.position = 'relative';
                      // Add click event listener as backup
                    forgotBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        window.location.href = '{{ route("password.request") }}';
                    });
                }
            }, 100);
            
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        togglePassword.textContent = '🙈';
                        togglePassword.title = 'Hide Password';
                    } else {
                        passwordInput.type = 'password';
                        togglePassword.textContent = '👁️';
                        togglePassword.title = 'Show Password';
                    }
                });
            }
        });
    </script>
</body>
</html>