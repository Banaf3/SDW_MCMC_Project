@extends('layouts.app')

@section('title', 'Audit Logs - Security Tracking')

@section('content')
<style>
    .audit-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0;
    }
    
    .audit-header {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .audit-title {
        color: #1a202c;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .security-icon {
        width: 24px;
        height: 24px;
        color: #dc2626;
    }
    
    .audit-subtitle {
        color: #6b7280;
        margin-bottom: 16px;
    }
    
    .filter-form {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: end;
        margin-bottom: 0;
    }
    
    .filter-group {
        flex: 1;
        min-width: 150px;
    }
    
    .filter-group.wide {
        flex: 2;
        min-width: 200px;
    }
    
    .filter-label {
        display: block;
        margin-bottom: 4px;
        font-weight: 500;
        color: #374151;
        font-size: 14px;
    }
    
    .filter-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .audit-section {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .audit-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }
    
    .audit-table th {
        text-align: left;
        padding: 12px 16px;
        background-color: #f8fafc;
        color: #374151;
        font-weight: 600;
        font-size: 14px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    
    .audit-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        color: #374151;
        font-size: 14px;
        vertical-align: top;
    }
    
    .audit-table tr:hover {
        background-color: #f8fafc;
    }
    
    .action-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }
    
    .action-status-updated {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .action-flagged {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .action-discarded {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .action-created {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .inquiry-link {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
    }
    
    .inquiry-link:hover {
        text-decoration: underline;
    }
    
    .admin-name {
        font-weight: 500;
        color: #1f2937;
    }
    
    .timestamp {
        color: #6b7280;
        font-size: 13px;
    }
    
    .reason-text {
        color: #6b7280;
        font-style: italic;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
    }
    
    .btn-secondary {
        background-color: white;
        color: #374151;
        border: 1px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        background-color: #f9fafb;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }
    
    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        color: #d1d5db;
    }
    
    .stats-row {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 16px;
        flex: 1;
        min-width: 150px;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: #dc2626;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 14px;
    }
</style>

<div class="audit-container">
    <!-- Header -->
    <div class="audit-header">
        <h1 class="audit-title">
            <svg class="security-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
            </svg>
            Secure Audit Logs
        </h1>
        <p class="audit-subtitle">Complete tracking of all administrative actions performed on inquiries</p>
        
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number">{{ $auditLogs->total() }}</div>
                <div class="stat-label">Total Log Entries</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $auditLogs->where('ActionDate', '>=', now()->subDay())->count() }}</div>
                <div class="stat-label">Last 24 Hours</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $auditLogs->where('ActionDate', '>=', now()->subWeek())->count() }}</div>
                <div class="stat-label">This Week</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $actions->count() }}</div>
                <div class="stat-label">Action Types</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="audit-header">
        <form method="GET" action="{{ route('admin.audit-logs') }}" class="filter-form">
            <div class="filter-group">
                <label class="filter-label">Inquiry ID</label>
                <input type="number" name="inquiry_id" value="{{ request('inquiry_id') }}" 
                       class="filter-input" placeholder="Enter ID">
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Action Type</label>
                <select name="action" class="filter-input">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ $action }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Administrator</label>
                <select name="admin_id" class="filter-input">
                    <option value="">All Admins</option>
                    @foreach($administrators as $admin)
                        <option value="{{ $admin->AdminID }}" {{ request('admin_id') == $admin->AdminID ? 'selected' : '' }}>
                            {{ $admin->AdminName }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input">
            </div>
            
            <div class="filter-group">
                <label class="filter-label">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input">
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.audit-logs') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="audit-section">
        @if($auditLogs->count() > 0)
            <table class="audit-table">                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Inquiry</th>
                        <th>Action</th>
                        <th>Administrator</th>
                    </tr>
                </thead>
                <tbody>                    @foreach($auditLogs as $log)
                        <tr>
                            <td>
                                <div class="timestamp">
                                    {{ \Carbon\Carbon::parse($log->ActionDate)->format('M d, Y') }}<br>
                                    {{ \Carbon\Carbon::parse($log->ActionDate)->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                @if($log->inquiry)
                                    <a href="{{ route('admin.inquiries.show', $log->InquiryID) }}" class="inquiry-link">
                                        #{{ $log->InquiryID }}
                                    </a>
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">
                                        {{ Str::limit($log->inquiry->InquiryTitle, 30) }}
                                    </div>
                                @else
                                    <span style="color: #9ca3af;">#{{ $log->InquiryID }} (Deleted)</span>
                                @endif
                            </td>
                            <td>
                                <span class="action-badge action-{{ strtolower(str_replace(' ', '-', $log->Action)) }}">
                                    {{ $log->Action }}
                                </span>
                            </td>
                            <td>
                                <div class="admin-name">
                                    {{ $log->PerformedBy }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div style="margin-top: 24px;">
                {{ $auditLogs->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3>No Audit Logs Found</h3>
                <p>No administrative actions match your current filters.</p>
            </div>
        @endif
    </div>
</div>
@endsection
