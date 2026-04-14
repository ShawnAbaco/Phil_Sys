{{-- resources/views/components/sidebar.blade.php --}}
@php
    // Get portal badge based on user role
    $designation = session('designation', '');
    $userRole = session('user_role', '');
    
    $portalBadge = '';
    $isOperator = false;
    $isScreener = false;
    
    if (str_contains(strtolower($designation), 'operator')) {
        $portalBadge = 'OPERATOR PORTAL';
        $isOperator = true;
    } elseif (str_contains(strtolower($designation), 'assistant')) {
        $portalBadge = 'ASSISTANT PORTAL';
        $isOperator = true;
    } elseif ($userRole === 'screener' || str_contains(strtolower($designation), 'screener')) {
        $portalBadge = 'SCREENER DASHBOARD';
        $isScreener = true;
    } else {
        $portalBadge = 'PORTAL';
    }
@endphp

<aside class="sidebar" id="mainSidebar">
    <div class="sidebar-header">

    <div class="logo-section">
                <img src="{{ asset('images/logo.png') }}" alt="National ID Logo" class="header-logo">
                <div class="logo-text">
                </div>
            </div>
        
        <button class="sidebar-close-btn" id="closeSidebarBtn">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        @if($isOperator)
            {{-- Operator Navigation --}}
            <a href="{{ route('screener.dashboard') }}" class="sidebar-link {{ request()->routeIs('screener.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('screener.appointments') }}" class="sidebar-link {{ request()->routeIs('screener.appointments') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                        clip-rule="evenodd" />
                </svg>
                <span>Serving</span>
                @php
                    $servingCount = \App\Models\TblAppointment::whereDate('date', Carbon\Carbon::now('Asia/Manila')->toDateString())
                        ->where('status', 'serving')
                        ->where('user_id', Auth::id())
                        ->count();
                @endphp
                @if($servingCount > 0)
                    <span class="badge serving-badge">{{ $servingCount }}</span>
                @endif
            </a>

            <!-- <a href="{{ route('screener.transactions') }}" class="sidebar-link {{ request()->routeIs('screener.transactions*') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z"
                        clip-rule="evenodd" />
                </svg>
                <span>Recent Transactions</span>
            </a> -->

            <a href="{{ route('screener.reports') }}" class="sidebar-link {{ request()->routeIs('screener.reports*') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z"
                        clip-rule="evenodd" />
                </svg>
                <span>Reports</span>
            </a>
        @elseif($isScreener)
            {{-- Screener Navigation --}}
            <a href="{{ route('screener.dashboard') }}" class="sidebar-link {{ request()->routeIs('screener.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('screener.appointments') }}" class="sidebar-link {{ request()->routeIs('screener.appointments') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                <span>Appointments</span>
                @php
                    $pendingCount = \App\Models\TblAppointment::whereDate('date', Carbon\Carbon::now('Asia/Manila')->toDateString())
                        ->where('status', 'pending')
                        ->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="badge pending-badge">{{ $pendingCount }}</span>
                @endif
            </a>

            <!-- <a href="{{ route('screener.transactions') }}" class="sidebar-link {{ request()->routeIs('screener.transactions*') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z"
                        clip-rule="evenodd" />
                </svg>
                <span>Recent Transactions</span>
            </a> -->

            <a href="{{ route('screener.reports') }}" class="sidebar-link {{ request()->routeIs('screener.reports*') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z"
                        clip-rule="evenodd" />
                </svg>
                <span>Reports</span>
            </a>
        @endif
    </nav>

    
</aside>

<style>
/* Sidebar Styles */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: 280px;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    color: #e2e8f0;
    display: flex;
    flex-direction: column;
    z-index: 1000;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar.open {
    transform: translateX(0);
}

.sidebar-header {
    padding: 24px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.sidebar-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.sidebar-logo-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 8px;
}

.sidebar-logo-text h2 {
    font-size: 1rem;
    font-weight: 700;
    color: white;
    margin: 0;
    line-height: 1.2;
}

.sidebar-badge {
    font-size: 0.65rem;
    color: #fbbf24;
    font-weight: 600;
    display: block;
    margin-top: 2px;
}

.sidebar-close-btn {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 8px;
    padding: 6px;
    cursor: pointer;
    color: #94a3b8;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar-close-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.sidebar-close-btn svg {
    width: 20px;
    height: 20px;
}

.sidebar-nav {
    flex: 1;
    padding: 24px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    font-weight: 500;
    position: relative;
}

.sidebar-link svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.sidebar-link:hover {
    background: rgba(59, 130, 246, 0.1);
    color: white;
}

.sidebar-link.active {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.sidebar-link.active svg {
    color: white;
}

.serving-badge {
    position: absolute;
    right: 16px;
    background: #ef4444;
    color: white;
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    min-width: 24px;
    text-align: center;
    animation: pulse-badge 1.5s infinite;
}

.pending-badge {
    position: absolute;
    right: 16px;
    background: #f59e0b;
    color: white;
    font-size: 0.6875rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    min-width: 24px;
    text-align: center;
    animation: pulse-badge 1.5s infinite;
}

@keyframes pulse-badge {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.05);
    }
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 10px 16px;
    background: rgba(239, 68, 68, 0.1);
    border: none;
    border-radius: 10px;
    color: #f87171;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.logout-btn svg {
    width: 18px;
    height: 18px;
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

/* Overlay for sidebar */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    display: none;
    backdrop-filter: blur(2px);
}

.sidebar-overlay.active {
    display: block;
}

/* Menu Toggle Button */
.menu-toggle-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s ease;
    color: var(--psa-blue, #0038A8);
    margin-right: 10px;
}

.menu-toggle-btn:hover {
    background: rgba(0, 56, 168, 0.1);
}

.menu-toggle-btn svg {
    width: 24px;
    height: 24px;
}

/* Page Header with Title and Welcome */
.page-header {
    margin-bottom: 24px;
    margin-top: 20px;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-800, #1f2937);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.welcome-message {
    font-size: 0.95rem;
    color: var(--gray-600, #4b5563);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: 60px;
}

.welcome-message svg {
    width: 18px;
    height: 18px;
    color: var(--psa-blue, #0038A8);
}

.welcome-role {
    font-weight: 600;
    color: var(--psa-blue, #0038A8);
}

/* Responsive */
@media (min-width: 769px) {
    .menu-toggle-btn {
        display: block;
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.25rem;
    }
}
</style>

<script>
    // Sidebar toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('mainSidebar');
        
        // Create overlay if not exists
        let overlay = document.querySelector('.sidebar-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);
        }
        
        // Create menu toggle button if not exists
        if (!document.querySelector('.menu-toggle-btn')) {
            const pageTitle = document.querySelector('.page-title');
            if (pageTitle) {
                const menuToggle = document.createElement('button');
                menuToggle.className = 'menu-toggle-btn';
                menuToggle.setAttribute('id', 'menuToggleBtn');
                menuToggle.innerHTML = `
                    <svg viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                `;
                pageTitle.insertBefore(menuToggle, pageTitle.firstChild);
            }
        }
        
        const menuToggle = document.getElementById('menuToggleBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        
        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (menuToggle) {
            menuToggle.addEventListener('click', openSidebar);
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', closeSidebar);
        }
        
        overlay.addEventListener('click', closeSidebar);
        
        // Close sidebar on link click
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', closeSidebar);
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
    });
</script>