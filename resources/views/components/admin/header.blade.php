@props(['title' => 'Dashboard', 'subtitle' => 'Welcome back, Administrator'])

<!-- Menu Overlay for Blur Effect -->
<div id="menuOverlay" class="menu-overlay"></div>

<div class="top-header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle">
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
            <span class="notification-count" id="notificationCount" style="display: none;">0</span>
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

<!-- Notification Panel -->
<div id="notificationPanel" class="notification-panel" style="display: none;">
    <div class="notification-header">
        <h3>
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                <path
                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
            </svg>
            Notifications
        </h3>
        <button onclick="closeNotificationPanel()">&times;</button>
    </div>
    <div class="notification-list" id="notificationList">
        <div style="padding: 20px; text-align: center; color: #6b7280;">
            Loading notifications...
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const menuOverlay = document.getElementById('menuOverlay');
        const adminProfile = document.getElementById('adminProfile');
        const profileDropdown = document.getElementById('profileDropdown');
        const notificationBell = document.getElementById('notificationBell');
        const notificationPanel = document.getElementById('notificationPanel');

        // ============================================
        // SIDEBAR TOGGLE WITH BLUR EFFECT
        // ============================================
        function toggleSidebar() {
            if (!sidebar) return;

            const isOpen = sidebar.classList.contains('open');

            if (isOpen) {
                // Close sidebar
                sidebar.classList.remove('open');
                menuOverlay.classList.remove('show');
                document.body.classList.remove('sidebar-open');
                menuToggle.classList.remove('active');

                // Remove body scroll lock
                document.body.style.overflow = '';
            } else {
                // Open sidebar
                sidebar.classList.add('open');
                menuOverlay.classList.add('show');
                document.body.classList.add('sidebar-open');
                menuToggle.classList.add('active');

                // Lock body scroll on mobile
                if (window.innerWidth <= 768) {
                    document.body.style.overflow = 'hidden';
                }

                // Close any open dropdowns
                if (profileDropdown) profileDropdown.classList.remove('show');
                if (notificationPanel) notificationPanel.classList.remove('show');
                notificationPanel.style.display = 'none';
            }
        }

        // Menu toggle click handler
        if (menuToggle) {
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        // Close sidebar when clicking overlay
        if (menuOverlay) {
            menuOverlay.addEventListener('click', function() {
                if (sidebar && sidebar.classList.contains('open')) {
                    toggleSidebar();
                }
            });
        }

        // Close sidebar when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
                toggleSidebar();
            }
        });

        // ============================================
        // PROFILE DROPDOWN
        // ============================================
        if (adminProfile && profileDropdown) {
            adminProfile.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');

                // Close notification panel when opening profile
                if (notificationPanel) {
                    notificationPanel.classList.remove('show');
                    notificationPanel.style.display = 'none';
                }
            });
        }

        // Close profile dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (adminProfile && profileDropdown && !adminProfile.contains(event.target)) {
                profileDropdown.classList.remove('show');
            }

            // Close notification panel when clicking outside
            if (notificationPanel && notificationBell && !notificationBell.contains(event.target) && !
                notificationPanel.contains(event.target)) {
                notificationPanel.classList.remove('show');
                notificationPanel.style.display = 'none';
            }
        });

        // Prevent dropdown from closing when clicking inside
        if (profileDropdown) {
            profileDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // ============================================
        // NOTIFICATION PANEL
        // ============================================
        if (notificationBell && notificationPanel) {
            notificationBell.addEventListener('click', function(e) {
                e.stopPropagation();

                // Toggle notification panel
                const isVisible = notificationPanel.style.display === 'block';

                if (isVisible) {
                    notificationPanel.classList.remove('show');
                    notificationPanel.style.display = 'none';
                } else {
                    // Close profile dropdown if open
                    if (profileDropdown) profileDropdown.classList.remove('show');

                    notificationPanel.style.display = 'block';
                    notificationPanel.classList.add('show');
                    loadNotifications();
                }
            });
        }

        window.closeNotificationPanel = function() {
            if (notificationPanel) {
                notificationPanel.classList.remove('show');
                notificationPanel.style.display = 'none';
            }
        };

        function loadNotifications() {
            // Simulate API call - replace with your actual endpoint
            fetch('/admin/notifications')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    const count = data.count || 0;
                    const notificationCount = document.getElementById('notificationCount');

                    if (notificationCount) {
                        notificationCount.textContent = count > 9 ? '9+' : count;
                        notificationCount.style.display = count > 0 ? 'flex' : 'none';
                    }

                    if (notificationList) {
                        if (data.notifications && data.notifications.length > 0) {
                            notificationList.innerHTML = data.notifications.map(notification => `
                                <div class="notification-item ${notification.read ? '' : 'unread'}" onclick="markAsRead(${notification.id})">
                                    <p>${notification.message}</p>
                                    <small>${notification.time_ago}</small>
                                </div>
                            `).join('');
                        } else {
                            notificationList.innerHTML = `
                                <div style="padding: 40px 20px; text-align: center;">
                                    <svg width="40" height="40" viewBox="0 0 20 20" fill="#9ca3af" style="margin-bottom: 12px;">
                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                    </svg>
                                    <p style="color: #6b7280;">No new notifications</p>
                                </div>
                            `;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    const notificationList = document.getElementById('notificationList');
                    if (notificationList) {
                        notificationList.innerHTML = `
                            <div style="padding: 20px; text-align: center; color: #ef4444;">
                                <svg width="40" height="40" viewBox="0 0 20 20" fill="currentColor" style="margin-bottom: 12px;">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p>Failed to load notifications</p>
                                <button onclick="retryLoadNotifications()" style="margin-top: 10px; padding: 6px 12px; background: #0038a8; color: white; border: none; border-radius: 6px; cursor: pointer;">Retry</button>
                            </div>
                        `;
                    }
                });
        }

        window.markAsRead = function(id) {
            fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(() => loadNotifications())
                .catch(error => console.error('Error marking notification as read:', error));
        };

        window.retryLoadNotifications = function() {
            loadNotifications();
        };

        // Change password button handler
        const changePasswordBtn = document.getElementById('changePasswordBtn');
        if (changePasswordBtn) {
            changePasswordBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (profileDropdown) profileDropdown.classList.remove('show');
                // Add your change password modal logic here
                alert('Change password functionality will be implemented');
            });
        }

        // Load initial notifications count only (not the panel)
        function loadNotificationCount() {
            fetch('/admin/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const count = data.count || 0;
                    const notificationCount = document.getElementById('notificationCount');
                    if (notificationCount) {
                        notificationCount.textContent = count > 9 ? '9+' : count;
                        notificationCount.style.display = count > 0 ? 'flex' : 'none';
                    }
                })
                .catch(error => console.error('Error loading notification count:', error));
        }

        // Load initial notification count
        loadNotificationCount();

        // Refresh notification count every 30 seconds
        setInterval(loadNotificationCount, 30000);

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768 && sidebar && sidebar.classList.contains('open')) {
                    document.body.style.overflow = '';
                }

                // Close notification panel on resize if it's open
                if (notificationPanel && notificationPanel.style.display === 'block') {
                    notificationPanel.classList.remove('show');
                    notificationPanel.style.display = 'none';
                }
            }, 250);
        });
    });
</script>
