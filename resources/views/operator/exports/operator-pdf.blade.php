<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Completed Transactions - {{ $dateToday }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            margin: 20px;
            color: #333;
            background: #fff;
            line-height: 1.4;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 150px;
            height: auto;
        }

        .title-section h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .title-section p {
            font-size: 12px;
            color: #666;
        }

        .date-section {
            text-align: right;
            font-size: 12px;
        }

        .date-section div {
            margin-bottom: 3px;
        }

        /* Operator Info */
        .operator-info {
            margin-bottom: 20px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }

        .operator-info strong {
            font-weight: bold;
        }

        /* Table */
        .table-container {
            margin-bottom: 20px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .table-header h2 {
            font-size: 16px;
            font-weight: bold;
        }

        .record-count {
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            padding: 8px 5px;
            text-align: left;
            border: 1px solid #ddd;
        }

        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
        }

        .number-cell {
            text-align: center;
        }

        .queue-number {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
        }

        .client-name {
            font-weight: normal;
        }

        .client-name small {
            font-size: 9px;
            color: #666;
            display: block;
        }

        .time-cell {
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <img src="{{ public_path('images/logo.png') }}" alt="PSA PHILSYS Logo" class="logo">
            <div class="title-section">
                <p>Operator Completed Transactions Report</p>
            </div>
        </div>
        <div class="date-section">
            <div><strong>Date:</strong> {{ $dateToday }}</div>
            <div><strong>Generated:</strong> {{ $timeGenerated }}</div>
            <div><strong>Report ID:</strong> OPR-{{ date('YmdHis') }}</div>
        </div>
    </div>

    <div class="operator-info">
        <span><strong>Window Number:</strong> {{ $windowNum }}</span>
    </div>

    <!-- Completed Transactions Table -->
    <div class="table-container">
        <div class="table-header">
            <h2>Completed Transactions</h2>
            <span class="record-count">Total Records: {{ $totalCompleted }}</span>
        </div>

        @if ($completedAppointments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">#</th>
                        <th style="width: 80px;">Queue #</th>
                        <th>Client Name</th>
                        <th style="width: 90px;">Age Category</th>
                        <th style="width: 80px;">Birthdate</th>
                        <th style="width: 100px;">TRN</th>
                        <th style="width: 100px;">PCN</th>
                        <th style="width: 100px;">Service</th>
                        <th style="width: 80px;">Served Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completedAppointments as $app)
                        <tr>
                            <td class="number-cell">{{ $app->row_number }}</td>
                            <td><span class="queue-number">{{ $app->q_id }}</span></td>
                            <td>
                                <span class="client-name">
                                    {{ $app->lname }}, {{ $app->fname }}
                                    @if ($app->mname || $app->suffix)
                                        <small>
                                            {{ trim($app->mname . ' ' . $app->suffix) }}
                                        </small>
                                    @endif
                                </span>
                            </td>
                            <td>{{ $app->age_category ?? 'N/A' }}</td>
                            <td>{{ $app->birthdate ? \Carbon\Carbon::parse($app->birthdate)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td>{{ $app->trn ?? 'N/A' }}</td>
                            <td>{{ $app->PCN ?? 'N/A' }}</td>
                            <td>{{ $app->queue_for }}</td>
                            <td class="time-cell">
                                {{ \Carbon\Carbon::parse($app->time_catered)->setTimezone('Asia/Manila')->format('h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table>
                <tr>
                    <td colspan="9" class="empty-state">
                        No completed transactions found
                    </td>
                </tr>
            </table>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>PSA PHILSYS - Queue Management System</div>
        <div>Page 1 of 1</div>
    </div>
</body>

</html>
