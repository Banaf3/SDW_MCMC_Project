@extends('layouts.app')

@section('title', 'Monitor Progress - MCMC Portal')

@section('styles')
<style>
    .page-title {
        font-size: 2rem;
        margin-bottom: 30px;
        color: #1e3c72;
        border-bottom: 3px solid #2a5298;
        padding-bottom: 10px;
    }

    /* Statistics Summary */
    .stats-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.35);
    }

    .stat-number {
        display: block;
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* Filter Section */
    .filter-section {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 8px;
        font-weight: 600;
        color: #495057;
    }

    .form-control {
        padding: 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
    }

    /* Button Styles */
    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-primary {
        background: linear-gradient(45deg, #2a5298 0%, #1e3c72 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #1e3c72 0%, #2a5298 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(42, 82, 152, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    /* Inquiry Cards */
    .inquiry-list {
        display: grid;
        gap: 25px;
    }

    .inquiry-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 5px solid #2a5298;
    }

    .inquiry-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .inquiry-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .inquiry-info {
        flex-grow: 1;
    }

    .inquiry-id {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .inquiry-date {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .inquiry-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .inquiry-content {
        color: #495057;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .inquiry-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    .meta-item {
        font-size: 0.9rem;
    }

    .meta-label {
        font-weight: 600;
        color: #1e3c72;
    }

    .inquiry-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
    }

    /* Status Badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }    .status-under-investigation {
        background: #fff3cd;
        color: #856404;
        border: 2px solid #ffeaa7;
    }

    .status-under-review {
        background: #d1ecf1;
        color: #0c5460;
        border: 2px solid #bee5eb;
    }

    .status-verified-as-true {
        background: #d4edda;
        color: #155724;
        border: 2px solid #c3e6cb;
    }

    .status-identified-as-fake {
        background: #f8d7da;
        color: #721c24;
        border: 2px solid #f5c6cb;
    }    .status-rejected {
        background: #e2e3e5;
        color: #383d41;
        border: 2px solid #d6d8db;
    }

    .status-rejected-by-agency {
        background: #f8d7da;
        color: #721c24;
        border: 2px solid #f5c6cb;
    }

    .status-resolved {
        background: #d1ecf1;
        color: #0c5460;
        border: 2px solid #bee5eb;
    }    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 2px solid #ffeaa7;
    }    .status-submitted {
        background: #e2e3e5;
        color: #383d41;
        border: 2px solid #d6d8db;
    }

    .status-discarded {
        background: #f8d7da;
        color: #721c24;
        border: 2px solid #f5c6cb;
    }

    /* View Details Button */
    .btn-info {
        background: linear-gradient(45deg, #17a2b8 0%, #138496 100%);
        color: white;
        font-size: 0.9rem;
        padding: 8px 16px;
    }

    .btn-info:hover {
        background: linear-gradient(45deg, #138496 0%, #17a2b8 100%);
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        color: #6c757d;
        font-style: italic;
        padding: 60px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .inquiry-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .inquiry-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .stats-summary {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1 class="page-title">📊 Monitor Progress</h1>

    <!-- Statistics Summary -->
    <div class="stats-summary">
        <div class="stat-card">
            <span class="stat-number">{{ $totalInquiries ?? 0 }}</span>
            <span class="stat-label">Total Inquiries</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $underInvestigation ?? 0 }}</span>
            <span class="stat-label">Under Investigation</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $verifiedAsTrue ?? 0 }}</span>
            <span class="stat-label">Verified as True</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $identifiedAsFake ?? 0 }}</span>
            <span class="stat-label">Identified as Fake</span>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('mcmc.progress.monitor') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="status">Filter by Status</label>                    <select class="form-control" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="Submitted" {{ ($currentFilters['status'] ?? '') === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="Under Review" {{ ($currentFilters['status'] ?? '') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                        <option value="Under Investigation" {{ ($currentFilters['status'] ?? '') === 'Under Investigation' ? 'selected' : '' }}>Under Investigation</option>
                        <option value="Verified as True" {{ ($currentFilters['status'] ?? '') === 'Verified as True' ? 'selected' : '' }}>Verified as True</option>
                        <option value="Identified as Fake" {{ ($currentFilters['status'] ?? '') === 'Identified as Fake' ? 'selected' : '' }}>Identified as Fake</option>
                        <option value="Rejected by Agency" {{ ($currentFilters['status'] ?? '') === 'Rejected by Agency' ? 'selected' : '' }}>Rejected by Agency</option>
                        <option value="Discarded" {{ ($currentFilters['status'] ?? '') === 'Discarded' ? 'selected' : '' }}>Discarded</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="agency">Filter by Agency</label>
                    <select class="form-control" id="agency" name="agency">
                        <option value="">All Agencies</option>
                        @if(isset($agencies))
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->AgencyID }}" {{ ($currentFilters['agency'] ?? '') == $agency->AgencyID ? 'selected' : '' }}>
                                    {{ $agency->AgencyName }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Search by title, description, or ID..." 
                           value="{{ $currentFilters['search'] ?? '' }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Inquiry List -->
    <div class="inquiry-list">
        @if(isset($inquiries) && $inquiries->count() > 0)
            @foreach($inquiries as $inquiry)
                <div class="inquiry-card">
                    <div class="inquiry-header">
                        <div class="inquiry-info">
                            <div class="inquiry-id">{{ $inquiry['id'] ?? 'N/A' }}</div>
                            <span class="inquiry-date">Last Updated: {{ $inquiry['lastUpdated'] ?? 'N/A' }}</span>
                        </div>
                        <h3 class="inquiry-title">{{ $inquiry['title'] ?? 'No Title' }}</h3>
                    </div>
                    <p class="inquiry-content">{{ Str::limit($inquiry['description'] ?? 'No description available', 150) }}</p>
                    <div class="inquiry-meta">
                        <div class="meta-item">
                            <span class="meta-label">Agency:</span> {{ $inquiry['agency'] ?? 'Unknown' }}
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Submitted By:</span> {{ $inquiry['submittedBy'] ?? 'Unknown' }}
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Submitted Date:</span> {{ $inquiry['submittedDate'] ?? 'N/A' }}
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Priority:</span> {{ $inquiry['priority'] ?? 'Normal' }}
                        </div>
                    </div>
                    <div class="inquiry-actions">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry['status'] ?? 'pending')) }}">
                            {{ $inquiry['status'] ?? 'Pending' }}
                        </span>
                        <button type="button" class="btn btn-info" onclick="viewInquiryDetails({{ $inquiry['InquiryID'] ?? 0 }})">
                            View Details
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                @if(isset($currentFilters) && ($currentFilters['status'] || $currentFilters['agency'] || $currentFilters['search']))
                    <h3>No inquiries found matching your filters</h3>
                    <p>Try adjusting your search criteria or clear the filters to see all inquiries.</p>
                    <a href="{{ route('mcmc.progress.monitor') }}" class="btn btn-secondary">Clear Filters</a>
                @else
                    <h3>No assigned inquiries found</h3>
                    <p>There are currently no inquiries assigned to agencies for tracking.</p>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Inquiry Details Modal (optional for future enhancement) -->
<div id="inquiryModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modalContent">
            <!-- Inquiry details will be loaded here via AJAX -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewInquiryDetails(inquiryId) {
    // Simple alert for now - can be enhanced with modal later
    alert('Viewing details for inquiry ID: ' + inquiryId + '\n\nThis feature can be enhanced with a detailed modal view.');
    
    // Future enhancement: Load inquiry details via AJAX
    /*
    fetch(`/mcmc/progress/inquiry/${inquiryId}/details`)
        .then(response => response.json())
        .then(data => {
            // Display in modal
            console.log(data);
        })
        .catch(error => {
            console.error('Error loading inquiry details:', error);
        });
    */
}
</script>
@endsection
