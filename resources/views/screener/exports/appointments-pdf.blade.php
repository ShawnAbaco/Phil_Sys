<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSA PhilSys - Transactions Report</title>
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

        /* Page break handling */
        .page-break {
            page-break-after: always;
        }

        /* Official Letterhead - Appears on every page */
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

        /* Page Number - Top Right Corner (Plain) */
        .page-number {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 10pt;
            color: #333333;
            font-weight: normal;
        }

        /* Reference Line - Appears on every page */
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

        /* Summary Line - Only on first page */
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

        /* Table Styles */
        .table-container {
            margin-bottom: 30px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            border: 1px solid #000000;
        }

        th {
            background-color: #ffffff;
            color: #000000;
            font-weight: 600;
            padding: 8px 5px;
            text-align: left;
            border-bottom: 2px solid #000000;
            border-right: 1px solid #cccccc;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th:last-child {
            border-right: none;
        }

        td {
            padding: 6px 5px;
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
            font-size: 10pt;
        }

        .client-name {
            font-weight: normal;
        }

        .trn-cell, .pcn-cell {
            font-family: 'Courier New', monospace;
            font-size: 9pt;
        }

        .window-cell {
            text-align: center;
        }

        .remarks-cell {
            text-align: center;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-completed {
            color: #2e7d32;
            font-weight: 600;
        }

        .status-cancelled {
            color: #b71c1c;
            font-weight: 600;
        }

        /* Column Widths */
        .col-sn { width: 4%; }
        .col-queue { width: 8%; }
        .col-name { width: 18%; }
        .col-service { width: 10%; }
        .col-trn { width: 12%; }
        .col-pcn { width: 12%; }
        .col-time { width: 10%; }
        .col-window { width: 6%; }
        .col-remarks { width: 10%; }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 9pt;
            padding: 8px 0;
            border-top: 1px solid #cccccc;
            color: #555555;
            font-style: italic;
            margin-top: 20px;
        }

        /* Signature - Only on last page */
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

        /* Empty State */
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
        // Define how many rows per page
        $rowsPerPage = 22;
        $totalRows = $completedAppointments->count();
        $totalPages = ceil($totalRows / $rowsPerPage);
        $currentPage = 1;
        $rowCounter = 0;
        
        // Count statistics by status
        $completedCount = $completedAppointments->where('status', 'completed')->count();
        $cancelledCount = $completedAppointments->where('status', 'cancelled')->count();
    @endphp

    @foreach ($completedAppointments->chunk($rowsPerPage) as $chunk)
        <!-- Official Letterhead (appears on every page) -->
        <div class="official-header">
            <div class="republika">Republic of the Philippines</div>
            <div class="psa-title">PHILIPPINE STATISTICS AUTHORITY</div>
            <div class="philsys-title">Philippine Identification System (PhilSys)</div>
            <div class="report-title">TRANSACTIONS REPORT</div>
            
            <!-- Page Number - Top Right Corner (Automatically generates based on total pages) -->
            <div class="page-number">
                page {{ $currentPage }} of {{ $totalPages }}
            </div>
        </div>

        <!-- Reference Line (appears on every page) -->
        <div class="reference-line">
            <span><strong>Report No.:</strong> PSA-REP-{{ date('YmdHis') }}</span>
            <span class="separator">|</span>
            <span><strong>Date:</strong> {{ $dateToday }}</span>
            <span class="separator">|</span>
            <span><strong>Time:</strong> {{ $timeGenerated }}</span>
        </div>

        <!-- Summary Line - Only on first page -->
        @if ($currentPage == 1)
            <div class="summary-line">
                <strong>Total Transactions:</strong> {{ $completedAppointments->count() }} 
                (Completed: {{ $completedCount }} | Cancelled: {{ $cancelledCount }})
            </div>
        @endif

        <!-- Transactions Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="col-sn">#</th>
                        <th class="col-queue">Queue No.</th>
                        <th class="col-name">Client Name</th>
                        <th class="col-service">Service</th>
                        <th class="col-trn">TRN</th>
                        <th class="col-pcn">PCN</th>
                        <th class="col-time">Time Served</th>
                        <th class="col-window">Window</th>
                        <th class="col-remarks">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chunk as $app)
                        @php
                            $rowCounter++;
                            
                            // Formal name formatting
                            $lastName = strtoupper($app->lname ?? '');
                            $firstName = ucwords(strtolower($app->fname ?? ''));
                            $middleName = $app->mname ? ucwords(strtolower($app->mname)) : '';
                            $suffix = $app->suffix ? strtoupper($app->suffix) : '';
                            
                            $fullName = $lastName . ', ' . $firstName;
                            if ($middleName) {
                                $fullName .= ' ' . $middleName;
                            }
                            if ($suffix) {
                                $fullName .= ' ' . $suffix;
                            }
                            
                            // Format TRN and PCN
                            $trn = $app->trn ?? '—';
                            $pcn = $app->PCN ?? '—';
                            
                            // Format time
                            $servedTime = $app->time_catered
                                ? \Carbon\Carbon::parse($app->time_catered)
                                    ->setTimezone('Asia/Manila')
                                    ->format('h:i A')
                                : '—';
                            
                            // Determine status and class for remarks
                            $status = $app->status ?? 'completed';
                            $statusDisplay = ucfirst(str_replace('_', ' ', $status));
                            $statusClass = '';
                            
                            if ($status === 'completed') {
                                $statusClass = 'status-completed';
                            } elseif ($status === 'cancelled') {
                                $statusClass = 'status-cancelled';
                            }
                        @endphp
                        <tr>
                            <td class="number-cell">{{ $app->row_number }}</td>
                            <td><span class="queue-number">{{ $app->q_id }}</span></td>
                            <td class="client-name">{{ $fullName }}</td>
                            <td>{{ $app->queue_for }}</td>
                            <td class="trn-cell">{{ $trn }}</td>
                            <td class="pcn-cell">{{ $pcn }}</td>
                            <td>{{ $servedTime }}</td>
                            <td class="window-cell">{{ $app->window_num ?? '—' }}</td>
                            <td class="remarks-cell {{ $statusClass }}">{{ $statusDisplay }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Signature Line - Only on last page -->
        @if ($currentPage == $totalPages)
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-text">(Signature Over Printed Name)</div>
                </div>
            </div>
        @endif

        <!-- Page Break (except for last page) -->
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif

        @php $currentPage++; @endphp
    @endforeach

    <!-- Handle empty result set -->
    @if ($totalRows == 0)
        <div class="official-header">
            <div class="republika">Republic of the Philippines</div>
            <div class="psa-title">PHILIPPINE STATISTICS AUTHORITY</div>
            <div class="philsys-title">Philippine Identification System (PhilSys)</div>
            <div class="report-title">TRANSACTIONS REPORT</div>
            
            <!-- Page Number - Top Right Corner -->
            <div class="page-number">
                page 1 of 1
            </div>
        </div>

        <div class="reference-line">
            <span><strong>Report No.:</strong> PSA-COMP-{{ date('YmdHis') }}</span>
            <span class="separator">|</span>
            <span><strong>Date:</strong> {{ $dateToday }}</span>
            <span class="separator">|</span>
            <span><strong>Time:</strong> {{ $timeGenerated }}</span>
        </div>

        <div class="summary-line">
            <strong>Total Transactions:</strong> 0
        </div>

        <div class="empty-state">
            No transactions recorded for this period.
        </div>
    @endif
</body>

</html>