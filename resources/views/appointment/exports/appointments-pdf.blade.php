<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Appointments Report - {{ $dateToday }}</title>
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

        /* Window Info */
        .window-info {
            text-align: right;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
            padding: 5px 10px;
            display: inline-block;
            float: right;
        }

        .clear {
            clear: both;
        }

        /* Table */
        .table-container {
            margin-bottom: 20px;
            clear: both;
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
            font-size: 12px;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
        }

        .number-cell {
            font-weight: normal;
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
            font-size: 10px;
            color: #666;
            display: block;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 11px;
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
                <p>Completed Transactions Report</p>
            </div>
        </div>
        <div class="date-section">
            <div><strong>Date:</strong> {{ $dateToday }}</div>
            <div><strong>Generated:</strong> {{ $timeGenerated }}</div>
            <div><strong>Report ID:</strong> COMP-{{ date('YmdHis') }}</div>
        </div>
    </div>

    <!-- Window Number Display -->
    @if ($completedAppointments->isNotEmpty() && $completedAppointments->first()->window_num)
        <div class="window-info">
            Window Number: {{ $completedAppointments->first()->window_num }}
        </div>
        <div class="clear"></div>
    @endif

    <!-- Completed Transactions Table -->
    <div class="table-container">
        <div class="table-header">
            <h2>Completed Transactions</h2>
            <span class="record-count">Total Records: {{ $completedCount }}</span>
        </div>

        @if ($completedAppointments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="width: 90px;">Queue #</th>
                        <th>Client Name</th>
                        <th style="width: 120px;">Service</th>
                        <th style="width: 100px;">Served Time</th>
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
                            <td>{{ $app->queue_for }}</td>
                            <td>{{ \Carbon\Carbon::parse($app->time_catered)->setTimezone('Asia/Manila')->format('h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table>
                <tr>
                    <td colspan="5" class="empty-state">
                        No completed transactions for today
                    </td>
                </tr>
            </table>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        Page 1 of 1
    </div>
</body>

</html>
