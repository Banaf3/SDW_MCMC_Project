<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriTrack - Inquiry Details</title>
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

        /* Main Content */
        .main-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        /* Inquiry Header */
        .inquiry-header {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            padding: 30px;
            position: relative;
        }

        .inquiry-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)" /></svg>');
        }

        .inquiry-header-content {
            position: relative;
            z-index: 1;
        }

        .inquiry-id-large {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .inquiry-title-large {
            font-size: 2.2rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .inquiry-meta-header {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .meta-item-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .meta-label-header {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .meta-value-header {
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Status Badge */
        .status-badge {
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-top: 10px;
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

        /* Content Sections */
        .content-body {
            padding: 30px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-title {
            color: #1e3c72;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            font-size: 1.2rem;
        }

        .section-content {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #2a5298;
        }

        .description-text {
            line-height: 1.7;
            font-size: 1.05rem;
            color: #444;
            margin-bottom: 15px;
        }        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
            margin-left: 15px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #2a5298, #1e3c72);
            border-radius: 3px;
        }

        .timeline-item {
            margin-bottom: 30px;
            position: relative;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .timeline-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(42, 82, 152, 0.15);
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -36px;
            top: 20px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #2a5298;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #2a5298;
        }

        .timeline-icon {
            position: absolute;
            left: -44px;
            top: 12px;
            width: 28px;
            height: 28px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: 3px solid #2a5298;
            z-index: 2;
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .timeline-date {
            font-size: 0.9rem;
            color: #2a5298;
            font-weight: 600;
            background: #f8f9fe;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid #e1e5f2;
        }

        .timeline-event {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e3c72;
            margin-bottom: 8px;
        }

        .timeline-description {
            color: #555;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .timeline-type-submitted .timeline-item::before { background: #28a745; box-shadow: 0 0 0 3px #28a745; }
        .timeline-type-assigned .timeline-item::before { background: #007bff; box-shadow: 0 0 0 3px #007bff; }
        .timeline-type-status .timeline-item::before { background: #ffc107; box-shadow: 0 0 0 3px #ffc107; }
        .timeline-type-resolved .timeline-item::before { background: #28a745; box-shadow: 0 0 0 3px #28a745; }
        .timeline-type-documents .timeline-item::before { background: #6f42c1; box-shadow: 0 0 0 3px #6f42c1; }

        .timeline-type-submitted .timeline-icon { border-color: #28a745; }
        .timeline-type-assigned .timeline-icon { border-color: #007bff; }
        .timeline-type-status .timeline-icon { border-color: #ffc107; }
        .timeline-type-resolved .timeline-icon { border-color: #28a745; }
        .timeline-type-documents .timeline-icon { border-color: #6f42c1; }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid #e9ecef;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
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

            .inquiry-meta-header {
                grid-template-columns: 1fr;
            }

            .inquiry-title-large {
                font-size: 1.8rem;
            }

            .content-body {
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }

            .header-container {
                padding: 0 1rem;
            }

            .header-user-info {
                gap: 0.5rem;
            }
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
                <!-- Inquiry Header Section -->                <div class="inquiry-header">
                    <div class="inquiry-header-content">
                        <div class="inquiry-id-large">ID: {{ $inquiry['id'] ?? 'N/A' }}</div>
                        <h1 class="inquiry-title-large">{{ $inquiry['title'] }}</h1>
                        <div class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry['status'])) }}">{{ $inquiry['status'] }}</div><div class="inquiry-meta-header">
                            <div class="meta-item-header">
                                <div class="meta-label-header">Inquiry ID</div>
                                <div class="meta-value-header">{{ $inquiry['id'] }}</div>
                            </div>
                            <div class="meta-item-header">
                                <div class="meta-label-header">Submitted Date</div>
                                <div class="meta-value-header">{{ $inquiry['submittedDate'] }}</div>
                            </div>
                            <div class="meta-item-header">
                                <div class="meta-label-header">Assigned To</div>
                                <div class="meta-value-header">{{ $inquiry['assignedTo'] }}</div>
                            </div>
                            <div class="meta-item-header">
                                <div class="meta-label-header">Assignment Date</div>
                                <div class="meta-value-header">{{ $inquiry['assignedDate'] }}</div>
                            </div>
                            <div class="meta-item-header">
                                <div class="meta-label-header">Agency Type</div>
                                <div class="meta-value-header">{{ $inquiry['agencyDescription'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Body -->
                <div class="content-body">
                    <!-- User Description Section -->
                    <div class="section">
                        <h2 class="section-title">
                            <span class="section-icon">👤</span>
                            User Description
                        </h2>
                        <div class="section-content">
                            <p class="description-text">
                                {{ $inquiry['userDescription'] ?? 'No description provided by the user.' }}
                            </p>
                        </div>
                    </div>                    <!-- Agency Comment Section -->
                    @if(isset($inquiry['agencyComment']) && $inquiry['agencyComment'])
                    <div class="section">
                        <h2 class="section-title">
                            <span class="section-icon">🏢</span>
                            Agency Response
                        </h2>
                        <div class="section-content">
                            <p class="description-text">
                                {{ $inquiry['agencyComment'] }}
                            </p>
                        </div>
                    </div>
                    @endif<!-- Supporting Documents Section -->
                    @if($inquiry['evidence'])
                    <div class="section">
                        <h2 class="section-title">
                            <span class="section-icon">📎</span>
                            Supporting Documents
                        </h2>
                        <div class="section-content">
                            <p class="description-text">
                                {{ $inquiry['evidence'] }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Agency Supporting Documents Section -->
                    <div class="section">
                        <h2 class="section-title">
                            <span class="section-icon">🏢</span>
                            Agency Supporting Documents
                        </h2>
                        <div class="section-content">
                            @if($inquiry['agencySupportingDocs'])
                                <p class="description-text">
                                    {{ $inquiry['agencySupportingDocs'] }}
                                </p>
                            @else
                                <p class="description-text" style="color: #6c757d; font-style: italic;">
                                    No supporting documents provided by the agency yet.
                                </p>
                            @endif
                        </div>
                    </div>                    <!-- Timeline Section -->
                    @if($inquiry['timeline'] && count($inquiry['timeline']) > 0)
                    <div class="section">
                        <h2 class="section-title">
                            <span class="section-icon">📅</span>
                            Investigation Timeline
                        </h2>
                        <div class="timeline">
                            @foreach($inquiry['timeline'] as $event)
                            <div class="timeline-type-{{ $event['type'] ?? 'default' }}">
                                <div class="timeline-item">
                                    <div class="timeline-icon">{{ $event['icon'] ?? '📋' }}</div>
                                    <div class="timeline-header">
                                        <div class="timeline-event">{{ $event['event'] }}</div>
                                        <div class="timeline-date">{{ $event['date'] }}</div>
                                    </div>
                                    @if(isset($event['description']))
                                    <div class="timeline-description">{{ $event['description'] }}</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="section">
                        <h2 class="section-title">
                            <span class="section-icon">📅</span>
                            Investigation Timeline
                        </h2>
                        <div class="section-content">
                            <p class="description-text" style="color: #6c757d; font-style: italic;">
                                No timeline information available yet. The timeline will be updated as the investigation progresses.
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="/inquiry_list" class="btn btn-secondary">← Back to My Inquiries</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            
            if (userInfo && dropdown && !userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        // Add loading state to action buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.href || this.href.includes('#')) return;

                const originalText = this.innerHTML;
                this.innerHTML = '⏳ Loading...';
                this.style.opacity = '0.7';
                this.style.pointerEvents = 'none';

                // Reset after 2 seconds (in real app, this would be handled by navigation)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.opacity = '1';
                    this.style.pointerEvents = 'auto';
                }, 2000);
            });
        });
    </script>
</body>
</html>