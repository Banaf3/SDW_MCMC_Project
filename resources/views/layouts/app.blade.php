<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VeriTrack - MCMC System')</title>
    
    <!-- Inline CSS -->
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f8f9fa;
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
        
        /* Main Container */
        .main-container { 
            display: flex; 
            min-height: calc(100% - 60px); /* Adjust min-height to account for header */
            padding-top: 60px; /* Account for fixed header */
        }
        
        /* Sidebar Styles */        .sidebar { 
            width: 250px; 
            background: linear-gradient(180deg, #495057 0%, #343a40 100%);
            color: white; 
            padding: 1.5rem 0;
            position: fixed;
            left: 0;
            top: 60px;
            bottom: 0;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        /* Content Area */
        .content { 
            flex: 1; 
            padding: 2rem 2rem 0 2rem; /* Removed bottom padding */
            margin-left: 250px;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Navigation */
        .sidebar-nav ul { 
            list-style: none; 
            padding: 0; 
        }
        
        .sidebar-nav li { 
            margin-bottom: 0.25rem; 
        }
        
        .sidebar-nav a { 
            color: rgba(255, 255, 255, 0.9); 
            text-decoration: none; 
            padding: 0.75rem 1.5rem; 
            display: block;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav a:hover { 
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #4c6ef5;
        }
        
        .sidebar-nav a.active { 
            background-color: rgba(76, 110, 245, 0.2);
            border-left-color: #4c6ef5;
            color: white;
        }
        
        .nav-section {
            margin-bottom: 1.5rem;
        }
        
        .nav-section h3 { 
            color: #adb5bd; 
            margin-bottom: 0.75rem; 
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 1.5rem;
        }
          .submenu {
            margin-left: 1rem;
        }
        
        .submenu a {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            border-left: 2px solid transparent;
        }
        
        /* Expandable Menu Styles */
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .nav-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            cursor: pointer;
        }
        
        .nav-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #4c6ef5;
        }
        
        .nav-arrow {
            transition: transform 0.3s ease;
        }
        
        .nav-toggle.active .nav-arrow {
            transform: rotate(180deg);
        }
        
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: rgba(0, 0, 0, 0.2);
        }
        
        .submenu.expanded {
            max-height: 200px;
        }
        
        .submenu li {
            margin: 0;
        }
        
        .submenu a {
            padding: 0.5rem 2rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            border-left: 2px solid transparent;
        }
        
        .submenu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #45c649;
            color: white;
        }
        
        .logout-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 1.5rem;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .logout-btn:hover {
            background-color: rgba(220, 53, 69, 0.2);
            border-left-color: #dc3545;
        }
          /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content {
                margin-left: 0;
            }
            
            .user-details {
                display: none;
            }
        }
        
        @yield('styles')
    </style>
</head>
<body>
    @include('layouts.partials.header')

    <div class="main-container">
        @include('layouts.partials.sidebar')
        
        <main class="content">
            @yield('content')
        </main>
    </div>    <!-- Inline JavaScript -->
    <script>
        // Expandable Menu Toggle
        function toggleSubmenu(menuId) {
            const submenu = document.getElementById(menuId);
            const toggleBtn = submenu.previousElementSibling;
            
            // Toggle expanded class
            submenu.classList.toggle('expanded');
            toggleBtn.classList.toggle('active');
            
            // Close other open submenus
            const allSubmenus = document.querySelectorAll('.submenu');
            const allToggles = document.querySelectorAll('.nav-toggle');
            
            allSubmenus.forEach((menu, index) => {
                if (menu.id !== menuId) {
                    menu.classList.remove('expanded');
                    allToggles[index].classList.remove('active');
                }
            });
        }
        
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
