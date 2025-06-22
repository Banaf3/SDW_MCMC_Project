<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - VeriTrack Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #4c6ef5 0%, #667eea 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .profile-container {
            max-width: 860px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .profile-header {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .profile-title {
            background: linear-gradient(135deg, #4c6ef5 0%, #667eea 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-align: center;
        }        .profile-subtitle {
            color: #2d3748;
            font-size: 1rem;
            margin: 0;
            font-weight: 500;
        }

        .role-indicator {
            background: linear-gradient(135deg, #4c6ef5 0%, #667eea 100%);
            color: white;
            padding: 0.7rem 2rem;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 700;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(76, 110, 245, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-top: 4px solid #4c6ef5;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 2rem;
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, rgba(76, 110, 245, 0.1) 0%, rgba(102, 126, 234, 0.1) 100%);
            border-left: 4px solid #4c6ef5;
        }

        .form-section h3 {
            color: #495057;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .section-icon {
            margin-right: 0.5rem;
            font-style: normal;
        }

        .form-group {
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            color: #2d3748;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .password-field {
            position: relative;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 3rem 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            border-color: #4c6ef5;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(76,110,245,0.2);
            transform: translateY(-1px);
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.1rem;
            user-select: none;
        }

        .toggle-password:hover {
            color: #4c6ef5;
        }        .form-hint {
            display: block;
            margin-top: 0.3rem;
            font-size: 0.8rem;
            color: #495057;
            font-style: italic;
            text-align: center;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            min-width: 180px;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: all 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4c6ef5 0%, #667eea 100%);
            color: white;
            border: 2px solid transparent;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76,110,245,0.4);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            border: 2px solid transparent;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108,117,125,0.4);
            color: white;
            text-decoration: none;
        }

        .icon {
            font-style: normal;
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
            
            .btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h2 class="profile-title">🔐 Change Administrator Password</h2>
            <p class="profile-subtitle">Update your administrator security credentials</p>
            <div style="margin-top: 1rem;">
                <span class="role-indicator">MCMC Administrator</span>
            </div>
        </div>
        
        <div class="profile-card">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="icon">✓</i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="icon">!</i>
                    <ul style="margin: 0; list-style: none;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
              <form method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <div class="form-section">
                    <h3><i class="section-icon">🔐</i> Administrator Security</h3>
                    
                    <div class="form-group">
                        <label for="current_password">Current Administrator Password</label>
                        <div class="password-field">
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility('current_password')">👁️</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Administrator Password</label>
                        <div class="password-field">
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility('new_password')">👁️</span>
                        </div>
                        <span class="form-hint">Minimum 8 characters with uppercase, lowercase, numbers, and symbols recommended</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <div class="password-field">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility('new_password_confirmation')">👁️</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon">🔐</i> Update Password
                    </button>
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                        <i class="icon">↩️</i> Back to Profile
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
</body>
</html>
