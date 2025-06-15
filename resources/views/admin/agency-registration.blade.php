<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Staff Registration - VeriTrack Admin</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .admin-header {
            background: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-title {
            color: #4c6ef5;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        .admin-info {
            color: #666;
            font-size: 0.95rem;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .card-header {
            background: linear-gradient(90deg, #4c6ef5 0%, #45c649 100%);
            color: #fff;
            padding: 1.5rem 2rem;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .card-body {
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #4c6ef5;
            outline: none;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary {
            background: linear-gradient(90deg, #4c6ef5 0%, #45c649 100%);
            color: #fff;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76,110,245,0.3);
        }
        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }
        .btn-block {
            width: 100%;
        }
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
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
        .credentials-box {
            background: #f8f9fa;
            border: 2px solid #4c6ef5;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        .credentials-box h4 {
            color: #4c6ef5;
            margin-bottom: 1rem;
        }
        .credential-item {
            margin-bottom: 0.5rem;
            font-family: monospace;
            background: #fff;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        .credential-item strong {
            color: #495057;
        }
        .nav-links {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .nav-links a {
            padding: 0.5rem 1rem;
            background: #fff;
            color: #4c6ef5;
            text-decoration: none;
            border-radius: 6px;
            border: 2px solid #4c6ef5;
            transition: all 0.2s;
        }
        .nav-links a:hover, .nav-links a.active {
            background: #4c6ef5;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1 class="admin-title">VeriTrack Admin Panel</h1>
        <div class="admin-info">
            Logged in as: {{ session('user_name') }} ({{ session('user_email') }})
            <a href="{{ route('logout') }}" style="margin-left: 1rem; color: #dc3545;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="container">
        <div class="nav-links">
            <a href="{{ route('admin.agency.register') }}" class="active">Register Agency Staff</a>
            <a href="{{ route('admin.agency.management') }}">Manage Agencies</a>
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('new_staff_credentials'))
            <div class="credentials-box">
                <h4>🎉 New Staff Credentials Generated</h4>
                <p>The following credentials have been generated for the new agency staff member. In a real system, these would be sent via email.</p>
                <div class="credential-item">
                    <strong>Name:</strong> {{ session('new_staff_credentials.staff_name') }}
                </div>
                <div class="credential-item">
                    <strong>Agency:</strong> {{ session('new_staff_credentials.agency_name') }}
                </div>
                <div class="credential-item">
                    <strong>Email:</strong> {{ session('new_staff_credentials.email') }}
                </div>
                <div class="credential-item">
                    <strong>Password:</strong> {{ session('new_staff_credentials.password') }}
                </div>
                <div class="credential-item">
                    <strong>Login URL:</strong> <a href="{{ session('new_staff_credentials.login_url') }}" target="_blank">{{ session('new_staff_credentials.login_url') }}</a>
                </div>
                <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                    📧 <em>Email would be sent to: {{ session('new_staff_credentials.email') }}</em>
                </p>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                Register New Agency Staff Member
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.agency.register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="staff_name">Staff Name</label>
                        <input type="text" name="staff_name" id="staff_name" class="form-control" 
                               value="{{ old('staff_name') }}" required 
                               placeholder="Enter the full name of the staff member">
                    </div>

                    <div class="form-group">
                        <label for="agency_id">Select Agency</label>
                        <select name="agency_id" id="agency_id" class="form-control" required>
                            <option value="">-- Select an Agency --</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->AgencyID }}" {{ old('agency_id') == $agency->AgencyID ? 'selected' : '' }}>
                                    {{ $agency->AgencyName }} ({{ $agency->AgencyType }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="staff_phone">Phone Number</label>
                        <input type="tel" name="staff_phone" id="staff_phone" class="form-control" 
                               value="{{ old('staff_phone') }}" required 
                               placeholder="Enter phone number (e.g., +60123456789)">
                    </div>

                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                        <h5 style="color: #495057; margin-bottom: 0.5rem;">📋 Registration Process:</h5>
                        <ol style="margin: 0; padding-left: 1.5rem; color: #666;">
                            <li>Unique email will be auto-generated based on staff name and agency</li>
                            <li>Secure password will be randomly generated</li>
                            <li>Staff credentials will be displayed here (simulated email sending)</li>
                            <li>Staff can login using the generated credentials</li>
                        </ol>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        🚀 Register Agency Staff
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
