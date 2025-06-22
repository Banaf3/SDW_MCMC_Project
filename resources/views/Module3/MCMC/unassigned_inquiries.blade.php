<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriTrack - Unassigned Inquiries</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Statistics Cards */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .stat-card.priority-high {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        }

        .stat-card.priority-medium {
            background: linear-gradient(135deg, #feca57 0%, #ff9ff3 100%);
        }

        .stat-card.priority-normal {
            background: linear-gradient(135deg, #48dbfb 0%, #0abde3 100%);
        }

        .stat-number {
            display: block;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Filters Section */
        .filters-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid #dee2e6;
        }

        .filters-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2a5298;
            margin-bottom: 1rem;
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

        .filter-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .filter-select, .filter-input {
            padding: 0.5rem;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: #2a5298;
        }

        .filter-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-filter {
            padding: 0.5rem 1rem;
            background: #2a5298;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background-color 0.3s ease;
        }

        .btn-filter:hover {
            background: #1e3c72;
        }

        .btn-clear {
            padding: 0.5rem 1rem;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background-color 0.3s ease;
        }

        .btn-clear:hover {
            background: #545b62;
        }

        /* Inquiries Grid */
        .inquiries-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .inquiry-card {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
        }

        .inquiry-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #2a5298;
        }

        .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .inquiry-id {
            font-weight: 600;
            color: #2a5298;
            font-size: 0.9rem;
        }

        .inquiry-date {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .priority-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
        }

        .priority-high {
            background: #e74c3c;
        }

        .priority-medium {
            background: #f39c12;
        }

        .priority-normal {
            background: #3498db;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 0.5rem;
        }

        .status-rejected {
            background: #ffebee;
            color: #c62828;
        }

        .status-pending {
            background: #fff3e0;
            color: #ef6c00;
        }

        .inquiry-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .inquiry-content {
            color: #5a6c7d;
            line-height: 1.6;
            margin-bottom: 1rem;
            max-height: 60px;
            overflow: hidden;
            position: relative;
        }

        .inquiry-content.expanded {
            max-height: none;
        }

        .inquiry-content.collapsed::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            background: linear-gradient(transparent, white);
        }

        .inquiry-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .meta-item {
            font-size: 0.8rem;
        }

        .meta-label {
            font-weight: 600;
            color: #2a5298;
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-value {
            margin-top: 0.25rem;
            font-size: 0.85rem;
            color: #333;
            font-weight: 500;
        }

        .time-pending {
            color: #e74c3c;
            font-weight: 600;
        }

        /* Action Buttons */
        .inquiry-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            align-items: center;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view {
            background: #3498db;
            color: white;
        }

        .btn-view:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }

        .btn-assign {
            background: #27ae60;
            color: white;
        }

        .btn-assign:hover {
            background: #229954;
            transform: translateY(-1px);
        }

        .btn-details {
            background: #8e44ad;
            color: white;
        }

        .btn-details:hover {
            background: #7d3c98;
            transform: translateY(-1px);
        }

        /* Assignment Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2a5298;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.25rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #2a5298;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #545b62;
        }

        .btn-submit {
            background: #28a745;
            color: white;
        }

        .btn-submit:hover {
            background: #218838;
        }

        /* Success Message */
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid #c3e6cb;
            display: none;
        }

        .success-message.show {
            display: block;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
            grid-column: 1 / -1;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .empty-message {
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-area {
                margin-left: 0;
                padding: 10px;
            }

            .stats-summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .inquiries-grid {
                grid-template-columns: 1fr;
            }

            .inquiry-meta {
                grid-template-columns: 1fr;
            }

            .inquiry-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .modal-content {
                margin: 10% auto;
                width: 95%;
                padding: 1.5rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats-summary {
                grid-template-columns: 1fr;
            }
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
            cursor: pointer;
            position: relative;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
        }

        .header-user-info:hover {
            background-color: rgba(255, 255, 255, 0.1);
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

        .dropdown-arrow {
            margin-left: 0.5rem;
            transition: transform 0.2s;
        }

        .header-user-info:hover .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Notification Styles */
        .notification-container {
            position: relative;
            cursor: pointer;
        }

        .notification-bell {
            position: relative;
            padding: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transition: background 0.3s ease;
        }

        .notification-bell:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Sidebar Styles */        
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

        .submenu li a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-left: 3px solid #4c6ef5;
        }
    </style>
</head>
<body>

    <!-- Include Header -->
    @include('layouts.partials.header')
    
    <div class="main-container">
        <!-- Include Sidebar -->
        @include('layouts.partials.sidebar')
        
        <div class="content-area">
            <!-- Main Content -->
            <div class="main-content">
                <h1 class="page-title">📋 Unassigned Inquiries Management</h1>

                <!-- Success Message -->
                <div id="successMessage" class="success-message">
                    <strong>Success!</strong> <span id="successText"></span>
                </div>

                <!-- Enhanced Priority-Based Stats Summary -->
                <div class="stats-summary">
                    <div class="stat-card">
                        <span class="stat-number">{{ $totalUnassigned }}</span>
                        <span class="stat-label">📊 Total Unassigned</span>
                    </div>
                    <div class="stat-card priority-high">
                        <span class="stat-number">{{ $highPriorityCount }}</span>
                        <span class="stat-label">🔥 High Priority (7+ days)</span>
                    </div>
                    <div class="stat-card priority-medium">
                        <span class="stat-number">{{ $mediumPriorityCount }}</span>
                        <span class="stat-label">⚡ Medium Priority (3-7 days)</span>
                    </div>
                    <div class="stat-card priority-normal">
                        <span class="stat-number">{{ $normalPriorityCount }}</span>
                        <span class="stat-label">✅ Normal Priority (0-3 days)</span>
                    </div>
                </div>

                <!-- Enhanced Filters Section -->
                <div class="filters-section">
                    <h3 class="filters-title">🔍 Filter & Search Inquiries</h3>
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label class="filter-label">Priority Level</label>
                            <select id="priorityFilter" class="filter-select">
                                <option value="">All Priorities</option>
                                <option value="High">High Priority (7+ days)</option>
                                <option value="Medium">Medium Priority (3-7 days)</option>
                                <option value="Normal">Normal Priority (0-3 days)</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select id="statusFilter" class="filter-select">
                                <option value="">All Statuses</option>
                                <option value="Submitted">Submitted</option>
                                <option value="Rejected by Agency">Rejected by Agency</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Search Text</label>
                            <input type="text" id="searchFilter" class="filter-input" placeholder="Search title, description, or user...">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Actions</label>
                            <div class="filter-actions">
                                <button class="btn-filter" onclick="applyFilters()">Filter</button>
                                <button class="btn-clear" onclick="clearFilters()">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Inquiries Grid -->
                <div class="inquiries-grid" id="inquiriesGrid">
                    @if($unassignedInquiries->count() > 0)
                        @foreach($unassignedInquiries as $inquiry)
                        <div class="inquiry-card" 
                             data-inquiry-id="{{ $inquiry['InquiryID'] }}"
                             data-priority="{{ $inquiry['priority'] }}"
                             data-status="{{ $inquiry['status'] }}"
                             data-search-text="{{ strtolower($inquiry['title'] . ' ' . $inquiry['description'] . ' ' . $inquiry['submittedBy']) }}">
                            
                            <!-- Priority Badge -->
                            <div class="priority-badge priority-{{ strtolower($inquiry['priority']) }}">
                                {{ $inquiry['priority'] }} Priority
                            </div>

                            <div class="inquiry-header">
                                <span class="inquiry-id">{{ $inquiry['reference_number'] }}</span>
                                <span class="inquiry-date">{{ $inquiry['submittedDateTime'] }}</span>
                                @if($inquiry['isRejected'])
                                    <span class="status-badge status-rejected">Rejected</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </div>
                            
                            <h3 class="inquiry-title">{{ $inquiry['title'] }}</h3>
                            
                            <div class="inquiry-content collapsed" id="content-{{ $inquiry['InquiryID'] }}">
                                {{ $inquiry['description'] }}
                            </div>
                            
                            <!-- Enhanced Meta Information -->
                            <div class="inquiry-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Time Pending</span>
                                    <span class="meta-value time-pending">{{ $inquiry['timePending'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Submitted by</span>
                                    <span class="meta-value">{{ $inquiry['submittedBy'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Status</span>
                                    <span class="meta-value">{{ $inquiry['status'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Priority</span>
                                    <span class="meta-value">{{ $inquiry['priority'] }}</span>
                                </div>
                                @if($inquiry['isRejected'] && $inquiry['rejectedByAgency'])
                                <div class="meta-item">
                                    <span class="meta-label">Rejected by</span>
                                    <span class="meta-value">{{ $inquiry['rejectedByAgency'] }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="inquiry-actions">
                                <button class="btn btn-view" onclick="toggleContent({{ $inquiry['InquiryID'] }})">
                                    <span>👁️</span> <span id="btn-text-{{ $inquiry['InquiryID'] }}">View More</span>
                                </button>
                                <button class="btn btn-details" onclick="openDetailsModal({{ $inquiry['InquiryID'] }})">
                                    <span>📋</span> Details
                                </button>
                                <button class="btn btn-assign" onclick="openAssignModal({{ $inquiry['InquiryID'] }})">
                                    <span>✅</span> Assign
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">✅</div>
                            <h3 class="empty-title">All Inquiries Assigned</h3>
                            <p class="empty-message">Great! All inquiries have been assigned to agencies. New unassigned inquiries will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Assignment Modal -->
    <div id="assignModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Assign Inquiry to Agency</h2>
                <button class="close-modal" onclick="closeAssignModal()">&times;</button>
            </div>
            
            <form id="assignForm">
                <div class="form-group">
                    <label for="agencySelect" class="form-label">Select Agency</label>
                    <select id="agencySelect" class="form-select" required>
                        <option value="">Choose an agency...</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->AgencyID }}">{{ $agency->AgencyName }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="assignComments" class="form-label">Comments (Optional)</label>
                    <textarea id="assignComments" class="form-textarea" placeholder="Add any additional notes or comments..."></textarea>
                </div>
                
                <div class="inquiry-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAssignModal()">Cancel</button>
                    <button type="submit" class="btn btn-submit">Assign Inquiry</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Inquiry Details</h2>
                <button class="close-modal" onclick="closeDetailsModal()">&times;</button>
            </div>
            
            <div id="detailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        // Store all inquiries data for filtering
        const allInquiries = @json($unassignedInquiries);
        let currentAssignInquiryId = null;

        // Toggle inquiry content
        function toggleContent(inquiryId) {
            const content = document.getElementById(`content-${inquiryId}`);
            const btnText = document.getElementById(`btn-text-${inquiryId}`);
            
            if (content.classList.contains('collapsed')) {
                content.classList.remove('collapsed');
                content.classList.add('expanded');
                btnText.textContent = 'View Less';
            } else {
                content.classList.add('collapsed');
                content.classList.remove('expanded');
                btnText.textContent = 'View More';
            }
        }

        // Open assignment modal
        function openAssignModal(inquiryId) {
            currentAssignInquiryId = inquiryId;
            document.getElementById('assignModal').classList.add('show');
        }

        // Close assignment modal
        function closeAssignModal() {
            document.getElementById('assignModal').classList.remove('show');
            document.getElementById('assignForm').reset();
            currentAssignInquiryId = null;
        }

        // Open details modal
        function openDetailsModal(inquiryId) {
            const inquiry = allInquiries.find(i => i.InquiryID === inquiryId);
            if (!inquiry) return;

            const content = `
                <div class="inquiry-meta">
                    <div class="meta-item">
                        <span class="meta-label">Reference Number</span>
                        <span class="meta-value">${inquiry.reference_number}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Title</span>
                        <span class="meta-value">${inquiry.title}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <span class="meta-value">${inquiry.status}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Priority</span>
                        <span class="meta-value">${inquiry.priority}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Submitted Date</span>
                        <span class="meta-value">${inquiry.submittedDateTime}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Time Pending</span>
                        <span class="meta-value time-pending">${inquiry.timePending}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Submitted By</span>
                        <span class="meta-value">${inquiry.submittedBy}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Email</span>
                        <span class="meta-value">${inquiry.submitterEmail}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Phone</span>
                        <span class="meta-value">${inquiry.submitterPhone}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Address</span>
                        <span class="meta-value">${inquiry.submitterAddress}</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                        ${inquiry.description}
                    </div>
                </div>

                ${inquiry.evidenceData ? `
                <div class="form-group">
                    <label class="form-label">Evidence Submitted</label>
                    <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                        <pre>${JSON.stringify(inquiry.evidenceData, null, 2)}</pre>
                    </div>
                </div>
                ` : ''}

                ${inquiry.isRejected && inquiry.rejectionData ? `
                <div class="form-group">
                    <label class="form-label">Rejection Details</label>
                    <div style="padding: 1rem; background: #ffebee; border-radius: 8px; border: 1px solid #ffcdd2;">
                        <strong>Rejected by:</strong> ${inquiry.rejectedByAgency}<br>
                        <strong>Reason:</strong> ${inquiry.rejectionData.reason || 'No reason provided'}
                    </div>
                </div>
                ` : ''}
            `;

            document.getElementById('detailsContent').innerHTML = content;
            document.getElementById('detailsModal').classList.add('show');
        }

        // Close details modal
        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.remove('show');
        }

        // Apply filters
        function applyFilters() {
            const priorityFilter = document.getElementById('priorityFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const searchFilter = document.getElementById('searchFilter').value.toLowerCase();

            const cards = document.querySelectorAll('.inquiry-card');
            
            cards.forEach(card => {
                const priority = card.dataset.priority;
                const status = card.dataset.status;
                const searchText = card.dataset.searchText;

                let show = true;

                if (priorityFilter && priority !== priorityFilter) {
                    show = false;
                }

                if (statusFilter && status !== statusFilter) {
                    show = false;
                }

                if (searchFilter && !searchText.includes(searchFilter)) {
                    show = false;
                }

                card.style.display = show ? 'block' : 'none';
            });
        }

        // Clear filters
        function clearFilters() {
            document.getElementById('priorityFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('searchFilter').value = '';
            
            const cards = document.querySelectorAll('.inquiry-card');
            cards.forEach(card => {
                card.style.display = 'block';
            });
        }

        // Handle assignment form submission
        document.getElementById('assignForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentAssignInquiryId) return;

            const agencyId = document.getElementById('agencySelect').value;
            const comments = document.getElementById('assignComments').value;

            if (!agencyId) {
                alert('Please select an agency');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Assigning...';
            submitBtn.disabled = true;

            // Make API call
            fetch(`/mcmc/assign-inquiry/${currentAssignInquiryId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    agency_id: agencyId,
                    comments: comments
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    document.getElementById('successText').textContent = data.message;
                    document.getElementById('successMessage').classList.add('show');
                    
                    // Remove the assigned inquiry card
                    const card = document.querySelector(`[data-inquiry-id="${currentAssignInquiryId}"]`);
                    if (card) {
                        card.remove();
                    }
                    
                    // Close modal
                    closeAssignModal();
                    
                    // Reload page after short delay to update stats
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while assigning the inquiry');
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                closeAssignModal();
                closeDetailsModal();
            }
        });

        // Hide success message after 5 seconds
        setTimeout(() => {
            document.getElementById('successMessage').classList.remove('show');
        }, 5000);

        // Real-time search
        document.getElementById('searchFilter').addEventListener('input', function() {
            if (this.value.length >= 2 || this.value.length === 0) {
                applyFilters();
            }
        });
    </script>

</body>
</html>