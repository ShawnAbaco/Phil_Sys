@props(['title' => 'Dashboard', 'subtitle' => 'Welcome back, Administrator'])

<div class="top-header">
    <div class="header-left">
        <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
        </button>
        <div class="page-title">
            <h1>{{ $title }}</h1>
            <p>{{ $subtitle }}</p>
        </div>
    </div>

    <div class="header-right">
        <div class="search-box">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="globalSearch" placeholder="Search...">
        </div>

        <div class="notification-badge" id="notificationBell">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
            </svg>
            <span class="notification-count" id="notificationCount">0</span>
        </div>

        <div class="admin-profile" id="adminProfile">
            <div class="admin-avatar">{{ substr(Auth::user()->name ?? 'Admin', 0, 2) }}</div>
            <div class="admin-info">
                <div class="admin-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                <div class="admin-role">{{ Auth::user()->designation ?? 'Administrator' }}</div>
            </div>

            {{-- Profile Dropdown Menu --}}
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">{{ substr(Auth::user()->name ?? 'Admin', 0, 2) }}</div>
                    <div class="dropdown-user-info">
                        <div class="dropdown-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                        <div class="dropdown-email">{{ Auth::user()->email ?? 'admin@example.com' }}</div>
                    </div>
                </div>
                <div class="dropdown-menu">
                    <a href="{{ route('profile.settings') }}" class="dropdown-item">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        <span>Profile Settings</span>
                    </a>
                    <a href="#" class="dropdown-item" id="changePasswordBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Change Password</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414l-5-5H3zm7 9a1 1 0 10-2 0v2a1 1 0 102 0v-2zM7 7h6v2H7V7z" clip-rule="evenodd" />
                        </svg>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Dropdown Styles - Add to your existing header.css */
.profile-dropdown {
    position: absolute;
    top: 70px;
    right: 0;
    width: 280px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    border: 1px solid var(--gray-200);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 1001;
    overflow: hidden;
}

.admin-profile {
    position: relative;
}

.admin-profile:hover .profile-dropdown,
.profile-dropdown:hover {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    padding: 20px;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 15px;
    background: linear-gradient(135deg, var(--psa-blue), var(--psa-red));
    color: white;
}

.dropdown-avatar {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--psa-blue);
    font-weight: 700;
    font-size: 1.2rem;
    text-transform: uppercase;
    border: 3px solid rgba(255,255,255,0.3);
}

.dropdown-user-info {
    flex: 1;
}

.dropdown-name {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 3px;
    color: white;
}

.dropdown-email {
    font-size: 0.75rem;
    opacity: 0.9;
    word-break: break-all;
    color: white;
}

.dropdown-menu {
    padding: 10px;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    color: var(--gray-700);
    text-decoration: none;
    border-radius: 40px;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.dropdown-item:hover {
    background: var(--gray-100);
    color: var(--psa-blue);
}

.dropdown-item.text-danger:hover {
    background: rgba(220, 38, 38, 0.1);
    color: var(--psa-red);
}

.dropdown-item svg {
    width: 18px;
    height: 18px;
}

.dropdown-divider {
    height: 1px;
    background: var(--gray-200);
    margin: 8px 0;
}

/* Mobile adjustments */
@media (max-width: 768px) {
    .profile-dropdown {
        width: 260px;
        right: 10px;
    }
    
    .admin-profile:hover .profile-dropdown {
        opacity: 0;
        visibility: hidden;
    }
    
    .profile-dropdown.show {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile dropdown toggle
    const profile = document.getElementById('adminProfile');
    const dropdown = document.getElementById('profileDropdown');
    
    if (profile && dropdown) {
        // For mobile: toggle on click
        profile.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!profile.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            }
        });
    }
    
    // Search functionality
    const searchInput = document.getElementById('globalSearch');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = `/admin/search?q=${encodeURIComponent(query)}`;
                }
            }
        });
    }
    
    // Load notifications
    loadNotifications();
});

function loadNotifications() {
    fetch('/admin/notifications')
        .then(response => response.json())
        .then(data => {
            const count = data.count || 0;
            document.getElementById('notificationCount').textContent = count > 9 ? '9+' : count;
        })
        .catch(error => console.error('Error loading notifications:', error));
}

// Refresh notifications every 30 seconds
setInterval(loadNotifications, 30000);
</script>