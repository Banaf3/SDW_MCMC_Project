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
                
                <!-- 1. Inquiry Submission -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('inquiry-submission')">
                        <span>Inquiry Submission</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="inquiry-submission">
                        <li><a href="#">Submit New Inquiry</a></li>
                        <li><a href="#">View My Inquiries</a></li>
                        <li><a href="#">Browse Public Inquiries</a></li>
                    </ul>
                </li>

                <!-- 2. Assignment Status -->
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

                <!-- 3. Progress Tracking -->
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

                <!-- 1. Manage Inquiries -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('manage-inquiries')">
                        <span>Manage Inquiries</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="manage-inquiries">
                        <li><a href="#">View New Inquiries</a></li>
                        <li><a href="#">Filter & Validate</a></li>
                        <li><a href="#">Previous Inquiries</a></li>
                        <li><a href="#">Generate Reports</a></li>
                    </ul>
                </li>

                <!-- 2. Assign Inquiries -->
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

                <!-- 3. Monitor Progress -->
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

                <!-- 1. Inquiry Access -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('inquiry-access')">
                        <span>Inquiry Access</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="inquiry-access">
                        <li><a href="#">Previous Inquiries</a></li>
                        <li><a href="#">Search & Filter</a></li>
                        <li><a href="#">Inquiry History</a></li>
                    </ul>
                </li>

                <!-- 2. Assignment Management -->
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

                <!-- 3. Investigation Updates -->
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
/* 
  Adjust the margin-top value below to suit your header's height.
  If your header is, for example, 70px tall, and you want 10px space, 
  set margin-top to 80px (if the sidebar starts from the very top) 
  or add to existing margin/padding.
  
  If your sidebar uses fixed positioning (e.g., position: fixed;), 
  you might need to adjust its 'top' property instead of 'margin-top'.
  e.g., .sidebar { top: 80px; height: calc(100vh - 80px); }
*/
.sidebar {
    margin-top: 1rem; /* Provides some spacing from the header. Adjust as needed. */
    /* Example: if your header is roughly 60-70px, and sidebar was flush, 
       you might need a more significant margin like:
       margin-top: 70px; 
    */
}
</style>
