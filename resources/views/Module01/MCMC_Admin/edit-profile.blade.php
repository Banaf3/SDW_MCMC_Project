@extends('layouts.app')

@section('title', 'Administrator Profile - VeriTrack')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-header-text">
            <h2 class="profile-title">👤 Administrator Profile</h2>
            <p class="profile-subtitle">Manage your administrator account information and system preferences</p>
        </div>
        <div class="admin-badge">
            <span class="role-indicator">System Administrator</span>
        </div>
    </div>
    
    <div class="profile-card">
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
          <form id="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-section admin-info-section">
                <h3><i class="section-icon">ℹ️</i> Personal Information</h3>
                  <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user['name'] ?? '' }}" placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user['email'] ?? '' }}" readonly>
                    <span class="form-hint">🔒 Email address cannot be changed</span>
                </div>
                  <div class="form-group">
                    <label for="role">Administrator Role</label>
                    <input type="text" name="role" id="role" class="form-control" value="{{ $user['role'] ?? '' }}" placeholder="e.g., System Admin, Super Admin" readonly>
                    <span class="form-hint">🔑 Your administrative role (cannot be changed)</span>
                </div>
            </div>
            
            <div class="form-section admin-contact-section">
                <h3><i class="section-icon">📞</i> Contact Information</h3>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ $user['phone'] ?? '' }}" placeholder="+60123456789">
                    <span class="form-hint">Your direct contact number</span>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3" placeholder="Your address">{{ $user['address'] ?? '' }}</textarea>
                    <span class="form-hint">Your address (optional)</span>
                </div>
            </div>
            
            <div class="form-actions admin-actions">
                <button type="submit" class="btn btn-primary admin-save-button">
                    <i class="save-icon">💾</i> Save Administrator Profile
                </button>
                <a href="{{ route('password.edit') }}" class="btn btn-warning change-password-btn">
                    <i class="password-icon">🔐</i> Change Password
                </a>
                <a href="#" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.profile-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0;
}

.profile-header {
    margin-bottom: 1.5rem;
    text-align: center;
}

.profile-header-text {
    margin-bottom: 0.8rem;
}

.profile-title {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-align: center;
}

.profile-subtitle {
    color: #6c757d;
    font-size: 1rem;
    margin: 0;
}

.admin-badge {
    margin-top: 1rem;
}

.role-indicator {
    display: inline-block;
    background: linear-gradient(135deg, #dc3545 0%, #a71e2a 100%);
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.profile-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 15px;
    border-left: 4px solid #dc3545;
}

.form-section h3 {
    color: #333;
    font-size: 1.2rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
}

.form-control {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.form-control[readonly] {
    background-color: #f8f9fa;
    color: #6c757d;
}

.form-hint {
    display: block;
    margin-top: 0.3rem;
    font-size: 0.8rem;
    color: #6c757d;
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
    text-align: center;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
    margin-top: 2rem;
}

.btn {
    display: inline-block;
    padding: 0.8rem 1.5rem;
    margin: 0 0.3rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #212529;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800 0%, #c69500 100%);
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
    transform: translateY(-1px);
}
</style>
@endsection

@section('scripts')
<script>
</script>
@endsection
