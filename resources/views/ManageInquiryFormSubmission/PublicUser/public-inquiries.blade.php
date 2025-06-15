@extends('layouts.app')

@section('title', 'Browse Public Inquiries - VeriTrack')

@section('content')
<div class="public-inquiries-container">
    <div class="page-header">
        <h1 class="page-title">Browse Public Inquiries</h1>
        <p class="page-description">View all inquiries submitted by the public. Personal information is protected for privacy.</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="filters-card">
        <form method="GET" action="{{ route('public.inquiries.index') }}" class="filters-form">
            <div class="filters-grid">
                <!-- Search -->
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by title or description..."
                           class="form-input">
                </div>

                <!-- Status Filter -->
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Agency Filter -->
                <div class="filter-group">
                    <label for="agency">Agency</label>
                    <select id="agency" name="agency" class="form-select">
                        <option value="all" {{ request('agency') === 'all' || !request('agency') ? 'selected' : '' }}>All Agencies</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->AgencyID }}" {{ request('agency') == $agency->AgencyID ? 'selected' : '' }}>
                                {{ $agency->AgencyName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div class="filter-group">
                    <label for="date_from">From Date</label>
                    <input type="date" 
                           id="date_from" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="form-input">
                </div>

                <!-- Date To -->
                <div class="filter-group">
                    <label for="date_to">To Date</label>
                    <input type="date" 
                           id="date_to" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="form-input">
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('public.inquiries.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Summary -->
    <div class="results-summary">
        <p>Showing {{ $inquiries->firstItem() ?? 0 }} to {{ $inquiries->lastItem() ?? 0 }} of {{ $inquiries->total() }} inquiries</p>
    </div>

    <!-- Inquiries List -->
    <div class="inquiries-list">
        @forelse($inquiries as $inquiry)
            <div class="inquiry-card">
                <div class="inquiry-header">
                    <h3 class="inquiry-title">
                        <a href="{{ route('public.inquiries.show', $inquiry->InquiryID) }}">
                            {{ $inquiry->InquiryTitle }}
                        </a>
                    </h3>
                    <div class="inquiry-meta">
                        <span class="inquiry-id">ID: #{{ $inquiry->InquiryID }}</span>
                        <span class="inquiry-date">{{ \Carbon\Carbon::parse($inquiry->SubmitionDate)->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="inquiry-body">
                    <p class="inquiry-description">
                        {{ Str::limit($inquiry->InquiryDescription, 200) }}
                    </p>
                </div>

                <div class="inquiry-footer">
                    <div class="inquiry-status">
                        <span class="status-badge status-{{ Str::slug($inquiry->InquiryStatus) }}">
                            {{ $inquiry->InquiryStatus }}
                        </span>
                    </div>
                    
                    <div class="inquiry-agency">
                        @if($inquiry->AgencyName)
                            <span class="agency-badge">{{ $inquiry->AgencyName }}</span>
                        @else
                            <span class="agency-badge unassigned">Unassigned</span>
                        @endif
                    </div>

                    <div class="inquiry-evidence">
                        @php
                            $evidence = $inquiry->InquiryEvidence ? json_decode($inquiry->InquiryEvidence, true) : [];
                            $fileCount = isset($evidence['files']) ? count($evidence['files']) : 0;
                        @endphp
                        @if($fileCount > 0)
                            <span class="evidence-indicator">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                </svg>
                                {{ $fileCount }} file{{ $fileCount > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('public.inquiries.show', $inquiry->InquiryID) }}" class="btn btn-outline">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="no-results">
                <div class="no-results-icon">
                    <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </div>
                <h3>No inquiries found</h3>
                <p>No inquiries match your current search criteria. Try adjusting your filters.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($inquiries->hasPages())
        <div class="pagination-wrapper">
            {{ $inquiries->links() }}
        </div>
    @endif
</div>

<style>
.public-inquiries-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.page-description {
    color: #718096;
    font-size: 1.1rem;
}

.filters-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: border-color 0.2s ease;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #4c6ef5;
    box-shadow: 0 0 0 3px rgba(76, 110, 245, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.btn-primary {
    background: #4c6ef5;
    color: white;
}

.btn-primary:hover {
    background: #364fc7;
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
}

.btn-outline {
    background: transparent;
    color: #4c6ef5;
    border: 1px solid #4c6ef5;
}

.btn-outline:hover {
    background: #4c6ef5;
    color: white;
}

.results-summary {
    margin-bottom: 1rem;
    color: #718096;
    font-size: 0.9rem;
}

.inquiries-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.inquiry-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.inquiry-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.inquiry-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.inquiry-title {
    font-size: 1.25rem;
    margin: 0;
}

.inquiry-title a {
    color: #2d3748;
    text-decoration: none;
}

.inquiry-title a:hover {
    color: #4c6ef5;
}

.inquiry-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
    color: #718096;
    font-size: 0.85rem;
}

.inquiry-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.inquiry-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-pending-review { background: #fef5e7; color: #9c4221; }
.status-in-progress { background: #e3f2fd; color: #1565c0; }
.status-completed { background: #e8f5e8; color: #2e7d32; }
.status-closed { background: #fafafa; color: #616161; }

.agency-badge {
    padding: 0.25rem 0.75rem;
    background: #f7fafc;
    color: #4a5568;
    border-radius: 20px;
    font-size: 0.8rem;
    border: 1px solid #e2e8f0;
}

.agency-badge.unassigned {
    background: #fed7d7;
    color: #c53030;
    border-color: #feb2b2;
}

.evidence-indicator {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #718096;
    font-size: 0.8rem;
}

.no-results {
    text-align: center;
    padding: 3rem;
    color: #718096;
}

.no-results-icon {
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-results h3 {
    margin-bottom: 0.5rem;
    color: #4a5568;
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .inquiry-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .inquiry-meta {
        align-items: flex-start;
    }
    
    .inquiry-footer {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection
