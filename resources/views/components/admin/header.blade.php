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
        </div>
    </div>
</div>