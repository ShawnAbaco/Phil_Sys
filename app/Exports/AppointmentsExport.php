<?php
namespace App\Exports;

use App\Models\TblAppointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class AppointmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $appointments;
    protected $totalToday;
    protected $completedCount;
    protected $dateToday;

    public function __construct($appointments, $totalToday, $completedCount, $dateToday)
    {
        $this->appointments = $appointments;
        $this->totalToday = $totalToday;
        $this->completedCount = $completedCount;
        $this->dateToday = $dateToday;
    }

    public function collection()
    {
        return $this->appointments;
    }

    public function headings(): array
    {
        return [
            ['PSA PHILSYS - COMPLETED & CANCELLED TRANSACTIONS REPORT'],
            ['Date: ' . $this->dateToday],
            [''],
            ['Summary Statistics'],
            ['Total Appointments Today:', $this->totalToday],
            ['Completed:', $this->completedCount],
            ['Cancelled:', $this->appointments->where('status', 'cancelled')->count()],
            [''],
            ['#', 'Queue #', 'Last Name', 'First Name', 'Middle Name', 'Suffix', 'Service', 'Served Time', 'Window', 'Remarks']
        ];
    }

    public function map($appointment): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        // Determine status display
        $status = $appointment->status ?? 'completed';
        $statusDisplay = ucfirst(str_replace('_', ' ', $status));
        
        return [
            $rowNumber,
            $appointment->q_id,
            $appointment->lname,
            $appointment->fname,
            $appointment->mname ?? '',
            $appointment->suffix ?? '',
            $appointment->queue_for,
            $appointment->time_catered 
                ? Carbon::parse($appointment->time_catered)->setTimezone('Asia/Manila')->format('h:i A')
                : '—',
            $appointment->window_num ?? '—',
            $statusDisplay, // Remarks column
        ];
    }

    public function title(): string
    {
        return 'Daily Transactions';
    }

    public function styles(Worksheet $sheet)
    {
        // Count statistics
        $cancelledCount = $this->appointments->where('status', 'cancelled')->count();
        
        // Merge cells for the title - updated to J (10 columns)
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A4:J4');
        
        // Title styling
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '0038A8'], // PSA Blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Date row styling
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Summary header styling
        $sheet->getStyle('A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B5563'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Summary rows styling
        $sheet->getStyle('A5:B7')->applyFromArray([
            'font' => [
                'size' => 11,
            ],
        ]);

        // Completed count styling
        $sheet->getStyle('B6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '2E7D32'], // Green
            ],
        ]);

        // Cancelled count styling
        $sheet->getStyle('B7')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'B71C1C'], // Red
            ],
        ]);

        // Table header styling - updated to J
        $sheet->getStyle('A9:J9')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0038A8'], // PSA Blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Add borders to the table - updated to J
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A9:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        // Color code the remarks column based on status
        for ($row = 10; $row <= $lastRow; $row++) {
            $status = $sheet->getCell('J' . $row)->getValue();
            if ($status === 'Completed') {
                $sheet->getStyle('J' . $row)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '2E7D32'], // Green
                        'bold' => true,
                    ],
                ]);
            } elseif ($status === 'Cancelled') {
                $sheet->getStyle('J' . $row)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'B71C1C'], // Red
                        'bold' => true,
                    ],
                ]);
            }
        }

        // Auto-size columns - updated range to J
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}