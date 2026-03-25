{{-- resources/views/operator/serving.blade.php --}}
<x-header title="Serving Appointments" />

<div class="app-container">
    <x-operator.sidebar />    
    <style>
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            margin-left: 0;
            padding: 30px;
            transition: all 0.3s ease;
        }

        @media (min-width: 769px) {
            .main-content {
                margin-left: 280px;
            }
        }

        .serving-badge-header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .serving-card {
            border-left: 4px solid #ef4444;
        }
    </style>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Message Container -->
        <div class="message-container" id="messageContainer"></div>

        {{-- Serving Appointments Card --}}
        <div class="card serving-card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                        <path fill-rule="evenodd"
                            d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                            clip-rule="evenodd" />
                    </svg>
                    Currently Serving
                    @if($servingCount > 0)
                        <span class="serving-badge-header">{{ $servingCount }} Active</span>
                    @endif
                </h3>
                <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; margin: 0;">
                    <div class="stat-card" style="padding: 0.75rem;">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626); width: 2rem; height: 2rem;">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Serving Now</span>
                            <span class="stat-value" style="color: #ef4444; font-size: 1.25rem;">{{ $servingCount }}</span>
                        </div>
                    </div>
                    <div class="stat-card" style="padding: 0.75rem;">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669); width: 2rem; height: 2rem;">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Completed Today</span>
                            <span class="stat-value" style="color: #10b981; font-size: 1.25rem;">{{ $completedCount ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="stat-card" style="padding: 0.75rem;">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 2rem; height: 2rem;">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Pending</span>
                            <span class="stat-value" style="color: #f59e0b; font-size: 1.25rem;">{{ $pendingCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                {{-- Search Bar --}}
                <div class="search-box-wrapper" style="margin-bottom: 20px;">
                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="text" id="searchServing" placeholder="Search by name or queue number...">
                    </div>
                </div>

                {{-- Serving Appointments Table --}}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Queue #</th>
                                <th>Client Name</th>
                                <th>Priority</th>
                                <th>Service</th>
                                <th>Serving Since</th>
                                <th>Window</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="servingTableBody">
                            @forelse($servingAppointments as $appointment)
                                @php
                                    $createdTime = \Carbon\Carbon::parse($appointment->updated_at)->setTimezone('Asia/Manila');
                                    $fullName = $appointment->lname . ', ' . $appointment->fname;
                                    if ($appointment->mname && trim($appointment->mname) !== '') {
                                        $fullName .= ' ' . $appointment->mname;
                                    }
                                    if ($appointment->suffix && trim($appointment->suffix) !== '') {
                                        $fullName .= ' ' . $appointment->suffix;
                                    }
                                    
                                    $priorityType = $appointment->priority_type ?? 'regular';
                                    $priorityDisplay = ucfirst($priorityType);
                                @endphp
                                <tr data-search="{{ strtolower($fullName . ' ' . $appointment->q_id) }}">
                                    <td>
                                        <span class="queue-number" style="font-size: 1rem; font-weight: 700;">
                                            {{ $appointment->q_id }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="client-name">
                                            {{ $fullName }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="priority-badge priority-{{ $priorityType }}">
                                            {{ strtoupper($priorityDisplay) }}
                                        </span>
                                    </td>
                                    <td>{{ $appointment->queue_for }}</td>
                                    <td>
                                        <span class="time-badge">
                                            {{ $createdTime->format('h:i A') }}
                                        </span>
                                        <small style="display: block; font-size: 0.65rem; color: #6b7280;">
                                            {{ $createdTime->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="window-indicator">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="12" height="12" style="display: inline; margin-right: 4px;">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                                <path fill-rule="evenodd"
                                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            #{{ $appointment->window_num }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-button-group">
                                            <button class="btn-action complete-btn" 
                                                    data-id="{{ $appointment->n_id }}"
                                                    data-name="{{ $fullName }}"
                                                    data-queue="{{ $appointment->q_id }}">
                                                <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Complete
                                            </button>
                                            <button class="btn-action secondary no-show-btn" 
                                                    data-id="{{ $appointment->n_id }}"
                                                    data-name="{{ $fullName }}"
                                                    data-queue="{{ $appointment->q_id }}">
                                                No Show
                                            </button>
                                            <button class="btn-action secondary cancel-btn" 
                                                    data-id="{{ $appointment->n_id }}"
                                                    data-name="{{ $fullName }}"
                                                    data-queue="{{ $appointment->q_id }}">
                                                Cancel
                                            </button>
                                            <button class="btn-action volume-btn" 
                                                    data-id="{{ $appointment->n_id }}"
                                                    data-name="{{ $fullName }}"
                                                    data-queue="{{ $appointment->q_id }}"
                                                    title="Announce again">
                                                <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                                                    <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-state">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p>No appointments currently being served</p>
                                        <p style="font-size: 0.75rem; margin-top: 8px;">Click "Serve" on the dashboard to start serving an appointment</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const windowNum = String({{ Js::from(session('window_num') ?? ($windowNum ?? '1')) }});
    let refreshInterval;
    let isProcessing = false;

    // Search functionality
    const searchInput = document.getElementById('searchServing');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#servingTableBody tr');
            
            rows.forEach(row => {
                if (row.classList.contains('empty-state')) return;
                const searchData = row.getAttribute('data-search') || row.textContent.toLowerCase();
                row.style.display = searchData.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Handle Complete Button Click
    function handleCompleteClick(e) {
        const button = e.currentTarget;
        const n_id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const queueNumber = button.getAttribute('data-queue');
        const row = button.closest('tr');

        Swal.fire({
            title: 'Complete Appointment?',
            text: `Mark ${name} (${queueNumber}) as completed?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Yes, Complete'
        }).then((result) => {
            if (result.isConfirmed) {
                updateAppointmentStatus(n_id, 'completed', row, name, queueNumber);
            }
        });
    }

    // Handle No Show Button Click
    function handleNoShowClick(e) {
        const button = e.currentTarget;
        const n_id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const queueNumber = button.getAttribute('data-queue');
        const row = button.closest('tr');

        Swal.fire({
            title: 'Mark as No Show?',
            text: `Mark ${name} (${queueNumber}) as no show?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6b7280',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Yes, No Show'
        }).then((result) => {
            if (result.isConfirmed) {
                updateAppointmentStatus(n_id, 'no_show', row, name, queueNumber);
            }
        });
    }

    // Handle Cancel Button Click
    function handleCancelClick(e) {
        const button = e.currentTarget;
        const n_id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const queueNumber = button.getAttribute('data-queue');
        const row = button.closest('tr');

        Swal.fire({
            title: 'Cancel Appointment?',
            text: `Cancel appointment for ${name} (${queueNumber})?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                updateAppointmentStatus(n_id, 'cancelled', row, name, queueNumber);
            }
        });
    }

    // Handle Volume Button Click - Re-announce
    function handleVolumeClick(e) {
        const button = e.currentTarget;
        const queueNumber = button.getAttribute('data-queue');
        const clientName = button.getAttribute('data-name');
        
        // Show loading state
        const originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner"></span>';
        
        fetch('{{ route('operator.trigger-announcement') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                window_num: windowNum,
                queue_number: queueNumber,
                client_name: clientName
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                
                Toast.fire({
                    icon: 'success',
                    title: `Announcement triggered for ${queueNumber}`
                });
            } else {
                showMessage('Failed to trigger announcement', 'error');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showMessage('Error connecting to server', 'error');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalHtml;
        });
    }

    // Update appointment status
    function updateAppointmentStatus(n_id, status, row, name, queueNumber) {
        if (isProcessing) return;
        isProcessing = true;

        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('{{ route('operator.update-window') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                n_id: n_id,
                window_num: windowNum,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            isProcessing = false;

            if (data.success) {
                // Remove the row from the table
                row.remove();
                
                // Check if table is empty
                const tbody = document.getElementById('servingTableBody');
                if (tbody.children.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="empty-state">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>No appointments currently being served</p>
                                <p style="font-size: 0.75rem; margin-top: 8px;">Click "Serve" on the dashboard to start serving an appointment</p>
                            </td>
                        </tr>
                    `;
                }
                
                // Update the serving count badge
                updateServingCount();
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: `${queueNumber} marked as ${status.replace('_', ' ')}`
                });
                
                // Refresh the dashboard data in background
                fetchDashboardData();
            } else {
                showMessage('Failed to update.', 'error');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.close();
            isProcessing = false;
            showMessage('Error connecting to server.', 'error');
        });
    }

    // Update serving count badge
    function updateServingCount() {
        const tbody = document.getElementById('servingTableBody');
        const rows = tbody.querySelectorAll('tr:not(.empty-state)');
        const count = rows.length;
        
        const badge = document.querySelector('.serving-badge-header');
        if (badge) {
            if (count > 0) {
                badge.textContent = `${count} Active`;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
        
        // Update the stat card
        const servingStat = document.querySelector('.stat-card:first-child .stat-value');
        if (servingStat) {
            servingStat.textContent = count;
            servingStat.style.color = count > 0 ? '#ef4444' : '#6b7280';
        }
    }

    // Show message
    function showMessage(text, type = 'success') {
        const messageContainer = document.getElementById('messageContainer');
        if (!messageContainer) return;

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
        setTimeout(() => messageDiv.remove(), 5000);
    }

    // Fetch dashboard data to update counts
    function fetchDashboardData() {
        fetch('{{ route('operator.fetch-appointments') }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.stats) {
                // Update the stats on the page
                const completedStat = document.querySelector('.stat-card:nth-child(2) .stat-value');
                const pendingStat = document.querySelector('.stat-card:nth-child(3) .stat-value');
                
                if (completedStat && data.stats.completed !== undefined) {
                    completedStat.textContent = data.stats.completed;
                }
                if (pendingStat && data.stats.pending !== undefined) {
                    pendingStat.textContent = data.stats.pending;
                }
            }
        })
        .catch(error => console.error('Error fetching dashboard data:', error));
    }

    // Attach event listeners
    function attachEventListeners() {
        document.querySelectorAll('.complete-btn').forEach(btn => {
            btn.removeEventListener('click', handleCompleteClick);
            btn.addEventListener('click', handleCompleteClick);
        });
        
        document.querySelectorAll('.no-show-btn').forEach(btn => {
            btn.removeEventListener('click', handleNoShowClick);
            btn.addEventListener('click', handleNoShowClick);
        });
        
        document.querySelectorAll('.cancel-btn').forEach(btn => {
            btn.removeEventListener('click', handleCancelClick);
            btn.addEventListener('click', handleCancelClick);
        });
        
        document.querySelectorAll('.volume-btn').forEach(btn => {
            btn.removeEventListener('click', handleVolumeClick);
            btn.addEventListener('click', handleVolumeClick);
        });
    }

    // Auto-refresh every 10 seconds
    function startAutoRefresh() {
        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = setInterval(function() {
            // Reload the page to refresh serving appointments
            location.reload();
        }, 10000);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        attachEventListeners();
        startAutoRefresh();
    });

    // Stop refresh on page unload
    window.addEventListener('beforeunload', function() {
        if (refreshInterval) clearInterval(refreshInterval);
    });
</script>