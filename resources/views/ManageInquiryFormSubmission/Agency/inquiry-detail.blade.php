@extends('layouts.app')

@section('title', 'Inquiry Details - Agency Investigation')

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
    
    .agency-actions {
        background: #f0f9ff;
        border: 2px solid #bae6fd;
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
        margin-bottom: 16px;
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
    
    .comment-section {
        margin-top: 20px;
    }
    
    .comment-input {
        width: 100%;
        min-height: 80px;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        resize: vertical;
        font-family: inherit;
    }
    
    .comment-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .history-timeline {
        position: relative;
        padding-left: 20px;
    }
    
    .history-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    
    .history-entry {
        position: relative;
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .history-entry::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 20px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #3b82f6;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e5e7eb;
    }
    
    .history-entry:last-child {
        border-bottom: none;
    }
    
    .history-action {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 4px;
    }
    
    .history-meta {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .history-notes {
        font-size: 14px;
        color: #4b5563;
        background: #f9fafb;
        padding: 8px 12px;
        border-radius: 4px;
        border-left: 3px solid #3b82f6;
    }
</style>

<div class="inquiry-details-container">
    <!-- Back Navigation -->
    <a href="{{ route('agency.inquiries.assigned') }}" class="back-button">
        <svg class="back-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Assigned Inquiries
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
                <div class="info-label">Assigned Agency</div>
                <div class="info-value">{{ $inquiry->agency->AgencyName ?? 'Your Agency' }}</div>
            </div>
        </div>
    </div>

    <!-- Inquiry Description -->
    <div class="section">
        <h2 class="section-header">
            <span>Inquiry Description</span>
        </h2>
        <div class="content-text">
            {{ $inquiry->InquiryDescription }}
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

    <!-- Agency Investigation Actions -->
    <div class="section">
        <div class="agency-actions">
            <h3 class="actions-title">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Agency Investigation Actions
            </h3>
              <div class="actions-grid">
                @if($inquiry->InquiryStatus === 'Under Investigation')
                    <form method="POST" action="{{ route('agency.inquiries.update-status', $inquiry->InquiryID) }}" style="display: contents;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Verified as True">
                        <input type="hidden" name="reason" value="Investigation completed - verified as true">
                        <button type="submit" class="btn btn-success">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Mark as Verified True
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('agency.inquiries.update-status', $inquiry->InquiryID) }}" style="display: contents;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Identified as Fake">
                        <input type="hidden" name="reason" value="Investigation completed - identified as fake">
                        <button type="submit" class="btn btn-warning">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.888-.833-2.664 0L4.15 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Mark as Fake
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('agency.inquiries.update-status', $inquiry->InquiryID) }}" style="display: contents;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Rejected">
                        <input type="hidden" name="reason" value="Investigation completed - inquiry rejected">
                        <button type="submit" class="btn btn-danger">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject Inquiry
                        </button>
                    </form>
                @else
                    <div style="padding: 20px; text-align: center; color: #6b7280; background: #f9fafb; border-radius: 6px;">
                        <p><strong>Investigation Status: {{ $inquiry->InquiryStatus }}</strong></p>
                        <p>This inquiry has been completed. No further actions are available.</p>
                    </div>
                @endif
            </div>

            <!-- Agency Comment Section -->
            <div class="comment-section">
                <form method="POST" action="{{ route('agency.inquiries.add-comment', $inquiry->InquiryID) }}">
                    @csrf
                    <label for="comment" style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151;">Add Investigation Notes</label>
                    <textarea name="comment" id="comment" class="comment-input" placeholder="Add notes about your investigation progress, findings, or updates..."></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-top: 8px;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Add Comment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Inquiry History & Tracking -->
    <div class="section">
        <h2 class="section-header">
            <span>Investigation History & Tracking ({{ $inquiryHistory->count() }} entries)</span>
        </h2>
        @if($inquiryHistory->count() > 0)
            <div class="history-timeline">
                @foreach($inquiryHistory as $entry)
                    <div class="history-entry">
                        <div class="history-action">{{ $entry->Action }}</div>
                        <div class="history-meta">
                            By {{ $entry->administrator->AdminName ?? $entry->PerformedBy }} 
                            on {{ \Carbon\Carbon::parse($entry->ActionDate)->format('M d, Y h:i A') }}
                            @if($entry->OldStatus && $entry->NewStatus && $entry->OldStatus !== $entry->NewStatus)
                                | Status: {{ $entry->OldStatus }} → {{ $entry->NewStatus }}
                            @endif
                        </div>
                        @if($entry->Reason || $entry->Notes)
                            <div class="history-notes">
                                @if($entry->Reason)
                                    <strong>Reason:</strong> {{ $entry->Reason }}<br>
                                @endif
                                @if($entry->Notes)
                                    <strong>Notes:</strong> {{ $entry->Notes }}
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #6b7280; text-align: center; padding: 20px;">No history entries found for this inquiry.</p>
        @endif
    </div>
</div>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
@endsection
