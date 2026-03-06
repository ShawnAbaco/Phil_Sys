<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - PSA PhilSys</title>

    <!-- Favicon / Logo -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/loading.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/loading.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Admin CSS - Single import that loads all modular CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @stack('styles')
</head>
<body>
    <!-- PSA Pattern Overlay -->
    <div class="psa-pattern"></div>

    <div class="app-wrapper">
        <!-- Sidebar Component -->
        <x-admin.sidebar :active="$activeMenu ?? 'dashboard'" />

        <div class="main-content">
            <!-- Header Component -->
            <x-admin.header 
                :title="$pageTitle ?? 'Dashboard'" 
                :subtitle="$pageSubtitle ?? 'Welcome back, Administrator'"
            />

            <!-- Main Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Notification Panel Component -->
    <x-admin.notification-panel />

    <!-- Loading Modal Component -->
    <x-admin.loading-modal />

    <script>
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');

            if (window.innerWidth <= 1200) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // Loading modal functions
        function showLoading() {
            document.getElementById('loadingModal').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loadingModal').classList.remove('show');
        }

        // Notification functions
        let notificationsVisible = false;

        document.getElementById('notificationBell')?.addEventListener('click', function(e) {
            e.stopPropagation();
            const panel = document.getElementById('notificationPanel');
            notificationsVisible = !notificationsVisible;
            panel.classList.toggle('show');
            
            if (notificationsVisible) {
                loadNotifications();
            }
        });

        document.addEventListener('click', function(event) {
            const panel = document.getElementById('notificationPanel');
            const bell = document.getElementById('notificationBell');
            
            if (panel && !panel.contains(event.target) && !bell.contains(event.target)) {
                panel.classList.remove('show');
                notificationsVisible = false;
            }
        });

        function closeNotifications() {
            document.getElementById('notificationPanel')?.classList.remove('show');
            notificationsVisible = false;
        }

        function loadNotifications() {
            fetch('/admin/notifications')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notificationList');
                    if (!list) return;
                    
                    if (data.length === 0) {
                        list.innerHTML = '<div class="notification-item"><p>No new notifications</p></div>';
                    } else {
                        list.innerHTML = data.map(n => `
                            <div class="notification-item">
                                <p>${n.message}</p>
                                <small>${n.time}</small>
                            </div>
                        `).join('');
                        
                        // Update notification count
                        const countBadge = document.getElementById('notificationCount');
                        if (countBadge) {
                            countBadge.textContent = data.length;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        // Global search
        let searchTimeout;
        const searchInput = document.getElementById('globalSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const query = e.target.value;
                    if (query.length > 2) {
                        console.log('Searching for:', query);
                        // Implement search functionality
                    }
                }, 500);
            });
        }
    </script>

    @stack('scripts')
</body>
</html>