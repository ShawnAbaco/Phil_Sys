# PSA PHILSYS - Operator Recent Transactions Report
# Date: {{ $dateToday }}
# Generated: {{ $timeGenerated }}
# Window Number: {{ $windowNum }}
# Total Records: {{ $totalCompleted }}
# Report ID: OPR-CSV-{{ date('YmdHis') }}
#
#,Queue #,Client Name,Age Category,Birthdate,TRN,PCN,Service,Served Time,Window
@foreach ($completedAppointments as $app)
    {{ $app->row_number }},{{ $app->q_id }},"{{ $app->lname }},
    {{ $app->fname }}{{ $app->mname ? ' ' . $app->mname : '' }}{{ $app->suffix ? ' ' . $app->suffix : '' }}",{{ $app->age_category ?? 'N/A' }},{{ $app->birthdate ? \Carbon\Carbon::parse($app->birthdate)->format('Y-m-d') : 'N/A' }},{{ $app->trn ?? 'N/A' }},{{ $app->PCN ?? 'N/A' }},"{{ $app->queue_for }}",{{ \Carbon\Carbon::parse($app->time_catered)->setTimezone('Asia/Manila')->format('h:i A') }},{{ $app->window_num ?? 'N/A' }}
@endforeach
