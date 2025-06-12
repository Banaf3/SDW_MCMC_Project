<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriTrack - My Inquiries</title>
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            font-size: 2.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .tagline {
            color: #666;
            font-size: 1.2rem;
        }

        .user-info {
            margin-top: 15px;
            padding: 15px;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            border-radius: 10px;
            display: inline-block;
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

        /* Search and Filter Section */
        .filter-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 20px;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e3c72;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
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
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        /* Inquiry Cards */
        .inquiries-container {
            display: grid;
            gap: 20px;
        }

        .inquiry-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .inquiry-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
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
        }

        .status-verified-true {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .status-identified-fake {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .status-rejected {
            background: #e2e3e5;
            color: #383d41;
            border: 2px solid #d6d8db;
        }

        /* Modal for Details */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .modal-title {
            color: #1e3c72;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close:hover {
            color: #1e3c72;
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-title {
            color: #1e3c72;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .timeline {
            border-left: 3px solid #2a5298;
            padding-left: 20px;
        }

        .timeline-item {
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -27px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #2a5298;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .timeline-content {
            color: #555;
        }

        /* Stats Summary */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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

            .modal-content {
                margin: 10% auto;
                width: 95%;
                padding: 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">VeriTrack</div>
            <div class="tagline">Truth Verification System - Your Inquiry Dashboard</div>
            <div class="user-info">
                👤 Welcome back, Public User | Last login: June 10, 2025
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1 class="page-title">My Inquiries</h1>

            <!-- Total Inquiries Summary -->
            <div class="stats-summary">                <div class="stat-card">
                    <span class="stat-number">{{ $totalInquiries }}</span>
                    <span class="stat-label">Total Inquiries</span>
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
                            <option value="verified-true">Verified as True</option>
                            <option value="identified-fake">Identified as Fake</option>
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
                @foreach($inquiries as $inquiry)
                <div class="inquiry-card" data-status="{{ strtolower(str_replace(' ', '-', $inquiry['status'])) }}" data-date="{{ $inquiry['submittedDate'] }}">
                    <div class="inquiry-header">
                        <span class="inquiry-id">VT-{{ date('Y') }}-{{ str_pad($inquiry['id'], 6, '0', STR_PAD_LEFT) }}</span>
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
                            <span class="meta-label">Priority:</span> {{ $inquiry['priority'] }}
                        </div>
                    </div>
                    <div class="inquiry-actions">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry['status'])) }}">{{ $inquiry['status'] }}</span>
                        <button class="btn btn-primary" onclick="showDetails('{{ $inquiry['id'] }}')">View Details</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal for Inquiry Details -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Inquiry Details</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div id="modalBody">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Get inquiries data from PHP
        const inquiriesData = @json($inquiries);
        const inquiriesById = {};
        inquiriesData.forEach(inquiry => {
            inquiriesById[inquiry.id] = inquiry;
        });

        // Filter functionality
        function filterInquiries() {
            const searchTerm = document.getElementById('searchInquiries').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            
            const cards = document.querySelectorAll('.inquiry-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const title = card.querySelector('.inquiry-title').textContent.toLowerCase();
                const content = card.querySelector('.inquiry-content').textContent.toLowerCase();
                const id = card.querySelector('.inquiry-id').textContent.toLowerCase();
                const status = card.dataset.status;
                
                const matchesSearch = searchTerm === '' || title.includes(searchTerm) || 
                                    content.includes(searchTerm) || id.includes(searchTerm);
                const matchesStatus = statusFilter === '' || status === statusFilter;
                
                if (matchesSearch && matchesStatus) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            if (visibleCount === 0) {
                showEmptyState();
            } else {
                hideEmptyState();
            }
        }

        function clearFilters() {
            document.getElementById('searchInquiries').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('dateFilter').value = '';
            
            const cards = document.querySelectorAll('.inquiry-card');
            cards.forEach(card => {
                card.style.display = 'block';
            });
            hideEmptyState();
        }

        function showEmptyState() {
            let emptyState = document.getElementById('emptyState');
            if (!emptyState) {
                emptyState = document.createElement('div');
                emptyState.id = 'emptyState';
                emptyState.className = 'empty-state';
                emptyState.innerHTML = `
                    <div class="empty-icon">🔍</div>
                    <h3>No inquiries found</h3>
                    <p>Try adjusting your search criteria or filters</p>
                `;
                document.getElementById('inquiriesList').appendChild(emptyState);
            }
            emptyState.style.display = 'block';
        }

        function hideEmptyState() {
            const emptyState = document.getElementById('emptyState');
            if (emptyState) {
                emptyState.style.display = 'none';
            }
        }

        // Modal functionality
        function showDetails(inquiryId) {
            const inquiry = inquiriesById[inquiryId];
            if (!inquiry) return;            document.getElementById('modalTitle').textContent = `${inquiry['reference_number']} - ${inquiry['title']}`;
            let modalBody = `
                <div class="detail-section">
                    <h3 class="detail-title">Reference Number</h3>
                    <p>${inquiry['reference_number']}</p>
                </div>
                <div class="detail-section">
                    <h3 class="detail-title">Description</h3>
                    <p>${inquiry['description']}</p>
                </div>
                <div class="detail-section">
                    <h3 class="detail-title">Assigned To</h3>
                    <p>${inquiry.assignedTo}</p>
                </div>
                <div class="detail-section">
                    <h3 class="detail-title">Type</h3>
                    <p>${inquiry.type}</p>
                </div>
                <div class="detail-section">
                    <h3 class="detail-title">Priority</h3>
                    <p>${inquiry.priority}</p>
                </div>
                <div class="detail-section">
                    <h3 class="detail-title">Submitted Date</h3>
                    <p>${inquiry.submittedDate}</p>
                </div>
                <div class="detail-section">
                    <h3 class="detail-title">Timeline</h3>
                    <ul class="timeline">${inquiry.timeline.map(event => `<li>${event.date}: ${event.event}</li>`).join('')}</ul>
                </div>
                ${inquiry.conclusion ? `<div class='detail-section'><h3 class='detail-title'>Conclusion</h3><p>${inquiry.conclusion}</p></div>` : ''}
                <div class="detail-section">
                    <h3 class="detail-title">Notes</h3>
                    <p>${inquiry.notes || 'No notes available.'}</p>
                </div>
            `;
            document.getElementById('modalBody').innerHTML = modalBody;
            document.getElementById('detailModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('detailModal').style.display = 'none';
        }
    </script>
</body>
</html>