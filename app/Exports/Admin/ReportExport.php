<?php

namespace App\Exports\Admin;

use App\Models\TblAppointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $appointments;
    protected $reportType;
    protected $startDate;
    protected $endDate;
    protected $stats;

    public function __construct($appointments, $reportType, $startDate = null, $endDate = null, $stats = [])
    {
        $this->appointments = $appointments;
        $this->reportType = $reportType;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->stats = $stats;
    }

    public function collection()
    {
        return $this->appointments;
    }

    public function headings(): array
    {
        $headers = [
            'Queue #',
            'Client Name',
            'Priority Type',
            'Service',
            'Status',
            'Window #',
            'Time Served',
            'Date Created'
        ];

        if ($this->reportType === 'detailed') {
            array_splice($headers, 1, 0, ['First Name', 'Middle Name', 'Last Name', 'Suffix']);
            array_splice($headers, 8, 0, ['TRN', 'PCN']);
        }

        return $headers;
    }

    public function map($appointment): array
    {
        $fullName = $appointment->lname . ', ' . $appointment->fname;
        if ($appointment->mname && $appointment->mname !== '') {
            $fullName .= ' ' . $appointment->mname;
        }
        if ($appointment->suffix && $appointment->suffix !== '') {
            $fullName .= ' ' . $appointment->suffix;
        }

        $row = [
            $appointment->q_id ?? '',
            $fullName,
            strtoupper($appointment->priority_type ?? 'regular'),
            $appointment->queue_for ?? '',
            strtoupper($appointment->status ?? 'pending'),
            $appointment->window_num ?: 'N/A',
            $appointment->time_catered ? Carbon::parse($appointment->time_catered)->format('Y-m-d H:i:s') : 'N/A',
            Carbon::parse($appointment->created_at)->format('Y-m-d H:i:s'),
        ];

        if ($this->reportType === 'detailed') {
            array_splice($row, 1, 1); // Remove the combined full name
            array_splice($row, 1, 0, [
                $appointment->fname ?? '',
                $appointment->mname ?? '',
                $appointment->lname ?? '',
                $appointment->suffix ?? ''
            ]);
            array_splice($row, 8, 0, [
                $appointment->trn ?? 'N/A',
                $appointment->PCN ?? 'N/A'
            ]);
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0038A8']]
            ],
        ];
    }

    public function columnWidths(): array
    {
        if ($this->reportType === 'detailed') {
            return [
                'A' => 12,
                'B' => 15,
                'C' => 15,
                'D' => 15,
                'E' => 12,
                'F' => 12,
                'G' => 20,
                'H' => 12,
                'I' => 15,
                'J' => 20,
                'K' => 20,
            ];
        }
        
        return [
            'A' => 12,
            'B' => 25,
            'C' => 12,
            'D' => 18,
            'E' => 12,
            'F' => 10,
            'G' => 20,
            'H' => 20,
        ];
    }
}