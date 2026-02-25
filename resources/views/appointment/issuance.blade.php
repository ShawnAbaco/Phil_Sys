{{-- resources/views/appointment/issuance.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Issuance - National ID System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #007BFF;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-info span {
            font-weight: bold;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h4 {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .stat-card .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #007BFF;
            margin: 5px 0;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type=text],
        input[type=date],
        input[type=time],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        input[type=text]:focus,
        input[type=date]:focus,
        select:focus {
            outline: none;
            border-color: #007BFF;
        }
        .btn {
            background: #007BFF;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #555;
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .table tr:hover {
            background: #f8f9fa;
        }
        .alert {
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
        .window-info {
            background: #e7f3ff;
            border-left: 4px solid #007BFF;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-box input {
            flex: 1;
        }
        .search-box button {
            padding: 10px 20px;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>National ID Appointment System - Screener Dashboard</h2>
        <div class="user-info">
            <span>{{ session('full_name') }} ({{ session('designation') }})</span>
            <span>Window: {{ session('window_num') }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="stats-container">
            <div class="stat-card">
                <h4>Total Queue Today</h4>
                <div class="stat-value">{{ $queueCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <h4>Pending</h4>
                <div class="stat-value" style="color: #ffc107;">{{ $pendingCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <h4>Completed</h4>
                <div class="stat-value" style="color: #28a745;">{{ $completedCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <h4>Your Window</h4>
                <div class="stat-value">{{ session('window_num') }}</div>
            </div>
        </div>

        {{-- Window Information --}}
        <div class="window-info">
            <strong>Current Window:</strong> Window {{ session('window_num') }} |
            <strong>Date:</strong> {{ now()->format('F d, Y') }} |
            <strong>Time:</strong> {{ now()->format('h:i A') }}
        </div>

        <div class="grid-2">
            {{-- Issue New Appointment --}}
            <div class="card">
                <h3>Issue New Appointment</h3>
                <form method="POST" action="{{ route('appointment.issue') }}">
                    @csrf
                    <div class="form-group">
                        <label for="queue_for">Queue For</label>
                        <select name="queue_for" id="queue_for" required>
                            <option value="">Select Service</option>
                            <option value="new_registration">New Registration</option>
                            <option value="replacement">Replacement ID</option>
                            <option value="correction">Correction of Entry</option>
                            <option value="verification">Verification</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" value="{{ old('fname') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="mname">Middle Name</label>
                        <input type="text" name="mname" id="mname" value="{{ old('mname') }}">
                    </div>

                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" value="{{ old('lname') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="suffix">Suffix</label>
                        <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}" placeholder="Jr., Sr., III, etc.">
                    </div>

                    <div class="form-group">
                        <label for="age_category">Age Category</label>
                        <select name="age_category" id="age_category" required>
                            <option value="">Select Category</option>
                            <option value="infant" {{ old('age_category') == 'infant' ? 'selected' : '' }}>Infant (0-1)</option>
                            <option value="child" {{ old('age_category') == 'child' ? 'selected' : '' }}>Child (2-12)</option>
                            <option value="teen" {{ old('age_category') == 'teen' ? 'selected' : '' }}>Teen (13-19)</option>
                            <option value="adult" {{ old('age_category') == 'adult' ? 'selected' : '' }}>Adult (20-59)</option>
                            <option value="senior" {{ old('age_category') == 'senior' ? 'selected' : '' }}>Senior (60+)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="trn">TRN (Transaction Reference Number)</label>
                        <input type="text" name="trn" id="trn" value="{{ old('trn') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="PCN">PCN (Philippine Civil Registration Number)</label>
                        <input type="text" name="PCN" id="PCN" value="{{ old('PCN') }}" required>
                    </div>

                    <button type="submit" class="btn">Issue Appointment</button>
                </form>
            </div>

            {{-- Today's Appointments --}}
            <div class="card">
                <h3>Today's Appointments ({{ $appointments->count() }})</h3>

                {{-- Search Box --}}
                <div class="search-box">
                    <input type="text" id="searchAppointments" placeholder="Search by name or TRN...">
                    <button class="btn" onclick="searchAppointments()">Search</button>
                </div>

                <div style="max-height: 400px; overflow-y: auto;">
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
                        <tbody>
                            @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->q_id }}</td>
                                <td>{{ $appointment->lname }}, {{ $appointment->fname }} {{ $appointment->mname }} {{ $appointment->suffix }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $appointment->queue_for)) }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('h:i A') }}</td>
                                <td>
                                    @php
                                        $servedTime = \Carbon\Carbon::parse($appointment->time_catered);
                                        $createdTime = \Carbon\Carbon::parse($appointment->date);
                                    @endphp
                                    @if($servedTime->gt($createdTime))
                                        <span class="status-badge status-completed">Completed</span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($servedTime->eq($createdTime))
                                        <button class="btn-success serve-btn"
                                                style="padding: 5px 10px; font-size: 12px;"
                                                data-id="{{ $appointment->n_id }}">
                                            Serve
                                        </button>
                                    @else
                                        <button class="btn" style="padding: 5px 10px; font-size: 12px;" disabled>
                                            Done
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">
                                    No appointments for today
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="card">
            <h3>Recent Transactions</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Queue #</th>
                            <th>Name</th>
                            <th>Service</th>
                            <th>Date/Time</th>
                            <th>Window</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->q_id }}</td>
                            <td>{{ $transaction->lname }}, {{ $transaction->fname }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $transaction->queue_for)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d h:i A') }}</td>
                            <td>Window {{ $transaction->window_num }}</td>
                            <td>
                                @php
                                    $servedTime = \Carbon\Carbon::parse($transaction->time_catered);
                                    $createdTime = \Carbon\Carbon::parse($transaction->date);
                                @endphp
                                @if($servedTime->gt($createdTime))
                                    <span class="status-badge status-completed">Completed</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">
                                No recent transactions
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function searchAppointments() {
            let searchTerm = document.getElementById('searchAppointments').value.toLowerCase();
            let tableRows = document.querySelectorAll('.table tbody tr');

            tableRows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Handle serve button clicks
        document.querySelectorAll('.serve-btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!confirm('Mark this appointment as served?')) {
                    return;
                }

                let id = this.dataset.id;
                fetch(`/appointment/serve/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('Appointment marked as served!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error marking appointment as served');
                    console.error(error);
                });
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);

        // Live search as you type
        document.getElementById('searchAppointments').addEventListener('keyup', function() {
            searchAppointments();
        });
    </script>
</body>
</html>
