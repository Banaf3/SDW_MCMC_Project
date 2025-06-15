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
                if ($user && !empty($user->ProfilePicture)) {
                    $profilePic = $user->ProfilePicture;
                }
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
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item logout-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg width="16" height="16" fill="currentColor">
                            <path d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Log Out
                    </button>
                </form>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <script>
                    document.querySelectorAll('.logout-item').forEach(function(btn) {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            document.getElementById('logout-form').submit();
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</header>

<script>
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const userInfo = document.querySelector('.header-user-info');
    const dropdown = document.getElementById('userDropdown');
    
    if (!userInfo.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});
</script>

<style>
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
