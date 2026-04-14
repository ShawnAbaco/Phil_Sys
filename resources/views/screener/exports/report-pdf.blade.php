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
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000000;
            background: white;
            margin: 0.5in;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }

        .header .republika {
            font-size: 8pt;
            letter-spacing: 1px;
        }

        .header h2 {
            font-size: 13pt;
            margin: 3px 0;
            letter-spacing: 1px;
        }

        .header h3 {
            font-size: 11pt;
            margin: 2px 0;
        }

        .header .report-title {
            font-size: 13pt;
            font-weight: bold;
            margin: 5px 0;
            padding: 3px 0;
        }

        /* Report Info */
        .report-info {
            margin-bottom: 10px;
            padding: 6px;
            background: #f5f5f5;
            border-left: 3px solid #0038A8;
            font-size: 8pt;
        }

        .report-info p {
            margin: 2px 0;
        }

        /* Summary Section */
        .summary {
            margin-bottom: 12px;
            padding: 6px;
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
            font-size: 8pt;
        }

        .summary strong {
            color: #2e7d32;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-bottom: 12px;
        }

        .data-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
            font-size: 7.5pt;
        }

        .data-table td {
            border: 1px solid #ccc;
            padding: 4px 3px;
            vertical-align: top;
        }

        /* Column Widths for Portrait */
        .col-sn { width: 4%; text-align: center; }
        .col-queue { width: 8%; }
        .col-name { width: 22%; }
        .col-priority { width: 8%; }
        .col-service { width: 14%; }
        .col-window { width: 6%; text-align: center; }
        .col-time { width: 9%; text-align: center; }
        .col-status { width: 9%; text-align: center; }

        /* Status Colors */
        .status-completed { color: #2e7d32; font-weight: bold; }
        .status-cancelled { color: #c62828; font-weight: bold; }
        .status-no_show { color: #ef6c00; font-weight: bold; }
        .status-pending { color: #f9a825; font-weight: bold; }
        .status-serving { color: #1565c0; font-weight: bold; }

        /* Priority Badges */
        .priority-senior { 
            background: #8b5cf6; 
            color: white; 
            padding: 2px 4px; 
            border-radius: 3px; 
            font-size: 6.5pt;
            display: inline-block;
        }
        .priority-infant { 
            background: #f59e0b; 
            color: white; 
            padding: 2px 4px; 
            border-radius: 3px; 
            font-size: 6.5pt;
            display: inline-block;
        }
        .priority-pwd { 
            background: #10b981; 
            color: white; 
            padding: 2px 4px; 
            border-radius: 3px; 
            font-size: 6.5pt;
            display: inline-block;
        }
        .priority-pregnant { 
            background: #ec4899; 
            color: white; 
            padding: 2px 4px; 
            border-radius: 3px; 
            font-size: 6.5pt;
            display: inline-block;
        }
        .priority-regular { 
            background: #6b7280; 
            color: white; 
            padding: 2px 4px; 
            border-radius: 3px; 
            font-size: 6.5pt;
            display: inline-block;
        }

        /* Queue Number */
        .queue-number {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 7pt;
            padding-top: 8px;
            margin-top: 15px;
            border-top: 1px solid #ccc;
            color: #666;
        }
        
        /* Page Number at Bottom Right */
        .page-number-container {
            text-align: right;
            font-size: 8pt;
            margin-top: 10px;
            padding-top: 5px;
            color: #333;
        }

        /* Page Break */
        .page-break {
            page-break-before: always;
        }

        /* Text Alignment */
        .text-center { text-align: center; }
        
        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        
        .signature-box {
            width: 220px;
            text-align: center;
        }
        
        .signature-line {
            margin: 5px 0 3px;
            border-bottom: 1px solid #000000;
            padding-top: 15px;
        }
        
        .signature-text {
            font-size: 8pt;
            font-style: italic;
        }
        
        /* Content wrapper */
        .content-wrapper {
            min-height: 90vh;
            position: relative;
        }
    </style>
</head>
<body>

@php
    $rowsPerPage = 18;
    $totalRows = $appointments->count();
    $totalPages = ceil($totalRows / $rowsPerPage);
    $currentPage = 1;
    $chunks = $appointments->chunk($rowsPerPage);
@endphp

@foreach ($chunks as $chunk)
    <div class="content-wrapper">
        {{-- Header Section --}}
        <div class="header">
            <div class="republika">Republic of the Philippines</div>
            <h2>PHILIPPINE STATISTICS AUTHORITY</h2>
            <h3>Philippine Identification System (PhilSys)</h3>
            <div class="report-title">SCREENER TRANSACTIONS REPORT</div>
        </div>

        {{-- Report Information --}}
        <div class="report-info">
            <p><strong>Report No.:</strong> SCR-{{ date('YmdHis') }} | <strong>Date:</strong> {{ $dateToday }} | <strong>Time:</strong> {{ $timeGenerated }}</p>
            <p><strong>Date Range:</strong> {{ $dateRangeDisplay }} | <strong>Service:</strong> {{ $serviceDisplay }} | <strong>Status:</strong> {{ $statusDisplay }}</p>
        </div>

        {{-- Summary - Only on first page --}}
        @if ($currentPage == 1)
        <div class="summary">
            <strong>Total Transactions:</strong> {{ $totalRecords }} 
            (Completed: {{ $completedCount }} | Cancelled: {{ $cancelledCount }} | No Show: {{ $noShowCount }} | Pending: {{ $pendingCount }} | Serving: {{ $servingCount }})
        </div>
        @endif

        {{-- Data Table --}}
        <table class="data-table">
            <thead>
                <tr>
                    <th class="col-sn">#</th>
                    <th class="col-queue">Queue</th>
                    <th class="col-name">Client Name</th>
                    <th class="col-priority">Priority</th>
                    <th class="col-service">Service</th>
                    <th class="col-window">Win</th>
                    <th class="col-time">Time</th>
                    <th class="col-status">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $app)
                    @php
                        // Format full name
                        $lastName = strtoupper($app->lname ?? '');
                        $firstName = ucwords(strtolower($app->fname ?? ''));
                        $middleName = $app->mname ? ' ' . ucwords(strtolower($app->mname)) : '';
                        $suffix = $app->suffix ? ' ' . strtoupper($app->suffix) : '';
                        $fullName = $lastName . ', ' . $firstName . $middleName . $suffix;
                        
                        // Truncate long names
                        if (strlen($fullName) > 30) {
                            $fullName = substr($fullName, 0, 28) . '...';
                        }
                        
                        // Format time
                        $servedTime = $app->time_catered
                            ? \Carbon\Carbon::parse($app->time_catered)->setTimezone('Asia/Manila')->format('h:i A')
                            : ($app->updated_at
                                ? \Carbon\Carbon::parse($app->updated_at)->setTimezone('Asia/Manila')->format('h:i A')
                                : \Carbon\Carbon::parse($app->created_at)->setTimezone('Asia/Manila')->format('h:i A'));
                        
                        $status = $app->status ?? 'pending';
                        $statusDisplay = ucfirst(str_replace('_', ' ', $status));
                        $priorityType = $app->priority_type ?? 'regular';
                        $priorityDisplay = ucfirst($priorityType);
                    @endphp
                    <tr>
                        <td class="col-sn">{{ $app->row_number }}</td>
                        <td class="col-queue"><span class="queue-number">{{ $app->q_id }}</span></td>
                        <td class="col-name">{{ $fullName }}</td>
                        <td class="col-priority"><span class="priority-{{ $priorityType }}">{{ $priorityDisplay }}</span></td>
                        <td class="col-service">{{ $app->queue_for }}</td>
                        <td class="col-window">{{ $app->window_num ?? '—' }}</td>
                        <td class="col-time">{{ $servedTime }}</td>
                        <td class="col-status status-{{ $status }}">{{ $statusDisplay }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Signature on last page only --}}
        @if ($currentPage == $totalPages && $totalRows > 0)
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-text">(Signature Over Printed Name)</div>
            </div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            PSA PhilSys - Official Document | Generated by Screener Portal
        </div>
        
        {{-- Page Number at Bottom Right --}}
        <div class="page-number-container">
            Page {{ $currentPage }} of {{ $totalPages }}
        </div>
    </div>

    @if (!$loop->last)
        <div class="page-break"></div>
    @endif

    @php $currentPage++; @endphp
@endforeach

{{-- Empty state if no records --}}
@if ($totalRows == 0)
    <div class="content-wrapper">
        <div class="header">
            <div class="republika">Republic of the Philippines</div>
            <h2>PHILIPPINE STATISTICS AUTHORITY</h2>
            <h3>Philippine Identification System (PhilSys)</h3>
            <div class="report-title">SCREENER TRANSACTIONS REPORT</div>
        </div>

        <div class="report-info">
            <p><strong>Report No.:</strong> SCR-{{ date('YmdHis') }} | <strong>Date:</strong> {{ $dateToday }} | <strong>Time:</strong> {{ $timeGenerated }}</p>
            <p><strong>Date Range:</strong> {{ $dateRangeDisplay }} | <strong>Service:</strong> {{ $serviceDisplay }} | <strong>Status:</strong> {{ $statusDisplay }}</p>
        </div>

        <div class="summary">
            <strong>Total Transactions:</strong> 0
        </div>

        <div style="padding: 50px; text-align: center;">
            <p>No transactions recorded for this period.</p>
        </div>

        <div class="footer">
            PSA PhilSys - Official Document | Generated by Screener Portal
        </div>
        
        <div class="page-number-container">
            Page 1 of 1
        </div>
    </div>
@endif

</body>
</html>