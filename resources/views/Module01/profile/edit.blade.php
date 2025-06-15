@extends('layouts.app')

@section('title', 'Edit Profile - VeriTrack')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-header-text">
            <h2 class="profile-title">My Profile</h2>
            <p class="profile-subtitle">Manage your account information and preferences</p>
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
                <i class="error-icon">!</i>
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <h3>Account Information</h3>
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name ?? '' }}" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email ?? '' }}" readonly>
                    <span class="form-hint">Email cannot be changed</span>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Personal Information</h3>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ $user->phone ?? '' }}">
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3">{{ $user->address ?? '' }}</textarea>
                </div>
            </div>
              <div class="form-section profile-pic-section">
                <h3>Profile Picture</h3>
                
                <div class="profile-picture-container">
                    <div class="profile-pic-wrapper">
                        @if(isset($user->profile_pic))
                            <div class="current-picture">
                                <img src="{{ asset('storage/' . $user->profile_pic) }}" alt="Profile Picture">
                            </div>
                        @else
                            <div class="current-picture no-image">
                                <span>{{ substr($user->name ?? 'User', 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="upload-controls">
                        <div class="form-group">
                            <label class="custom-file-upload" for="profile_pic">
                                <i class="upload-icon">↑</i> Choose a file
                            </label>
                            <input type="file" name="profile_pic" id="profile_pic" class="form-control-file">
                            <span class="selected-file-name">No file selected</span>
                            <div class="file-restrictions">Allowed formats: JPG, PNG, GIF. Max size: 2MB</div>
                        </div>
                    </div>
                </div>
            </div>
              <div class="form-section password-section">
                <h3>Security</h3>
                <p class="password-info">Leave blank if you don't want to change your password</p>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <div class="password-field">
                        <input type="password" name="current_password" id="current_password" class="form-control">
                        <span class="toggle-password" onclick="togglePasswordVisibility('current_password')">👁️</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <div class="password-field">
                        <input type="password" name="new_password" id="new_password" class="form-control">
                        <span class="toggle-password" onclick="togglePasswordVisibility('new_password')">👁️</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <div class="password-field">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                        <span class="toggle-password" onclick="togglePasswordVisibility('new_password_confirmation')">👁️</span>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary save-button">
                    <i class="save-icon">✓</i> Save Changes
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
.profile-container {
    max-width: 860px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.profile-header {
    margin-bottom: 1.5rem;
    text-align: center;
}

.profile-header-text {
    margin-bottom: 0.8rem;
}

.profile-title {
    color: #3c4858;
    font-size: 2.3rem;
    margin-bottom: 0.8rem;
    font-weight: 700;
    position: relative;
    display: inline-block;
    padding-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    background: linear-gradient(90deg, #4c6ef5, #45c649);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0px 2px 5px rgba(0,0,0,0.08);
}

.profile-title::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #4c6ef5 0%, #45c649 70%, #f7971e 100%);
    bottom: 0;
    left: 0;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(76,110,245,0.2);
}

.profile-subtitle {
    color: #858c97;
    font-size: 1rem;
}

.profile-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    padding: 1.5rem;
}

.form-section {
    margin-bottom: 2.2rem;
    padding-bottom: 1.8rem;
    border-bottom: 1px solid #e9ecef;
    width: 100%;
    max-width: 680px;
    margin-left: auto;
    margin-right: auto;
}

.form-section h3 {
    color: #3c4858;
    font-size: 1.25rem;
    margin-bottom: 1.2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    padding: 0.3rem 0.8rem;
    border-left: 4px solid #4c6ef5;
    background: rgba(76, 110, 245, 0.05);
    border-radius: 0 4px 4px 0;
}

.form-group {
    margin-bottom: 1.4rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    width: 100%;
    max-width: 420px; /* Consistent width for all form groups */
    margin-left: auto;
    margin-right: auto;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #3c4858;
    font-weight: 600;
    font-size: 0.9rem;
    padding-left: 0.2rem;
}

.form-control {
    width: 100%;
    max-width: 420px;
    padding: 0.5rem 0.8rem;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 0.95rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    height: 2.6rem; /* Consistent height for all inputs */
    line-height: 1.2;
    margin: 0;
}

textarea.form-control {
    height: auto;
    padding: 0.6rem 0.8rem;
    line-height: 1.4;
    min-height: 5rem;
    width: 100%;
    max-width: 420px;
    resize: vertical;
}

.form-control-file {
    display: none;
}

.form-control:focus {
    border-color: #4c6ef5;
    outline: none;
    background-color: #fff;
    box-shadow: 0 0 0 2px rgba(76,110,245,0.15);
}

.form-hint {
    display: block;
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.3rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.6rem 1.8rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    height: 2.8rem;
    min-width: 160px;
    letter-spacing: 0.5px;
}

.btn-primary {
    background: linear-gradient(90deg, #4c6ef5 0%, #45c649 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(76,110,245,0.3);
}

.btn-secondary {
    background: #f8f9fa;
    color: #495057;
    border: 1px solid #ced4da;
}

.btn-primary:hover {
    opacity: 0.95;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(76,110,245,0.25);
}

.btn-secondary:hover {
    background: #e9ecef;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

.form-actions {
    display: flex;
    margin-top: 2.5rem;
    width: 100%;
    max-width: 420px;
    margin-left: auto;
    margin-right: auto;
    justify-content: space-between;
}

.alert {
    padding: 0.8rem 1.2rem;
    border-radius: 6px;
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.alert-success {
    background: #e6f8e9;
    color: #37b24d;
    border-left: 3px solid #37b24d;
}

.alert-danger {
    background: #fff2f0;
    color: #c92a2a;
    border-left: 3px solid #c92a2a;
}

.success-icon, .error-icon {
    font-style: normal;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 8px;
    font-weight: bold;
    font-size: 0.8rem;
}

.success-icon {
    background: #37b24d;
    color: white;
}

.error-icon {
    background: #c92a2a;
    color: white;
}

.error-list {
    margin: 0;
    padding-left: 1.8rem;
    list-style-type: disc;
    font-size: 0.85rem;
}

.profile-picture-container {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.profile-pic-wrapper {
    position: relative;
}

.current-picture {
    width: 80px; /* Even smaller profile picture */
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 2px solid white;
    position: relative;
}

.current-picture img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.current-picture.no-image {
    background: linear-gradient(45deg, #4c6ef5, #45c649);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    font-weight: bold;
}

.upload-controls {
    flex: 1;
}

.custom-file-upload {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #4c6ef5;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.85rem;
}

.custom-file-upload:hover {
    background: #365ad4;
    transform: translateY(-1px);
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

.upload-icon {
    margin-right: 5px;
    font-style: normal;
}

.password-info {
    color: #6c757d;
    font-style: italic;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.password-field {
    position: relative;
    width: 100%;
    max-width: 420px;
    margin: 0 auto;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    font-size: 1rem;
}

.password-section {
    background: #f9fbff;
    padding: 1.5rem;
    border-radius: 10px;
    border-bottom: none;
    margin-top: 1rem;
    border-left: 4px solid #4c6ef5;
    box-shadow: 0 2px 8px rgba(76,110,245,0.05);
    max-width: 680px;
    margin-left: auto;
    margin-right: auto;
}

.profile-pic-section {
    background: #f9fbff;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #45c649;
    box-shadow: 0 2px 8px rgba(69,198,73,0.05);
    max-width: 680px;
    margin-left: auto;
    margin-right: auto;
}

.save-icon {
    margin-right: 6px;
    font-style: normal;
}

@media (max-width: 768px) {
    .profile-picture-container {
        flex-direction: column;
        align-items: center;
        gap: 1.2rem;
    }
    
    .profile-pic-wrapper {
        margin-bottom: 0.8rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-secondary {        
        margin-left: 0;
        margin-top: 0.8rem;
    }
}
</style>

<script>
// Toggle password visibility
function togglePasswordVisibility(inputId) {
    const passwordInput = document.getElementById(inputId);
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
}

// File input handler
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('profile_pic');
    const fileNameDisplay = document.querySelector('.selected-file-name');
    
    fileInput.addEventListener('change', function() {
        if(this.files && this.files[0]) {
            fileNameDisplay.textContent = this.files[0].name;
            
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                const currentPicture = document.querySelector('.current-picture');
                if(currentPicture.classList.contains('no-image')) {
                    currentPicture.classList.remove('no-image');
                    currentPicture.innerHTML = '';
                }
                
                let img = currentPicture.querySelector('img');
                if(!img) {
                    img = document.createElement('img');
                    currentPicture.appendChild(img);
                }
                
                img.src = e.target.result;
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            fileNameDisplay.textContent = 'No file selected';
        }
    });
});
</script>
@endsection
