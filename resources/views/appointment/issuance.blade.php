{{-- resources/views/appointment/issuance.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Issuance - National ID System</title>

    <!-- Favicon / Logo -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/loading.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/loading.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- For better SEO and social sharing -->
    <meta property="og:title" content="National ID Appointment System">
    <meta property="og:description" content="Philippine National ID Appointment and Queue Management System">
    <meta property="og:image" content="{{ asset('images/loading.png') }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PSA-themed header with arrow pattern -->
    <div class="header-overlay"></div>

    <header class="main-header">
        <div class="header-content">
            <div class="logo-area">
                <img src="{{ asset('images/logo.png') }}" alt="National ID Logo" class="header-logo">
                <div class="header-title">
                    <h1>National ID Appointment System</h1>
                    <span class="badge">Screener Dashboard</span>
                </div>
            </div>
            <div class="user-area">
                <div class="user-details">
                    <span class="user-name">{{ session('full_name') }}</span>
                    <span class="user-designation">{{ session('designation') }}</span>
                </div>
                <div class="window-badge">
                    <svg class="window-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.616a1 1 0 01.894-1.79l1.599.8L9 4.323V3a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Window {{ session('window_num') }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg class="logout-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414l-5-5H3zm7 10a1 1 0 11-2 0V9.414l-1.293 1.293a1 1 0 01-1.414-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L10 9.414V13z"
                                clip-rule="evenodd" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="main-container">
        {{-- Alert Messages --}}
        <div class="alert-container">
            @if (session('success'))
                <div class="alert alert-success">
                    <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            @if (session('error') || $errors->any())
                <div class="alert alert-error">
                    <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        @if (session('error'))
                            <span>{{ session('error') }}</span>
                        @else
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
        </div>

        {{-- Stats Cards --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--psa-blue), var(--psa-red))">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Queue Today</span>
                    <span class="stat-value">{{ $queueCount ?? 0 }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #FBBF24)">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Pending</span>
                    <span class="stat-value pending">{{ $pendingCount ?? 0 }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #34D399)">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Completed</span>
                    <span class="stat-value completed">{{ $completedCount ?? 0 }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--psa-red), #EF4444)">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.293 6.707a1 1 0 001.414 0L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 000 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Queue at Window {{ session('window_num') }}</span>
                    <span class="stat-value">{{ $windowQueueCount ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Date/Time Bar --}}
        <div class="datetime-bar">
            <div class="datetime-left">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ now()->setTimezone('Asia/Manila')->format('l, F d, Y') }}</span>
            </div>
            <div class="datetime-right">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ now()->setTimezone('Asia/Manila')->format('h:i A') }}</span>
            </div>
        </div>

        <div class="dashboard-grid">
            {{-- Issue New Appointment Card --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                clip-rule="evenodd" />
                        </svg>
                        Issue New Appointment
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('appointment.issue') }}" id="appointmentForm">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="queue_for">Queue For</label>
                                <select name="queue_for" id="queue_for" required>
                                    <option value="">Select Service</option>
                                    <option value="nid_registration">NID Registration</option>
                                    <option value="status-inquiry">Status Inquiry</option>
                                    <option value="updating">Updating</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname" value="{{ old('fname') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" name="mname" id="mname" value="{{ old('mname') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname" value="{{ old('lname') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}"
                                    placeholder="Jr., Sr., III">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="age_category">Age Category</label>
                                <select name="age_category" id="age_category" required>
                                    <option value="">Select Category</option>
                                    <option value="infant">Infant (0-1)</option>
                                    <option value="child">Child (2-12)</option>
                                    <option value="teen">Teen (13-19)</option>
                                    <option value="adult">Adult (20-59)</option>
                                    <option value="senior">Senior (60+)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="birthdate">Birthdate</label>
                                <input type="date" name="birthdate" id="birthdate"
                                    value="{{ old('birthdate') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="trn">TRN <span class="field-hint">(Transaction Reference
                                        Number)</span></label>
                                <input type="text" name="trn" id="trn" value="{{ old('trn') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="PCN">PCN <span class="field-hint">(Philippine Civil Registration
                                        Number)</span></label>
                                <input type="text" name="PCN" id="PCN" value="{{ old('PCN') }}"
                                    required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Issue Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Today's Appointments Card --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        Today's Appointments
                    </h3>
                    <div class="card-actions">
                        <span class="badge">{{ $appointments->count() }} total</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="text" id="searchAppointments" placeholder="Search by name or TRN...">
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Queue #</th>
                                    <th>Name</th>
                                    <th>Service</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsTableBody">
                                @forelse($appointments as $appointment)
                                    @php
                                        $servedTime = \Carbon\Carbon::parse($appointment->time_catered);
                                        $createdTime = \Carbon\Carbon::parse($appointment->date);
                                        $isCompleted = $servedTime->gt($createdTime);
                                    @endphp
                                    <tr
                                        data-search="{{ strtolower($appointment->lname . ' ' . $appointment->fname . ' ' . $appointment->trn) }}">
                                        <td><span class="queue-number">{{ $appointment->q_id }}</span></td>
                                        <td>
                                            <div class="client-name">
                                                {{ $appointment->lname }}, {{ $appointment->fname }}
                                                @if ($appointment->mname || $appointment->suffix)
                                                    <small>{{ $appointment->mname }}
                                                        {{ $appointment->suffix }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $appointment->queue_for)) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->date)->format('h:i A') }}</td>
                                        <td>
                                            @if ($isCompleted)
                                                <span class="status-badge status-completed">
                                                    <span class="status-dot"></span>
                                                    Completed
                                                </span>
                                            @else
                                                <span class="status-badge status-pending">
                                                    <span class="status-dot"></span>
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$isCompleted)
                                                <button class="btn-action serve-btn"
                                                    data-id="{{ $appointment->n_id }}"
                                                    data-name="{{ $appointment->fname }} {{ $appointment->lname }}">
                                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Serve
                                                </button>
                                            @else
                                                <span class="badge-completed">Served</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <p>No appointments for today</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="card full-width">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z"
                            clip-rule="evenodd" />
                    </svg>
                    Recent Transactions
                </h3>
                <div class="card-actions">
                    <span class="badge">Last 24 hours</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Queue #</th>
                                <th>Client</th>
                                <th>Service</th>
                                <th>Date/Time</th>
                                <th>Window</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                                @php
                                    $servedTime = \Carbon\Carbon::parse($transaction->time_catered);
                                    $createdTime = \Carbon\Carbon::parse($transaction->date);
                                    $isCompleted = $servedTime->gt($createdTime);
                                @endphp
                                <tr>
                                    <td><span class="queue-number small">{{ $transaction->q_id }}</span></td>
                                    <td>
                                        <div class="client-name">
                                            {{ $transaction->lname }}, {{ $transaction->fname }}
                                        </div>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->queue_for)) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->date)->format('M d, h:i A') }}</td>
                                    <td>
                                        <span class="window-indicator">Window {{ $transaction->window_num }}</span>
                                    </td>
                                    <td>
                                        @if ($isCompleted)
                                            <span class="status-badge status-completed">
                                                <span class="status-dot"></span>
                                                Completed
                                            </span>
                                        @else
                                            <span class="status-badge status-pending">
                                                <span class="status-dot"></span>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p>No recent transactions</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    {{-- Loading Modal --}}
    <div class="loading-modal" id="loadingModal">
        <div class="loading-content">
            <img src="{{ asset('images/loading.png') }}" alt="Loading..." class="loading-logo rotate-logo">
            <p class="loading-text">Processing...</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Search functionality
        function searchAppointments() {
            const searchTerm = document.getElementById('searchAppointments').value.toLowerCase();
            const rows = document.querySelectorAll('#appointmentsTableBody tr');

            rows.forEach(row => {
                const searchData = row.dataset.search || row.textContent.toLowerCase();
                if (searchData.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Live search
        document.getElementById('searchAppointments').addEventListener('keyup', searchAppointments);

        // Serve appointment
        document.querySelectorAll('.serve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;

                if (!confirm(`Mark appointment for ${name} as served?`)) {
                    return;
                }

                showLoading();

                fetch(`/appointment/serve/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            // Show success message
                            const alert = createAlert('success', 'Appointment marked as served!');
                            document.querySelector('.alert-container').appendChild(alert);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('error', data.message || 'Error marking appointment');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showAlert('error', 'Connection error. Please try again.');
                        console.error(error);
                    });
            });
        });

        // Form submission loading
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            showLoading();
        });

        // Utility functions
        function showLoading() {
            document.getElementById('loadingModal').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loadingModal').classList.remove('show');
        }

        function createAlert(type, message) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;

            const icon = type === 'success' ?
                '<svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                '<svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';

            alert.innerHTML = `
                ${icon}
                <span>${message}</span>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
            `;

            return alert;
        }

        function showAlert(type, message) {
            const alert = createAlert(type, message);
            document.querySelector('.alert-container').appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.remove();
            });
        }, 5000);
    </script>
</body>

</html>
