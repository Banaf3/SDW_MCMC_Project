@extends('layouts.app')

@section('title', 'Manage Inquiries - Review & Filter')

@section('content')
<style>
    .admin-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0;
    }
    
    .admin-header {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .admin-title {
        color: #1a202c;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .admin-subtitle {
        color: #6b7280;
        margin-bottom: 16px;
    }
    
    .stats-row {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
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
        color: #1e40af;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 14px;
    }
    
    .inquiries-section {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .inquiry-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 16px;
        transition: border-color 0.2s;
    }
    
    .inquiry-card:hover {
        border-color: #3b82f6;
    }
    
    .inquiry-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    
    .inquiry-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 4px;
    }
    
    .inquiry-meta {
        color: #6b7280;
        font-size: 14px;
    }
    
    .inquiry-status {
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .inquiry-description {
        color: #374151;
        margin-bottom: 16px;
        line-height: 1.5;
    }
    
    .inquiry-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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
    
    .btn-warning {
        background-color: #f59e0b;
        color: white;
    }
    
    .btn-warning:hover {
        background-color: #d97706;
    }
    
    .btn-danger {
        background-color: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #dc2626;
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
    
    .filter-form {
        margin-bottom: 0;
    }
    
    .filter-form select,
    .filter-form input {
        font-size: 14px;
    }
    
    .filter-form label {
        font-size: 14px;
    }
</style>

<div class="admin-container">    <!-- Header -->
    <div class="admin-header">
        <h1 class="admin-title">Manage Inquiries</h1>
        <p class="admin-subtitle">Review, filter, and manage inquiries from public users</p>
        
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number">{{ $totalInquiries }}</div>
                <div class="stat-label">Total Inquiries</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $newInquiries }}</div>
                <div class="stat-label">New (Pending Review)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $todayInquiries }}</div>
                <div class="stat-label">Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $weekInquiries }}</div>
                <div class="stat-label">This Week</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-header">
        <form method="GET" action="{{ route('admin.inquiries.new') }}" class="filter-form">
            <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: end;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #374151;">Status</label>
                    <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #374151;">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;">
                </div>
                
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #374151;">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;">
                </div>
                
                <div style="flex: 2; min-width: 200px;">
                    <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #374151;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or description..."
                           style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;">
                </div>
                
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.inquiries.new') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>    <!-- Inquiries List -->
    <div class="inquiries-section">
        @if($inquiries->count() > 0)
            @foreach($inquiries as $inquiry)
                <div class="inquiry-card">
                    <div class="inquiry-header">
                        <div>
                            <div class="inquiry-title">{{ $inquiry->InquiryTitle }}</div>
                            <div class="inquiry-meta">
                                ID: #{{ $inquiry->InquiryID }} | 
                                Submitted: {{ \Carbon\Carbon::parse($inquiry->SubmitionDate)->format('M d, Y') }} |
                                User: {{ $inquiry->user->UserName ?? 'Unknown' }}
                            </div>
                        </div>
                        <span class="inquiry-status status-pending">{{ $inquiry->InquiryStatus }}</span>
                    </div>
                    
                    <div class="inquiry-description">
                        {{ Str::limit($inquiry->InquiryDescription, 200) }}
                    </div>
                      <div class="inquiry-actions">
                        <a href="{{ route('admin.inquiries.show', $inquiry->InquiryID) }}" class="btn btn-primary">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                            Review Details
                        </a>
                        
                        @if($inquiry->InquiryStatus === 'Submitted')
                        <form method="POST" action="{{ route('admin.inquiries.update-status', $inquiry->InquiryID) }}" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Under Review">
                            <button type="submit" class="btn btn-secondary">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Approve for Review
                            </button>
                        </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.inquiries.discard', $inquiry->InquiryID) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="reason" value="Non-serious inquiry">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to discard this inquiry?')">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Discard
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
              <!-- Pagination -->
            <div style="margin-top: 24px;">
                {{ $inquiries->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>                <h3>No Inquiries Found</h3>
                <p>No inquiries match your current filters.</p>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
@endsection
