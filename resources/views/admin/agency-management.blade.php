<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Management - VeriTrack Admin</title>
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
            max-width: 1200px;
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
        .btn-success {
            background: #28a745;
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
        .agency-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        .agency-card {
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            transition: border-color 0.2s;
        }
        .agency-card:hover {
            border-color: #4c6ef5;
        }
        .agency-name {
            color: #4c6ef5;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .agency-info {
            color: #666;
            margin-bottom: 1rem;
        }
        .staff-list {
            margin-top: 1rem;
        }
        .staff-item {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .staff-name {
            font-weight: 500;
            color: #495057;
        }
        .staff-email {
            color: #666;
            font-size: 0.9rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .stats-box {
            background: linear-gradient(45deg, #4c6ef5, #45c649);
            color: #fff;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1rem;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
        }
        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
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
            <a href="{{ route('admin.agency.register') }}">Register Agency Staff</a>
            <a href="{{ route('admin.agency.management') }}" class="active">Manage Agencies</a>
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

        <div class="form-row" style="margin-bottom: 2rem;">
            <div class="stats-box">
                <div class="stats-number">{{ $agencies->count() }}</div>
                <div class="stats-label">Total Agencies</div>
            </div>
            <div class="stats-box">
                <div class="stats-number">{{ $agencies->sum(function($agency) { return $agency->staff->count(); }) }}</div>
                <div class="stats-label">Total Staff Members</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Create New Agency
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.agency.create') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="agency_name">Agency Name</label>
                            <input type="text" name="agency_name" id="agency_name" class="form-control" 
                                   value="{{ old('agency_name') }}" required 
                                   placeholder="e.g., Malaysian Maritime Enforcement Agency">
                        </div>
                        <div class="form-group">
                            <label for="agency_type">Agency Type</label>
                            <select name="agency_type" id="agency_type" class="form-control" required>
                                <option value="">-- Select Type --</option>
                                <option value="Government" {{ old('agency_type') == 'Government' ? 'selected' : '' }}>Government</option>
                                <option value="Private" {{ old('agency_type') == 'Private' ? 'selected' : '' }}>Private</option>
                                <option value="NGO" {{ old('agency_type') == 'NGO' ? 'selected' : '' }}>NGO</option>
                                <option value="International" {{ old('agency_type') == 'International' ? 'selected' : '' }}>International</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="agency_email">Agency Email</label>
                            <input type="email" name="agency_email" id="agency_email" class="form-control" 
                                   value="{{ old('agency_email') }}" required 
                                   placeholder="contact@agency.gov.my">
                        </div>
                        <div class="form-group">
                            <label for="agency_phone">Phone Number</label>
                            <input type="tel" name="agency_phone" id="agency_phone" class="form-control" 
                                   value="{{ old('agency_phone') }}" required 
                                   placeholder="+60123456789">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">
                        🏢 Create New Agency
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Existing Agencies & Staff
            </div>
            <div class="card-body">
                @if($agencies->count() > 0)
                    <div class="agency-grid">
                        @foreach($agencies as $agency)
                            <div class="agency-card">
                                <div class="agency-name">{{ $agency->AgencyName }}</div>
                                <div class="agency-info">
                                    <strong>Type:</strong> {{ $agency->AgencyType }}<br>
                                    <strong>Email:</strong> {{ $agency->AgencyEmail }}<br>
                                    <strong>Phone:</strong> {{ $agency->AgencyPhoneNum }}<br>
                                    <strong>Staff Count:</strong> {{ $agency->staff->count() }}
                                </div>
                                
                                @if($agency->staff->count() > 0)
                                    <div class="staff-list">
                                        <strong style="color: #495057; font-size: 0.95rem;">Staff Members:</strong>
                                        @foreach($agency->staff as $staff)
                                            <div class="staff-item">
                                                <div>
                                                    <div class="staff-name">{{ $staff->StaffName }}</div>
                                                    <div class="staff-email">{{ $staff->staffEmail }}</div>
                                                </div>
                                                <div style="color: #28a745; font-size: 0.9rem;">
                                                    📞 {{ $staff->staffPhoneNum }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div style="color: #666; font-style: italic; margin-top: 1rem;">
                                        No staff members registered yet.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; color: #666; padding: 2rem;">
                        <h4>No agencies found</h4>
                        <p>Create your first agency using the form above.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
