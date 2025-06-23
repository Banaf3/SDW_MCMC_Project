@extends('layouts.app')

@section('title', 'Assigned Inquiries - Agency Portal')

@section('content')
<style>
    .agency-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .agency-header {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .agency-title {
        color: #1a202c;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .agency-subtitle {
        color: #6b7280;
        margin-bottom: 20px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 4px;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 14px;
    }
    
    .stat-total { color: #374151; }
    .stat-investigation { color: #3b82f6; }
    .stat-verified { color: #10b981; }
    .stat-fake { color: #ef4444; }
    .stat-rejected { color: #f59e0b; }
    
    .filters-section {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-label {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 4px;
    }
    
    .filter-input,
    .filter-select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .filter-buttons {
        display: flex;
        gap: 8px;
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
        transition: all 0.2s;
        background: white;
    }
    
    .inquiry-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .inquiry-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
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
        text-transform: uppercase;
        white-space: nowrap;
    }
    
    .status-under-investigation {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .status-verified-as-true {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-identified-as-fake {
        background-color: #fecaca;
        color: #991b1b;
    }
    
    .status-rejected {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .status-pending-review {
        background-color: #f3f4f6;
        color: #374151;
    }
    
    .inquiry-description {
        color: #4b5563;
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
    
    .btn-success {
        background-color: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background-color: #059669;
    }
    
    .btn-warning {
        background-color: #f59e0b;
        color: white;
    }
    
    .btn-warning:hover {
        background-color: #d97706;
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
    
    @media (max-width: 768px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .inquiry-header {
            flex-direction: column;
            gap: 8px;
        }
        
        .inquiry-actions {
            justify-content: flex-start;
        }
    }
</style>

<div class="agency-container">
    <!-- Header -->
    <div class="agency-header">
        <h1 class="agency-title">Assigned Inquiries</h1>
        <p class="agency-subtitle">Manage and track inquiries assigned to your agency</p>
        
        <!-- Statistics Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number stat-total">{{ $totalAssigned }}</div>
                <div class="stat-label">Total Assigned</div>
            </div>
            <div class="stat-card">
                <div class="stat-number stat-investigation">{{ $underInvestigation }}</div>
                <div class="stat-label">Under Investigation</div>
            </div>
            <div class="stat-card">
                <div class="stat-number stat-verified">{{ $verified }}</div>
                <div class="stat-label">Verified as True</div>
            </div>
            <div class="stat-card">
                <div class="stat-number stat-fake">{{ $fake }}</div>
                <div class="stat-label">Identified as Fake</div>
            </div>
            <div class="stat-card">
                <div class="stat-number stat-rejected">{{ $rejected }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('agency.inquiries.list') }}">
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Category</label>
                    <select name="category" class="filter-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
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
                
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search inquiries..." class="filter-input">
                </div>
                
                <div class="filter-buttons">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('agency.inquiries.list') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Inquiries List -->
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
                        <span class="inquiry-status status-{{ strtolower(str_replace(' ', '-', $inquiry->InquiryStatus)) }}">
                            {{ $inquiry->InquiryStatus }}
                        </span>
                    </div>
                    
                    <div class="inquiry-description">
                        {{ Str::limit($inquiry->InquiryDescription, 200) }}
                    </div>
                    
                    <div class="inquiry-actions">
                        <a href="{{ route('agency.inquiries.show', $inquiry->InquiryID) }}" class="btn btn-primary">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                            View Details
                        </a>
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
                </svg>
                <h3>No Assigned Inquiries Found</h3>
                <p>No inquiries match your current filters or no inquiries have been assigned to your agency yet.</p>
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
