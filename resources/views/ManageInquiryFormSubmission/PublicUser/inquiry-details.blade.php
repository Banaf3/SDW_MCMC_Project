@extends('layouts.app')

@section('title', 'Inquiry Details')

@section('content')
<style>
    .inquiry-details-container {
        max-width: 1000px;
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
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        margin-bottom: 16px;
    }
    
    .back-button:hover {
        color: #475569;
    }
    
    .back-icon {
        width: 16px;
        height: 16px;
        margin-right: 6px;
    }
    
    .header-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 500;
        text-align: center;
    }
    
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .status-assigned {
        background-color: #e0f2fe;
        color: #0369a1;
    }
    
    .status-reviewing {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .status-resolved {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .status-rejected {
        background-color: #fee2e2;
        color: #b91c1c;
    }
    
    .inquiry-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .meta-item {
        font-size: 14px;
        color: #64748b;
    }
    
    .meta-label {
        font-weight: 500;
        margin-right: 4px;
        color: #475569;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        margin-top: 0;
        margin-bottom: 12px;
    }
    
    .inquiry-description {
        white-space: pre-line;
        color: #334155;
        line-height: 1.6;
    }
    
    .evidence-files {
        margin-top: 20px;
    }
    
    .file-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    
    .file-icon {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        color: #64748b;
        flex-shrink: 0;
    }
    
    .file-info {
        flex: 1;
    }
    
    .file-name {
        font-size: 14px;
        font-weight: 500;
        color: #334155;
        margin-bottom: 2px;
    }
    
    .file-meta {
        font-size: 12px;
        color: #64748b;
    }
    
    .file-actions {
        margin-left: 16px;
    }
    
    .file-download {
        color: #3b82f6;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
    }
    
    .file-download:hover {
        text-decoration: underline;
    }
    
    .timeline {
        position: relative;
        padding-left: 32px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background-color: #e2e8f0;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 24px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-icon {
        position: absolute;
        left: -32px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .timeline-icon.status-change {
        background-color: #bfdbfe;
    }
    
    .timeline-icon.comment {
        background-color: #dbeafe;
    }
    
    .timeline-icon.resolved {
        background-color: #bbf7d0;
    }
    
    .timeline-content {
        background-color: #f8fafc;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }
    
    .timeline-title {
        font-weight: 500;
        font-size: 14px;
        color: #334155;
        margin: 0;
    }
    
    .timeline-date {
        font-size: 12px;
        color: #64748b;
    }
    
    .timeline-body {
        font-size: 14px;
        color: #475569;
        white-space: pre-line;
    }
    
    .no-evidence {
        font-size: 14px;
        color: #64748b;
        font-style: italic;
    }
    
    .no-updates {
        font-size: 14px;
        color: #64748b;
        font-style: italic;
    }
    
    .resolved-section {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 16px;
        margin-top: 20px;
    }
    
    .resolved-title {
        font-size: 16px;
        font-weight: 600;
        color: #166534;
        margin-top: 0;
        margin-bottom: 12px;
    }
    
    .resolved-text {
        color: #166534;
        font-size: 14px;
        line-height: 1.6;
        white-space: pre-line;
    }
    
    @media (max-width: 640px) {
        .inquiry-meta {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<div class="inquiry-details-container">    <!-- Back Button -->
    <a href="{{ route('inquiries.index') }}" class="back-button">
        <svg class="back-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to My Inquiries
    </a>
    
    <!-- Inquiry Header -->
    <div class="section">
        <div class="section-header">
            <h1 class="header-title">{{ $inquiry->InquiryTitle }}</h1>
            <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->InquiryStatus)) }}">
                {{ $inquiry->InquiryStatus }}
            </span>
        </div>
        
        <div class="inquiry-meta">
            <div class="meta-item">
                <span class="meta-label">Inquiry ID:</span>
                #{{ $inquiry->InquiryID }}
            </div>
            <div class="meta-item">
                <span class="meta-label">Submitted:</span>
                {{ \Carbon\Carbon::parse($inquiry->SubmitionDate)->format('M d, Y') }}
            </div>
        </div>
        
        <!-- Inquiry Description -->
        <h2 class="section-title">Description</h2>
        <div class="inquiry-description">
            {{ $inquiry->InquiryDescription }}
        </div>
    </div>
    
    <!-- Supporting Evidence -->
    <div class="section">
        <h2 class="section-title">Supporting Evidence</h2>
        
        @php
            $evidenceData = json_decode($inquiry->InquiryEvidence, true);
            $files = $evidenceData['files'] ?? [];
        @endphp
        
        @if(count($files) > 0)
            <div class="evidence-files">
                @foreach($files as $file)
                    <div class="file-item">
                        @php
                            $fileType = $file['type'] ?? 'file';
                            $fileIcon = '';
                            
                            switch($fileType) {
                                case 'image':
                                    $fileIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />';
                                    break;
                                case 'document':
                                case 'pdf':
                                    $fileIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />';
                                    break;
                                default:
                                    $fileIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />';
                            }
                            
                            $fileName = $file['original_name'] ?? basename($file['path'] ?? 'unknown');
                            $fileSize = isset($file['size']) ? ($file['size'] < 1024 * 1024 ? round($file['size'] / 1024, 1) . ' KB' : round($file['size'] / (1024 * 1024), 1) . ' MB') : 'Unknown size';
                        @endphp
                        
                        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            {!! $fileIcon !!}
                        </svg>
                        
                        <div class="file-info">
                            <div class="file-name">{{ $fileName }}</div>
                            <div class="file-meta">{{ $fileSize }} • {{ strtoupper($fileType) }}</div>
                        </div>
                        
                        @if(isset($file['path']))
                            <div class="file-actions">
                                <a href="{{ asset('storage/' . $file['path']) }}" class="file-download" target="_blank" download>
                                    Download
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-evidence">No supporting evidence was provided with this inquiry.</p>
        @endif
    </div>
    
    <!-- Status History and Updates -->
    <div class="section">
        <h2 class="section-title">Status Updates</h2>
        
        @php
            $statusHistory = json_decode($inquiry->StatusHistory ?? '[]', true);
        @endphp
        
        @if(!empty($statusHistory))
            <div class="timeline">
                @foreach($statusHistory as $entry)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $entry['type'] ?? 'status-change' }}"></div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h3 class="timeline-title">{{ $entry['title'] ?? 'Status changed to ' . ($entry['status'] ?? 'Unknown') }}</h3>
                                <span class="timeline-date">{{ isset($entry['date']) ? \Carbon\Carbon::parse($entry['date'])->format('M d, Y - h:i A') : 'Unknown date' }}</span>
                            </div>
                            @if(!empty($entry['comment']))
                                <div class="timeline-body">{{ $entry['comment'] }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-updates">No status updates available for this inquiry yet.</p>
        @endif
        
        <!-- Resolution Information (if resolved) -->
        @if($inquiry->InquiryStatus === 'Resolved' && !empty($inquiry->ResolvedExplanation))
            <div class="resolved-section">
                <h3 class="resolved-title">Resolution</h3>
                <div class="resolved-text">{{ $inquiry->ResolvedExplanation }}</div>
                
                @if(!empty($inquiry->ResolvedSupportingDocs))
                    <div class="evidence-files" style="margin-top: 16px;">
                        <div class="file-item">
                            <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            
                            <div class="file-info">
                                <div class="file-name">{{ basename($inquiry->ResolvedSupportingDocs) }}</div>
                                <div class="file-meta">Supporting Documentation</div>
                            </div>
                            
                            <div class="file-actions">
                                <a href="{{ asset('storage/' . $inquiry->ResolvedSupportingDocs) }}" class="file-download" target="_blank" download>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
