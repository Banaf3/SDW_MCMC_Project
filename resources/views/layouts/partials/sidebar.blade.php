@php
    // Get user data from session (same as header for consistency)
    $userId = session('user_id', '');
    $userType = session('user_type', '');
    $userName = session('user_name', 'Guest');
    $userEmail = session('user_email', '');
    
    // Convert user type to role display name
    $userRole = 'Guest';
    if ($userType === 'admin') {
        $userRole = 'Administrator';
    } elseif ($userType === 'agency') {
        $userRole = 'Agency Staff';
    } elseif ($userType === 'public') {
        $userRole = 'Public User';
    }
    
    // Determine permissions
    $isPublic = $userType === 'public';
    $isAdmin = $userType === 'admin';
    $isAgency = $userType === 'agency';
    $isLoggedIn = !empty($userId);
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
                        <li><a href="{{ route('inquiries.create') }}">Submit New Inquiry</a></li>
                        <li><a href="{{ route('inquiries.index') }}">View My Inquiries</a></li>
                        <li><a href="{{ route('public.inquiries.index') }}">Browse Public Inquiries</a></li>
                    </ul>
                </li>



                <!-- 4. Progress Tracking - Direct Link -->
                <li class="nav-item">
                    <a href="{{ route('public.progress.track') }}" class="nav-toggle" style="justify-content: flex-start;">
                        <span>Progress Tracking</span>
                    </a>
                </li>
            @endif

            @if($isAdmin)
                <!-- MCMC ADMIN MENU -->

                <!-- Unassigned Inquiries - Single Link -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('user-management')">
                        <span>User Management</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="user-management">
                        <li><a href="{{ route('admin.agency.register') }}">Register Agency Staff</a></li>
                        <li><a href="{{ route('admin.agency.management') }}">Manage Agencies</a></li>
                        <li><a href="#">User Reports</a></li>
                    </ul>
                </li>

                <!-- 2. Manage Inquiries -->
                <li class="nav-item">
                    <a href="#" class="nav-toggle" onclick="toggleSubmenu('manage-inquiries')">
                        <span>Manage Inquiries</span>
                        <svg class="nav-arrow" width="12" height="12" fill="currentColor">
                            <path d="M4 6l4 4 4-4H4z"/>
                        </svg>
                    </a>
                    <ul class="submenu" id="manage-inquiries">
                        <li><a href="{{ route('admin.inquiries.new') }}">View Inquiries</a></li>
                        <li><a href="{{ route('admin.audit-logs') }}">View Audit Logs</a></li>
                        <li><a href="{{ route('admin.reports') }}">Generate Reports</a></li>
                    </ul>
                </li>

                <!-- 3. Unassigned Inquiries - Direct Link -->
                <li class="nav-item">
                    <a href="{{ route('mcmc.unassigned.inquiries') }}" class="nav-toggle" style="justify-content: flex-start;">
                        <span>Unassigned Inquiries</span>
                    </a>
                </li>

                <!-- 4. Monitor Progress - Direct Link -->
                <li class="nav-item">
                    <a href="{{ route('mcmc.progress.monitor') }}" class="nav-toggle" style="justify-content: flex-start;">
                        <span>Monitor Progress</span>
                    </a>
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
                        <li><a href="{{ route('agency.inquiries.list') }}">Assigned Inquiries</a></li>
                    </ul>
                </li>

                <!-- 3. Accept/Reject Inquiries - Direct Link -->
                <li class="nav-item">
                    <a href="{{ route('agency.inquiries.assigned') }}" class="nav-toggle" style="justify-content: flex-start;">
                        <span>Accept/Reject Inquiries</span>
                    </a>
                </li>

                <!-- 4. Update Progress - Direct Link -->
                <li class="nav-item">
                    <a href="{{ route('agency.progress.update') }}" class="nav-toggle" style="justify-content: flex-start;">
                        <span>Update Progress</span>
                    </a>
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

/* Arrow animation */
.nav-toggle .nav-arrow {
    transition: transform 0.3s ease;
}

.nav-toggle.active .nav-arrow {
    transform: rotate(180deg);
}

/* Ensure submenu styling works */
.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background-color: rgba(0, 0, 0, 0.2);
    list-style: none;
    margin: 0;
    padding: 0;
}

.submenu.expanded {
    max-height: 200px;
}

.submenu li {
    margin: 0;
}

.submenu a {
    display: block;
    padding: 0.5rem 2rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    border-left: 2px solid transparent;
    transition: all 0.2s ease;
}

.submenu a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-left-color: #45c649;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure toggleSubmenu function exists and works
    window.toggleSubmenu = function(menuId) {
        const submenu = document.getElementById(menuId);
        if (!submenu) return;
        
        const toggleBtn = submenu.previousElementSibling;
        
        // Toggle expanded class
        const isExpanded = submenu.classList.contains('expanded');
        
        // Close all other submenus first
        document.querySelectorAll('.submenu.expanded').forEach(menu => {
            if (menu.id !== menuId) {
                menu.classList.remove('expanded');
            }
        });
        
        document.querySelectorAll('.nav-toggle.active').forEach(btn => {
            if (btn !== toggleBtn) {
                btn.classList.remove('active');
            }
        });
        
        // Toggle current submenu
        if (isExpanded) {
            submenu.classList.remove('expanded');
            if (toggleBtn) toggleBtn.classList.remove('active');
        } else {
            submenu.classList.add('expanded');
            if (toggleBtn) toggleBtn.classList.add('active');
        }
    };
});
</script>
