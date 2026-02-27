@props(['title' => 'National ID System'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - National ID System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/loading.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/loading.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PSA-themed header with arrow pattern (only for screener) -->
    @if ($isScreener())
        <div class="header-overlay"></div>
    @endif

    <header class="main-header">
        <div class="header-content">
            <!-- Logo Section (same for both) -->
            <div class="logo-section {{ $isScreener() ? 'logo-area' : '' }}">
                <img src="{{ asset('images/logo.png') }}" alt="National ID Logo" class="header-logo">
                <div class="logo-text">
                    <h1>National ID System</h1>
                    <span class="badge">{{ $isOperator() ? 'Operator Portal' : 'Screener Dashboard' }}</span>
                </div>
            </div>

            <!-- User Section -->
            <div class="user-section {{ $isScreener() ? 'user-area' : '' }}">
                {{-- WINDOW BADGE - Only show for Operator --}}
                @if ($isOperator() && $windowNum)
                    <div class="window-badge">
                        <svg class="window-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.616a1 1 0 01.894-1.79l1.599.8L9 4.323V3a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Window #{{ $windowNum }}</span>
                    </div>
                @endif



                <!-- User Menu Container -->
                <div class="user-menu-container">
                    <!-- User Info with Avatar and Arrow -->
                    <div class="user-info" id="userMenuButton">
                        <div class="user-details">
                            <span class="user-name">{{ $userName ?? 'User' }}</span>
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

                        <!-- Profile Settings Link (Optional) -->
                        <a href="#" class="dropdown-item">
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

    <!-- Overlay for clicking outside -->
    <div class="dropdown-overlay" id="dropdownOverlay"></div>

    @if ($isOperator())
        <script>
            // Update Date and Time for Operator
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
    </script>
</body>

</html>
