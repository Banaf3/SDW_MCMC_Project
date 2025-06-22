@extends('layouts.app')

@section('title', 'Test Inquiries')

@section('content')
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.inquiry-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.inquiry-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 8px;
}

.inquiry-meta {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 12px;
}

.inquiry-description {
    color: #374151;
    line-height: 1.5;
    margin-bottom: 12px;
}

.inquiry-evidence {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 12px;
    margin-top: 8px;
}

.evidence-item {
    margin-bottom: 8px;
    font-size: 0.875rem;
}

.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}
</style>

<div class="container">
    <h1 style="color: #1a202c; font-size: 2rem; font-weight: bold; margin-bottom: 24px;">Test Inquiries</h1>
      <div style="margin-bottom: 20px;">
        <a href="{{ route('test.inquiry.create') }}" style="background: #3b82f6; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 500;">Submit New Inquiry</a>
    </div>

    @if($inquiries->count() > 0)
        @foreach($inquiries as $inquiry)
            <div class="inquiry-card">
                <div class="inquiry-title">{{ $inquiry->InquiryTitle }}</div>
                <div class="inquiry-meta">
                    <span class="status-badge status-pending">{{ $inquiry->InquiryStatus }}</span>
                    • Submitted: {{ $inquiry->SubmissionDate ? $inquiry->SubmissionDate->format('M d, Y H:i') : 'N/A' }}
                    • ID: {{ $inquiry->InquiryID }}
                </div>
                <div class="inquiry-description">{{ $inquiry->InquiryDescription }}</div>
                  @if($inquiry->InquiryEvidence)
                    @php
                        $evidence = json_decode($inquiry->InquiryEvidence, true);
                    @endphp
                    <div class="inquiry-evidence">
                        <strong>Supporting Evidence:</strong>
                        
                        @if(isset($evidence['news_source']) && $evidence['news_source'])
                            <div class="evidence-item"><strong>News Source:</strong> {{ $evidence['news_source'] }}</div>
                        @endif
                        
                        @if(isset($evidence['urls']) && is_array($evidence['urls']) && count($evidence['urls']) > 0)
                            <div class="evidence-item">
                                <strong>Related URLs ({{ count($evidence['urls']) }}):</strong>
                                @foreach($evidence['urls'] as $url)
                                    <div style="margin-left: 16px;">• <a href="{{ $url }}" target="_blank" style="color: #3b82f6;">{{ $url }}</a></div>
                                @endforeach
                            </div>
                        @endif
                        
                        @if(isset($evidence['additional_context']) && $evidence['additional_context'])
                            <div class="evidence-item"><strong>Additional Context:</strong> {{ $evidence['additional_context'] }}</div>
                        @endif
                        
                        @if(isset($evidence['files']) && is_array($evidence['files']) && count($evidence['files']) > 0)
                            <div class="evidence-item">
                                <strong>Supporting Files ({{ count($evidence['files']) }}):</strong>
                                @foreach($evidence['files'] as $file)
                                    <div style="margin-left: 16px;">
                                        • {{ $file['original_name'] ?? 'Unknown file' }} 
                                        ({{ number_format(($file['size'] ?? 0)/1024, 1) }} KB)
                                        @if(isset($file['type']))
                                            <span style="background: #e0e7ff; color: #3730a3; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin-left: 8px;">{{ strtoupper($file['type']) }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        {{-- Legacy support for old evidence format --}}
                        @if(isset($evidence['news_url']) && $evidence['news_url'])
                            <div class="evidence-item"><strong>News URL:</strong> <a href="{{ $evidence['news_url'] }}" target="_blank" style="color: #3b82f6;">{{ $evidence['news_url'] }}</a></div>
                        @endif
                        
                        @if(isset($evidence['documents']) && count($evidence['documents']) > 0)
                            <div class="evidence-item">
                                <strong>Documents ({{ count($evidence['documents']) }}):</strong>
                                @foreach($evidence['documents'] as $doc)
                                    <div style="margin-left: 16px;">• {{ $doc['original_name'] }} ({{ number_format($doc['size']/1024, 1) }} KB)</div>
                                @endforeach
                            </div>
                        @endif
                        
                        @if(isset($evidence['images']) && count($evidence['images']) > 0)
                            <div class="evidence-item">
                                <strong>Images ({{ count($evidence['images']) }}):</strong>
                                @foreach($evidence['images'] as $img)
                                    <div style="margin-left: 16px;">• {{ $img['original_name'] }} ({{ number_format($img['size']/1024, 1) }} KB)</div>
                                @endforeach
                            </div>
                        @endif
                        
                        @if(isset($evidence['additional_links']) && count($evidence['additional_links']) > 0)
                            <div class="evidence-item">
                                <strong>Additional Links:</strong>
                                @foreach($evidence['additional_links'] as $link)
                                    <div style="margin-left: 16px;">• <a href="{{ $link }}" target="_blank" style="color: #3b82f6;">{{ $link }}</a></div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <p>No inquiries submitted yet.</p>
            <a href="{{ route('test.inquiry.create') }}" style="color: #3b82f6; text-decoration: none;">Submit your first inquiry</a>
        </div>
    @endif
</div>
@endsection
