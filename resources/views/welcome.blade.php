@extends('layouts.app')

@section('title', 'VeriTrack Dashboard')

@section('content')
@php
    $userRole = request('role', 'public');
    $roleDisplay = match($userRole) {
        'admin' => 'Administrator',
        'agency' => 'Agency Staff',
        default => 'Public User'
    };
@endphp

<div class="welcome-content">
    <h1>Welcome to VeriTrack System</h1>
    
    <!-- Test different user types -->
    <div style="margin: 20px 0; padding: 20px; background: #e9ecef; border-radius: 8px;">
        <h3 style="margin-bottom: 15px;">Test User Roles:</h3>
        <a href="?role=public" style="margin-right: 10px; padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">Public User</a>
        <a href="?role=admin" style="margin-right: 10px; padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">Administrator</a>
        <a href="?role=agency" style="margin-right: 10px; padding: 10px 15px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">Agency Staff</a>
    </div>
    
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

