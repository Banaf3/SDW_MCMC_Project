@extends('layouts.app')

@section('title', 'VeriTrack Dashboard')

@section('content')
@php
    // Get user data from session (consistent with header and sidebar)
    $userId = session('user_id', '');
    $userType = session('user_type', '');
    $userName = session('user_name', 'Guest');
    $userEmail = session('user_email', '');
    
    // Convert user type to role display name
    $roleDisplay = 'Guest';
    if ($userType === 'admin') {
        $roleDisplay = 'Administrator';
    } elseif ($userType === 'agency') {
        $roleDisplay = 'Agency Staff';
    } elseif ($userType === 'public') {
        $roleDisplay = 'Public User';
    }
    
    $isLoggedIn = !empty($userId);
@endphp

<div class="welcome-content">
    <h1>Welcome to VeriTrack System</h1>
    
    @if($isLoggedIn)
        <!-- Logged in user info -->
        <div style="margin: 20px 0; padding: 20px; background: #d4edda; border-radius: 8px; border: 1px solid #c3e6cb;">
            <h3 style="margin-bottom: 15px; color: #155724;">👋 Welcome, {{ $userName }}!</h3>
            <p style="margin: 0; color: #155724;"><strong>Role:</strong> {{ $roleDisplay }}</p>
            <p style="margin: 0; color: #155724;"><strong>Email:</strong> {{ $userEmail }}</p>
            @if($userType === 'admin')
                <div style="margin-top: 10px; padding: 10px; background: #cce5ff; border-radius: 6px;">
                    <strong>🔧 Admin Features:</strong> You now have access to Agency Registration and Management in the sidebar menu under "User Management".
                </div>
            @endif
        </div>
    @else
        <!-- Not logged in - show login options -->
        <div style="margin: 20px 0; padding: 20px; background: #fff3cd; border-radius: 8px; border: 1px solid #ffeaa7;">
            <h3 style="margin-bottom: 15px; color: #856404;">🔐 Please Log In</h3>
            <p style="color: #856404; margin-bottom: 15px;">To access VeriTrack features, please log in with your account.</p>
            <a href="{{ route('login') }}" style="padding: 10px 20px; background: #4c6ef5; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">🔑 Login</a>
            <a href="{{ route('register') }}" style="padding: 10px 20px; background: #45c649; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">📝 Register (Public Users)</a>
        </div>
    @endif
    
    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px;">
        <h2 style="color: #6c757d; margin-bottom: 1rem;">Dashboard Overview</h2>
        <p style="color: #6c757d; line-height: 1.6;">
            This is the VeriTrack system for managing inquiry submissions, assignments, and progress tracking.
            Use the sidebar navigation to access different modules based on your user permissions.
        </p>
        
        <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 6px; border-left: 4px solid #4c6ef5;">
            <h4 style="color: #495057; margin-bottom: 0.5rem;">Current User Role</h4>
            <p style="color: #6c757d; margin: 0;">
                You are currently logged in as a <strong>{{ $roleDisplay }}</strong>. 
                Your available modules are displayed in the sidebar.
            </p>
        </div>
        
        <div style="margin-top: 1.5rem; padding: 1rem; background: #d1ecf1; border-radius: 6px; border-left: 4px solid #17a2b8;">
            <h4 style="color: #0c5460; margin-bottom: 0.5rem;">Module Structure</h4>
            <ul style="color: #0c5460; margin: 0; padding-left: 20px;">
                <li><strong>Module 1:</strong> Manage User (User Registration, Login, Profile Management)</li>
                <li><strong>Module 2:</strong> Manage Inquiry Form Submission</li>
                <li><strong>Module 3:</strong> Inquiry Assignment (to Agencies)</li>
                <li><strong>Module 4:</strong> Inquiry Progress Tracking</li>
            </ul>
        </div>
    </div>
</div>
@endsection
