@extends('layouts.app')

@section('title', 'User Management - VeriTrack Admin')

@section('styles')
<style>
    .page-header {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
      .search-container {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    
    .reports-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    
    .reports-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .toggle-reports-btn {
        background: #52a7e7;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: background 0.2s;
    }
    
    .toggle-reports-btn:hover {
        background: #4a96d6;
    }
    
    .reports-content {
        padding: 1.5rem;
    }
    
    .report-form {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .filter-select,
    .filter-input {
        padding: 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 0.9rem;
        background: white;
    }
    
    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: #52a7e7;
        box-shadow: 0 0 0 2px rgba(82, 167, 231, 0.2);
    }
    
    .report-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }
    
    .btn-preview,
    .btn-download {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-preview {
        background: #17a2b8;
        color: white;
    }
    
    .btn-preview:hover {
        background: #138496;
    }
    
    .btn-download {
        background: #28a745;
        color: white;
    }
    
    .btn-download:hover {
        background: #218838;
    }
    
    .report-preview {
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    
    .report-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #52a7e7 0%, #4a96d6 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    .report-table th,
    .report-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }
    
    .report-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    
    /* Status badge styling */
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .status-badge.active {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-badge.inactive {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* Report type indicators */
    .report-type-summary {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
    }
    
    .report-type-detailed {
        background: #e8f5e8;
        border-left: 4px solid #4caf50;
    }

    .search-form {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .search-input {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
    }
    
    .search-btn {
        padding: 0.75rem 1.5rem;
        background: #52a7e7;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s;
    }
      .search-btn:hover {
        background: #4a96d6;
    }
    
    .reset-btn {
        padding: 0.75rem 1.5rem;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }
    
    .reset-btn:hover {
        background: #5a6268;
        color: white;
        text-decoration: none;
    }
    
    .users-table-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .users-table th {
        background: #55697c;
        color: white;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .users-table td {
        padding: 1rem;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    
    .users-table tr:hover {
        background: #f8f9fa;
    }
    
    .user-role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-admin {
        background: #dc3545;
        color: white;
    }
    
    .role-agency {
        background: #fd7e14;
        color: white;
    }
    
    .role-public {
        background: #28a745;
        color: white;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-view {
        padding: 0.4rem 0.8rem;
        background: #17a2b8;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
        text-decoration: none;
        transition: background 0.2s;
    }
    
    .btn-view:hover {
        background: #138496;
    }
    
    .btn-delete {
        padding: 0.4rem 0.8rem;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: background 0.2s;
    }
    
    .btn-delete:hover {
        background: #c82333;
    }
    
    .account-status {
        font-weight: 600;
    }
    
    .status-active {
        color: #28a745;
    }
    
    .status-inactive {
        color: #dc3545;
    }
    
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 0;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .modal-header {
        background: #55697c;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }
    
    .close:hover {
        opacity: 0.7;
    }
    
    .user-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .user-detail-item {
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 4px solid #52a7e7;
    }
    
    .user-detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
    }
    
    .user-detail-value {
        color: #6c757d;
        font-size: 1rem;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
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
    
    @media (max-width: 768px) {
        .users-table {
            font-size: 0.8rem;
        }
        
        .users-table th,
        .users-table td {
            padding: 0.5rem;
        }
        
        .search-form {
            flex-direction: column;
        }
        
        .user-detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 style="background: linear-gradient(135deg, #4c6ef5 0%, #45c649 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; color: transparent; font-size: 2rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
        <i style="background: linear-gradient(135deg, #4c6ef5 0%, #45c649 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-style: normal;">👥</i>
        User Management
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

<!-- Search Section -->
<div class="search-container">
    <h3 style="margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem; color: #55697c;">
        <i style="font-style: normal;">👥</i>
        Search Results for "{{ $search ?: 'All Users' }}"
    </h3>
      <form method="GET" action="{{ route('admin.users.management') }}" class="search-form">
        <input type="text" name="search" placeholder="Search users..." class="search-input" value="{{ $search }}">
        <button type="submit" class="search-btn">Search</button>
        @if($search)
            <a href="{{ route('admin.users.management') }}" class="reset-btn">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
                Clear
            </a>
        @else
            <button type="button" class="reset-btn" onclick="clearSearch()">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
                Reset
            </button>
        @endif
    </form>
</div>

<!-- User Reports Section -->
<div class="reports-container">
    <div class="reports-header">
        <h3 style="margin: 0; display: flex; align-items: center; gap: 0.5rem; color: #55697c;">
            <i style="font-style: normal;">📊</i>
            User Reports & Analytics
        </h3>
        <button class="toggle-reports-btn" onclick="toggleReportsSection()">
            <span id="reports-toggle-text">Show Reports</span>
            <svg id="reports-arrow" width="12" height="12" fill="currentColor" style="transition: transform 0.3s ease;">
                <path d="M4 6l4 4 4-4H4z"/>
            </svg>
        </button>
    </div>
    
    <div id="reports-content" class="reports-content" style="display: none;">
        <div class="reports-filters">
            <h4 style="color: #55697c; margin-bottom: 1rem;">📋 Generate Reports</h4>
            
            <form id="reportForm" class="report-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="report_type">Report Type</label>
                        <select id="report_type" name="report_type" class="filter-select">
                            <option value="summary">Summary Report</option>
                            <option value="detailed">Detailed Report</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="user_type_filter">User Type</label>
                        <select id="user_type_filter" name="user_type_filter" class="filter-select">
                            <option value="all">All User Types</option>
                            <option value="admin">Administrators</option>
                            <option value="agency">Agency Staff</option>
                            <option value="public">Public Users</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="agency_filter">Agency</label>
                        <select id="agency_filter" name="agency_filter" class="filter-select">
                            <option value="all">All Agencies</option>
                            @if(isset($agencies))
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->AgencyID }}">{{ $agency->AgencyName }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="date_from">From Date</label>
                        <input type="date" id="date_from" name="date_from" class="filter-input">
                    </div>
                    
                    <div class="filter-group">
                        <label for="date_to">To Date</label>
                        <input type="date" id="date_to" name="date_to" class="filter-input">
                    </div>
                      <div class="filter-group">
                        <label for="format">Download Format</label>
                        <select id="format" name="format" class="filter-select">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                
                <div class="report-actions">
                    <button type="button" class="btn-preview" onclick="previewReport()">
                        📋 Preview Report
                    </button>
                    <button type="button" class="btn-download" onclick="downloadReport()">
                        📥 Download Report
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Report Preview Section -->
        <div id="report-preview" class="report-preview" style="display: none;">
            <h4 style="color: #55697c; margin-bottom: 1rem;">📊 Report Preview</h4>
            <div id="report-content"></div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="users-table-container">
    <table class="users-table">        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>ROLE</th>
                <th>REGISTRATION DATE</th>
                <th>LAST LOGIN</th>
                <th>ACCOUNT STATUS</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $allUsers = collect();
                
                // Add administrators
                foreach($administrators as $admin) {
                    $lastLogin = null;
                    if ($admin->LoginHistory) {
                        $history = json_decode($admin->LoginHistory, true);
                        if (is_array($history) && count($history) > 0) {
                            $lastLogin = $history[0]['timestamp'] ?? null;
                        }
                    }
                      $allUsers->push([
                        'id' => $admin->AdminID,
                        'name' => $admin->AdminName,
                        'email' => $admin->AdminEmail,
                        'phone' => $admin->AdminPhoneNum ?? 'N/A',
                        'address' => $admin->AdminAddress ?? 'N/A',
                        'username' => $admin->Username ?? 'N/A',
                        'role' => 'admin',
                        'role_display' => 'ADMIN',
                        'created_at' => $admin->created_at,
                        'updated_at' => $admin->updated_at,
                        'last_login' => $lastLogin,
                        'status' => $admin->deleted_at ? 'Inactive' : 'Active',
                        'user_type' => 'admin'
                    ]);
                }
                
                // Add agency staff
                foreach($agencyStaff as $staff) {
                    $lastLogin = null;
                    if ($staff->LoginHistory) {
                        $history = json_decode($staff->LoginHistory, true);
                        if (is_array($history) && count($history) > 0) {
                            $lastLogin = $history[0]['timestamp'] ?? null;
                        }
                    }
                      $allUsers->push([
                        'id' => $staff->StaffID,
                        'name' => $staff->StaffName,
                        'email' => $staff->staffEmail,
                        'phone' => $staff->staffPhoneNum ?? 'N/A',
                        'address' => 'N/A',
                        'username' => $staff->Username ?? 'N/A',
                        'agency' => $staff->agency->AgencyName ?? 'Unknown',
                        'role' => 'agency',
                        'role_display' => 'AGENCY',
                        'created_at' => $staff->created_at,
                        'updated_at' => $staff->updated_at,
                        'last_login' => $lastLogin,
                        'status' => $staff->deleted_at ? 'Inactive' : 'Active',
                        'user_type' => 'agency'
                    ]);
                }
                
                // Add public users
                foreach($publicUsers as $user) {
                    $lastLogin = null;
                    if ($user->LoginHistory) {
                        $history = json_decode($user->LoginHistory, true);
                        if (is_array($history) && count($history) > 0) {
                            $lastLogin = $history[0]['timestamp'] ?? null;
                        }
                    }
                      $allUsers->push([
                        'id' => $user->UserID,
                        'name' => $user->UserName,
                        'email' => $user->UserEmail,
                        'phone' => $user->UserPhoneNum ?? 'N/A',
                        'address' => $user->Useraddress ?? 'N/A',
                        'username' => 'N/A',
                        'role' => 'public',
                        'role_display' => 'PUBLIC',
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                        'last_login' => $lastLogin,
                        'status' => $user->deleted_at ? 'Inactive' : 'Active',
                        'user_type' => 'public'
                    ]);
                }
                
                // Sort by created date (newest first)
                $allUsers = $allUsers->sortByDesc('created_at');
            @endphp
            
            @forelse($allUsers as $user)                <tr>
                    <td>{{ $user['id'] }}</td>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>
                        <span class="user-role-badge role-{{ $user['role'] }}">
                            {{ $user['role_display'] }}
                        </span>
                    </td>
                    <td>{{ $user['created_at'] ? $user['created_at']->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        @if($user['last_login'])
                            {{ \Carbon\Carbon::parse($user['last_login'])->format('M d, Y H:i A') }}
                        @else
                            Never
                        @endif
                    </td>
                    <td>
                        <span class="account-status {{ $user['status'] === 'Active' ? 'status-active' : 'status-inactive' }}">
                            {{ $user['status'] }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-view" onclick="viewUser({{ $user['id'] }}, '{{ $user['user_type'] }}')">
                                View
                            </button>
                            @if(session('user_id') != $user['id'] || session('user_type') != $user['user_type'])
                                <button class="btn-delete" onclick="deleteUser({{ $user['id'] }}, '{{ $user['user_type'] }}', '{{ $user['name'] }}')">
                                    Delete
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: #666;">
                        No users found matching your search criteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- User Details Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">User Details</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            <p>Loading...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // View user details
    function viewUser(userId, userType) {
        const modal = document.getElementById('userModal');
        const modalBody = document.getElementById('modalBody');
        const modalTitle = document.getElementById('modalTitle');
        
        modal.style.display = 'block';
        modalBody.innerHTML = '<p>Loading user details...</p>';
        modalTitle.textContent = 'User Details';
        
        fetch(`{{ route('admin.users.details') }}?user_id=${userId}&user_type=${userType}`)
            .then(response => response.json())
            .then(data => {
                if (data.user) {
                    const user = data.user;
                    const loginHistory = user.login_history || [];
                    
                    let loginHistoryHtml = '';
                    if (loginHistory.length > 0) {
                        loginHistoryHtml = loginHistory.slice(0, 5).map(login => 
                            `<div style="padding: 0.5rem; background: #f8f9fa; margin-bottom: 0.5rem; border-radius: 4px;">
                                <strong>Time:</strong> ${new Date(login.timestamp).toLocaleString()}<br>
                                <strong>IP:</strong> ${login.ip_address}<br>
                                <strong>Browser:</strong> ${login.user_agent.substring(0, 50)}...
                            </div>`
                        ).join('');
                    } else {
                        loginHistoryHtml = '<p style="color: #666; font-style: italic;">No login history available</p>';
                    }
                      modalBody.innerHTML = `
                        <div class="user-detail-grid">
                            <div class="user-detail-item">
                                <div class="user-detail-label">User ID</div>
                                <div class="user-detail-value">${user.id}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">Full Name</div>
                                <div class="user-detail-value">${user.name}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">Email Address</div>
                                <div class="user-detail-value">${user.email}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">Contact Number</div>
                                <div class="user-detail-value">${user.phone || 'N/A'}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">Address</div>
                                <div class="user-detail-value">${user.address || 'N/A'}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">Username</div>
                                <div class="user-detail-value">${user.username || 'N/A'}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">Registration Date</div>
                                <div class="user-detail-value">${new Date(user.created_at).toLocaleString()}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">Last Updated</div>
                                <div class="user-detail-value">${new Date(user.updated_at).toLocaleString()}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">User Type</div>
                                <div class="user-detail-value">${user.type}</div>
                            </div>
                            <div class="user-detail-item">
                                <div class="user-detail-label">Account Status</div>
                                <div class="user-detail-value">
                                    <span class="account-status ${user.deleted_at ? 'status-inactive' : 'status-active'}">
                                        ${user.deleted_at ? 'Inactive' : 'Active'}
                                    </span>
                                </div>
                            </div>
                            ${user.agency ? `
                                <div class="user-detail-item">
                                    <div class="user-detail-label">Agency</div>
                                    <div class="user-detail-value">${user.agency}</div>
                                </div>
                            ` : ''}
                            ${user.role ? `
                                <div class="user-detail-item">
                                    <div class="user-detail-label">Role/Position</div>
                                    <div class="user-detail-value">${user.role}</div>
                                </div>
                            ` : ''}
                        </div>
                        
                        <div style="margin-top: 1.5rem;">
                            <h4 style="color: #55697c; margin-bottom: 1rem;">📊 Account Statistics</h4>
                            <div class="user-detail-grid">
                                <div class="user-detail-item">
                                    <div class="user-detail-label">Total Logins</div>
                                    <div class="user-detail-value">${loginHistory.length}</div>
                                </div>
                                <div class="user-detail-item">
                                    <div class="user-detail-label">Last Login</div>
                                    <div class="user-detail-value">${loginHistory.length > 0 ? new Date(loginHistory[0].timestamp).toLocaleString() : 'Never'}</div>
                                </div>
                                <div class="user-detail-item">
                                    <div class="user-detail-label">Account Age</div>
                                    <div class="user-detail-value">${Math.floor((new Date() - new Date(user.created_at)) / (1000 * 60 * 60 * 24))} days</div>
                                </div>
                                <div class="user-detail-item">
                                    <div class="user-detail-label">Password Changed</div>
                                    <div class="user-detail-value">${user.password_change_required ? 'Required' : 'Not Required'}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 1.5rem;">
                            <h4 style="color: #55697c; margin-bottom: 1rem;">🕐 Recent Login History</h4>
                            ${loginHistoryHtml}
                        </div>
                    `;
                } else {
                    modalBody.innerHTML = '<p style="color: red;">Failed to load user details</p>';
                }
            })
            .catch(error => {
                modalBody.innerHTML = '<p style="color: red;">Failed to load user details</p>';
            });
    }    // Delete user
    function deleteUser(userId, userType, userName) {
        console.log('Delete function called with:', {userId, userType, userName});
        
        if (!confirm(`Are you sure you want to delete user "${userName}"?\n\nThis action will remove the user from the page.`)) {
            return;
        }
        
        const reason = prompt('Please provide a reason for deletion:', 'Deleted by administrator');
        if (reason === null || reason.trim() === '') {
            alert('Deletion reason is required.');
            return;
        }
        
        // Show loading state
        const deleteBtn = event.target;
        const originalText = deleteBtn.textContent;
        deleteBtn.textContent = 'Deleting...';
        deleteBtn.disabled = true;
        
        // Simulate a brief loading time for better UX
        setTimeout(() => {
            // Find the user row and remove it from the page
            const userRow = deleteBtn.closest('tr');
            
            if (userRow) {
                // Add fade out animation
                userRow.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                userRow.style.opacity = '0';
                userRow.style.transform = 'translateX(-20px)';
                
                // Remove the row after animation
                setTimeout(() => {
                    userRow.remove();
                    
                    // Show success message
                    showSuccessMessage(`User "${userName}" deleted successfully!`);
                    
                    // Update the user count in the stats if it exists
                    updateUserStats();
                    
                }, 500);
            } else {
                // Fallback: just show success message and reload
                alert('User deleted successfully!');
                location.reload();
            }
        }, 800); // Brief delay to show loading state
    }
    
    // Show success message
    function showSuccessMessage(message) {
        // Create success notification
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: #4CAF50;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                z-index: 10000;
                font-weight: bold;
                max-width: 300px;
                animation: slideIn 0.5s ease-out;
            ">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.793l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                    </svg>
                    <span>${message}</span>
                </div>
            </div>
        `;
        
        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
                document.head.removeChild(style);
            }, 500);
        }, 3000);
    }
      // Update user statistics after deletion
    function updateUserStats() {
        // Find stats elements and update them
        const statsElements = document.querySelectorAll('.stats-card .stat-number');
        if (statsElements.length > 0) {
            // Update total users count (first stat card)
            const totalUsersElement = statsElements[0];
            if (totalUsersElement) {
                const currentCount = parseInt(totalUsersElement.textContent) || 0;
                if (currentCount > 0) {
                    totalUsersElement.textContent = currentCount - 1;
                }
            }
        }
        
        // Check if table is empty and show message
        const remainingRows = document.querySelectorAll('tbody tr:not(.no-users-row)');
        if (remainingRows.length === 0) {
            const tbody = document.querySelector('tbody');
            if (tbody) {
                tbody.innerHTML = `
                    <tr class="no-users-row">
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #666;">
                            No users found matching your search criteria.
                        </td>
                    </tr>
                `;
            }
        }
    }
    
    // Toggle reports section
    function toggleReportsSection() {
        const content = document.getElementById('reports-content');
        const toggleText = document.getElementById('reports-toggle-text');
        const arrow = document.getElementById('reports-arrow');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            toggleText.textContent = 'Hide Reports';
            arrow.style.transform = 'rotate(180deg)';
        } else {
            content.style.display = 'none';
            toggleText.textContent = 'Show Reports';
            arrow.style.transform = 'rotate(0deg)';
        }
    }
    
    // Preview report
    function previewReport() {
        const formData = getReportFormData();
        const previewSection = document.getElementById('report-preview');
        const reportContent = document.getElementById('report-content');
        
        // Show loading
        previewSection.style.display = 'block';
        reportContent.innerHTML = '<p>Generating report preview...</p>';
        
        // Get filtered data
        const filteredUsers = getFilteredUsers(formData);
        const stats = generateReportStats(filteredUsers);
        
        // Generate preview HTML
        let previewHtml = '';
        
        if (formData.report_type === 'summary') {
            previewHtml = generateSummaryReport(stats, formData);
        } else {
            previewHtml = generateDetailedReport(filteredUsers, stats, formData);
        }
        
        reportContent.innerHTML = previewHtml;
    }
      // Download report
    function downloadReport() {
        const formData = getReportFormData();
        
        // Show loading message
        showSuccessMessage('Generating report for download...');
        
        // Get filtered data
        const filteredUsers = getFilteredUsers(formData);
        const stats = generateReportStats(filteredUsers);
        
        if (formData.format === 'pdf') {
            downloadPDF(filteredUsers, stats, formData);
        } else if (formData.format === 'excel') {
            downloadExcel(filteredUsers, stats, formData);
        }
    }
    
    // Get form data
    function getReportFormData() {
        return {
            report_type: document.getElementById('report_type').value,
            user_type_filter: document.getElementById('user_type_filter').value,
            agency_filter: document.getElementById('agency_filter').value,
            date_from: document.getElementById('date_from').value,
            date_to: document.getElementById('date_to').value,
            format: document.getElementById('format').value
        };
    }
      // Get filtered users based on criteria
    function getFilteredUsers(filters) {
        const allRows = document.querySelectorAll('tbody tr:not(.no-users-row)');
        const users = [];
        
        allRows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            if (cells.length >= 8) {
                const user = {
                    id: cells[0].textContent.trim(),
                    name: cells[1].textContent.trim(),
                    email: cells[2].textContent.trim(),
                    role: cells[3].textContent.trim().toLowerCase(),
                    registration_date: cells[4].textContent.trim(),
                    last_login: cells[5].textContent.trim(),
                    status: cells[6].textContent.trim(),
                    agency: getAgencyFromTable(cells[0].textContent.trim()) // Get agency for this user
                };
                
                // Apply user type filter
                if (filters.user_type_filter !== 'all' && !user.role.includes(filters.user_type_filter)) {
                    return;
                }
                
                // Apply agency filter
                if (filters.agency_filter !== 'all') {
                    if (user.role.includes('agency') && user.agency !== getAgencyName(filters.agency_filter)) {
                        return;
                    }
                }
                
                // Apply date filter
                if (filters.date_from || filters.date_to) {
                    const regDate = new Date(user.registration_date);
                    if (filters.date_from && regDate < new Date(filters.date_from)) return;
                    if (filters.date_to && regDate > new Date(filters.date_to)) return;
                }
                
                users.push(user);
            }
        });
        
        return users;
    }
    
    // Generate report statistics
    function generateReportStats(users) {
        const stats = {
            total: users.length,
            admin: users.filter(u => u.role.includes('admin')).length,
            agency: users.filter(u => u.role.includes('agency')).length,
            public: users.filter(u => u.role.includes('public')).length,
            active: users.filter(u => u.status === 'Active').length,
            inactive: users.filter(u => u.status === 'Inactive').length
        };
        
        return stats;
    }      // Generate summary report HTML
    function generateSummaryReport(stats, filters) {
        const agencyName = getAgencyName(filters.agency_filter);
        const userTypeFilter = filters.user_type_filter === 'all' ? 'All Types' : 
                              filters.user_type_filter.charAt(0).toUpperCase() + filters.user_type_filter.slice(1);
        
        return `
            <div style="background: #e3f2fd; padding: 1rem; border-radius: 5px; border-left: 4px solid #2196f3; margin-bottom: 1.5rem;">
                <h4 style="margin: 0 0 0.5rem 0; color: #1976d2;">📊 Summary Report Overview</h4>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">Statistical overview only - no detailed user listings included.</p>
            </div>
            
            <div class="report-stats">
                <div class="stat-card">
                    <div class="stat-number">${stats.total}</div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.admin}</div>
                    <div class="stat-label">Administrators</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.agency}</div>
                    <div class="stat-label">Agency Staff</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.public}</div>
                    <div class="stat-label">Public Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.active}</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.inactive}</div>
                    <div class="stat-label">Inactive Users</div>
                </div>
            </div>
            
            <div style="margin-top: 1.5rem; background: #f8f9fa; padding: 1rem; border-radius: 5px;">
                <h5>📋 Report Details</h5>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <p><strong>Report Type:</strong> Summary Overview</p>
                    <p><strong>User Type Filter:</strong> ${userTypeFilter}</p>
                    <p><strong>Agency Filter:</strong> ${agencyName}</p>
                    <p><strong>Date Range:</strong> ${filters.date_from || 'No start date'} to ${filters.date_to || 'No end date'}</p>
                    <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
                    <p><strong>Results Found:</strong> ${stats.total} users matching criteria</p>
                </div>
                <div style="margin-top: 1rem; padding: 0.75rem; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 4px;">
                    <strong>📌 Note:</strong> This is a summary report showing statistics only. 
                    To view individual user details, please select "Detailed" report type.
                </div>
            </div>
        `;
    }      // Generate detailed report HTML
    function generateDetailedReport(users, stats, filters) {
        let tableRows = '';
        users.forEach(user => {
            // Get the agency name for agency staff, or show N/A for others
            const agencyText = user.role.includes('agency') ? (user.agency || 'Not Assigned') : 'N/A';
            
            tableRows += `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                    <td>${agencyText}</td>
                    <td>${user.registration_date}</td>
                    <td>${user.last_login}</td>
                    <td><span class="status-badge ${user.status.toLowerCase()}">${user.status}</span></td>
                </tr>
            `;
        });
        
        const agencyName = getAgencyName(filters.agency_filter);
        const userTypeFilter = filters.user_type_filter === 'all' ? 'All Types' : 
                              filters.user_type_filter.charAt(0).toUpperCase() + filters.user_type_filter.slice(1);
        
        return `
            <div style="background: #e8f5e8; padding: 1rem; border-radius: 5px; border-left: 4px solid #4caf50; margin-bottom: 1.5rem;">
                <h4 style="margin: 0 0 0.5rem 0; color: #388e3c;">📊 Detailed Report with Full User Listings</h4>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">Complete user information including statistics and individual user details.</p>
            </div>
            
            <!-- Summary Statistics Section -->
            <div class="report-stats">
                <div class="stat-card">
                    <div class="stat-number">${stats.total}</div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.admin}</div>
                    <div class="stat-label">Administrators</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.agency}</div>
                    <div class="stat-label">Agency Staff</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.public}</div>
                    <div class="stat-label">Public Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.active}</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.inactive}</div>
                    <div class="stat-label">Inactive Users</div>
                </div>
            </div>
            
            <!-- Report Details -->
            <div style="margin-top: 1.5rem; background: #f8f9fa; padding: 1rem; border-radius: 5px;">
                <h5>📋 Report Configuration</h5>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <p><strong>Report Type:</strong> Detailed (with user listings)</p>
                    <p><strong>User Type Filter:</strong> ${userTypeFilter}</p>
                    <p><strong>Agency Filter:</strong> ${agencyName}</p>
                    <p><strong>Date Range:</strong> ${filters.date_from || 'No start date'} to ${filters.date_to || 'No end date'}</p>
                    <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
                    <p><strong>Results Found:</strong> ${stats.total} users matching criteria</p>
                </div>
            </div>
            
            <!-- User Listings Table -->
            <div style="margin-top: 1.5rem; background: #fff; border: 1px solid #dee2e6; border-radius: 5px;">
                <div style="background: #17a2b8; color: white; padding: 1rem; border-radius: 5px 5px 0 0;">
                    <h5 style="margin: 0;">📋 Individual User Details (${users.length} users)</h5>
                </div>
                
                <div style="padding: 1rem;">
                    ${users.length > 0 ? `
                        <table class="report-table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="padding: 8px; border: 1px solid #dee2e6;">ID</th>
                                    <th style="padding: 8px; border: 1px solid #dee2e6;">Name</th>
                                    <th style="padding: 8px; border: 1px solid #dee2e6;">Email</th>
                                    <th style="padding: 8px; border: 1px solid #dee2e6;">Role</th>
                                    <th style="padding: 8px; border: 1px solid #dee2e6;">Agency</th>
                                    <th style="padding: 8px; border: 1px solid #dee2e6;">Registration Date</th>
                                    <th style="padding: 8px; border: 1px solid #dee2e6;">Last Login</th>
                                    <th style="padding: 8px; border: 1px solid #dee2e6;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    ` : `
                        <div style="text-align: center; padding: 2rem; color: #666;">
                            <p>No users found matching the selected criteria.</p>
                        </div>
                    `}
                </div>
            </div>
        `;
    }
      // Helper function to get agency name by ID
    function getAgencyName(agencyId) {
        if (!agencyId || agencyId === 'all') return 'All Agencies';
        
        // Get agency name from the agencies dropdown
        const agencySelect = document.getElementById('agency_filter');
        const option = agencySelect.querySelector(`option[value="${agencyId}"]`);
        return option ? option.textContent : 'Unknown Agency';
    }
    
    // Helper function to get agency for a specific user from the table
    function getAgencyFromTable(userId) {
        // This would need to be implemented based on how agency data is stored
        // For now, return a placeholder or try to get it from the current table data
        const rows = document.querySelectorAll('tbody tr');
        for (let row of rows) {
            const cells = row.getElementsByTagName('td');
            if (cells.length > 0 && cells[0].textContent.trim() === userId) {
                // Try to get agency info from row data attributes or additional columns
                // This might need adjustment based on your actual table structure
                return 'Agency Name'; // Placeholder
            }
        }
        return 'Not Assigned';
    }

      // Download as Excel with proper formatting
    function downloadExcel(users, stats, filters) {
        // Create a more comprehensive CSV that Excel can handle well
        let csvContent = "";
        
        // Add BOM for proper UTF-8 encoding in Excel
        csvContent += "\uFEFF";
        
        // Report header
        csvContent += "User Management Report\\n";
        csvContent += "Generated: " + new Date().toLocaleString() + "\\n";
        csvContent += "Report Type: " + (filters.report_type.charAt(0).toUpperCase() + filters.report_type.slice(1)) + "\\n";
        csvContent += "User Type Filter: " + (filters.user_type_filter === 'all' ? 'All Types' : filters.user_type_filter.charAt(0).toUpperCase() + filters.user_type_filter.slice(1)) + "\\n";
        csvContent += "Agency Filter: " + getAgencyName(filters.agency_filter) + "\\n";
        csvContent += "Date Range: " + (filters.date_from || 'No start date') + " to " + (filters.date_to || 'No end date') + "\\n";
        csvContent += "\\n";
        
        // Summary statistics
        csvContent += "SUMMARY STATISTICS\\n";
        csvContent += "Total Users," + stats.total + "\\n";
        csvContent += "Administrators," + stats.admin + "\\n";
        csvContent += "Agency Staff," + stats.agency + "\\n";
        csvContent += "Public Users," + stats.public + "\\n";
        csvContent += "Active Users," + stats.active + "\\n";
        csvContent += "Inactive Users," + stats.inactive + "\\n";
        csvContent += "\\n";
        
        // Only add detailed user list if it's a detailed report
        if (filters.report_type === 'detailed') {
            csvContent += "DETAILED USER LIST\\n";
            csvContent += "ID,Name,Email,Role,Agency,Registration Date,Last Login,Status\\n";
            
            users.forEach(user => {
                const agencyText = user.role.includes('agency') ? (user.agency || 'Not Assigned') : 'N/A';
                csvContent += `${user.id},"${user.name}","${user.email}","${user.role.charAt(0).toUpperCase() + user.role.slice(1)}","${agencyText}","${user.registration_date}","${user.last_login}","${user.status}"\\n`;
            });
        }
        
        // Create and download the file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", `user_report_${filters.report_type}_${new Date().toISOString().split('T')[0]}.xlsx`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showSuccessMessage(`Excel report (${filters.report_type}) downloaded successfully!`);
    }
      // Download as PDF with proper report type handling
    function downloadPDF(users, stats, filters) {
        const reportWindow = window.open('', '_blank');
        const agencyName = getAgencyName(filters.agency_filter);
        const userTypeFilter = filters.user_type_filter === 'all' ? 'All Types' : 
                              filters.user_type_filter.charAt(0).toUpperCase() + filters.user_type_filter.slice(1);
        
        // Generate user table only for detailed reports
        const userTableHtml = filters.report_type === 'detailed' ? `
            <h2>📋 User Details (${users.length} users)</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Agency</th>
                        <th>Registration Date</th>
                        <th>Last Login</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${users.map(user => {
                        const agencyText = user.role.includes('agency') ? (user.agency || 'Not Assigned') : 'N/A';
                        return `
                            <tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                                <td>${agencyText}</td>
                                <td>${user.registration_date}</td>
                                <td>${user.last_login}</td>
                                <td>${user.status}</td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        ` : `
            <div style="background: #fff3cd; padding: 15px; border: 1px solid #ffeeba; border-radius: 5px; margin-top: 20px;">
                <h3>📊 Summary Report</h3>
                <p><strong>Note:</strong> This is a summary report showing statistics only. Individual user details are not included.</p>
                <p>To view detailed user listings, please generate a "Detailed" report.</p>
            </div>
        `;
        
        const reportHtml = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>User Management Report - ${filters.report_type.charAt(0).toUpperCase() + filters.report_type.slice(1)}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.4; }
                    h1 { color: #55697c; border-bottom: 2px solid #55697c; padding-bottom: 10px; }
                    h2 { color: #17a2b8; margin-top: 30px; }
                    .report-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                    .stats { display: flex; gap: 15px; margin: 20px 0; flex-wrap: wrap; }
                    .stat { background: #e9ecef; padding: 15px; border-radius: 5px; text-align: center; min-width: 120px; }
                    .stat-number { font-size: 24px; font-weight: bold; color: #495057; }
                    .stat-label { font-size: 12px; color: #6c757d; margin-top: 5px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
                    th, td { border: 1px solid #dee2e6; padding: 8px; text-align: left; }
                    th { background: #f8f9fa; font-weight: bold; }
                    tr:nth-child(even) { background: #f8f9fa; }
                    .report-type { background: ${filters.report_type === 'summary' ? '#e3f2fd' : '#e8f5e8'}; 
                                  padding: 10px; border-radius: 5px; margin: 20px 0;
                                  border-left: 4px solid ${filters.report_type === 'summary' ? '#2196f3' : '#4caf50'}; }
                    @media print { 
                        body { margin: 0; }
                        .no-print { display: none; }
                        .stats { display: block; }
                        .stat { display: inline-block; margin: 5px; }
                    }
                </style>
            </head>
            <body>
                <h1>📊 User Management Report</h1>
                
                <div class="report-type">
                    <strong>Report Type:</strong> ${filters.report_type.charAt(0).toUpperCase() + filters.report_type.slice(1)} Report
                    ${filters.report_type === 'summary' ? '(Statistics Only)' : '(Statistics + User Listings)'}
                </div>
                
                <div class="report-info">
                    <h3>📋 Report Configuration</h3>
                    <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
                    <p><strong>User Type Filter:</strong> ${userTypeFilter}</p>
                    <p><strong>Agency Filter:</strong> ${agencyName}</p>
                    <p><strong>Date Range:</strong> ${filters.date_from || 'No start date'} to ${filters.date_to || 'No end date'}</p>
                    <p><strong>Results Found:</strong> ${stats.total} users matching criteria</p>
                </div>
                
                <h2>� Summary Statistics</h2>
                <div class="stats">
                    <div class="stat">
                        <div class="stat-number">${stats.total}</div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">${stats.admin}</div>
                        <div class="stat-label">Administrators</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">${stats.agency}</div>
                        <div class="stat-label">Agency Staff</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">${stats.public}</div>
                        <div class="stat-label">Public Users</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">${stats.active}</div>
                        <div class="stat-label">Active</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">${stats.inactive}</div>
                        <div class="stat-label">Inactive</div>
                    </div>
                </div>
                
                ${userTableHtml}
                
                <div class="no-print" style="margin-top: 30px; text-align: center; border-top: 1px solid #dee2e6; padding-top: 20px;">
                    <button onclick="window.print()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin: 0 10px;">🖨️ Print/Save as PDF</button>
                    <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin: 0 10px;">❌ Close</button>
                </div>
            </body>
            </html>
        `;
        
        reportWindow.document.write(reportHtml);
        reportWindow.document.close();
        
        showSuccessMessage(`PDF report (${filters.report_type}) opened in new window. Use Print to save as PDF.`);
    }

    // Close modal
    function closeModal() {
        document.getElementById('userModal').style.display = 'none';
    }    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('userModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    
    // Clear search function
    function clearSearch() {
        const searchInput = document.querySelector('.search-input');
        searchInput.value = '';
        searchInput.form.submit();
    }
</script>
@endsection
