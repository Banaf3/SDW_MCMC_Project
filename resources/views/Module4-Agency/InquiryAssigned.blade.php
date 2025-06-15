<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriTrack - Assigned Inquiries</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            color: #333;
        }

        /* Main Container */
        .main-container { 
            display: flex; 
            min-height: 100vh;
            padding-top: 80px; /* Account for fixed header */
        }

        /* Content Area */
        .content-area {
            flex: 1;
            margin-left: 250px; /* Account for sidebar width */
            padding: 20px;
        }

        /* Header Styles */
        .header { 
            background: linear-gradient(135deg, #4c6ef5 0%, #45c649 100%);
            color: white; 
            padding: 0.75rem 0; 
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-container { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            max-width: 100%; 
            margin: 0 auto; 
            padding: 0 2rem; 
        }
        
        .logo h1 { 
            font-size: 1.5rem; 
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        
        /* Header User Info */
        .header-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .header-user-info .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .user-details .user-name {
            font-weight: 600;
            margin-bottom: 0.125rem;
            color: white;
            font-size: 0.9rem;
        }
        
        .user-details .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Agency Sidebar Styles */        
        .sidebar { 
            width: 250px; 
            background: linear-gradient(180deg, #495057 0%, #343a40 100%);
            color: white; 
            padding: 1.5rem 0;
            position: fixed;
            left: 0;
            top: 60px;
            bottom: 0;
            overflow-y: auto;
            z-index: 999;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-section h3 {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #adb5bd;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        
        .nav-item .nav-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #e9ecef;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .nav-item .nav-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-arrow {
            transition: transform 0.2s ease;
        }
        
        .nav-item.active .nav-arrow {
            transform: rotate(180deg);
        }
        
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: rgba(0, 0, 0, 0.2);
        }
        
        .submenu.show {
            max-height: 300px;
        }
        
        .submenu li a {
            display: block;
            padding: 0.5rem 3rem;
            color: #ced4da;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .submenu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 3.25rem;
        }

        /* Main Content */
        .main-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

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
</head>
<body>
    <!-- Include Header -->
    @include('layouts.partials.header')
    
    <div class="main-container">
        <!-- Agency Sidebar -->
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-section">
                        <h3>Agency Dashboard</h3>
                    </li>
                    <li class="nav-item">
                        <div class="nav-toggle">
                            <span>📊 Overview</span>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-toggle">
                            <span>📋 Inquiry Management</span>
                            <span class="nav-arrow">▼</span>
                        </div>
                        <ul class="submenu">
                            <li><a href="/agency/inquiries/assigned">Assigned Inquiries</a></li>
                            <li><a href="/agency/inquiries/in-progress">In Progress</a></li>
                            <li><a href="/agency/inquiries/completed">Completed</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <div class="nav-toggle">
                            <span>📈 Reports</span>
                            <span class="nav-arrow">▼</span>
                        </div>
                        <ul class="submenu">
                            <li><a href="/agency/reports/monthly">Monthly Reports</a></li>
                            <li><a href="/agency/reports/performance">Performance</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <div class="nav-toggle">
                            <span>⚙️ Settings</span>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
        
        <div class="content-area">            <!-- Main Content -->
            <div class="main-content">
                <h1 class="page-title">Assigned Inquiries</h1>

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
                                    <span class="meta-label">Type:</span> {{ $inquiry['type'] ?? 'General' }}
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Submitted By:</span> {{ $inquiry['submittedBy'] ?? 'Unknown' }}
                                </div>
                            </div>
                            <div class="inquiry-actions">
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry['status'] ?? 'pending')) }}">{{ $inquiry['status'] ?? 'Pending' }}</span>
                                <a href="/agency/inquiry-edit/{{ $inquiry['InquiryID'] ?? '#' }}" class="btn btn-success">Edit Inquiry</a>
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
            </div>
        </div>
    </div>    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const navToggles = document.querySelectorAll('.nav-toggle');
            
            navToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const navItem = this.parentElement;
                    const submenu = navItem.querySelector('.submenu');
                    
                    if (submenu) {
                        navItem.classList.toggle('active');
                        submenu.classList.toggle('show');
                    }
                });
            });
        });
    </script>
</body>
</html>