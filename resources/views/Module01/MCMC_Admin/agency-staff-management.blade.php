@extends('layouts.app')

@section('title', 'Agency & Staff Management - VeriTrack Admin')

@section('styles')
<style>
    /* Page specific styles */
    .page-header {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    
    .nav-tabs {
        display: flex;
        background: #fff;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 0;
    }
      .nav-tab {
        flex: 1;
        padding: 1.2rem;
        background: #f8f9fa;
        color: #666;
        text-align: center;
        cursor: pointer;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s;
        border-right: 1px solid #dee2e6;
    }
    
    .nav-tab:last-child {
        border-right: none;
    }
    
    .nav-tab.active {
        background: linear-gradient(90deg, #4c6ef5 0%, #45c649 100%);
        color: #fff;
    }
    
    .nav-tab:hover:not(.active) {
        background: #e9ecef;
    }
    
    .tab-content {
        background: #fff;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.1);
        overflow: hidden;
    }    .tab-pane {
        display: none;
        padding: 1.5rem;
    }
    
    .tab-pane.active {
        display: block;
    }
      .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.4rem;
        color: #495057;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    
    .form-control:focus {
        border-color: #4c6ef5;
        outline: none;
    }
      .btn {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
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
    
    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
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
    }    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
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
    
    .credentials-display {
        background: #f8f9fa;
        border: 2px solid #28a745;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .credentials-title {
        color: #28a745;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }
    
    .credential-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #dee2e6;
    }
    
    .credential-item:last-child {
        border-bottom: none;
    }
    
    .credential-label {
        font-weight: 500;
        color: #495057;
    }
    
    .credential-value {
        color: #007bff;
        font-family: monospace;
        font-weight: 600;
    }

    .form-control.error {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .form-control.success {
        border-color: #28a745;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
    }    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .nav-tabs {
            flex-direction: column;
        }
        .nav-tab {
            border-right: none;
            border-bottom: 1px solid #dee2e6;
        }
        .nav-tab:last-child {
            border-bottom: none;
        }
        .form-row {
            grid-template-columns: 1fr;
        }
        .agency-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 style="background: linear-gradient(135deg, #4c6ef5 0%, #45c649 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; color: transparent; font-size: 2rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
        <i style="background: linear-gradient(135deg, #4c6ef5 0%, #45c649 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-style: normal;">🏢</i>
        Agency & Staff Management
    </h1>
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

<!-- Display new staff credentials if available -->
@if(session('new_staff_credentials'))
    <div class="credentials-display">
        <div class="credentials-title">✅ New Staff Account Created Successfully!</div>
        @php $creds = session('new_staff_credentials'); @endphp
        <div class="credential-item">
            <span class="credential-label">Staff Name:</span>
            <span class="credential-value">{{ $creds['staff_name'] }}</span>
        </div>
        <div class="credential-item">
            <span class="credential-label">Agency:</span>
            <span class="credential-value">{{ $creds['agency_name'] }}</span>
        </div>
        <div class="credential-item">
            <span class="credential-label">Username:</span>
            <span class="credential-value">{{ $creds['username'] }}</span>
        </div>
        <div class="credential-item">
            <span class="credential-label">Email:</span>
            <span class="credential-value">{{ $creds['email'] }}</span>
        </div>
        <div class="credential-item">
            <span class="credential-label">Temporary Password:</span>
            <span class="credential-value">{{ $creds['password'] }}</span>
        </div>
        <div class="credential-item">
            <span class="credential-label">Login URL:</span>
            <a href="{{ $creds['login_url'] }}" class="credential-value">{{ $creds['login_url'] }}</a>
        </div>        <div class="alert alert-info" style="margin-top: 1rem; margin-bottom: 0;">
            <strong>✅ Login credentials sent successfully!</strong> The staff member will receive their username and temporary password via email at <strong>{{ $creds['email'] }}</strong>. They will be required to change their password on first login.
        </div>
    </div>
@endif

<!-- Tab Navigation -->
<div class="nav-tabs">
    <button class="nav-tab active" onclick="switchTab('register-staff')">
        👥 Register Agency Staff
    </button>
    <button class="nav-tab" onclick="switchTab('create-agency')">
        🏢 Create New Agency
    </button>
    <button class="nav-tab" onclick="switchTab('manage-agencies')">
        📋 Manage Agencies
    </button>
</div>

<div class="tab-content">
    <!-- Register Staff Tab -->
    <div id="register-staff" class="tab-pane active">
        @if($agencies->count() == 0)
            <div class="alert alert-info">
                <strong>No agencies available!</strong> You need to create at least one agency before registering staff members.
            </div>
        @else
            <form method="POST" action="{{ route('admin.agency.register.store') }}">
                @csrf
                <div class="form-row">                    <div class="form-group">
                        <label for="staff_name">Staff Name <span style="color: red;">*</span></label>
                        <input type="text" name="staff_name" id="staff_name" class="form-control" 
                               value="{{ old('staff_name') }}" required
                               placeholder="Enter staff member's full name">
                    </div>
                    <div class="form-group">
                        <label for="agency_id">Select Agency <span style="color: red;">*</span></label>
                        <select name="agency_id" id="agency_id" class="form-control" required>
                            <option value="">-- Select an Agency --</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->AgencyID }}" 
                                        {{ old('agency_id') == $agency->AgencyID ? 'selected' : '' }}>
                                    {{ $agency->AgencyName }} ({{ $agency->AgencyType }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="staff_email">Staff Email <span style="color: red;">*</span></label>
                        <input type="email" name="staff_email" id="staff_email" class="form-control" 
                               value="{{ old('staff_email') }}" required 
                               placeholder="staff@agency.com">
                    </div>
                    <div class="form-group">
                        <label for="staff_phone">Phone Number</label>                        <input type="tel" name="staff_phone" id="staff_phone" class="form-control" 
                               value="{{ old('staff_phone') }}"
                               placeholder="+60123456789">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    👤 Register New Staff Member
                </button>
            </form>
        @endif
    </div>

    <!-- Create Agency Tab -->
    <div id="create-agency" class="tab-pane">
        <form method="POST" action="{{ route('admin.agency.create') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="agency_name">Agency Name <span style="color: red;">*</span></label>
                    <input type="text" name="agency_name" id="agency_name" class="form-control" 
                           value="{{ old('agency_name') }}" required 
                           placeholder="e.g., Malaysian Maritime Enforcement Agency">
                </div>
                <div class="form-group">
                    <label for="agency_type">Agency Type <span style="color: red;">*</span></label>
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
                    <label for="agency_email">Agency Email <span style="color: red;">*</span></label>
                    <input type="email" name="agency_email" id="agency_email" class="form-control" 
                           value="{{ old('agency_email') }}" required 
                           placeholder="contact@agency.gov.my">
                </div>
                <div class="form-group">
                    <label for="agency_phone">Phone Number <span style="color: red;">*</span></label>
                    <input type="tel" name="agency_phone" id="agency_phone" class="form-control" 
                           value="{{ old('agency_phone') }}" required 
                           placeholder="+60123456789">
                </div>
            </div>
            <div class="form-group">
                <label for="agency_address">Agency Address</label>
                <textarea name="agency_address" id="agency_address" class="form-control" rows="3" 
                          placeholder="Enter complete agency address">{{ old('agency_address') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success btn-block">
                🏢 Create New Agency
            </button>
        </form>
    </div>

    <!-- Manage Agencies Tab -->
    <div id="manage-agencies" class="tab-pane">
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

        @if($agencies->count() > 0)
            <div class="agency-grid">
                @foreach($agencies as $agency)
                    <div class="agency-card">
                        <div class="agency-name">{{ $agency->AgencyName }}</div>
                        <div class="agency-info">
                            <strong>Type:</strong> {{ $agency->AgencyType }}<br>
                            <strong>Email:</strong> {{ $agency->AgencyEmail }}<br>
                            <strong>Phone:</strong> {{ $agency->AgencyPhoneNum }}<br>
                            @if($agency->AgencyAddress)
                                <strong>Address:</strong> {{ $agency->AgencyAddress }}<br>
                            @endif
                            <strong>Staff Count:</strong> {{ $agency->staff->count() }}
                        </div>
                        
                        @if($agency->staff->count() > 0)
                            <div class="staff-list">
                                <strong style="color: #495057; font-size: 0.95rem;">Staff Members:</strong>
                                @foreach($agency->staff as $staff)
                                    <div class="staff-item">
                                        <div>
                                            <div class="staff-name">{{ $staff->user->name ?? 'Agency Staff' }}</div>
                                            <div class="staff-email">{{ $staff->user->email ?? 'No email' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="color: #666; font-style: italic; margin-top: 1rem;">
                                No staff members registered yet
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; color: #666; padding: 2rem;">
                <h4>No agencies found</h4>
                <p>Create your first agency using the "Create New Agency" tab above.</p>
                <button class="btn btn-primary" onclick="switchTab('create-agency')">
                    Create Your First Agency
                </button>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tabId) {
        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show selected tab pane
        document.getElementById(tabId).classList.add('active');
        
        // Add active class to clicked tab
        event.target.classList.add('active');
    }

    // Client-side validation
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validatePhone(phone) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]+$/;
        return phone === '' || phoneRegex.test(phone);
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        field.classList.add('error');
        field.classList.remove('success');
        
        let errorDiv = field.parentNode.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    function showFieldSuccess(fieldId) {
        const field = document.getElementById(fieldId);
        field.classList.remove('error');
        field.classList.add('success');
        
        const errorDiv = field.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    function clearFieldValidation(fieldId) {
        const field = document.getElementById(fieldId);
        field.classList.remove('error', 'success');
        
        const errorDiv = field.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    // Real-time validation
    document.addEventListener('DOMContentLoaded', function() {
        // Email validation
        const emailFields = ['staff_email', 'agency_email'];
        emailFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        if (this.hasAttribute('required')) {
                            showFieldError(fieldId, 'This field is required');
                        } else {
                            clearFieldValidation(fieldId);
                        }
                    } else if (!validateEmail(this.value)) {
                        showFieldError(fieldId, 'Please enter a valid email address');
                    } else {
                        showFieldSuccess(fieldId);
                    }
                });
            }
        });

        // Phone validation
        const phoneFields = ['staff_phone', 'agency_phone'];
        phoneFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', function() {
                    if (!validatePhone(this.value)) {
                        showFieldError(fieldId, 'Please enter a valid phone number');
                    } else if (this.value.trim() !== '') {
                        showFieldSuccess(fieldId);
                    } else {
                        clearFieldValidation(fieldId);
                    }
                });
            }
        });        // Required field validation
        const requiredFields = ['staff_name', 'agency_id', 'agency_name', 'agency_type'];
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        showFieldError(fieldId, 'This field is required');
                    } else {
                        showFieldSuccess(fieldId);
                    }
                });
            }
        });
    });
</script>
@endsection
