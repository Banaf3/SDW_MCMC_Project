@php
    // Determine user permissions based on role from header
    $userRole = request('role') === 'admin' ? 'Administrator' : (request('role') === 'agency' ? 'Agency Staff' : 'Public User');
    $isPublic = $userRole === 'Public User';
    $isAdmin = $userRole === 'Administrator';
    $isAgency = $userRole === 'Agency Staff';
@endphp

<aside class="sidebar">
    <nav class="sidebar-nav">
        <ul>
            <!-- Menu Header -->
            <li class="nav-section">
                <h3>Menu</h3>
            </li>

            @if($isPublic)
                <!-- PUBLIC USER MENU -->
                
                <!-- 2. Inquiry Submission -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('inquiry-submission')">
                        <span>Inquiry Submission</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="inquiry-submission">
                        <li><a href="#">Submit New Inquiry</a></li>
                        <li><a href="{{ url('/inquiry_list') }}" class="active">View My Inquiries</a></li>
                        <li><a href="#">Browse Public Inquiries</a></li>
                    </ul>
                </li>

                <!-- 3. Assignment Status -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('assignment-status')">
                        <span>Assignment Status</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="assignment-status">
                        <li><a href="#">View Agency Assignment</a></li>
                        <li><a href="#">Assignment History</a></li>
                    </ul>
                </li>

                <!-- 4. Progress Tracking -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('progress-tracking')">
                        <span>Progress Tracking</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="progress-tracking">
                        <li><a href="#">Track Inquiry Status</a></li>
                        <li><a href="#">Status History</a></li>
                        <li><a href="#">Dashboard</a></li>
                    </ul>
                </li>
            @endif

            @if($isAdmin)
                <!-- MCMC ADMIN MENU -->

                <!-- 2. Manage Inquiries -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('manage-inquiries')">
                        <span>Manage Inquiries</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="manage-inquiries">
                        <li><a href="#">View Inquiries</a></li>
                        <li><a href="#">Generate Reports</a></li>
                    </ul>
                </li>

                <!-- 3. Assign Inquiries -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('assign-inquiries')">
                        <span>Assign Inquiries</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="assign-inquiries">
                        <li><a href="#">Assign to Agencies</a></li>
                        <li><a href="#">View Assignments</a></li>
                        <li><a href="#">Assignment Reports</a></li>
                        <li><a href="#">Analytics</a></li>
                    </ul>
                </li>

                <!-- 4. Monitor Progress -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('monitor-progress')">
                        <span>Monitor Progress</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="monitor-progress">
                        <li><a href="#">Track Agency Progress</a></li>
                        <li><a href="#">Performance Reports</a></li>
                        <li><a href="#">Visual Analytics</a></li>
                        <li><a href="#">System Overview</a></li>
                    </ul>
                </li>
            @endif

            @if($isAgency)
                <!-- AGENCY STAFF MENU -->

                <!-- 2. Inquiry Access -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('inquiry-access')">
                        <span>Inquiry Access</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="inquiry-access">
                        <li><a href="#">Assigned Inquiries</a></li>
                    </ul>
                </li>

                <!-- 3. Assignment Management -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('assignment-management')">
                        <span>Assignment Management</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="assignment-management">
                        <li><a href="#">Received Inquiries</a></li>
                        <li><a href="#">Jurisdiction Review</a></li>
                        <li><a href="#">Accept/Reject</a></li>
                    </ul>
                </li>

                <!-- 4. Investigation Updates -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('investigation-updates')">
                        <span>Investigation Updates</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="investigation-updates">
                        <li><a href="#">Update Status</a></li>
                        <li><a href="#">Investigation Details</a></li>
                        <li><a href="#">MCMC Communication</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</aside>

<style>
.sidebar {
    margin-top: 1rem;
}

.submenu li a.active {
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff;
    font-weight: 600;
    border-left: 3px solid #45c649;
    padding-left: 2.75rem;
}

.submenu li a.active:hover {
    background-color: rgba(255, 255, 255, 0.3);
}
</style>
