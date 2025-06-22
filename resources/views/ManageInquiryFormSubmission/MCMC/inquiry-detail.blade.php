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
    
    .evidence-display {
        margin-top: 16px;
    }
    
    .evidence-files-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .evidence-file-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: all 0.2s;
    }
    
    .evidence-file-item:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }
    
    .file-preview {
        flex-shrink: 0;
    }
      .file-info {
        min-width: 0;
        flex: 1;
    }
    
    .file-name {
        font-weight: 500;
        color: #212529;
        margin-bottom: 4px;
        word-break: break-word;
    }
    
    .file-type {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .file-actions {
        margin-top: 8px;
    }

    .btn-download {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-download:hover {
        background: #2563eb;
        color: white;
        text-decoration: none;
    }

    .btn-download svg {
        width: 14px;
        height: 14px;
    }
    
    .evidence-description {
        margin-top: 16px;
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
        </div>    <!-- Evidence Files -->
    @if($inquiry->InquiryEvidence)
    <div class="section">
        <h2 class="section-header">
            <span>Evidence Files</span>
        </h2>
        <div class="content-section">
            <div class="evidence-display">
                @if(isset($evidence['files']) && count($evidence['files']) > 0)
                    <!-- Display actual uploaded files -->
                    <div class="evidence-files-grid">
                        @foreach($evidence['files'] as $index => $file)
                            @php
                                $fileExtension = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                $isImage = in_array($fileExtension, $imageExtensions);
                                $fileSize = isset($file['size']) ? $file['size'] : 0;
                                $fileSizeFormatted = $fileSize > 0 ? number_format($fileSize / 1024, 1) . ' KB' : 'Unknown size';
                            @endphp
                            
                            <div class="evidence-file-item">
                                <div class="file-preview">
                                    @if($isImage)
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="color: #10b981;">
                                            <path d="M4 3h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zm1 2v14h14V5H5zm2 3l3.5 4.5 2.5-3L16 14H8l-1-6z"/>
                                        </svg>
                                    @elseif($fileExtension === 'pdf')
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="color: #ef4444;">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                        </svg>
                                    @elseif(in_array($fileExtension, ['mp3', 'wav', 'ogg']))
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="color: #8b5cf6;">
                                            <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                                        </svg>
                                    @elseif(in_array($fileExtension, ['mp4', 'avi', 'mov', 'wmv']))
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="color: #f59e0b;">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    @elseif(in_array($fileExtension, ['doc', 'docx']))
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="color: #2563eb;">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                        </svg>
                                    @else
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="color: #6b7280;">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="file-info">
                                    <div class="file-name">{{ $file['original_name'] }}</div>
                                    <div class="file-type">{{ strtoupper($fileExtension) }} File • {{ $fileSizeFormatted }}</div>
                                    <div class="file-actions">
                                        <a href="{{ route('admin.inquiries.download-evidence', [$inquiry->InquiryID, $index]) }}" 
                                           class="btn-download" 
                                           target="_blank"
                                           title="Download {{ $file['original_name'] }}">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Show evidence text directly if no files in JSON -->
                    <div class="content-text">
                        {{ $inquiry->InquiryEvidence }}
                    </div>
                @endif
            </div>
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
            </h3>            <div class="actions-grid">
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
