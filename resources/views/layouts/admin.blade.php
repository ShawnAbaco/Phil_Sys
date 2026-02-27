<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Queue System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-transition { transition: all 0.3s ease; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar-transition bg-white shadow-xl w-64 flex-shrink-0 hidden md:block">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-20 border-b border-gray-200">
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Queue<span class="text-blue-600">Master</span></span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto scrollbar-hide py-6 px-4">
                    <div class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                            <i class="fas fa-home w-5"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <!-- Queue Management -->
                        <div class="nav-group">
                            <button class="nav-group-btn w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-users w-5"></i>
                                    <span class="font-medium">Queue Management</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform"></i>
                            </button>
                            <div class="nav-group-content pl-12 space-y-1 mt-1 hidden">
                                <a href="{{ route('admin.queue.current') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Current Queue</a>
                                <a href="{{ route('admin.queue.history') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Queue History</a>
                                <a href="{{ route('admin.queue.categories') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Categories</a>
                            </div>
                        </div>

                        <!-- User Management -->
                        <div class="nav-group">
                            <button class="nav-group-btn w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-user-cog w-5"></i>
                                    <span class="font-medium">Users</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform"></i>
                            </button>
                            <div class="nav-group-content pl-12 space-y-1 mt-1 hidden">
                                <a href="{{ route('admin.users.operators') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Operators</a>
                                <a href="{{ route('admin.users.customers') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Customers</a>
                                <a href="{{ route('admin.users.admins') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Administrators</a>
                                <a href="{{ route('admin.users.roles') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Roles & Permissions</a>
                            </div>
                        </div>

                        <!-- Appointments -->
                        <a href="{{ route('admin.appointments') }}" class="nav-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-calendar-check w-5"></i>
                            <span class="font-medium">Appointments</span>
                        </a>

                        <!-- Reports -->
                        <div class="nav-group">
                            <button class="nav-group-btn w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-chart-bar w-5"></i>
                                    <span class="font-medium">Reports</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform"></i>
                            </button>
                            <div class="nav-group-content pl-12 space-y-1 mt-1 hidden">
                                <a href="{{ route('admin.reports.daily') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Daily Reports</a>
                                <a href="{{ route('admin.reports.weekly') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Weekly Summary</a>
                                <a href="{{ route('admin.reports.monthly') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Monthly Analytics</a>
                                <a href="{{ route('admin.reports.performance') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Performance Metrics</a>
                            </div>
                        </div>

                        <!-- System Settings -->
                        <div class="nav-group">
                            <button class="nav-group-btn w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-cog w-5"></i>
                                    <span class="font-medium">Settings</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform"></i>
                            </button>
                            <div class="nav-group-content pl-12 space-y-1 mt-1 hidden">
                                <a href="{{ route('admin.settings.general') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">General</a>
                                <a href="{{ route('admin.settings.windows') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Window Configuration</a>
                                <a href="{{ route('admin.settings.notifications') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Notifications</a>
                                <a href="{{ route('admin.settings.backup') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-600">Backup & Restore</a>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- User Profile -->
                <div class="border-t border-gray-200 p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">Administrator</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <button class="md:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div class="flex items-center space-x-4 ml-auto">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <button class="notifications-toggle text-gray-500 hover:text-gray-700 relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                            </button>
                        </div>

                        <!-- Quick Actions -->
                        <div class="relative">
                            <button class="quick-actions-toggle flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus"></i>
                                <span class="hidden md:inline">Quick Actions</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar (Hidden by default) -->
    <div class="mobile-sidebar fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="absolute left-0 top-0 bottom-0 w-64 bg-white">
            <!-- Same sidebar content as above -->
        </div>
    </div>

    <script>
        // Toggle navigation groups
        document.querySelectorAll('.nav-group-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                const icon = btn.querySelector('.fa-chevron-down');
                content.classList.toggle('hidden');
                icon.style.transform = content.classList.contains('hidden') ? '' : 'rotate(180deg)';
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
