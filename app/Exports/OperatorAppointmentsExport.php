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

class OperatorAppointmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $appointments;
    protected $totalCompleted;
    protected $dateToday;
    protected $timeGenerated;
    protected $windowNum;
    protected $userId;

    public function __construct($appointments, $totalCompleted, $dateToday, $timeGenerated, $windowNum, $userId)
    {
        $this->appointments = $appointments;
        $this->totalCompleted = $totalCompleted;
        $this->dateToday = $dateToday;
        $this->timeGenerated = $timeGenerated;
        $this->windowNum = $windowNum;
        $this->userId = $userId;
    }

    public function collection()
    {
        return $this->appointments;
    }

    public function headings(): array
    {
        return [
            ['PSA PHILSYS - OPERATOR TRANSACTIONS REPORT'],
            ['Window Number: ' . $this->windowNum],
            ['Date: ' . $this->dateToday . ' | Generated: ' . $this->timeGenerated],
            ['Total Records: ' . $this->totalCompleted],
            ['Report ID: OPR-EXCEL-' . date('YmdHis')],
            [''],
            ['#', 'Queue #', 'Last Name', 'First Name', 'Middle Name', 'Suffix', 'Age Category', 'Birthdate', 'TRN', 'PCN', 'Service', 'Served Time', 'Remarks']
        ];
    }

    public function map($appointment): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        $birthdate = $appointment->birthdate 
            ? Carbon::parse($appointment->birthdate)->format('Y-m-d') 
            : 'N/A';
        
        $servedTime = $appointment->time_catered
            ? Carbon::parse($appointment->time_catered)
                ->setTimezone('Asia/Manila')
                ->format('h:i A')
            : '—';
        
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
            $appointment->age_category ?? 'N/A',
            $birthdate,
            $appointment->trn ?? 'N/A',
            $appointment->PCN ?? 'N/A',
            $appointment->queue_for,
            $servedTime,
            $statusDisplay, // Remarks column
        ];
    }

    public function title(): string
    {
        return 'Window ' . $this->windowNum . ' Transactions';
    }

    public function styles(Worksheet $sheet)
    {
        // Count statistics for summary
        $completedCount = $this->appointments->where('status', 'completed')->count();
        $cancelledCount = $this->appointments->where('status', 'cancelled')->count();
        
        // Insert summary statistics after the headers
        $sheet->insertNewRowBefore(7, 2);
        $sheet->setCellValue('A7', 'Summary:');
        $sheet->setCellValue('B7', 'Completed: ' . $completedCount);
        $sheet->setCellValue('D7', 'Cancelled: ' . $cancelledCount);
        
        // Merge cells for the title - updated from N to M (13 columns)
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');
        $sheet->mergeCells('A4:M4');
        $sheet->mergeCells('A5:M5');
        $sheet->mergeCells('A6:M6'); // Empty row
        
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

        // Window number styling
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'CE1126'], // PSA Red
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Date and time styling
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Total records styling
        $sheet->getStyle('A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Summary row styling
        $sheet->getStyle('A7:B7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F0F8FF'],
            ],
        ]);
        
        $sheet->getStyle('D7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF0F0'],
            ],
        ]);

        // Table header styling (now at row 9 because we inserted 2 rows) - updated from N to M
        $sheet->getStyle('A9:M9')->applyFromArray([
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

        // Add borders to the table - updated from N to M
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A9:M' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        // Color code the remarks column based on status - updated from N to M (column 13)
        for ($row = 10; $row <= $lastRow; $row++) {
            $status = $sheet->getCell('M' . $row)->getValue();
            if ($status === 'Completed') {
                $sheet->getStyle('M' . $row)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '2E7D32'], // Green
                        'bold' => true,
                    ],
                ]);
            } elseif ($status === 'Cancelled') {
                $sheet->getStyle('M' . $row)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'B71C1C'], // Red
                        'bold' => true,
                    ],
                ]);
            }
        }

        // Auto-size columns - updated range from A to M
        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}