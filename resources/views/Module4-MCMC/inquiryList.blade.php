<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriTrack - All Inquiries</title>
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
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            position: relative;
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

        /* Page Title */
        .page-title {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            font-weight: 700;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(30, 60, 114, 0.1);
            border: 1px solid #e9ecef;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 20px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #1e3c72;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
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
        .inquiries-container {
            display: grid;
            gap: 25px;
        }

        .inquiry-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(30, 60, 114, 0.1);
            border: 2px solid #f8f9fa;
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
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-block;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
        }

        .status-under-investigation {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-verified-as-true {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-identified-as-fake {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-rejected {
            background: #e2e3e5;
            color: #383d41;
            border: 2px solid #d6d8db;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        }

        .stat-card:hover {
            border-color: #2a5298;
            transform: translateY(-2px);
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

        /* Status-specific stat cards */
        .stat-card.under-investigation {
            border-color: #ffeaa7;
        }

        .stat-card.under-investigation .stat-number {
            color: #856404;
        }

        .stat-card.verified-true {
            border-color: #c3e6cb;
        }

        .stat-card.verified-true .stat-number {
            color: #155724;
        }

        .stat-card.identified-fake {
            border-color: #f5c6cb;
        }

        .stat-card.identified-fake .stat-number {
            color: #721c24;
        }

        .stat-card.rejected {
            border-color: #d6d8db;
        }

        .stat-card.rejected .stat-number {
            color: #383d41;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-area {
                margin-left: 0;
                padding: 10px;
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

            .stats-summary {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-summary {
                grid-template-columns: 1fr;
            }
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
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: rgba(0, 0, 0, 0.2);
        }
        
        .submenu.show {
            max-height: 200px;
        }
        
        .submenu li {
            border-left: 3px solid transparent;
        }
        
        .submenu a {
            display: block;
            padding: 0.625rem 2rem;
            color: #ced4da;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .submenu a:hover,
        .submenu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #4c6ef5;
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
                <h1 class="page-title">All Inquiries</h1>

                <!-- Stats Summary with Status Counts -->
                <div class="stats-summary">
                    <div class="stat-card">
                        <span class="stat-number">{{ $totalInquiries ?? 0 }}</span>
                        <span class="stat-label">Total Inquiries</span>
                    </div>
                    <div class="stat-card under-investigation">
                        <span class="stat-number">{{ $statusCounts['Under Investigation'] ?? 0 }}</span>
                        <span class="stat-label">Under Investigation</span>
                    </div>
                    <div class="stat-card verified-true">
                        <span class="stat-number">{{ $statusCounts['Verified as True'] ?? 0 }}</span>
                        <span class="stat-label">Verified as True</span>
                    </div>
                    <div class="stat-card identified-fake">
                        <span class="stat-number">{{ $statusCounts['Identified as Fake'] ?? 0 }}</span>
                        <span class="stat-label">Identified as Fake</span>
                    </div>
                    <div class="stat-card rejected">
                        <span class="stat-number">{{ $statusCounts['Rejected'] ?? 0 }}</span>
                        <span class="stat-label">Rejected</span>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-grid">
                        <div class="form-group">
                            <label for="searchInquiries">Search Inquiries</label>
                            <input type="text" class="form-control" id="searchInquiries" placeholder="Search by ID, subject, or content...">
                        </div>
                        <div class="form-group">
                            <label for="statusFilter">Status</label>
                            <select class="form-control" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="under-investigation">Under Investigation</option>
                                <option value="verified-as-true">Verified as True</option>
                                <option value="identified-as-fake">Identified as Fake</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dateFilter">Date Range</label>
                            <select class="form-control" id="dateFilter">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="quarter">Last 3 Months</option>
                            </select>
                        </div>
                        <div>
                            <button class="btn btn-primary" onclick="filterInquiries()">Apply Filters</button>
                        </div>
                    </div>
                    <div style="margin-top: 15px;">
                        <button class="btn btn-secondary" onclick="clearFilters()">Clear All Filters</button>
                    </div>
                </div>

                <!-- Inquiries List -->
                <div class="inquiries-container" id="inquiriesList">
                    @if(isset($inquiries) && count($inquiries) > 0)
                        @foreach($inquiries as $inquiry)
                        <div class="inquiry-card" data-status="{{ strtolower(str_replace(' ', '-', $inquiry['status'])) }}" data-date="{{ $inquiry['submittedDate'] }}">
                            <div class="inquiry-header">
                                <span class="inquiry-id">ID: {{ $inquiry['id'] ?? 'N/A' }}</span>
                                <span class="inquiry-date">Submitted: {{ $inquiry['submittedDate'] }}</span>
                            </div>
                            <h3 class="inquiry-title">{{ $inquiry['title'] }}</h3>
                            <p class="inquiry-content">{{ Str::limit($inquiry['description'], 150) }}</p>
                            <div class="inquiry-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Type:</span> {{ $inquiry['type'] }}
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Assigned to:</span> {{ $inquiry['assignedTo'] }}
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Submitted by:</span> {{ $inquiry['submittedBy'] ?? 'Unknown User' }}
                                </div>
                            </div>
                            <div class="inquiry-actions">
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry['status'])) }}">{{ $inquiry['status'] }}</span>
                                <a href="/inquiry-detail/{{ $inquiry['id'] }}" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">📋</div>
                            <h3>No Inquiries Found</h3>
                            <p>There are no inquiries in the system yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter functionality
        function filterInquiries() {
            const searchTerm = document.getElementById('searchInquiries').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            
            const cards = document.querySelectorAll('.inquiry-card');
            let visibleCount = 0;
            
            // Get current date for date filtering
            const today = new Date();
            const startOfToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay()); // Start of current week (Sunday)
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const startOfQuarter = new Date(today);
            startOfQuarter.setMonth(today.getMonth() - 3);
            
            cards.forEach(card => {
                const title = card.querySelector('.inquiry-title').textContent.toLowerCase();
                const content = card.querySelector('.inquiry-content').textContent.toLowerCase();
                const id = card.querySelector('.inquiry-id').textContent.toLowerCase();
                const status = card.dataset.status;
                const submittedDateStr = card.dataset.date;
                
                // Parse the submitted date
                const submittedDate = new Date(submittedDateStr);
                
                const matchesSearch = searchTerm === '' || title.includes(searchTerm) || 
                                    content.includes(searchTerm) || id.includes(searchTerm);
                const matchesStatus = statusFilter === '' || status === statusFilter;
                
                // Date filtering logic
                let matchesDate = true;
                if (dateFilter !== '') {
                    switch (dateFilter) {
                        case 'today':
                            matchesDate = submittedDate >= startOfToday;
                            break;
                        case 'week':
                            matchesDate = submittedDate >= startOfWeek;
                            break;
                        case 'month':
                            matchesDate = submittedDate >= startOfMonth;
                            break;
                        case 'quarter':
                            matchesDate = submittedDate >= startOfQuarter;
                            break;
                    }
                }
                
                if (matchesSearch && matchesStatus && matchesDate) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update results count if needed
            console.log(`Showing ${visibleCount} of ${cards.length} inquiries`);
        }
        
        function clearFilters() {
            document.getElementById('searchInquiries').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('dateFilter').value = '';
            
            // Show all cards
            document.querySelectorAll('.inquiry-card').forEach(card => {
                card.style.display = 'block';
            });
        }
        
        // Real-time search
        document.getElementById('searchInquiries').addEventListener('input', filterInquiries);
        document.getElementById('statusFilter').addEventListener('change', filterInquiries);
        document.getElementById('dateFilter').addEventListener('change', filterInquiries);
    </script>
</body>
</html>
