<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSA PhilSys - Screener Transactions Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0.75in;
            color: #000000;
            background: #ffffff;
            line-height: 1.3;
            font-size: 11pt;
            position: relative;
        }

        .page-break {
            page-break-after: always;
        }

        .official-header {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
        }

        .republika {
            font-size: 10pt;
            font-weight: normal;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .psa-title {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 5px 0 2px;
        }

        .philsys-title {
            font-size: 12pt;
            font-weight: normal;
            margin-bottom: 15px;
        }

        .report-title {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            border-top: 2px solid #000000;
            border-bottom: 2px solid #000000;
            padding: 8px 0;
            margin: 10px 0;
            letter-spacing: 1px;
        }

        .page-number {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 10pt;
            color: #333333;
            font-weight: normal;
        }

        .reference-line {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 20px;
            border-bottom: 1px solid #cccccc;
            padding: 8px 0;
            letter-spacing: 0.3px;
        }

        .reference-line span {
            margin: 0 10px;
            white-space: nowrap;
        }

        .reference-line .separator {
            color: #999999;
            font-weight: normal;
            margin: 0 5px;
        }

        .reference-line strong {
            font-weight: 600;
            margin-right: 5px;
        }

        .filter-info-line {
            text-align: left;
            font-size: 10pt;
            margin: 10px 0 15px;
            padding: 8px 0;
            background: #f5f5f5;
            border-left: 3px solid #0038A8;
            padding-left: 10px;
        }

        .filter-info-line strong {
            font-weight: 600;
            color: #0038A8;
        }

        .summary-line {
            text-align: left;
            font-size: 11pt;
            margin: 15px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000000;
        }

        .summary-line strong {
            font-weight: 600;
            color: #2e7d32;
            font-size: 13pt;
            margin-left: 10px;
        }

        .table-container {
            margin-bottom: 30px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            border: 1px solid #000000;
        }

        th {
            background-color: #ffffff;
            color: #000000;
            font-weight: 600;
            padding: 6px 4px;
            text-align: left;
            border-bottom: 2px solid #000000;
            border-right: 1px solid #cccccc;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        th:last-child {
            border-right: none;
        }

        td {
            padding: 5px 4px;
            border-bottom: 1px solid #cccccc;
            border-right: 1px solid #cccccc;
            vertical-align: top;
        }

        td:last-child {
            border-right: none;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .number-cell {
            text-align: center;
            font-weight: normal;
        }

        .queue-number {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            font-size: 9pt;
        }

        .time-cell {
            font-family: 'Courier New', monospace;
            font-size: 8.5pt;
            text-align: center;
        }

        .remarks-cell {
            text-align: center;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-completed { color: #2e7d32; font-weight: 600; }
        .status-cancelled { color: #b71c1c; font-weight: 600; }
        .status-no_show { color: #f57c00; font-weight: 600; }
        .status-pending { color: #f59e0b; font-weight: 600; }
        .status-serving { color: #2563eb; font-weight: 600; }

        .priority-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: 600;
            color: white;
        }

        .priority-senior { background: #8b5cf6; }
        .priority-infant { background: #f59e0b; }
        .priority-pwd { background: #10b981; }
        .priority-pregnant { background: #ec4899; }
        .priority-regular { background: #6b7280; }

        .col-sn { width: 3%; }
        .col-queue { width: 6%; }
        .col-name { width: 18%; }
        .col-priority { width: 6%; }
        .col-service { width: 10%; }
        .col-window { width: 5%; }
        .col-time { width: 8%; }
        .col-status { width: 8%; }

        .footer {
            text-align: center;
            font-size: 9pt;
            padding: 8px 0;
            border-top: 1px solid #cccccc;
            color: #555555;
            font-style: italic;
            margin-top: 20px;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            width: 250px;
            text-align: center;
        }

        .signature-line {
            margin: 5px 0 3px;
            border-bottom: 1px solid #000000;
            padding-top: 20px;
        }

        .signature-text {
            font-size: 10pt;
            font-style: italic;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666666;
            font-style: italic;
            border: 1px solid #cccccc;
        }
    </style>
</head>

<body>
    @php
        $rowsPerPage = 18;
        $totalRows = $appointments->count();
        $totalPages = ceil($totalRows / $rowsPerPage);
        $currentPageNum = 1;
        
        $completedCount = $appointments->where('status', 'completed')->count();
        $cancelledCount = $appointments->where('status', 'cancelled')->count();
        $noShowCount = $appointments->where('status', 'no_show')->count();
        $pendingCount = $appointments->where('status', 'pending')->count();
        $servingCount = $appointments->where('status', 'serving')->count();
    @endphp

    @foreach ($appointments->chunk($rowsPerPage) as $chunk)
        <div class="official-header">
            <div class="republika">Republic of the Philippines</div>
            <div class="psa-title">PHILIPPINE STATISTICS AUTHORITY</div>
            <div class="philsys-title">Philippine Identification System (PhilSys)</div>
            <div class="report-title">SCREENER TRANSACTIONS REPORT</div>
            <div class="page-number">page {{ $currentPageNum }} of {{ $totalPages }}</div>
        </div>

        <div class="reference-line">
            <span><strong>Report No.:</strong> SCR-{{ date('YmdHis') }}</span>
            <span class="separator">|</span>
            <span><strong>Date:</strong> {{ $dateToday }}</span>
            <span class="separator">|</span>
            <span><strong>Time:</strong> {{ $timeGenerated }}</span>
        </div>

        <div class="filter-info-line">
            <strong>Date Range:</strong> {{ $dateRangeDisplay }} &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Service:</strong> {{ $serviceDisplay }} &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Status:</strong> {{ $statusDisplay }}
        </div>

        @if ($currentPageNum == 1)
            <div class="summary-line">
                <strong>Total Transactions:</strong> {{ $totalRows }} 
                (Completed: {{ $completedCount }} | Cancelled: {{ $cancelledCount }} | No Show: {{ $noShowCount }} | Pending: {{ $pendingCount }} | Serving: {{ $servingCount }})
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="col-sn">#</th>
                        <th class="col-queue">Queue</th>
                        <th class="col-name">Client Name</th>
                        <th class="col-priority">Priority</th>
                        <th class="col-service">Service</th>
                        <th class="col-window">Window</th>
                        <th class="col-time">Time</th>
                        <th class="col-status">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chunk as $app)
                        @php
                            $lastName = strtoupper($app->lname ?? '');
                            $firstName = ucwords(strtolower($app->fname ?? ''));
                            $middleName = $app->mname ? ucwords(strtolower($app->mname)) : '';
                            $suffix = $app->suffix ? strtoupper($app->suffix) : '';
                            
                            $fullName = $lastName . ', ' . $firstName;
                            if ($middleName) $fullName .= ' ' . $middleName;
                            if ($suffix) $fullName .= ' ' . $suffix;
                            
                            $servedTime = $app->time_catered
                                ? \Carbon\Carbon::parse($app->time_catered)->setTimezone('Asia/Manila')->format('h:i A')
                                : ($app->updated_at
                                    ? \Carbon\Carbon::parse($app->updated_at)->setTimezone('Asia/Manila')->format('h:i A')
                                    : \Carbon\Carbon::parse($app->created_at)->setTimezone('Asia/Manila')->format('h:i A'));
                            
                            $status = $app->status ?? 'pending';
                            $statusDisplay = ucfirst(str_replace('_', ' ', $status));
                            $statusClass = 'status-' . str_replace('_', '-', $status);
                            
                            $priorityType = $app->priority_type ?? 'regular';
                            $priorityDisplay = ucfirst($priorityType);
                        @endphp
                        <tr>
                            <td class="number-cell">{{ $app->row_number }}</td>
                            <td><span class="queue-number">{{ $app->q_id }}</span></td>
                            <td>{{ $fullName }}</td>
                            <td><span class="priority-badge priority-{{ $priorityType }}">{{ $priorityDisplay }}</span></td>
                            <td>{{ $app->queue_for }}</td>
                            <td>{{ $app->window_num ?? '—' }}</td>
                            <td class="time-cell">{{ $servedTime }}</td>
                            <td class="remarks-cell {{ $statusClass }}">{{ $statusDisplay }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($currentPageNum == $totalPages)
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-text">(Signature Over Printed Name)</div>
                </div>
            </div>
        @endif

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif

        @php $currentPageNum++; @endphp
    @endforeach

    @if ($totalRows == 0)
        <div class="official-header">
            <div class="republika">Republic of the Philippines</div>
            <div class="psa-title">PHILIPPINE STATISTICS AUTHORITY</div>
            <div class="philsys-title">Philippine Identification System (PhilSys)</div>
            <div class="report-title">SCREENER TRANSACTIONS REPORT</div>
            <div class="page-number">page 1 of 1</div>
        </div>

        <div class="reference-line">
            <span><strong>Report No.:</strong> SCR-{{ date('YmdHis') }}</span>
            <span class="separator">|</span>
            <span><strong>Date:</strong> {{ $dateToday }}</span>
            <span class="separator">|</span>
            <span><strong>Time:</strong> {{ $timeGenerated }}</span>
        </div>

        <div class="filter-info-line">
            <strong>Date Range:</strong> {{ $dateRangeDisplay }} &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Service:</strong> {{ $serviceDisplay }} &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Status:</strong> {{ $statusDisplay }}
        </div>

        <div class="summary-line">
            <strong>Total Transactions:</strong> 0
        </div>

        <div class="empty-state">
            No transactions recorded for this period.
        </div>
    @endif

    @if ($totalRows > 0)
        <div class="footer">
            PSA PhilSys - Official Document | Generated by Screener Portal
        </div>
    @endif
</body>
</html>