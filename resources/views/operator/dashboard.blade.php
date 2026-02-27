<!DOCTYPE html>
<html>
<head>
    <title>Operator Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Your existing styles remain the same */
        body {
            background-color: white;
            color: #003366;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #e6f0ff;
            border: 1px solid #99bbff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 75, 150, 0.1);
        }
        h1, h2 {
            color: #003366;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #99bbff;
            padding: 10px;
            text-align: left;
            color: #003366;
        }
        th {
            background-color: #cce0ff;
        }
        tr:nth-child(even) {
            background-color: #f2f9ff;
        }
        button, .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 5px;
        }
        button:hover, .btn:hover {
            background-color: #0056b3;
        }
        .logout {
            float: right;
            background-color: #dc3545;
        }
        .logout:hover {
            background-color: #a71d2a;
        }
        #message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .message-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Operator Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}" style="float:right;">
            @csrf
            <button type="submit" class="logout">Logout</button>
        </form>
        <h2>Today's Appointments (Window #{{ $windowNum }})</h2>
        <div id="message"></div>
        <table id="appointmentsTable">
            <thead>
                <tr>
                    <th>Queue #</th>
                    <th>Category</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Suffix</th>
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
        <p id="noAppointments" style="display:none;">No appointments found for today.</p>
    </div>

    <script>
        const windowNum = {{ Js::from($windowNum) }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const messageDiv = document.getElementById('message');
        const appointmentsBody = document.getElementById('appointmentsBody');
        const noAppointments = document.getElementById('noAppointments');

        function showMessage(text, type = 'success') {
            messageDiv.textContent = text;
            messageDiv.className = type === 'success' ? 'message-success' : 'message-error';
            setTimeout(() => {
                messageDiv.textContent = '';
                messageDiv.className = '';
            }, 5000);
        }

        function loadAppointments() {
            fetch('{{ route("operator.fetch-appointments") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const appointments = data.appointments;
                        appointmentsBody.innerHTML = '';
                        if (appointments.length === 0) {
                            noAppointments.style.display = 'block';
                        } else {
                            noAppointments.style.display = 'none';
                            appointments.forEach(row => {
                                const tr = document.createElement('tr');
                                tr.id = 'row-' + row.n_id;
                                tr.innerHTML = `
                                    <td>${escapeHtml(row.q_id)}</td>
                                    <td>${escapeHtml(row.queue_for)}</td>
                                    <td>${escapeHtml(row.fname)}</td>
                                    <td>${escapeHtml(row.mname || '')}</td>
                                    <td>${escapeHtml(row.lname)}</td>
                                    <td>${escapeHtml(row.suffix || '')}</td>
                                    <td>${escapeHtml(row.age_category)}</td>
                                    <td>${escapeHtml(formatDate(row.birthdate))}</td>
                                    <td>${escapeHtml(row.trn)}</td>
                                    <td>${escapeHtml(row.PCN)}</td>
                                    <td><button class="nextBtn" data-id="${row.n_id}">Next</button></td>
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

        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            return String(text).replace(/[&<>"']/g, function(m) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[m];
            });
        }

        function attachButtonListeners() {
            document.querySelectorAll('.nextBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const n_id = this.getAttribute('data-id');
                    if (!confirm('Call this queue number to window #' + windowNum + '?')) {
                        return;
                    }

                    fetch('{{ route("operator.update-window") }}', {
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
                            if (appointmentsBody.children.length === 0) {
                                noAppointments.style.display = 'block';
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

        loadAppointments();
        setInterval(loadAppointments, 10000);
    </script>
</body>
</html>
