<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - National ID System</title>

    <!-- Favicon / Logo -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/loading.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/loading.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --psa-red: #CE1126;
            --psa-blue: #0038A8;
            --psa-yellow: #FCD116;
            --psa-red-light: rgba(206, 17, 38, 0.1);
            --psa-blue-light: rgba(0, 56, 168, 0.1);
            --psa-yellow-light: rgba(252, 209, 22, 0.1);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        html, body {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-100);
            position: relative;
            display: flex;
        }

        /* PSA Pattern Overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 50px,
                    rgba(206, 17, 38, 0.02) 50px,
                    rgba(206, 17, 38, 0.02) 100px
                );
            pointer-events: none;
            z-index: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            border-right: 3px solid var(--psa-yellow);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 20;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 2px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 15px;
            background: linear-gradient(135deg, var(--psa-blue), var(--psa-red));
        }

        .sidebar-header img {
            height: 45px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .sidebar-header h2 {
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .sidebar-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.8rem;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .menu-section {
            margin-bottom: 25px;
        }

        .menu-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-500);
            font-weight: 600;
            margin-bottom: 12px;
            padding-left: 12px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--gray-700);
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .menu-item:hover {
            background: var(--psa-blue-light);
            color: var(--psa-blue);
        }

        .menu-item.active {
            background: var(--psa-blue);
            color: white;
            box-shadow: 0 4px 10px var(--psa-blue-light);
        }

        .menu-item.active svg {
            color: white;
        }

        .menu-item svg {
            width: 20px;
            height: 20px;
            color: var(--gray-500);
            transition: color 0.2s ease;
        }

        .menu-item:hover svg {
            color: var(--psa-blue);
        }

        .menu-badge {
            margin-left: auto;
            background: var(--psa-red);
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid var(--psa-yellow);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            height: 80px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray-600);
        }

        .page-title h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--psa-blue);
            letter-spacing: -0.5px;
        }

        .page-title p {
            color: var(--gray-500);
            font-size: 0.9rem;
            margin-top: 2px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 16px 10px 42px;
            border: 2px solid var(--gray-200);
            border-radius: 40px;
            width: 280px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--psa-blue);
            box-shadow: 0 0 0 4px var(--psa-blue-light);
        }

        .search-box svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--gray-400);
        }

        .notification-badge {
            position: relative;
            cursor: pointer;
        }

        .notification-badge svg {
            width: 24px;
            height: 24px;
            color: var(--gray-600);
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--psa-red);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: var(--gray-50);
            border-radius: 40px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .admin-profile:hover {
            border-color: var(--psa-yellow);
            background: white;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--psa-blue), var(--psa-red));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .admin-info {
            line-height: 1.3;
        }

        .admin-name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.95rem;
        }

        .admin-role {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* Dashboard Content */
        .dashboard-content {
            flex: 1;
            padding: 25px 30px;
            overflow-y: auto;
            background: var(--gray-50);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 2px solid var(--gray-200);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: var(--psa-yellow);
            box-shadow: 0 8px 24px rgba(0, 56, 168, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            display: block;
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-bottom: 4px;
            font-weight: 500;
        }

        .stat-value {
            display: block;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1.2;
        }

        .stat-change {
            font-size: 0.8rem;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Charts Row */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            border: 2px solid var(--gray-200);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-header h3 svg {
            width: 20px;
            height: 20px;
            color: var(--psa-blue);
        }

        .chart-period {
            padding: 6px 12px;
            border: 2px solid var(--gray-200);
            border-radius: 30px;
            font-size: 0.8rem;
            color: var(--gray-600);
            background: none;
            cursor: pointer;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            border: 2px solid var(--gray-200);
            margin-bottom: 25px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-header h3 svg {
            width: 20px;
            height: 20px;
            color: var(--psa-blue);
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: var(--psa-blue);
            color: white;
        }

        .btn-primary:hover {
            background: var(--psa-red);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px var(--psa-red-light);
        }

        .btn-outline {
            background: white;
            border: 2px solid var(--gray-200);
            color: var(--gray-700);
        }

        .btn-outline:hover {
            border-color: var(--psa-blue);
            color: var(--psa-blue);
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 16px;
            background: var(--gray-50);
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--gray-200);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--gray-200);
            color: var(--gray-700);
            font-size: 0.95rem;
        }

        tr:hover td {
            background: var(--gray-50);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: var(--psa-blue-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--psa-blue);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: var(--success);
            color: white;
        }

        .status-inactive {
            background: var(--gray-200);
            color: var(--gray-600);
        }

        .status-pending {
            background: var(--warning);
            color: white;
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .action-btn:hover {
            background: var(--psa-blue);
            color: white;
        }

        .action-btn.delete:hover {
            background: var(--danger);
            color: white;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .quick-action-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            border: 2px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quick-action-card:hover {
            transform: translateY(-2px);
            border-color: var(--psa-yellow);
            box-shadow: 0 8px 24px rgba(0, 56, 168, 0.1);
        }

        .quick-action-icon {
            width: 60px;
            height: 60px;
            background: var(--psa-blue-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--psa-blue);
            font-size: 24px;
        }

        .quick-action-title {
            font-weight: 600;
            color: var(--gray-800);
        }

        .quick-action-desc {
            font-size: 0.8rem;
            color: var(--gray-500);
            text-align: center;
        }

        /* Loading Modal */
        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading-modal.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .loading-content {
            background: white;
            padding: 30px 45px;
            border-radius: 32px;
            text-align: center;
            border: 4px solid var(--psa-yellow);
            box-shadow: 0 25px 50px -15px rgba(0, 0, 0, 0.5);
        }

        .rotate-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            animation: rotate 1.2s linear infinite;
        }

        .loading-text {
            color: var(--psa-blue);
            font-weight: 600;
            font-size: 16px;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-row {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .sidebar {
                position: fixed;
                left: -280px;
                transition: left 0.3s ease;
                z-index: 100;
            }

            .sidebar.open {
                left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .top-header {
                flex-direction: column;
                height: auto;
                gap: 15px;
                padding: 15px;
            }

            .header-left {
                width: 100%;
                justify-content: space-between;
            }

            .header-right {
                width: 100%;
                flex-wrap: wrap;
                justify-content: center;
            }

            .search-box {
                width: 100%;
            }

            .search-box input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="National ID Logo">
            <div>
                <h2>NATIONAL ID SYSTEM</h2>
                <p>Administrator</p>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Main</div>
                <div class="menu-item active">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span>Dashboard</span>
                </div>
                <div class="menu-item">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    <span>Users Management</span>
                </div>
                <div class="menu-item">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span>Appointments</span>
                    <span class="menu-badge">12</span>
                </div>
                <div class="menu-item">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                    <span>Reports</span>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Queue Management</div>
                <div class="menu-item">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.963 7.963 0 0114.5 16z" />
                    </svg>
                    <span>Windows</span>
                </div>
                <div class="menu-item">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    <span>Queue History</span>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Settings</div>
                <div class="menu-item">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    <span>System Settings</span>
                </div>
                <div class="menu-item">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Audit Logs</span>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="page-title">
                    <h1>Dashboard</h1>
                    <p>Welcome back, Administrator</p>
                </div>
            </div>

            <div class="header-right">
                <div class="search-box">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    <input type="text" placeholder="Search...">
                </div>

                <div class="notification-badge">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                    <span class="notification-count">5</span>
                </div>

                <div class="admin-profile">
                    <div class="admin-avatar">AD</div>
                    <div class="admin-info">
                        <div class="admin-name">Admin User</div>
                        <div class="admin-role">Super Administrator</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--psa-blue), var(--psa-red))">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Users</span>
                        <span class="stat-value">1,234</span>
                        <div class="stat-change positive">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                            </svg>
                            +12% this week
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #FBBF24)">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Appointments Today</span>
                        <span class="stat-value">156</span>
                        <div class="stat-change positive">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                            </svg>
                            +8% from yesterday
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--psa-red), #EF4444)">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Pending Queues</span>
                        <span class="stat-value">42</span>
                        <div class="stat-change negative">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                            </svg>
                            -5 from peak
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--success), #34D399)">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Completed Today</span>
                        <span class="stat-value">114</span>
                        <div class="stat-change positive">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                            </svg>
                            73% success rate
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                            Appointments Overview
                        </h3>
                        <select class="chart-period">
                            <option>Last 7 days</option>
                            <option>Last 30 days</option>
                            <option>This month</option>
                        </select>
                    </div>
                    <div style="height: 250px; display: flex; align-items: flex-end; gap: 10px; padding: 20px 0;">
                        <div style="flex: 1; text-align: center;">
                            <div style="height: 180px; background: var(--psa-blue-light); border-radius: 8px 8px 0 0; margin-bottom: 8px;"></div>
                            <span style="font-size: 0.8rem; color: var(--gray-600);">Mon</span>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <div style="height: 120px; background: var(--psa-blue-light); border-radius: 8px 8px 0 0; margin-bottom: 8px;"></div>
                            <span style="font-size: 0.8rem; color: var(--gray-600);">Tue</span>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <div style="height: 200px; background: var(--psa-blue-light); border-radius: 8px 8px 0 0; margin-bottom: 8px;"></div>
                            <span style="font-size: 0.8rem; color: var(--gray-600);">Wed</span>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <div style="height: 160px; background: var(--psa-blue-light); border-radius: 8px 8px 0 0; margin-bottom: 8px;"></div>
                            <span style="font-size: 0.8rem; color: var(--gray-600);">Thu</span>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <div style="height: 220px; background: var(--psa-blue-light); border-radius: 8px 8px 0 0; margin-bottom: 8px;"></div>
                            <span style="font-size: 0.8rem; color: var(--gray-600);">Fri</span>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <div style="height: 140px; background: var(--psa-blue-light); border-radius: 8px 8px 0 0; margin-bottom: 8px;"></div>
                            <span style="font-size: 0.8rem; color: var(--gray-600);">Sat</span>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <div style="height: 100px; background: var(--psa-blue-light); border-radius: 8px 8px 0 0; margin-bottom: 8px;"></div>
                            <span style="font-size: 0.8rem; color: var(--gray-600);">Sun</span>
                        </div>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3>
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                            </svg>
                            Queue Distribution
                        </h3>
                    </div>
                    <div style="height: 250px; display: flex; align-items: center; justify-content: center;">
                        <div style="width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(var(--psa-blue) 0deg 130deg, var(--psa-red) 130deg 220deg, var(--psa-yellow) 220deg 300deg, var(--success) 300deg 360deg);"></div>
                    </div>
                    <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <div style="width: 12px; height: 12px; background: var(--psa-blue); border-radius: 4px;"></div>
                            <span style="font-size: 0.8rem;">Status (36%)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <div style="width: 12px; height: 12px; background: var(--psa-red); border-radius: 4px;"></div>
                            <span style="font-size: 0.8rem;">Registration (28%)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <div style="width: 12px; height: 12px; background: var(--psa-yellow); border-radius: 4px;"></div>
                            <span style="font-size: 0.8rem;">Updating (22%)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <div style="width: 12px; height: 12px; background: var(--success); border-radius: 4px;"></div>
                            <span style="font-size: 0.8rem;">Other (14%)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div class="table-card">
                <div class="table-header">
                    <h3>
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        Recent Users
                    </h3>
                    <div class="table-actions">
                        <button class="btn btn-outline">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filter
                        </button>
                        <button class="btn btn-primary">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add User
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Designation</th>
                                <th>Window</th>
                                <th>Status</th>
                                <th>Last Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">JL</div>
                                        <div>
                                            <div style="font-weight: 600;">Joy E. Llido</div>
                                            <div style="font-size: 0.8rem; color: var(--gray-500);">jllido.psa</div>
                                        </div>
                                    </div>
                                </td>
                                <td>Registration Kit Operator</td>
                                <td>Window 1</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>2 mins ago</td>
                                <td>
                                    <div class="action-btns">
                                        <button class="action-btn">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <button class="action-btn delete">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">LF</div>
                                        <div>
                                            <div style="font-weight: 600;">Lourdes Joy Fabela</div>
                                            <div style="font-size: 0.8rem; color: var(--gray-500);">lfabela.psa</div>
                                        </div>
                                    </div>
                                </td>
                                <td>Registration Kit Operator</td>
                                <td>Window 3</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>5 mins ago</td>
                                <td>
                                    <div class="action-btns">
                                        <button class="action-btn">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <button class="action-btn delete">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">SC</div>
                                        <div>
                                            <div style="font-weight: 600;">Screener</div>
                                            <div style="font-size: 0.8rem; color: var(--gray-500);">screener</div>
                                        </div>
                                    </div>
                                </td>
                                <td>Screener</td>
                                <td>-</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>1 min ago</td>
                                <td>
                                    <div class="action-btns">
                                        <button class="action-btn">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <button class="action-btn delete">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="quick-action-card">
                    <div class="quick-action-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                        </svg>
                    </div>
                    <div class="quick-action-title">Add User</div>
                    <div class="quick-action-desc">Create new operator or screener account</div>
                </div>

                <div class="quick-action-card">
                    <div class="quick-action-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="quick-action-title">Generate Report</div>
                    <div class="quick-action-desc">Export system analytics and logs</div>
                </div>

                <div class="quick-action-card">
                    <div class="quick-action-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="quick-action-title">System Settings</div>
                    <div class="quick-action-desc">Configure system parameters</div>
                </div>

                <div class="quick-action-card">
                    <div class="quick-action-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
                        </svg>
                    </div>
                    <div class="quick-action-title">Window Management</div>
                    <div class="quick-action-desc">Assign and configure windows</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="loading-modal" id="loadingModal">
        <div class="loading-content">
            <img src="{{ asset('images/loading.png') }}" alt="Loading..." class="rotate-logo">
            <p class="loading-text">Please wait...</p>
        </div>
    </div>

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

        // Menu item click handler
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.menu-item').forEach(mi => mi.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Action buttons
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Add your action logic here
                showLoading();
                setTimeout(hideLoading, 1000);
            });
        });

        // Quick action cards
        document.querySelectorAll('.quick-action-card').forEach(card => {
            card.addEventListener('click', function() {
                showLoading();
                setTimeout(hideLoading, 1000);
            });
        });

        // Admin profile click
        document.querySelector('.admin-profile').addEventListener('click', function() {
            showLoading();
            setTimeout(hideLoading, 1000);
        });

        // Loading modal functions
        function showLoading() {
            document.getElementById('loadingModal').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loadingModal').classList.remove('show');
        }

        // Search functionality (demo)
        const searchInput = document.querySelector('.search-box input');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                console.log('Searching for:', this.value);
            });
        }

        // Notification click
        document.querySelector('.notification-badge').addEventListener('click', function() {
            alert('Notifications panel would open here');
        });
    </script>
</body>
</html>
