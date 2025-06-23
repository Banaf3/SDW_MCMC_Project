@extends('layouts.app')

@section('title', 'Update Progress - Agency Portal')

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
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #2a5298, #1e3c72);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.25);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #20c997, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
        }

        /* Inquiry Cards */
        .inquiries-container {
            display: grid;
            gap: 25px;
        }

        .inquiry-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .inquiry-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(30, 60, 114, 0.15);
            border-color: #2a5298;
        }

        .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .inquiry-id {
            font-family: 'Courier New', monospace;
            background: #1e3c72;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .inquiry-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .inquiry-title {
            font-size: 1.3rem;
            color: #1e3c72;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .inquiry-content {
            color: #555;
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
        }

        .status-under-investigation {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }        .status-verified-as-true {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .status-identified-as-fake {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .status-rejected {
            background: #e2e3e5;
            color: #383d41;
            border: 2px solid #d6d8db;
        }

        .status-resolved {
            background: #d1ecf1;
            color: #0c5460;
            border: 2px solid #bee5eb;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-area {
                margin-left: 0;
                padding: 10px;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

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

            .header-container {
                padding: 0 1rem;
            }
        }

        @media (max-width: 480px) {
            .stats-summary {
                grid-template-columns: 1fr;
            }
        }
</style>
@endsection

@section('content')
<h1 class="page-title">Update Progress</h1>

                <!-- Success/Info Messages -->
                @if(session('success'))
                    <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #c3e6cb;">
                        {{ session('success') }}
                    </div>
                @endif

                @if(isset($currentFilters) && ($currentFilters['status'] || $currentFilters['search']))
                    <div class="alert alert-info" style="background: #d1ecf1; color: #0c5460; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #bee5eb;">
                        <strong>Filters Applied:</strong>
                        @if($currentFilters['status'])
                            Status: "{{ $currentFilters['status'] }}"
                        @endif
                        @if($currentFilters['search'])
                            @if($currentFilters['status']) | @endif
                            Search: "{{ $currentFilters['search'] }}"
                        @endif
                        - Showing {{ $filteredCount ?? 0 }} of {{ $totalInquiries ?? 0 }} inquiries
                    </div>
                @endif<!-- Total Inquiries Summary -->
                <div class="stats-summary">
                    <div class="stat-card">
                        <span class="stat-number">{{ $totalInquiries ?? 0 }}</span>
                        <span class="stat-label">Total Assigned</span>
                    </div>
                    @if(isset($filteredCount) && $filteredCount != $totalInquiries)
                    <div class="stat-card">
                        <span class="stat-number">{{ $filteredCount }}</span>
                        <span class="stat-label">Filtered Results</span>
                    </div>
                    @endif
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <form method="GET" action="{{ route('agency.inquiries.assigned') }}">
                        <div class="filter-grid">
                            <div class="form-group">
                                <label for="search">Search Inquiries</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ $currentFilters['search'] ?? '' }}" 
                                       placeholder="Search by ID, subject, or content...">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="Under Investigation" {{ (isset($currentFilters['status']) && $currentFilters['status'] == 'Under Investigation') ? 'selected' : '' }}>Under Investigation</option>
                                    <option value="Verified as True" {{ (isset($currentFilters['status']) && $currentFilters['status'] == 'Verified as True') ? 'selected' : '' }}>Verified as True</option>
                                    <option value="Identified as Fake" {{ (isset($currentFilters['status']) && $currentFilters['status'] == 'Identified as Fake') ? 'selected' : '' }}>Identified as Fake</option>
                                    <option value="Rejected" {{ (isset($currentFilters['status']) && $currentFilters['status'] == 'Rejected') ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                            </div>
                        </div>
                        <div style="margin-top: 15px;">
                            <a href="{{ route('agency.inquiries.assigned') }}" class="btn btn-secondary">Clear All Filters</a>
                        </div>
                    </form>
                </div>                <!-- Inquiries List -->
                <div class="inquiries-container" id="inquiriesList">
                    @if(isset($inquiries) && count($inquiries) > 0)
                        @foreach($inquiries as $inquiry)
                        <div class="inquiry-card" data-status="{{ strtolower(str_replace(' ', '-', $inquiry['status'] ?? 'pending')) }}" data-date="{{ $inquiry['submittedDate'] ?? '' }}">
                            <div class="inquiry-header">
                                <span class="inquiry-id">ID: {{ $inquiry['InquiryID'] ?? 'N/A' }}</span>
                                <span class="inquiry-date">Assigned: {{ $inquiry['assignedDate'] ?? $inquiry['submittedDate'] ?? 'N/A' }}</span>
                            </div>
                            <h3 class="inquiry-title">{{ $inquiry['title'] ?? 'No Title' }}</h3>
                            <p class="inquiry-content">{{ Str::limit($inquiry['description'] ?? 'No description available', 150) }}</p>
                            <div class="inquiry-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Submitted By:</span> {{ $inquiry['submittedBy'] ?? 'Unknown' }}
                                </div>
                            </div>                            <div class="inquiry-actions">
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry['status'] ?? 'pending')) }}">{{ $inquiry['status'] ?? 'Pending' }}</span>
                                <a href="{{ route('agency.inquiry.edit', $inquiry['InquiryID'] ?? 1) }}" class="btn btn-success">Update Progress</a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="inquiry-card">
                            <div class="inquiry-content" style="text-align: center; color: #6c757d; font-style: italic;">
                                @if(isset($currentFilters) && ($currentFilters['status'] || $currentFilters['search']))
                                    No inquiries match your current filters. <a href="{{ route('agency.inquiries.assigned') }}" style="color: #2a5298;">Clear filters</a> to see all inquiries.
                                @else
                                    No inquiries assigned to this agency yet.
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
@endsection