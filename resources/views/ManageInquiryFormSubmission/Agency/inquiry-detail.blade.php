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
    
    .clickable-evidence {
        text-decoration: none;
        color: inherit;
        position: relative;
        cursor: pointer;
    }
    
    .clickable-evidence:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    
    .evidence-actions {
        position: absolute;
        top: 8px;
        right: 8px;
        background: white;
        border-radius: 50%;
        padding: 4px;
        opacity: 0;
        transition: opacity 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .clickable-evidence:hover .evidence-actions {
        opacity: 1;
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
    <a href="{{ route('agency.inquiries.list') }}" class="back-button">
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
    @if($inquiry->InquiryEvidence)
    <div class="section">
        <h2 class="section-header">
            <span>Evidence Files</span>
        </h2>
        <div class="evidence-grid">
            @if(isset($evidence['files']) && count($evidence['files']) > 0)
                @foreach($evidence['files'] as $index => $file)
                    @php
                        $fileExtension = strtolower(pathinfo($file['original_name'] ?? $file['name'] ?? 'unknown', PATHINFO_EXTENSION));
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        $isImage = in_array($fileExtension, $imageExtensions);
                        $fileSize = isset($file['size']) ? $file['size'] : 0;
                        $fileSizeFormatted = $fileSize > 0 ? number_format($fileSize / 1024, 1) . ' KB' : 'Unknown size';
                    @endphp
                    
                    <a href="{{ route('agency.inquiries.detail.download-evidence', ['inquiryId' => $inquiry->InquiryID, 'fileIndex' => $index]) }}" 
                       target="_blank" class="evidence-item clickable-evidence">
                        <div class="evidence-preview">
                            @if($isImage)
                                <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24" style="color: #10b981;">
                                    <path d="M4 3h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zm1 2v14h14V5H5zm2 3l3.5 4.5 2.5-3L16 14H8l-1-6z"/>
                                </svg>
                            @elseif($fileExtension === 'pdf')
                                <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24" style="color: #ef4444;">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                </svg>
                            @else
                                <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                </svg>
                            @endif
                        </div>
                        <div class="evidence-info">
                            <div class="evidence-name">{{ $file['original_name'] ?? $file['name'] ?? 'Unknown file' }}</div>
                            <div class="evidence-meta">{{ $fileSizeFormatted }}</div>
                        </div>
                        <div class="evidence-actions">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            @else
                <!-- Display text evidence if no files -->
                <div class="evidence-text">
                    {{ $evidence['description'] ?? 'No evidence description available' }}
                </div>
            @endif
        </div>
    </div>
    @endif

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
