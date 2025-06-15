@extends('layouts.app')

@section('title', 'Public Inquiry Details - VeriTrack')

@section('content')
<div class="inquiry-details-container">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <a href="{{ route('public.inquiries.index') }}" class="back-link">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Back to Public Inquiries
            </a>
            <h1 class="page-title">Public Inquiry Details</h1>
            <p class="page-description">Personal information is protected for privacy</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-grid">
        <!-- Left Column - Inquiry Details -->
        <div class="main-content">
            <!-- Inquiry Info Card -->
            <div class="info-card">
                <div class="card-header">
                    <h2>{{ $inquiry->InquiryTitle }}</h2>
                    <div class="inquiry-meta">
                        <span class="inquiry-id">ID: #{{ $inquiry->InquiryID }}</span>
                        <span class="inquiry-date">{{ \Carbon\Carbon::parse($inquiry->SubmitionDate)->format('F d, Y') }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="field-group">
                        <label>Description</label>
                        <div class="field-content">
                            <p>{{ $inquiry->InquiryDescription }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supporting Evidence -->
            @if(!empty($evidence['files']) && count($evidence['files']) > 0)
                <div class="info-card">
                    <div class="card-header">
                        <h3>Supporting Evidence</h3>
                        <span class="evidence-count">{{ count($evidence['files']) }} file{{ count($evidence['files']) > 1 ? 's' : '' }}</span>
                    </div>

                    <div class="card-body">
                        <div class="evidence-grid">
                            @foreach($evidence['files'] as $file)
                                <div class="evidence-item">
                                    <div class="file-icon">
                                        @switch($file['type'])
                                            @case('image')
                                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093L6.002 12.5l-3.777-3.947a.5.5 0 0 0-.577-.093L.002 9.5V3a1 1 0 0 1 1-1h12z"/>
                                                </svg>
                                                @break
                                            @case('pdf')
                                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                                    <path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.813.038.17.09.346.15.521.017-.173.024-.353.004-.543.007-.172-.007-.334-.064-.463z"/>
                                                </svg>
                                                @break
                                            @case('video')
                                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M6.271 5.055a.5.5 0 0 1 .52.016L11 7.055a.5.5 0 0 1 0 .89L6.791 9.929A.5.5 0 0 1 6 9.5V6.5a.5.5 0 0 1 .271-.445z"/>
                                                </svg>
                                                @break
                                            @default
                                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4 1h5v2.5A1.5 1.5 0 0 0 10.5 5H13v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                                </svg>
                                        @endswitch
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">{{ $file['original_name'] }}</div>
                                        <div class="file-meta">
                                            <span class="file-size">{{ number_format($file['size'] / 1024, 1) }} KB</span>
                                            <span class="file-type">{{ strtoupper($file['type']) }}</span>
                                        </div>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ asset('storage/' . $file['path']) }}" 
                                           target="_blank" 
                                           class="btn-view">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="info-card">
                    <div class="card-body">
                        <div class="no-evidence">
                            <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4 1h5v2.5A1.5 1.5 0 0 0 10.5 5H13v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                            </svg>
                            <p>No supporting evidence attached to this inquiry.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Status & Info -->
        <div class="sidebar-content">
            <!-- Status Card -->
            <div class="info-card">
                <div class="card-header">
                    <h3>Status Information</h3>
                </div>
                <div class="card-body">
                    <div class="status-display">
                        <span class="status-badge status-{{ Str::slug($inquiry->InquiryStatus) }}">
                            {{ $inquiry->InquiryStatus }}
                        </span>
                    </div>
                    
                    <div class="field-group">
                        <label>Submission Date</label>
                        <div class="field-content">
                            {{ \Carbon\Carbon::parse($inquiry->SubmitionDate)->format('F d, Y') }}
                        </div>
                    </div>

                    <div class="field-group">
                        <label>Assigned Agency</label>
                        <div class="field-content">
                            @if($inquiry->AgencyName)
                                <span class="agency-badge">{{ $inquiry->AgencyName }}</span>
                            @else
                                <span class="agency-badge unassigned">Not yet assigned</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Privacy Notice -->
            <div class="info-card privacy-notice">
                <div class="card-header">
                    <h3>Privacy Notice</h3>
                </div>
                <div class="card-body">
                    <div class="privacy-content">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                        </svg>
                        <p>Personal information about the inquiry submitter is protected and not displayed publicly to ensure privacy and confidentiality.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.inquiry-details-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #4c6ef5;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 1rem;
    transition: color 0.2s ease;
}

.back-link:hover {
    color: #364fc7;
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.page-description {
    color: #718096;
    font-size: 1rem;
    margin: 0;
}

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2, .card-header h3 {
    margin: 0;
    color: #2d3748;
}

.card-body {
    padding: 1.5rem;
}

.inquiry-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
    color: #718096;
    font-size: 0.85rem;
}

.field-group {
    margin-bottom: 1.5rem;
}

.field-group:last-child {
    margin-bottom: 0;
}

.field-group label {
    display: block;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.field-content {
    color: #2d3748;
    line-height: 1.6;
}

.evidence-count {
    background: #e2e8f0;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.evidence-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.evidence-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.file-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4c6ef5;
    border: 1px solid #e2e8f0;
}

.file-info {
    flex: 1;
    min-width: 0;
}

.file-name {
    font-weight: 500;
    color: #2d3748;
    margin-bottom: 0.25rem;
    word-break: break-word;
}

.file-meta {
    display: flex;
    gap: 1rem;
    color: #718096;
    font-size: 0.8rem;
}

.file-actions {
    flex-shrink: 0;
}

.btn-view {
    padding: 0.5rem 1rem;
    background: #4c6ef5;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: background 0.2s ease;
}

.btn-view:hover {
    background: #364fc7;
}

.no-evidence {
    text-align: center;
    color: #718096;
    padding: 2rem;
}

.no-evidence svg {
    margin-bottom: 1rem;
    opacity: 0.5;
}

.status-display {
    margin-bottom: 1.5rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
}

.status-pending-review { background: #fef5e7; color: #9c4221; }
.status-in-progress { background: #e3f2fd; color: #1565c0; }
.status-completed { background: #e8f5e8; color: #2e7d32; }
.status-closed { background: #fafafa; color: #616161; }

.agency-badge {
    padding: 0.5rem 1rem;
    background: #f7fafc;
    color: #4a5568;
    border-radius: 20px;
    font-size: 0.85rem;
    border: 1px solid #e2e8f0;
    display: inline-block;
}

.agency-badge.unassigned {
    background: #fed7d7;
    color: #c53030;
    border-color: #feb2b2;
}

.privacy-notice {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.privacy-notice .card-header {
    border-bottom-color: rgba(255, 255, 255, 0.2);
}

.privacy-notice h3 {
    color: white;
}

.privacy-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.privacy-content svg {
    flex-shrink: 0;
    margin-top: 0.125rem;
    opacity: 0.9;
}

.privacy-content p {
    margin: 0;
    line-height: 1.5;
    font-size: 0.9rem;
    opacity: 0.95;
}

@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .inquiry-meta {
        align-items: flex-start;
    }
    
    .evidence-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .file-meta {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>
@endsection
