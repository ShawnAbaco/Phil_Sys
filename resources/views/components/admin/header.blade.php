@props(['title' => 'Dashboard', 'subtitle' => 'Welcome back, Administrator'])

<div class="top-header">
    <div class="header-left">
        <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd" />
            </svg>
        </button>
        <div class="page-title">
            <h1>{{ $title }}</h1>
            <p>{{ $subtitle }}</p>
        </div>
    </div>

    <div class="header-right">
        <div class="notification-badge" id="notificationBell">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path
                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
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
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Profile Settings</span>
                    </a>
                    <a href="#" class="dropdown-item" id="changePasswordBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Change Password</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" class="dropdown-item text-danger"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414l-5-5H3zm7 9a1 1 0 10-2 0v2a1 1 0 102 0v-2zM7 7h6v2H7V7z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Profile dropdown toggle
        const adminProfile = document.getElementById('adminProfile');
        const profileDropdown = document.getElementById('profileDropdown');

        if (adminProfile && profileDropdown) {
            // Toggle dropdown on click
            adminProfile.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!adminProfile.contains(event.target)) {
                    profileDropdown.classList.remove('show');
                }
            });

            // Prevent dropdown from closing when clicking inside
            profileDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Change password button handler
        const changePasswordBtn = document.getElementById('changePasswordBtn');
        if (changePasswordBtn) {
            changePasswordBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // Add your change password modal logic here
                alert('Change password functionality will be implemented');
                profileDropdown.classList.remove('show');
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
                const notificationCount = document.getElementById('notificationCount');
                if (notificationCount) {
                    notificationCount.textContent = count > 9 ? '9+' : count;
                    notificationCount.style.display = count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => console.error('Error loading notifications:', error));
    }

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
</script>
