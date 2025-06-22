@extends('layouts.app')

@section('title', 'Agency Staff Profile - VeriTrack')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-header-text">
            <h2 class="profile-title">👤 Agency Staff Profile</h2>
            <p class="profile-subtitle">Manage your agency account information and professional details</p>
        </div>
        <div class="agency-badge">
            <span class="role-indicator">Agency Staff</span>
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
          <form id="profile-form" method="POST" action="{{ route('agency.profile.update') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-section profile-pic-section agency-pic-section">
                <h3><i class="section-icon">🖼️</i> Profile Picture</h3>
                
                <div class="profile-picture-container">
                    <div class="profile-pic-wrapper">
                        @if(isset($user->profile_pic) && $user->profile_pic)
                            <div class="current-picture" id="profile-pic-display">
                                <img src="{{ asset('storage/' . $user->profile_pic) }}" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div class="current-picture no-image agency-avatar" id="profile-pic-display">
                                <span>{{ substr($user->name ?? 'S', 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="upload-controls">
                        <div class="form-group">
                            <button type="button" class="custom-file-upload agency-upload" onclick="triggerFileInput()">
                                <i class="upload-icon">📁</i> Choose Profile Photo
                            </button>
                            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="display: none;" onchange="handleFileSelect(this)">
                            <span class="selected-file-name" id="file-name-display">No file selected</span>
                            <div class="file-restrictions">Upload a professional photo. JPG, PNG, GIF. Max size: 2MB</div>
                        </div>
                    </div>
                </div>
            </div>
              <div class="form-section agency-info-section">
                <h3><i class="section-icon">ℹ️</i> Personal Information</h3>
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->StaffName ?? '' }}" placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->staffEmail ?? '' }}" readonly>
                    <span class="form-hint">🔒 Email address cannot be changed</span>
                </div>
            </div>
            
            <div class="form-section agency-contact-section">
                <h3><i class="section-icon">📞</i> Contact Information</h3>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ $user->staffPhoneNum ?? '' }}" placeholder="+60123456789">
                    <span class="form-hint">Your direct contact number</span>
                </div>
            </div>
            
            <div class="form-actions agency-actions">
                <button type="submit" class="btn btn-primary agency-save-button">
                    <i class="save-icon">💾</i> Save Staff Profile
                </button>                <a href="{{ route('agency.password.change') }}" class="btn btn-warning change-password-btn">
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
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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

.agency-badge {
    margin-top: 1rem;
}

.role-indicator {
    display: inline-block;
    background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
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
    border-left: 4px solid #17a2b8;
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
    border-color: #17a2b8;
    box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
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

.profile-picture-container {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.profile-pic-wrapper {
    flex-shrink: 0;
}

.current-picture {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #17a2b8;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.current-picture.no-image {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    font-size: 2.5rem;
    font-weight: bold;
}

.upload-controls {
    flex: 1;
}

.custom-file-upload {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    background: linear-gradient(90deg, #17a2b8 0%, #138496 100%);
    color: white;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
}

.custom-file-upload:hover {
    background: #138496;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}

.selected-file-name {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #495057;
}

.file-restrictions {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.3rem;
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
function triggerFileInput() {
    console.log('Button clicked - opening file dialog');
    document.getElementById('profile_pic').click();
}

function handleFileSelect(input) {
    console.log('File input changed:', input.files);
    const fileNameDisplay = document.getElementById('file-name-display');
    const profilePicDisplay = document.getElementById('profile-pic-display');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        console.log('Selected file:', file.name, file.size, file.type);
        
        // Update filename display immediately
        fileNameDisplay.textContent = file.name;
        fileNameDisplay.style.color = '#28a745';
        fileNameDisplay.style.fontWeight = 'bold';
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file (JPG, PNG, GIF)');
            input.value = '';
            fileNameDisplay.textContent = 'No file selected';
            fileNameDisplay.style.color = '#dc3545';
            return;
        }
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File must be smaller than 2MB');
            input.value = '';
            fileNameDisplay.textContent = 'No file selected';
            fileNameDisplay.style.color = '#dc3545';
            return;
        }
        
        // Show image preview
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Creating image preview');
            profilePicDisplay.innerHTML = '';
            profilePicDisplay.classList.remove('no-image', 'agency-avatar');
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;';
            img.alt = 'Profile Picture Preview';
            
            profilePicDisplay.appendChild(img);
            console.log('Image preview updated successfully');
        };
        reader.readAsDataURL(file);
    } else {
        fileNameDisplay.textContent = 'No file selected';
        fileNameDisplay.style.color = '#6c757d';
        fileNameDisplay.style.fontWeight = 'normal';
    }
}
</script>
@endsection
