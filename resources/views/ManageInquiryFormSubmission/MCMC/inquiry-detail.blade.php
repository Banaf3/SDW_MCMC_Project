@extends('layouts.app')

@section('title', 'Inquiry Details - MCMC Review')

@section('content')
<style>
    .inquiry-details-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .section {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 16px;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        margin-bottom: 16px;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        transition: all 0.2s;
    }
    
    .back-button:hover {
        color: #475569;
        background-color: #f8fafc;
    }
    
    .back-icon {
        width: 16px;
        height: 16px;
        margin-right: 6px;
    }
    
    .header-title {
        margin: 0;
        color: #1a202c;
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    .inquiry-id {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .status-review {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .status-resolved {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-flagged {
        background-color: #fecaca;
        color: #991b1b;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .info-item {
        background: #f8fafc;
        padding: 16px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    
    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }
    
    .info-value {
        color: #1a202c;
        font-weight: 500;
    }
    
    .content-section {
        margin-bottom: 24px;
    }
    
    .content-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .content-text {
        color: #4b5563;
        line-height: 1.6;
        background: #f9fafb;
        padding: 16px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }
    
    .evidence-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }
    
    .evidence-item {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    
    .evidence-item:hover {
        border-color: #3b82f6;
    }
    
    .evidence-preview {
        height: 120px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
    }
    
    .evidence-info {
        padding: 12px;
        background: white;
    }
    
    .evidence-name {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 4px;
        color: #1a202c;
    }
    
    .evidence-meta {
        font-size: 12px;
        color: #6b7280;
    }
    
    .admin-actions {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
    }
    
    .actions-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }
    
    .btn {
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-align: center;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
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
    
    .btn-danger {
        background-color: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #dc2626;
    }
    
    .notes-section {
        margin-top: 20px;
    }
    
    .notes-input {
        width: 100%;
        min-height: 80px;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        resize: vertical;
        font-family: inherit;
    }
    
    .notes-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .audit-history {
        background: #fefefe;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .audit-entry {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .audit-entry:last-child {
        border-bottom: none;
    }
    
    .audit-action {
        font-weight: 600;
        color: #1a202c;
    }
    
    .audit-meta {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }
</style>

<div class="inquiry-details-container">
    <!-- Back Navigation -->
    <a href="{{ route('admin.inquiries.new') }}" class="back-button">
        <svg class="back-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Inquiries
    </a>

    <!-- Inquiry Header -->
    <div class="section">
        <div class="section-header">
            <div>
                <div class="inquiry-id">Inquiry #{{ $inquiry->InquiryID }}</div>
                <h1 class="header-title">{{ $inquiry->InquiryTitle }}</h1>
            </div>
            <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->InquiryStatus)) }}">
                {{ $inquiry->InquiryStatus }}
            </span>
        </div>

        <!-- Basic Information Grid -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Submitted By</div>
                <div class="info-value">{{ $inquiry->user->UserName ?? 'Unknown User' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Contact Email</div>
                <div class="info-value">{{ $inquiry->user->UserEmail ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Submission Date</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($inquiry->SubmitionDate)->format('M d, Y h:i A') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Agency</div>
                <div class="info-value">{{ $inquiry->agency->AgencyName ?? 'General Inquiry' }}</div>
            </div>
        </div>
    </div>

    <!-- Inquiry Description -->
    <div class="section">
        <h2 class="section-header">
            <span>Inquiry Description</span>
        </h2>
        <div class="content-section">
            <div class="content-text">
                {{ $inquiry->InquiryDescription }}
            </div>
        </div>
    </div>

    <!-- Evidence Files -->
    @if($inquiry->evidence && count($inquiry->evidence) > 0)
    <div class="section">
        <h2 class="section-header">
            <span>Evidence Files ({{ count($inquiry->evidence) }})</span>
        </h2>
        <div class="evidence-grid">
            @foreach($inquiry->evidence as $evidence)
                <div class="evidence-item">
                    <div class="evidence-preview">
                        @if(in_array(pathinfo($evidence->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ Storage::url($evidence->file_path) }}" alt="Evidence" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                            </svg>
                        @endif
                    </div>
                    <div class="evidence-info">
                        <div class="evidence-name">{{ $evidence->original_filename }}</div>
                        <div class="evidence-meta">{{ number_format($evidence->file_size / 1024, 1) }} KB</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- MCMC Admin Actions -->
    <div class="section">
        <div class="admin-actions">
            <h3 class="actions-title">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                MCMC Admin Actions
            </h3>
            
            <div class="actions-grid">
                <form method="POST" action="{{ route('admin.inquiries.update-status', $inquiry->InquiryID) }}" style="display: contents;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="Under Review">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve for Review
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.inquiries.update-status', $inquiry->InquiryID) }}" style="display: contents;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="Resolved">
                    <button type="submit" class="btn btn-success">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Mark as Resolved
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.inquiries.flag', $inquiry->InquiryID) }}" style="display: contents;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="reason" value="Requires further validation">
                    <button type="submit" class="btn btn-warning">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.888-.833-2.664 0L4.15 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Flag for Review
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.inquiries.discard', $inquiry->InquiryID) }}" style="display: contents;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="reason" value="Non-serious inquiry">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to discard this inquiry? This action cannot be undone.')">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Discard Inquiry
                    </button>
                </form>
            </div>

            <!-- Admin Notes Section -->
            <div class="notes-section">
                <form method="POST" action="{{ route('admin.inquiries.update-status', $inquiry->InquiryID) }}">
                    @csrf
                    @method('PUT')
                    <div class="content-label">Add Admin Notes</div>
                    <textarea name="notes" class="notes-input" placeholder="Add notes about this inquiry for internal tracking..."></textarea>
                    <input type="hidden" name="status" value="{{ $inquiry->InquiryStatus }}">
                    <button type="submit" class="btn btn-primary" style="margin-top: 8px;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"/>
                        </svg>
                        Save Notes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Audit History -->
    @if($inquiry->auditLogs && $inquiry->auditLogs->count() > 0)
    <div class="section">
        <h2 class="section-header">
            <span>Audit History ({{ $inquiry->auditLogs->count() }} entries)</span>
        </h2>
        <div class="audit-history">
            @foreach($inquiry->auditLogs->sortByDesc('ActionDate') as $log)
                <div class="audit-entry">
                    <div class="audit-action">{{ $log->Action }}</div>
                    <div class="audit-meta">
                        By {{ $log->administrator->AdminName ?? $log->PerformedBy }} 
                        on {{ \Carbon\Carbon::parse($log->ActionDate)->format('M d, Y h:i A') }}
                        @if($log->Reason)
                            - {{ $log->Reason }}
                        @endif
                    </div>
                    @if($log->Notes)
                        <div style="margin-top: 4px; color: #4b5563; font-size: 13px;">
                            Notes: {{ $log->Notes }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
@endsection
