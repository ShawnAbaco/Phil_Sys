{{-- resources/views/components/header.blade.php --}}
@props(['title' => 'PSA - Queue Management System'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - PSA - Queue Management System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/loading.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/loading.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/screener/appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/screener/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/screener/reports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/screener/transactions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <!-- Dark Mode CSS - loaded separately -->
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stat-grid.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dark Mode Script -->
    <script>
        // Dark mode initialization - runs before page render to prevent flash
        (function() {
            try {
                const darkMode = localStorage.getItem('darkMode') === 'true';
                if (darkMode) {
                    document.documentElement.classList.add('dark-mode');
                } else {
                    document.documentElement.classList.remove('dark-mode');
                }
            } catch (e) {
                console.error('Dark mode initialization failed:', e);
            }
        })();
    </script>
</head>

<body>
    <!-- PSA-themed header with arrow pattern (only for screeners) -->

    <div class="header-overlay"></div>


    <header class="main-header">
        <div class="header-content">



            <!-- Logo Section -->

            <div class="page-header">

                <div class="page-title">
                    <div class="logo-section">
                        <div class="logo-text">
                            <h1>Queue Management System</h1>
                            <span class="badge">{{ $getPortalBadge() }}</span>
                        </div>
                    </div>

                </div>




            </div>

            <!-- Overlay for clicking outside -->
            <div class="dropdown-overlay" id="dropdownOverlay"></div>

            <!-- User Section - Same for Operators and Assistants -->
            <div class="user-section">
                {{-- WINDOW BADGE - Show for both Operators and Assistants --}}
                @if ($showWindowBadge())
                    <div class="window-badge">
                        <svg class="window-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.616a1 1 0 01.894-1.79l1.599.8L9 4.323V3a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Window #{{ $windowNum }}</span>
                    </div>
                @endif

                <!-- Dark Mode Toggle Button -->
                <div class="dark-mode-toggle" id="darkModeToggle" title="Toggle dark mode">
                    <!-- Sun icon (for light mode) -->
                    <svg class="sun-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            clip-rule="evenodd" />
                    </svg>
                    <!-- Moon icon (for dark mode) -->
                    <svg class="moon-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                </div>

                <!-- User Menu Container -->
                <div class="user-menu-container">
                    <!-- User Info with Avatar and Arrow -->
                    <div class="user-info" id="userMenuButton">
                        <div class="user-details">
                            <span class="user-name">{{ $userName ?? 'User' }}</span>
                            <span class="user-designation">{{ session('designation') }}</span>

                            <svg class="dropdown-arrow" width="20" height="20" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="user-avatar">
                            {{ $userName ? substr($userName, 0, 1) : 'U' }}
                        </div>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu" id="userDropdown">
                        <!-- Profile Settings Link -->
                        <a href="{{ route('profile.settings') }}" class="dropdown-item">
                            <svg class="dropdown-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Profile Settings
                        </a>

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <svg class="dropdown-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414l-5-5H3zm7 10a1 1 0 11-2 0V9.414l-1.293 1.293a1 1 0 01-1.414-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L10 9.414V13z"
                                        clip-rule="evenodd" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Header with Title and Welcome Message -->


    @if ($isOperator())
        <script>
            // Update Date and Time for Operators and Assistants
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };
                const datetimeElement = document.querySelector('#datetime span');
                if (datetimeElement) {
                    datetimeElement.textContent = now.toLocaleDateString('en-US', options);
                }
            }
            setInterval(updateDateTime, 1000);
            updateDateTime();
        </script>
    @endif

    <script>
        // Dropdown menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            const dropdownOverlay = document.getElementById('dropdownOverlay');
            const dropdownArrow = document.querySelector('.dropdown-arrow');

            function toggleDropdown(show) {
                if (show) {
                    userDropdown.classList.add('show');
                    dropdownOverlay.classList.add('show');
                    userMenuButton.classList.add('active');
                    dropdownArrow.classList.add('rotated');
                } else {
                    userDropdown.classList.remove('show');
                    dropdownOverlay.classList.remove('show');
                    userMenuButton.classList.remove('active');
                    dropdownArrow.classList.remove('rotated');
                }
            }

            // Toggle dropdown on button click
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                const isShowing = userDropdown.classList.contains('show');
                toggleDropdown(!isShowing);
            });

            // Close dropdown when clicking outside
            dropdownOverlay.addEventListener('click', function() {
                toggleDropdown(false);
            });

            // Close dropdown when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && userDropdown.classList.contains('show')) {
                    toggleDropdown(false);
                }
            });

            // Prevent dropdown from closing when clicking inside it
            userDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Handle responsive behavior
            function handleResize() {
                if (window.innerWidth <= 768) {
                    // Mobile adjustments if needed
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize();
        });

        // Dark Mode Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const htmlElement = document.documentElement;

            // Check for saved preference
            const darkMode = localStorage.getItem('darkMode') === 'true';

            // Apply dark mode if saved preference exists
            if (darkMode) {
                htmlElement.classList.add('dark-mode');
            }

            // Toggle dark mode on button click
            darkModeToggle.addEventListener('click', function() {
                const isDarkMode = htmlElement.classList.contains('dark-mode');

                if (isDarkMode) {
                    htmlElement.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'false');
                } else {
                    htmlElement.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'true');
                }
            });
        });

        // Optional: Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            const htmlElement = document.documentElement;
            const savedPreference = localStorage.getItem('darkMode');

            // Only change if user hasn't set a manual preference
            if (savedPreference === null) {
                if (e.matches) {
                    htmlElement.classList.add('dark-mode');
                } else {
                    htmlElement.classList.remove('dark-mode');
                }
            }
        });
    </script>
</body>

</html>
