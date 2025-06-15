@extends('layouts.app')

@section('title', 'My Inquiries')

@section('content')
<style>
    .user-inquiries-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .inquiry-section {
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
    
    .section-title {
        color: #1a202c;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }
    
    .btn-new-inquiry {
        background-color: #3b82f6;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    
    .btn-new-inquiry:hover {
        background-color: #2563eb;
    }
    
    .inquiry-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .inquiry-table th {
        text-align: left;
        padding: 12px 16px;
        background-color: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .inquiry-table td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        font-size: 14px;
    }
    
    .inquiry-table tr:hover {
        background-color: #f8fafc;
    }
    
    .inquiry-title {
        font-weight: 500;
        color: #1e40af;
    }
    
    .inquiry-title a {
        text-decoration: none;
        color: #1e40af;
    }
    
    .inquiry-title a:hover {
        text-decoration: underline;
    }
    
    .inquiry-date {
        color: #64748b;
        font-size: 13px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 500;
        text-align: center;
        min-width: 100px;
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
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    
    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        color: #94a3b8;
    }
    
    .empty-state-title {
        font-size: 18px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }
    
    .empty-state-message {
        color: #64748b;
        margin-bottom: 24px;
    }
    
    @media (max-width: 768px) {
        .inquiry-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .btn-new-inquiry {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="user-inquiries-container">
    <div class="inquiry-section">
        <div class="section-header">
            <h1 class="section-title">My Inquiries</h1>
            <a href="{{ route('test.inquiry.create') }}" class="btn-new-inquiry">
                + Submit New Inquiry
            </a>
        </div>
        
        @if(count($inquiries) > 0)
            <div class="table-responsive">
                <table class="inquiry-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Inquiry Title</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $inquiry)
                            <tr>
                                <td>#{{ $inquiry->InquiryID }}</td>
                                <td class="inquiry-title">
                                    <a href="{{ route('test.inquiries.user.show', $inquiry->InquiryID) }}">
                                        {{ $inquiry->InquiryTitle }}
                                    </a>
                                </td>
                                <td class="inquiry-date">
                                    {{ \Carbon\Carbon::parse($inquiry->SubmitionDate)->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->InquiryStatus)) }}">
                                        {{ $inquiry->InquiryStatus }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('test.inquiries.user.show', $inquiry->InquiryID) }}" style="color: #3b82f6; text-decoration: none;">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="empty-state-title">No inquiries found</h3>
                <p class="empty-state-message">You haven't submitted any inquiries yet.</p>
                <a href="{{ route('test.inquiry.create') }}" class="btn-new-inquiry">
                    Submit Your First Inquiry
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
