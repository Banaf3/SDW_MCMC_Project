@php
    // Mock user data - replace with actual authentication
    $currentUser = [
        'name' => 'Austin Robertson',
        'role' => request('role') === 'admin' ? 'Administrator' : (request('role') === 'agency' ? 'Agency Staff' : 'Public User'),
        'avatar' => 'AR'
    ];
@endphp

<header class="header">
    <div class="header-container">
        <div class="logo">
            <h1>VeriTrack 1.0</h1>
        </div>
        
        <div class="header-right">
            <!-- Notification Bell -->
            <div class="notification-container" onclick="toggleNotifications()">
                <div class="notification-bell">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                    </svg>
                    <span class="notification-badge" id="notificationBadge">0</span>
                </div>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <h4>Notifications</h4>
                        <button class="mark-all-read" onclick="markAllAsRead()">Mark all as read</button>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <!-- Notifications will be populated here -->
                    </div>
                </div>
            </div>
            
            <div class="header-user-info" onclick="toggleUserDropdown()">>
            <div class="user-avatar">{{ $currentUser['avatar'] }}</div>
            <div class="user-details">
                <div class="user-name">{{ $currentUser['name'] }}</div>
                <div class="user-role">{{ $currentUser['role'] }}</div>
            </div>
            <svg class="dropdown-arrow" width="12" height="12" fill="currentColor">
                <path d="M4 6l4 4 4-4H4z"/>
            </svg>
            
            <!-- User Dropdown Menu -->
            <div class="user-dropdown" id="userDropdown">
                <a href="#" class="dropdown-item">
                    <svg width="16" height="16" fill="currentColor">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                    Profile
                </a>
                <a href="#" class="dropdown-item">
                    <svg width="16" height="16" fill="currentColor">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                    </svg>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="#" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item logout-item">
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
</header>

<script>
// Global notifications array
let notifications = [];

// Configuration
const NOTIFICATION_CONFIG = {
    userId: {{ $currentUserId ?? 1 }}, // Get user ID from Laravel view data
    refreshInterval: 30000, // Refresh every 30 seconds
    apiEndpoints: {
        getNotifications: '/api/notifications',
        markAllRead: '/api/notifications/mark-all-read',
        markAsRead: '/api/notifications/{id}/mark-read',
        getUnreadCount: '/api/notifications/unread-count'
    }
};

function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('show');
    if (dropdown.classList.contains('show')) {
        loadNotifications();
    }
}

function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Load notifications from API
async function loadNotifications() {
    try {
        const response = await fetch(`${NOTIFICATION_CONFIG.apiEndpoints.getNotifications}?user_id=${NOTIFICATION_CONFIG.userId}`);
        const data = await response.json();
        
        notifications = data.notifications || [];
        
        displayNotifications();
        updateNotificationBadge(data.unread_count || 0);
    } catch (error) {
        console.error('Error loading notifications:', error);
        // Fallback to empty state
        notifications = [];
        displayNotifications();
        updateNotificationBadge(0);
    }
}

function displayNotifications() {
    const notificationList = document.getElementById('notificationList');
    
    // Clear existing notifications
    notificationList.innerHTML = '';
    
    if (notifications.length === 0) {
        notificationList.innerHTML = '<div class="no-notifications">No notifications</div>';
        return;
    }
    
    // Add notifications
    notifications.forEach(notification => {
        const notificationItem = document.createElement('div');
        notificationItem.className = `notification-item ${notification.read ? 'read' : 'unread'}`;
        notificationItem.onclick = () => openNotification(notification);
        
        notificationItem.innerHTML = `
            <div class="notification-content">
                <div class="notification-title">${notification.title}</div>
                <div class="notification-message">${notification.message}</div>
                <div class="notification-time">${notification.time}</div>
            </div>
            ${!notification.read ? '<div class="unread-indicator"></div>' : ''}
        `;
        
        notificationList.appendChild(notificationItem);
    });
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    badge.textContent = count;
    badge.style.display = count > 0 ? 'block' : 'none';
}

async function openNotification(notification) {
    // Mark as read if it's unread
    if (!notification.read) {
        try {
            await fetch(
                NOTIFICATION_CONFIG.apiEndpoints.markAsRead.replace('{id}', notification.id),
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        user_id: NOTIFICATION_CONFIG.userId
                    })
                }
            );
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }
    
    // Redirect to inquiry detail page
    window.location.href = `/inquiry-detail/${notification.inquiryId}`;
}

async function markAllAsRead() {
    try {
        await fetch(NOTIFICATION_CONFIG.apiEndpoints.markAllRead, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                user_id: NOTIFICATION_CONFIG.userId
            })
        });
        
        // Reload notifications to update the UI
        await loadNotifications();
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
}

// Periodically check for new notifications
function startNotificationPolling() {
    setInterval(async () => {
        try {
            const response = await fetch(`${NOTIFICATION_CONFIG.apiEndpoints.getUnreadCount}?user_id=${NOTIFICATION_CONFIG.userId}`);
            const data = await response.json();
            updateNotificationBadge(data.unread_count || 0);
        } catch (error) {
            console.error('Error polling for notifications:', error);
        }
    }, NOTIFICATION_CONFIG.refreshInterval);
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const userInfo = document.querySelector('.header-user-info');
    const notificationContainer = document.querySelector('.notification-container');
    const userDropdown = document.getElementById('userDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (!userInfo.contains(event.target)) {
        userDropdown.classList.remove('show');
    }
    
    if (!notificationContainer.contains(event.target)) {
        notificationDropdown.classList.remove('show');
    }
});

// Initialize notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to head if not already present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const csrfMeta = document.createElement('meta');
        csrfMeta.name = 'csrf-token';
        csrfMeta.content = '{{ csrf_token() }}';
        document.head.appendChild(csrfMeta);
    }
    
    loadNotifications();
    startNotificationPolling();
});
</script>

<style>
.header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Notification Styles */
.notification-container {
    position: relative;
    cursor: pointer;
}

.notification-bell {
    position: relative;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: background-color 0.2s;
    color: white;
}

.notification-bell:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    min-width: 18px;
    height: 18px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    border: 2px solid #4c6ef5;
}

.notification-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    width: 350px;
    max-height: 400px;
    overflow: hidden;
    z-index: 1000;
    transform: translateY(-10px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
}

.notification-dropdown.show {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

.notification-header {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
}

.notification-header h4 {
    margin: 0;
    color: #333;
    font-size: 1.1rem;
}

.mark-all-read {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    font-size: 0.85rem;
    text-decoration: underline;
}

.mark-all-read:hover {
    color: #0056b3;
}

.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    padding: 1rem;
    border-bottom: 1px solid #f1f3f4;
    cursor: pointer;
    transition: background-color 0.2s;
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #e3f2fd;
}

.notification-item.unread:hover {
    background-color: #bbdefb;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.notification-message {
    color: #666;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 0.25rem;
}

.notification-time {
    color: #999;
    font-size: 0.75rem;
}

.unread-indicator {
    width: 8px;
    height: 8px;
    background: #007bff;
    border-radius: 50%;
    margin-top: 0.25rem;
}

.no-notifications {
    padding: 2rem 1rem;
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
</style>
