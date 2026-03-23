<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSA PhilSys - Operator Transactions Report</title>
</head>

<body>
    @php
        // Define how many rows per page
        $rowsPerPage = 18; // Adjusted for more columns
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
            <div class="report-title">OPERATOR TRANSACTIONS REPORT</div>
            
            <!-- Page Number - Top Right Corner -->
            <div class="page-number">
                page {{ $currentPage }} of {{ $totalPages }}
            </div>
        </div>

        <!-- Reference Line (appears on every page) -->
        <div class="reference-line">
            <span><strong>Report No.:</strong> OPR-{{ date('YmdHis') }}</span>
            <span class="separator">|</span>
            <span><strong>Date:</strong> {{ $dateToday }}</span>
            <span class="separator">|</span>
            <span><strong>Time:</strong> {{ $timeGenerated }}</span>
        </div>

        <!-- Operator Info Line -->
        <div class="operator-info-line">
            <strong>Window Number:</strong> {{ $windowNum }}
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
                        <th class="col-queue">Queue</th>
                        <th class="col-name">Client Name</th>
                        <th class="col-age">Age</th>
                        <th class="col-birthdate">Birthdate</th>
                        <th class="col-trn">TRN</th>
                        <th class="col-pcn">PCN</th>
                        <th class="col-service">Service</th>
                        <th class="col-time">Time</th>
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
                            
                            // Format birthdate
                            $birthdate = $app->birthdate 
                                ? \Carbon\Carbon::parse($app->birthdate)->format('m/d/y')
                                : '—';
                            
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
                            <td class="client-name">
                                {{ $fullName }}
                                @if ($app->age_category)
                                    <small>Age: {{ $app->age_category }}</small>
                                @endif
                            </td>
                            <td>{{ $app->age_category ? substr($app->age_category, 0, 1) : '—' }}</td>
                            <td>{{ $birthdate }}</td>
                            <td class="trn-cell">{{ $trn }}</td>
                            <td class="pcn-cell">{{ $pcn }}</td>
                            <td>{{ $app->queue_for }}</td>
                            <td class="time-cell">{{ $servedTime }}</td>
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
            <div class="report-title">OPERATOR TRANSACTIONS REPORT</div>
            
            <!-- Page Number - Top Right Corner -->
            <div class="page-number">
                page 1 of 1
            </div>
        </div>

        <div class="reference-line">
            <span><strong>Report No.:</strong> OPR-{{ date('YmdHis') }}</span>
            <span class="separator">|</span>
            <span><strong>Date:</strong> {{ $dateToday }}</span>
            <span class="separator">|</span>
            <span><strong>Time:</strong> {{ $timeGenerated }}</span>
        </div>

        <div class="operator-info-line">
            <strong>Window Number:</strong> {{ $windowNum }}
        </div>

        <div class="summary-line">
            <strong>Total Transactions:</strong> 0
        </div>

        <div class="empty-state">
            No transactions recorded for this period.
        </div>
    @endif

    <!-- Footer (only appears if there's content) -->
    @if ($totalRows > 0)
        <div class="footer">
            PSA PhilSys - Official Document | Generated by Window {{ $windowNum }}
        </div>
    @endif
</body>

</html>