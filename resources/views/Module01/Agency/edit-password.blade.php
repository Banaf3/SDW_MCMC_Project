<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - VeriTrack</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
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
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
            padding: 0.7rem 2rem;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 700;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
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
            border-top: 4px solid #17a2b8;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 2rem;
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
            border-left: 4px solid #17a2b8;
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

        .form-control {
            width: 100%;
            max-width: 300px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            margin: 0 auto;
            display: block;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            border-color: #17a2b8;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(23,162,184,0.2);
            transform: translateY(-1px);
        }        .form-hint {
            display: block;
            margin-top: 0.3rem;
            font-size: 0.8rem;
            color: #495057;
            font-style: italic;
            text-align: center;
        }

        .password-requirements {
            margin-top: 0.5rem;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 1.2rem;
            list-style: none;
        }

        .password-requirements li {
            margin: 0.3rem 0;
            padding: 0.2rem 0;
            transition: all 0.3s ease;
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

        .agency-save-button {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border: 2px solid transparent;
        }

        .agency-save-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(23,162,184,0.4);
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

        .save-icon {
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
            <div class="profile-header-text">
                <h2 class="profile-title">🔐 Change Password</h2>
                <p class="profile-subtitle">Update your account password for security</p>
            </div>
            <div class="public-badge">
                <span class="role-indicator">Agency Staff</span>
            </div>
        </div>
          <div class="profile-card">
            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="warning-icon">⚠️</i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="success-icon">✓</i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="error-icon">⚠️</i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
            
            <!-- Security Notice -->
            <div class="alert" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: white; margin-bottom: 2rem;">
                <i class="info-icon">🔐</i>
                <div>
                    <strong>Security Notice:</strong><br>
                    For your account security, you must change your password before accessing the system. 
                    Please choose a strong password that meets all the requirements below.
                </div>            </div>
              <form method="POST" action="{{ route('agency.password.change.submit') }}">
                @csrf
                
                <div class="form-section">
                    <h3><i class="section-icon">🔒</i> Change Password</h3>
                    
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                        <span class="form-hint">Enter your current password to verify your identity</span>
                        @error('current_password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                      <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                        <div class="password-requirements">
                            <p class="form-hint" style="margin-bottom: 8px; font-weight: 600;">Password must meet ALL requirements:</p>
                            <ul style="text-align: left; max-width: 400px; margin: 0 auto; font-size: 0.75rem; color: #666;">
                                <li id="req-length">At least 8 characters long</li>
                                <li id="req-uppercase">At least 1 uppercase letter (A-Z)</li>
                                <li id="req-lowercase">At least 1 lowercase letter (a-z)</li>
                                <li id="req-number">At least 1 number (0-9)</li>
                                <li id="req-special">At least 1 special character (@$!%*?&)</li>
                            </ul>
                        </div>
                        @error('new_password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        <span class="form-hint">Re-enter your new password to confirm</span>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn agency-save-button">
                        <i class="save-icon">🔐</i> Update Password
                    </button>
                    <a href="#" class="btn btn-secondary" onclick="alert('Profile functionality not implemented yet')">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>        // Password validation with real-time feedback
        document.addEventListener('DOMContentLoaded', function() {
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('new_password_confirmation');
            const submitButton = document.querySelector('button[type="submit"]');
            
            // Password requirements elements
            const reqLength = document.getElementById('req-length');
            const reqUppercase = document.getElementById('req-uppercase');
            const reqLowercase = document.getElementById('req-lowercase');
            const reqNumber = document.getElementById('req-number');
            const reqSpecial = document.getElementById('req-special');
            
            function checkRequirement(element, condition) {
                if (condition) {
                    element.style.color = '#28a745';
                    element.style.fontWeight = 'bold';
                    element.innerHTML = '✓ ' + element.textContent.replace('✓ ', '').replace('✗ ', '');
                } else {
                    element.style.color = '#dc3545';
                    element.style.fontWeight = 'normal';
                    element.innerHTML = '✗ ' + element.textContent.replace('✓ ', '').replace('✗ ', '');
                }
            }
            
            function validatePasswordStrength() {
                const password = newPassword.value;
                
                if (password.length === 0) {
                    // Reset all requirements to default state
                    [reqLength, reqUppercase, reqLowercase, reqNumber, reqSpecial].forEach(el => {
                        el.style.color = '#666';
                        el.style.fontWeight = 'normal';
                        el.innerHTML = el.textContent.replace('✓ ', '').replace('✗ ', '');
                    });
                    return false;
                }
                
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecial = /[@$!%*?&]/.test(password);
                
                checkRequirement(reqLength, hasLength);
                checkRequirement(reqUppercase, hasUppercase);
                checkRequirement(reqLowercase, hasLowercase);
                checkRequirement(reqNumber, hasNumber);
                checkRequirement(reqSpecial, hasSpecial);
                
                return hasLength && hasUppercase && hasLowercase && hasNumber && hasSpecial;
            }
            
            function validatePasswordMatch() {
                if (newPassword.value && confirmPassword.value) {
                    if (newPassword.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity('Passwords do not match');
                        return false;
                    } else {
                        confirmPassword.setCustomValidity('');
                        return true;
                    }
                }
                return true;
            }
            
            function updateSubmitButton() {
                const isPasswordStrong = validatePasswordStrength();
                const doPasswordsMatch = validatePasswordMatch();
                const hasCurrentPassword = document.getElementById('current_password').value.length > 0;
                
                if (isPasswordStrong && doPasswordsMatch && hasCurrentPassword) {
                    submitButton.disabled = false;
                    submitButton.style.opacity = '1';
                } else {
                    submitButton.disabled = true;
                    submitButton.style.opacity = '0.6';
                }
            }
            
            // Add event listeners
            newPassword.addEventListener('input', function() {
                validatePasswordStrength();
                validatePasswordMatch();
                updateSubmitButton();
            });
            
            confirmPassword.addEventListener('input', function() {
                validatePasswordMatch();
                updateSubmitButton();
            });
            
            document.getElementById('current_password').addEventListener('input', updateSubmitButton);
            
            // Initial state
            updateSubmitButton();
            
            newPassword.addEventListener('input', validatePassword);
            confirmPassword.addEventListener('input', validatePassword);
        });
    </script>
</body>
</html>
