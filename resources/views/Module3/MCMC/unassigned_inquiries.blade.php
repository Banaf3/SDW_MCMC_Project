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

        /* Stats Summary */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }        .stat-card:hover {
            border-color: #2a5298;
            transform: translateY(-2px);
        }

        /* Priority-specific stat card styling */
        .stat-card.priority-high {
            border-color: #e74c3c;
            background: linear-gradient(135deg, #fff 0%, #ffebee 100%);
        }

        .stat-card.priority-high:hover {
            border-color: #c0392b;
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        }

        .stat-card.priority-high .stat-number {
            color: #e74c3c;
        }

        .stat-card.priority-medium {
            border-color: #f39c12;
            background: linear-gradient(135deg, #fff 0%, #fff8e1 100%);
        }

        .stat-card.priority-medium:hover {
            border-color: #d68910;
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        }

        .stat-card.priority-medium .stat-number {
            color: #f39c12;
        }

        .stat-card.priority-normal {
            border-color: #27ae60;
            background: linear-gradient(135deg, #fff 0%, #e8f5e8 100%);
        }

        .stat-card.priority-normal:hover {
            border-color: #1e8449;
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
        }        .stat-card.priority-normal .stat-number {
            color: #27ae60;
        }

        /* Filter card styling */
        .filter-card {
            cursor: pointer;
            user-select: none;
        }

        .filter-card.active {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .filter-card:not(.active) {
            opacity: 0.8;
        }        .filter-card:hover:not(.active) {
            opacity: 1;
        }

        /* Empty state for filtered results */
        .empty-state-filter {
            text-align: center;
            padding: 3rem 2rem;
            color: #666;
            background: white;
            border-radius: 12px;
            border: 2px dashed #e9ecef;
        }

        .empty-state-filter .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-state-filter .empty-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .empty-state-filter .empty-message {
            color: #666;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: #1e3c72;
            display: block;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Inquiries Grid */
        .inquiries-grid {
            display: grid;
            gap: 1.5rem;
        }

        .inquiry-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #e74c3c;
            transition: all 0.3s ease;
            position: relative;
        }        .inquiry-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        /* Rejected inquiry styling */
        .inquiry-card.rejected {
            border-left: 4px solid #f39c12;
            background: linear-gradient(135deg, #fff 0%, #fef9e7 100%);
        }

        .inquiry-card.rejected:hover {
            box-shadow: 0 8px 24px rgba(243, 156, 18, 0.2);
        }

        .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            gap: 1rem;
        }

        .inquiry-id {
            background: #2a5298;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            white-space: nowrap;
        }

        .inquiry-date {
            color: #666;
            font-size: 0.9rem;
        }

        .inquiry-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .inquiry-content-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }

        .inquiry-content {
            color: #555;
            line-height: 1.6;
            transition: all 0.4s ease;
            overflow: hidden;
        }

        .inquiry-content.collapsed {
            max-height: 4.8em;
            position: relative;
        }

        .inquiry-content.collapsed::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1.5em;
            background: linear-gradient(transparent, white);
            pointer-events: none;
        }

        .inquiry-content.expanded {
            max-height: none;
        }

        .inquiry-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: all 0.4s ease;
        }        .inquiry-meta.show {
            max-height: 600px; /* Increased for more content */
            opacity: 1;
            margin-top: 1rem;
        }

        /* Meta item styles */
        .meta-item {
            font-size: 0.85rem;
        }

        .meta-label {
            font-weight: 600;
            color: #2a5298;
            display: block;
        }

        .meta-value {
            margin-top: 0.25rem;
            font-size: 0.9rem;
            color: #333;
        }        /* Rejection comment styles */
        .rejection-comment {
            grid-column: 1 / -1; /* Span full width */
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 0.75rem;
            margin-top: 0.5rem;
        }

        .rejection-comment .meta-label {
            color: #856404;
            font-weight: 700;
        }        .rejection-text {
            color: #856404;
            font-style: italic;
            line-height: 1.4;
            margin-top: 0.5rem;
        }

        .rejection-agency {
            background: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        /* Additional meta item styles */
        .evidence-item {
            grid-column: 1 / -1; /* Span full width */
            background: #e8f4fd;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 0.75rem;
            margin-top: 0.5rem;
        }

        .evidence-item .meta-label {
            color: #0066cc;
            font-weight: 700;
        }

        .evidence-file {
            display: inline-block;
            background: #fff;
            padding: 0.25rem 0.5rem;
            margin: 0.125rem;
            border-radius: 4px;
            border: 1px solid #b3d9ff;
            font-size: 0.8rem;
        }

        .evidence-text {
            color: #0066cc;
            font-style: normal;
        }

        .address-item {
            grid-column: 1 / -1; /* Span full width */
        }

        .admin-comment {
            grid-column: 1 / -1; /* Span full width */
            background: #f0f8ff;
            border: 1px solid #add8e6;
            border-radius: 6px;
            padding: 0.75rem;
            margin-top: 0.5rem;
        }

        .admin-comment .meta-label {
            color: #4682b4;
            font-weight: 700;
        }        /* Status and Priority badges */
        .status-pending-review {
            background: #17a2b8;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-rejected-by-agency {
            background: #f39c12;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .priority-high {
            background: #dc3545;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .priority-medium {
            background: #ffc107;
            color: #212529;
            padding: 0.4rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .priority-normal {
            background: #28a745;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        /* Rejection warning in modal */
        .rejection-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 1rem;
            margin: 1rem 0;
            color: #856404;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Action Buttons */
        .inquiry-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            align-items: center;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
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
        }        @media (max-width: 480px) {
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
                <h1 class="page-title">Unassigned Inquiries</h1>

                <!-- Success Message -->
                <div id="successMessage" class="success-message">
                    <strong>Success!</strong> <span id="successText"></span>
                </div>                <!-- Stats Summary -->
                <div class="stats-summary">
                    <div class="stat-card filter-card active" data-priority="all">
                        <span class="stat-number">{{ $totalUnassigned }}</span>
                        <span class="stat-label">All Inquiries</span>
                    </div>
                    <div class="stat-card priority-high filter-card" data-priority="High">
                        <span class="stat-number">{{ $highPriorityCount }}</span>
                        <span class="stat-label">High Priority</span>
                    </div>
                    <div class="stat-card priority-medium filter-card" data-priority="Medium">
                        <span class="stat-number">{{ $mediumPriorityCount }}</span>
                        <span class="stat-label">Medium Priority</span>
                    </div>
                    <div class="stat-card priority-normal filter-card" data-priority="Normal">
                        <span class="stat-number">{{ $normalPriorityCount }}</span>
                        <span class="stat-label">Normal Priority</span>
                    </div>
                </div>

                <!-- Inquiries Grid -->
                <div class="inquiries-grid">
                    @if($unassignedInquiries->count() > 0)
                        @foreach($unassignedInquiries as $inquiry)
                        <div class="inquiry-card{{ $inquiry['isRejected'] ? ' rejected' : '' }}" data-inquiry-id="{{ $inquiry['InquiryID'] }}" data-priority="{{ $inquiry['priority'] }}">
                            <div class="inquiry-header">
                                <span class="inquiry-id">{{ $inquiry['reference_number'] }}</span>
                                <span class="inquiry-date">{{ $inquiry['submittedDate'] }}</span>
                            </div>
                            
                            <h3 class="inquiry-title">{{ $inquiry['title'] }}</h3>
                            
                            <!-- Updated HTML structure for inquiry content -->
                            <div class="inquiry-content-wrapper">
                                <div class="inquiry-content collapsed" id="content-{{ $inquiry['InquiryID'] }}">
                                    {{ $inquiry['description'] }}
                                </div>
                            </div>                            <!-- Updated meta section with ID -->
                            <div class="inquiry-meta" id="meta-{{ $inquiry['InquiryID'] }}">
                                <!-- Basic Information Row -->
                                <div class="meta-item">
                                    <span class="meta-label">Status:</span>
                                    <span class="meta-value status-{{ strtolower(str_replace(' ', '-', $inquiry['status'])) }}">{{ $inquiry['status'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Priority:</span>
                                    <span class="meta-value priority-{{ strtolower($inquiry['priority']) }}">{{ $inquiry['priority'] }}</span>
                                </div>                                <div class="meta-item">
                                    <span class="meta-label">Days Pending:</span>
                                    <span class="meta-value">{{ $inquiry['timePending'] }}</span>
                                </div>
                                
                                <!-- Submitter Information Row -->
                                <div class="meta-item">
                                    <span class="meta-label">Submitted by:</span>
                                    <span class="meta-value">{{ $inquiry['submittedBy'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Contact Email:</span>
                                    <span class="meta-value">{{ $inquiry['submitterEmail'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Phone Number:</span>
                                    <span class="meta-value">{{ $inquiry['submitterPhone'] }}</span>
                                </div>
                                  <!-- Timing Information Row -->
                                <div class="meta-item">
                                    <span class="meta-label">Submitted On:</span>
                                    <span class="meta-value">{{ $inquiry['submittedDateTime'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Assignment:</span>
                                    <span class="meta-value">
                                        @if($inquiry['isRejected'])
                                            Previously Rejected
                                        @else
                                            Not Assigned
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Evidence Information -->
                                @if($inquiry['evidenceData'])
                                <div class="meta-item evidence-item">
                                    <span class="meta-label">Evidence Files:</span>
                                    <div class="meta-value">
                                        @if(isset($inquiry['evidenceData']['files']) && is_array($inquiry['evidenceData']['files']))
                                            @foreach($inquiry['evidenceData']['files'] as $file)
                                                <span class="evidence-file">
                                                    📎 {{ $file['name'] ?? 'Unknown file' }} 
                                                    <small>({{ ucfirst($file['type'] ?? 'file') }})</small>
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="evidence-text">{{ $inquiry['evidence'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                @elseif($inquiry['evidence'])
                                <div class="meta-item evidence-item">
                                    <span class="meta-label">Evidence:</span>
                                    <span class="meta-value evidence-text">{{ $inquiry['evidence'] }}</span>
                                </div>
                                @endif
                                
                                <!-- Address Information -->
                                @if($inquiry['submitterAddress'] && $inquiry['submitterAddress'] !== 'N/A')
                                <div class="meta-item address-item">
                                    <span class="meta-label">Submitter Address:</span>
                                    <span class="meta-value">{{ $inquiry['submitterAddress'] }}</span>
                                </div>
                                @endif
                                
                                <!-- Admin Comments if any -->
                                @if($inquiry['adminComment'])
                                <div class="meta-item admin-comment">
                                    <span class="meta-label">Admin Notes:</span>
                                    <span class="meta-value">{{ $inquiry['adminComment'] }}</span>
                                </div>
                                @endif
                                  <!-- Rejection Comments for previously rejected inquiries -->
                                @if($inquiry['isRejected'] && $inquiry['rejectionData'])
                                <div class="meta-item rejection-comment">
                                    <span class="meta-label">Rejected by:</span>
                                    <span class="meta-value rejection-agency">{{ $inquiry['rejectedByAgency'] }}</span>
                                    <br>
                                    <span class="meta-label">Rejection Date:</span>
                                    <span class="meta-value">{{ isset($inquiry['rejectionData']['rejection_date']) ? \Carbon\Carbon::parse($inquiry['rejectionData']['rejection_date'])->format('F j, Y g:i A') : 'N/A' }}</span>
                                    <br>
                                    <span class="meta-label">Reason:</span>
                                    <span class="meta-value rejection-text">{{ $inquiry['rejectionData']['rejection_comments'] ?? 'No specific reason provided' }}</span>
                                </div>
                                @elseif($inquiry['isRejected'] && !empty($inquiry['rejectionComment']))
                                <div class="meta-item rejection-comment">
                                    <span class="meta-label">Rejection Reason:</span>
                                    <span class="meta-value rejection-text">{{ $inquiry['rejectionComment'] }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="inquiry-actions">
                                <button class="btn btn-view" onclick="toggleContent({{ $inquiry['InquiryID'] }})">
                                    <span>📄</span> View More
                                </button>                                <button class="btn btn-assign" onclick="openAssignModal({{ $inquiry['InquiryID'] }}, '{{ $inquiry['rejectedByAgencyId'] ?? '' }}', '{{ addslashes($inquiry['rejectedByAgency'] ?? '') }}')">
                                    <span>📋</span> Assign to Agency
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
                    <label for="assignmentComments" class="form-label">Assignment Comments (Optional)</label>
                    <textarea id="assignmentComments" class="form-textarea" 
                        placeholder="Add any notes or special instructions for the assigned agency..."></textarea>
                </div>
                
                <div class="inquiry-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAssignModal()">Cancel</button>
                    <button type="submit" class="btn btn-submit">
                        <span>✓</span> Assign Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentInquiryId = null;

        function toggleContent(inquiryId) {
            const content = document.getElementById(`content-${inquiryId}`);
            const meta = document.getElementById(`meta-${inquiryId}`);
            const button = event.target.closest('.btn-view');
            const card = document.querySelector(`[data-inquiry-id="${inquiryId}"]`);

            if (content.classList.contains('collapsed')) {
                // Expand content
                content.classList.remove('collapsed');
                content.classList.add('expanded');
                if (meta) meta.classList.add('show');
                button.innerHTML = '<span>📄</span> View Less';
                button.classList.add('expanded');
                if (card) card.classList.add('expanded');
            } else {
                // Collapse content
                content.classList.remove('expanded');
                content.classList.add('collapsed');
                if (meta) meta.classList.remove('show');
                button.innerHTML = '<span>📄</span> View More';
                button.classList.remove('expanded');
                if (card) card.classList.remove('expanded');            }
        }

        function openAssignModal(inquiryId, rejectedAgencyId = '', rejectedAgencyName = '') {
            currentInquiryId = inquiryId;
            
            // Clear any previous warnings
            const existingWarning = document.getElementById('rejectionWarning');
            if (existingWarning) {
                existingWarning.remove();
            }
            
            // Add warning if this inquiry was previously rejected
            if (rejectedAgencyId && rejectedAgencyName) {
                const modalContent = document.querySelector('#assignModal .modal-content');
                const warningDiv = document.createElement('div');
                warningDiv.id = 'rejectionWarning';
                warningDiv.className = 'rejection-warning';
                warningDiv.innerHTML = `
                    <strong>⚠️ Warning:</strong> This inquiry was previously rejected by <strong>${rejectedAgencyName}</strong>. 
                    Consider assigning to a different agency.
                `;
                
                // Insert warning after modal header
                const modalHeader = modalContent.querySelector('.modal-header');
                modalHeader.insertAdjacentElement('afterend', warningDiv);
                
                // Highlight the rejected agency in the dropdown
                const agencySelect = document.getElementById('agencySelect');
                Array.from(agencySelect.options).forEach(option => {
                    if (option.value === rejectedAgencyId) {
                        option.style.backgroundColor = '#ffebee';
                        option.style.color = '#c62828';
                        option.text = option.text + ' (Previously Rejected)';
                    }
                });
            }
            
            document.getElementById('assignModal').classList.add('show');
        }        function closeAssignModal() {
            // Remove warning if it exists
            const existingWarning = document.getElementById('rejectionWarning');
            if (existingWarning) {
                existingWarning.remove();
            }
            
            // Reset agency dropdown to original state
            const agencySelect = document.getElementById('agencySelect');
            Array.from(agencySelect.options).forEach(option => {
                if (option.text.includes('(Previously Rejected)')) {
                    option.text = option.text.replace(' (Previously Rejected)', '');
                    option.style.backgroundColor = '';
                    option.style.color = '';
                }
            });
            
            document.getElementById('assignModal').classList.remove('show');
            document.getElementById('assignForm').reset();
            currentInquiryId = null;
        }

        // Handle assignment form submission
        document.getElementById('assignForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const agencyId = document.getElementById('agencySelect').value;
            const comments = document.getElementById('assignmentComments').value;
            
            if (!agencyId) {
                alert('Please select an agency');
                return;
            }
            
            try {
                const response = await fetch(`/mcmc/inquiries/${currentInquiryId}/assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        agency_id: agencyId,
                        comments: comments
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    document.getElementById('successText').textContent = result.message;
                    document.getElementById('successMessage').classList.add('show');                    // Remove the assigned inquiry card from the UI
                    const inquiryCard = document.querySelector(`[data-inquiry-id="${currentInquiryId}"]`);
                    if (inquiryCard) {
                        // Get the priority of the removed inquiry for stats update
                        const priorityClass = inquiryCard.getAttribute('data-priority') || 'Normal';
                        
                        inquiryCard.remove();
                        
                        // Update total count
                        const totalStatNumber = document.querySelector('.stat-card:first-child .stat-number');
                        if (totalStatNumber) {
                            const currentCount = parseInt(totalStatNumber.textContent);
                            totalStatNumber.textContent = Math.max(0, currentCount - 1);
                        }
                        
                        // Update priority-specific count
                        let prioritySelector = '';
                        if (priorityClass === 'High') {
                            prioritySelector = '.stat-card.priority-high .stat-number';
                        } else if (priorityClass === 'Medium') {
                            prioritySelector = '.stat-card.priority-medium .stat-number';
                        } else {
                            prioritySelector = '.stat-card.priority-normal .stat-number';
                        }
                        
                        const priorityStatNumber = document.querySelector(prioritySelector);
                        if (priorityStatNumber) {
                            const currentPriorityCount = parseInt(priorityStatNumber.textContent);
                            priorityStatNumber.textContent = Math.max(0, currentPriorityCount - 1);
                        }
                    }
                    
                    // Close modal
                    closeAssignModal();
                    
                    // Hide success message after 5 seconds
                    setTimeout(() => {
                        document.getElementById('successMessage').classList.remove('show');
                    }, 5000);
                    
                    // Check if no more inquiries and show empty state
                    const remainingCards = document.querySelectorAll('.inquiry-card').length;
                    if (remainingCards === 0) {
                        document.querySelector('.inquiries-grid').innerHTML = `
                            <div class="empty-state">
                                <div class="empty-icon">✅</div>
                                <h3 class="empty-title">All Inquiries Assigned</h3>
                                <p class="empty-message">Great! All inquiries have been assigned to agencies. New unassigned inquiries will appear here.</p>
                            </div>
                        `;
                    }
                    
                } else {
                    alert('Error: ' + result.message);
                }
                
            } catch (error) {
                console.error('Assignment error:', error);
                alert('An error occurred while assigning the inquiry');
            }
        });        // Close modal when clicking outside
        document.getElementById('assignModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssignModal();
            }
        });

        // Sidebar functionality
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const navItem = submenu.closest('.nav-item');
            
            if (submenu.classList.contains('show')) {
                submenu.classList.remove('show');
                navItem.classList.remove('active');
            } else {
                // Close all other submenus
                document.querySelectorAll('.submenu.show').forEach(menu => {
                    menu.classList.remove('show');
                    menu.closest('.nav-item').classList.remove('active');
                });
                
                submenu.classList.add('show');
                navItem.classList.add('active');
            }        }

        // Close dropdown when clicking outside (for header dropdown)
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.header-user-info');
            const dropdown = document.getElementById('userDropdown');
            
            if (dropdown && userInfo && !userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Priority filtering functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterCards = document.querySelectorAll('.filter-card');
            const inquiryCards = document.querySelectorAll('.inquiry-card');

            // Add click event listeners to filter cards
            filterCards.forEach(card => {
                card.addEventListener('click', function() {
                    const priority = this.getAttribute('data-priority');
                    
                    // Update active state
                    filterCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter inquiry cards
                    filterInquiries(priority);
                });
            });

            function filterInquiries(priority) {
                inquiryCards.forEach(card => {
                    const cardPriority = card.getAttribute('data-priority');
                    
                    if (priority === 'all' || cardPriority === priority) {
                        card.style.display = 'block';
                        // Add a small animation
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.style.opacity = '1';
                        }, 50);
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Check if no inquiries are visible after filtering
                const visibleCards = Array.from(inquiryCards).filter(card => 
                    card.style.display !== 'none'
                );
                
                const inquiriesGrid = document.querySelector('.inquiries-grid');
                const existingEmpty = inquiriesGrid.querySelector('.empty-state-filter');
                
                if (visibleCards.length === 0) {
                    if (!existingEmpty) {
                        const priorityLabel = priority === 'all' ? 'inquiries' : 
                                            priority.toLowerCase() + ' priority inquiries';
                        inquiriesGrid.insertAdjacentHTML('beforeend', `
                            <div class="empty-state-filter">
                                <div class="empty-icon">🔍</div>
                                <h3 class="empty-title">No ${priorityLabel} found</h3>
                                <p class="empty-message">There are currently no ${priorityLabel} to display.</p>
                            </div>
                        `);
                    }
                } else {
                    if (existingEmpty) {
                        existingEmpty.remove();
                    }
                }
            }
        });
    </script>
</body>
</html>