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
        }

        .inquiry-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
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

        .inquiry-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .inquiry-content.collapsed {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .inquiry-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

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
                </div>

                <!-- Stats Summary -->
                <div class="stats-summary">
                    <div class="stat-card">
                        <span class="stat-number">{{ $totalUnassigned }}</span>
                        <span class="stat-label">Unassigned Inquiries</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">{{ $totalAgencies }}</span>
                        <span class="stat-label">Available Agencies</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">{{ $todaySubmissions }}</span>
                        <span class="stat-label">Today's Submissions</span>
                    </div>
                </div>

                <!-- Inquiries Grid -->
                <div class="inquiries-grid">
                    @if($unassignedInquiries->count() > 0)
                        @foreach($unassignedInquiries as $inquiry)
                        <div class="inquiry-card" data-inquiry-id="{{ $inquiry['InquiryID'] }}">
                            <div class="inquiry-header">
                                <span class="inquiry-id">{{ $inquiry['reference_number'] }}</span>
                                <span class="inquiry-date">{{ $inquiry['submittedDate'] }}</span>
                            </div>
                            
                            <h3 class="inquiry-title">{{ $inquiry['title'] }}</h3>
                            
                            <div class="inquiry-content collapsed" id="content-{{ $inquiry['InquiryID'] }}">
                                {{ $inquiry['description'] }}
                            </div>
                            
                            <div class="inquiry-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Status:</span>
                                    <span class="meta-value">{{ $inquiry['status'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Submitted by:</span>
                                    <span class="meta-value">{{ $inquiry['submittedBy'] }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Assignment:</span>
                                    <span class="meta-value">Not Assigned</span>
                                </div>
                            </div>
                            
                            <div class="inquiry-actions">
                                <button class="btn btn-view" onclick="toggleContent({{ $inquiry['InquiryID'] }})">
                                    <span>📄</span> View More
                                </button>
                                <button class="btn btn-assign" onclick="openAssignModal({{ $inquiry['InquiryID'] }})">
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
            const button = event.target.closest('.btn-view');
            
            if (content.classList.contains('collapsed')) {
                content.classList.remove('collapsed');
                button.innerHTML = '<span>📄</span> View Less';
            } else {
                content.classList.add('collapsed');
                button.innerHTML = '<span>📄</span> View More';
            }
        }

        function openAssignModal(inquiryId) {
            currentInquiryId = inquiryId;
            document.getElementById('assignModal').classList.add('show');
        }

        function closeAssignModal() {
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
                const response = await fetch(`/mcmc/assign-inquiry/${currentInquiryId}`, {
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
                    document.getElementById('successMessage').classList.add('show');
                    
                    // Remove the assigned inquiry card from the UI
                    const inquiryCard = document.querySelector(`[data-inquiry-id="${currentInquiryId}"]`);
                    if (inquiryCard) {
                        inquiryCard.remove();
                    }
                    
                    // Update stats
                    const statNumber = document.querySelector('.stat-number');
                    if (statNumber) {
                        const currentCount = parseInt(statNumber.textContent);
                        statNumber.textContent = currentCount - 1;
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
            }
        }

        // Close dropdown when clicking outside (for header dropdown)
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.header-user-info');
            const dropdown = document.getElementById('userDropdown');
            
            if (dropdown && userInfo && !userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>