@php
    // Get user data from session
    $userId = session('user_id', '');
    $userType = session('user_type', '');
    $userName = session('user_name', 'Guest');
    $userEmail = session('user_email', '');
    
    // Get profile picture if available
    $profilePic = null;
    try {
        if ($userId && $userType) {
            if ($userType === 'admin') {
                $user = \App\Models\Administrator::find($userId);
                // Admins do not have profile pictures
            } elseif ($userType === 'agency') {
                $user = \App\Models\AgencyStaff::find($userId);
                if ($user && !empty($user->ProfilePic)) {
                    $profilePic = $user->ProfilePic;
                }
            } elseif ($userType === 'public') {
                $user = \App\Models\PublicUser::find($userId);
                if ($user && !empty($user->ProfilePic)) {
                    $profilePic = $user->ProfilePic;
                }
            }
        }
    } catch (\Exception $e) {
        // In case of any errors, just don't display the profile pic
        $profilePic = null;
    }
    
    // Get user initials for avatar
    $userInitials = '';
    if ($userName) {
        $nameParts = explode(' ', $userName);
        if (count($nameParts) >= 2) {
            $userInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
        } else {
            $userInitials = strtoupper(substr($userName, 0, 2));
        }
    }
    
    // Convert user type to role display name
    $userRole = 'Guest';
    if ($userType === 'admin') {
        $userRole = 'Administrator';
    } elseif ($userType === 'agency') {
        $userRole = 'Agency Staff';
    } elseif ($userType === 'public') {
        $userRole = 'Public User';
    }
@endphp

<header class="header">
    <div class="header-container">
        <div class="logo">
            <h1>VeriTrack 1.0</h1>
        </div>
        
        @if(!empty($userId) && !empty($userType))
        <div class="header-actions">
            <!-- Notification Bell (only for public users) -->
            @if($userType === 'public')
            <div class="notification-container">
                <div class="notification-bell" onclick="toggleNotifications()">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                    </svg>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </div>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <h4>Notifications</h4>
                        <button class="mark-all-read" onclick="markAllNotificationsRead()">Mark all read</button>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <div class="no-notifications">No notifications</div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- User Profile Dropdown -->
            <div class="header-user-info" onclick="toggleUserDropdown()">
            <div class="user-avatar">
                @if($profilePic)
                    <img src="{{ asset('storage/' . $profilePic) }}" alt="{{ $userInitials }}">
                @else
                    {{ $userInitials }}
                @endif
            </div>
            <div class="user-details">
                <div class="user-name">{{ $userName }}</div>
                <div class="user-role">{{ $userRole }}</div>
            </div>
            <svg class="dropdown-arrow" width="12" height="12" fill="currentColor">
                <path d="M4 6l4 4 4-4H4z"/>
            </svg>
            
            <!-- User Dropdown Menu -->
            <div class="user-dropdown" id="userDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg width="16" height="16" fill="currentColor">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                    Edit Profile
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="dropdown-item logout-item" style="border: none; background: none; width: 100%; text-align: left; cursor: pointer;">
                        <svg width="16" height="16" fill="currentColor">
                            <path d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
        </div>
        @endif
    </div>
</header>

<script>
// User dropdown functionality
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
    
    // Close notification dropdown if open
    const notificationDropdown = document.getElementById('notificationDropdown');
    if (notificationDropdown) {
        notificationDropdown.classList.remove('show');
    }
}

// Notification functionality
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('show');
    
    // Close user dropdown if open
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.classList.remove('show');
    }
    
    // Load notifications when opened
    if (dropdown.classList.contains('show')) {
        loadNotifications();
    }
}

function loadNotifications() {
    const userId = '{{ $userId }}';
    if (!userId) return;
    
    fetch(`/notifications?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            updateNotificationsList(data.notifications);
            updateNotificationBadge(data.unread_count);
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function updateNotificationsList(notifications) {
    const listContainer = document.getElementById('notificationList');
    
    if (notifications.length === 0) {
        listContainer.innerHTML = '<div class="no-notifications">No notifications</div>';
        return;
    }
    
    const notificationsHTML = notifications.map(notification => `
        <div class="notification-item ${notification.read ? 'read' : 'unread'}" data-id="${notification.id}">
            <div class="notification-content" onclick="markNotificationRead('${notification.id}', ${notification.inquiryId})">
                <div class="notification-title">${notification.title}</div>
                <div class="notification-message">${notification.message}</div>
                <div class="notification-time">${notification.time}</div>
            </div>
            ${!notification.read ? '<div class="notification-unread-dot"></div>' : ''}
        </div>
    `).join('');
    
    listContainer.innerHTML = notificationsHTML;
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'block';
    } else {
        badge.style.display = 'none';
    }
}

function markNotificationRead(notificationId, inquiryId) {
    const userId = '{{ $userId }}';
    if (!userId) return;
    
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the notification item to mark as read
            const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');
                const dot = notificationItem.querySelector('.notification-unread-dot');
                if (dot) dot.remove();
            }
            
            // Refresh badge count
            loadNotificationCount();
            
            // Navigate to inquiry detail if inquiry ID exists
            if (inquiryId) {
                window.location.href = `/public/inquiry/${inquiryId}`;
            }
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

function markAllNotificationsRead() {
    const userId = '{{ $userId }}';
    if (!userId) return;
    
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mark all notifications as read in UI
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('unread');
                item.classList.add('read');
                const dot = item.querySelector('.notification-unread-dot');
                if (dot) dot.remove();
            });
            
            // Update badge
            updateNotificationBadge(0);
        }
    })
    .catch(error => console.error('Error marking all notifications as read:', error));
}

function loadNotificationCount() {
    const userId = '{{ $userId }}';
    if (!userId || '{{ $userType }}' !== 'public') return;
    
    fetch(`/notifications/unread-count?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unread_count);
        })
        .catch(error => console.error('Error loading notification count:', error));
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const userInfo = document.querySelector('.header-user-info');
    const userDropdown = document.getElementById('userDropdown');
    const notificationContainer = document.querySelector('.notification-container');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (userInfo && !userInfo.contains(event.target)) {
        userDropdown.classList.remove('show');
    }
    
    if (notificationContainer && !notificationContainer.contains(event.target)) {
        notificationDropdown.classList.remove('show');
    }
});

// Load notification count on page load for public users
document.addEventListener('DOMContentLoaded', function() {
    if ('{{ $userType }}' === 'public') {
        loadNotificationCount();
        
        // Poll for new notifications every 30 seconds
        setInterval(loadNotificationCount, 30000);
    }
});
</script>

<style>
.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.notification-container {
    position: relative;
}

.notification-bell {
    position: relative;
    padding: 0.5rem;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.2s;
    color: white;
}

.notification-bell:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.notification-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 0.75rem;
    font-weight: bold;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    width: 350px;
    max-height: 400px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 1000;
    border: 1px solid #e1e5e9;
}

.notification-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.notification-header {
    padding: 1rem;
    border-bottom: 1px solid #e1e5e9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h4 {
    margin: 0;
    font-size: 1.1rem;
    color: #333;
}

.mark-all-read {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
}

.mark-all-read:hover {
    background-color: #f8f9fa;
}

.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    position: relative;
    padding: 1rem;
    border-bottom: 1px solid #f1f3f4;
    cursor: pointer;
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #f0f8ff;
}

.notification-item.unread .notification-unread-dot {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 8px;
    height: 8px;
    background: #007bff;
    border-radius: 50%;
}

.notification-content {
    padding-right: 1rem;
}

.notification-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.notification-message {
    color: #666;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.notification-time {
    color: #999;
    font-size: 0.75rem;
}

.no-notifications {
    padding: 2rem;
    text-align: center;
    color: #999;
    font-style: italic;
}

.header-user-info {
    position: relative;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: background-color 0.2s;
}

.header-user-info:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.dropdown-arrow {
    margin-left: 0.5rem;
    transition: transform 0.2s;
}

.header-user-info:hover .dropdown-arrow {
    transform: rotate(180deg);
}

.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 1000;
    margin-top: 0.5rem;
}

.user-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: #374151;
    text-decoration: none;
    transition: background-color 0.2s;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background-color: #f3f4f6;
}

.dropdown-item svg {
    margin-right: 0.75rem;
    color: #6b7280;
}

.dropdown-divider {
    height: 1px;
    background-color: #e5e7eb;
    margin: 0.25rem 0;
}

.logout-item {
    color: #dc2626;
    cursor: pointer;
}

.logout-item:hover {
    background-color: #fee2e2;
}

.logout-item svg {
    color: #dc2626;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin-right: 10px;
    overflow: hidden;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
