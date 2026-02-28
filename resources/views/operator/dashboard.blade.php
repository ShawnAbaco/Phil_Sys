<x-header title="Operator Dashboard" />

<!-- Main Content -->
<main class="main-content">
    <!-- Message Container -->
    <div class="message-container" id="messageContainer"></div>

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

        <!-- Stats Cards - Enhanced with smaller, compact design -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <svg viewBox="0 0 20 20" fill="currentColor">
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
                    <svg viewBox="0 0 20 20" fill="currentColor">
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
                    <svg viewBox="0 0 20 20" fill="currentColor">
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
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.293 6.707a1 1 0 001.414 0L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 000 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Queue at Window</span>
                    <span class="stat-value" id="windowDisplay">{{ session('window_num') }}</span>
                </div>
            </div>
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


            <!-- Table with Sticky Header -->
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
</main>

<style>
    /* ===== OPERATOR DASHBOARD ENHANCED STYLES ===== */

    /* Stats Cards - Smaller and Compact */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        transition: all 0.15s;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2563eb, #1d4ed8);
        opacity: 0;
        transition: opacity 0.2s;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
    }

    .stat-icon.completed {
        background: linear-gradient(135deg, #10B981, #34D399);
    }

    .stat-icon.window {
        background: linear-gradient(135deg, #dc2626, #ef4444);
    }

    .stat-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .stat-content {
        min-width: 0;
        flex: 1;
    }

    .stat-label {
        display: block;
        font-size: 0.6875rem;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 0.125rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stat-value {
        display: block;
        font-size: 1.375rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-value.pending {
        color: #d97706;
    }

    .stat-value.completed {
        color: #059669;
    }

    /* Card Styling */
    .card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .card-header h3 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .card-header h3 svg {
        width: 1.125rem;
        height: 1.125rem;
        color: #2563eb;
    }

    .card-body {
        padding: 1.25rem;
    }

    /* Search Box */
    .search-box {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1rem;
        height: 1rem;
        color: #94a3b8;
    }

    .search-box input {
        width: 100%;
        padding: 0.5rem 0.75rem 0.5rem 2.25rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        font-size: 0.8125rem;
        background: white;
        transition: all 0.15s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-box input::placeholder {
        color: #94a3b8;
    }

    /* Table Container with Sticky Header */
    .table-responsive {
        overflow-x: auto;
        overflow-y: auto;
        border-radius: 0.375rem;
        margin: 0;
        width: 100%;
        max-height: 500px;
        border: 1px solid #e2e8f0;
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 #f1f5f9;
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    /* Table Styling */
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.8125rem;
        min-width: 900px;
    }

    /* Sticky Table Header */
    .table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table th {
        text-align: left;
        padding: 0.75rem 1rem;
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border-bottom: 2px solid #cbd5e1;
        white-space: nowrap;
        position: sticky;
        top: 0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Table Cells */
    .table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
        vertical-align: middle;
        background: white;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover td {
        background: #f8fafc;
    }

    /* Queue Number Badge */
    .queue-number {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: #f1f5f9;
        border-radius: 0.25rem;
        font-weight: 600;
        color: #334155;
        font-size: 0.6875rem;
        white-space: nowrap;
        font-family: monospace;
    }

    /* Next Button */
    .next-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        background: white;
        color: #475569;
        transition: all 0.15s;
        white-space: nowrap;
    }

    .next-btn:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .next-btn:active {
        transform: translateY(0);
    }

    .next-btn svg {
        width: 0.875rem;
        height: 0.875rem;
        color: #059669;
    }

    /* Message Container */
    .message-container {
        margin-bottom: 1.5rem;
    }

    .message {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.875rem 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-10px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .message-success {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #065f46;
    }

    .message-error {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .message-icon {
        width: 1.125rem;
        height: 1.125rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    /* No Appointments Message */
    .no-appointments {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: #64748b;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }

    .no-appointments svg {
        width: 2.5rem;
        height: 2.5rem;
        color: #cbd5e1;
    }

    .no-appointments p {
        font-size: 0.875rem;
        margin: 0;
    }

    /* Responsive Adjustments */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            gap: 0.75rem;
        }

        .stat-value {
            font-size: 1.25rem;
        }

        .stat-icon {
            width: 2.25rem;
            height: 2.25rem;
        }

        .stat-icon svg {
            width: 1.125rem;
            height: 1.125rem;
        }

        .table-responsive {
            max-height: 400px;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .card-body {
            padding: 1rem;
        }

        .next-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.6875rem;
        }

        .next-btn svg {
            width: 0.75rem;
            height: 0.75rem;
        }
    }

    /* Loading State */
    .loading {
        text-align: center;
        padding: 2rem;
        color: #64748b;
    }

    .loading svg {
        width: 2rem;
        height: 2rem;
        animation: spin 1s linear infinite;
        margin-bottom: 0.5rem;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    // Configuration
    const windowNum = {{ Js::from(session('window_num')) }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // DOM Elements
    const messageContainer = document.getElementById('messageContainer');
    const appointmentsBody = document.getElementById('appointmentsBody');
    const noAppointments = document.getElementById('noAppointments');
    const searchInput = document.getElementById('searchInput');
    const totalCount = document.getElementById('totalCount');
    const pendingCount = document.getElementById('pendingCount');
    const servedCount = document.getElementById('servedCount');

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
