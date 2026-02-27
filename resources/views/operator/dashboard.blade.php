<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard - National ID System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/loading.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/loading.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Top Header with all elements -->
    <header class="main-header">
        <div class="header-content">
            <!-- Logo Section -->
            <div class="logo-section">
                <img src="{{ asset('images/logo.png') }}" alt="National ID Logo" class="header-logo">
                <div class="logo-text">
                    <h1>National ID System</h1>
                    <span>Operator Portal</span>
                </div>
            </div>

            <!-- User Section - All elements in one row -->
            <div class="user-section">


                <!-- Window Badge -->
                <div class="window-badge">
                    <svg class="window-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.616a1 1 0 01.894-1.79l1.599.8L9 4.323V3a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Window #{{ $windowNum }}</span>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
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

                <!-- User Info with Avatar -->
                <div class="user-info">
                    <div class="user-details">
                        <span class="user-name">{{ session('full_name') }}</span>
                        <span class="user-role">{{ session('designation') }}</span>
                    </div>
                    <div class="user-avatar">
                        {{ substr(session('full_name'), 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Message Container -->
        <div class="message-container" id="messageContainer"></div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Today</span>
                    <span class="stat-value" id="totalCount">0</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon pending">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Pending</span>
                    <span class="stat-value pending" id="pendingCount">0</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon completed">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Served</span>
                    <span class="stat-value completed" id="servedCount">0</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon window">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd"
                            d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.293 6.707a1 1 0 001.414 0L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 000 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Queue at Window</span>
                    <span class="stat-value" id="windowDisplay">{{ $windowNum }}</span>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Today's Appointments
                </h3>
            </div>
            <div class="card-body">
                <!-- Search Box -->
                <div class="search-box">
                    <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by name, queue number, or TRN...">
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table" id="appointmentsTable">
                        <thead>
                            <tr>
                                <th>Queue #</th>
                                <th>Category</th>
                                <th>Full Name</th>
                                <th>Age Category</th>
                                <th>Birthdate</th>
                                <th>TRN</th>
                                <th>PCN</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsBody">
                            <!-- Appointment rows will be loaded here by JS -->
                        </tbody>
                    </table>
                </div>

                <!-- No Appointments Message -->
                <div id="noAppointments" class="no-appointments" style="display: none;">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    <p>No appointments found for today.</p>
                </div>
            </div>
        </div>

        <!-- Date & Time -->
        <div class="datetime-display" id="datetime">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                    clip-rule="evenodd" />
            </svg>
            <span></span>
        </div>
    </main>

    <script>
        // Configuration
        const windowNum = {{ Js::from($windowNum) }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // DOM Elements
        const messageContainer = document.getElementById('messageContainer');
        const appointmentsBody = document.getElementById('appointmentsBody');
        const noAppointments = document.getElementById('noAppointments');
        const searchInput = document.getElementById('searchInput');
        const totalCount = document.getElementById('totalCount');
        const pendingCount = document.getElementById('pendingCount');
        const servedCount = document.getElementById('servedCount');

        // Update Date and Time
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
            document.querySelector('#datetime span').textContent = now.toLocaleDateString('en-US', options);
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Show Message
        function showMessage(text, type = 'success') {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message message-${type}`;
            messageDiv.innerHTML = `
                <svg class="message-icon" viewBox="0 0 20 20" fill="currentColor">
                    ${type === 'success' 
                        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                    }
                </svg>
                <span>${text}</span>
            `;

            messageContainer.innerHTML = '';
            messageContainer.appendChild(messageDiv);

            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }

        // Escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            };
            return String(text).replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }

        // Format Date
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        // Format Full Name
        function formatFullName(row) {
            let name = row.lname;
            if (row.fname) name += ', ' + row.fname;
            if (row.mname) name += ' ' + row.mname;
            if (row.suffix) name += ' ' + row.suffix;
            return name;
        }

        // Load Appointments
        function loadAppointments() {
            fetch('{{ route('operator.fetch-appointments') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const appointments = data.appointments;
                        appointmentsBody.innerHTML = '';

                        // Update stats
                        totalCount.textContent = appointments.length;
                        pendingCount.textContent = appointments.length;
                        servedCount.textContent = '0';

                        if (appointments.length === 0) {
                            noAppointments.style.display = 'flex';
                        } else {
                            noAppointments.style.display = 'none';
                            appointments.forEach(row => {
                                const tr = document.createElement('tr');
                                tr.id = 'row-' + row.n_id;
                                tr.setAttribute('data-search',
                                    (row.q_id + ' ' + row.lname + ' ' + row.fname + ' ' + row.trn)
                                    .toLowerCase()
                                );
                                tr.innerHTML = `
                                    <td><span class="queue-number">${escapeHtml(row.q_id)}</span></td>
                                    <td>${escapeHtml(row.queue_for || '')}</td>
                                    <td>${escapeHtml(formatFullName(row))}</td>
                                    <td>${escapeHtml(row.age_category || '')}</td>
                                    <td>${escapeHtml(formatDate(row.birthdate))}</td>
                                    <td>${escapeHtml(row.trn)}</td>
                                    <td>${escapeHtml(row.PCN)}</td>
                                    <td>
                                        <button class="next-btn" data-id="${row.n_id}">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Call Next
                                        </button>
                                    </td>
                                `;
                                appointmentsBody.appendChild(tr);
                            });
                            attachButtonListeners();
                        }
                    } else {
                        showMessage(data.message || 'Failed to load appointments.', 'error');
                    }
                })
                .catch(err => {
                    showMessage('Error loading appointments: ' + err, 'error');
                });
        }

        // Attach Button Listeners
        function attachButtonListeners() {
            document.querySelectorAll('.next-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const n_id = this.getAttribute('data-id');
                    if (!confirm('Call this queue number to window #' + windowNum + '?')) {
                        return;
                    }

                    fetch('{{ route('operator.update-window') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                n_id: n_id,
                                window_num: windowNum
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showMessage(data.message, 'success');
                                const row = document.getElementById('row-' + n_id);
                                if (row) row.remove();

                                // Update counts
                                const remainingRows = appointmentsBody.children.length;
                                totalCount.textContent = remainingRows;
                                pendingCount.textContent = remainingRows;
                                servedCount.textContent = parseInt(servedCount.textContent) + 1;

                                if (remainingRows === 0) {
                                    noAppointments.style.display = 'flex';
                                }
                            } else {
                                showMessage(data.message || 'Failed to update.', 'error');
                            }
                        })
                        .catch(err => {
                            showMessage('Error: ' + err, 'error');
                        });
                });
            });
        }

        // Search Functionality
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = appointmentsBody.querySelectorAll('tr');

            rows.forEach(row => {
                const searchData = row.getAttribute('data-search') || row.textContent.toLowerCase();
                if (searchData.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Initial Load
        loadAppointments();

        // Refresh every 10 seconds
        setInterval(loadAppointments, 10000);
    </script>
</body>

</html>
