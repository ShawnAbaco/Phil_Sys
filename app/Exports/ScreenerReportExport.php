<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class ScreenerReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $appointments;
    protected $totalRecords;
    protected $dateToday;
    protected $timeGenerated;
    protected $dateRangeDisplay;
    protected $serviceDisplay;
    protected $statusDisplay;
    protected $startDate;
    protected $endDate;

    public function __construct($appointments, $totalRecords, $dateToday, $timeGenerated, $dateRangeDisplay, $serviceDisplay, $statusDisplay, $startDate, $endDate)
    {
        $this->appointments = $appointments;
        $this->totalRecords = $totalRecords;
        $this->dateToday = $dateToday;
        $this->timeGenerated = $timeGenerated;
        $this->dateRangeDisplay = $dateRangeDisplay;
        $this->serviceDisplay = $serviceDisplay;
        $this->statusDisplay = $statusDisplay;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->appointments;
    }

    public function headings(): array
    {
        return [
            ['PSA PHILSYS - SCREENER TRANSACTIONS REPORT'],
            ['Date Range: ' . $this->dateRangeDisplay],
            ['Service Filter: ' . $this->serviceDisplay . ' | Status Filter: ' . $this->statusDisplay],
            ['Generated: ' . $this->dateToday . ' at ' . $this->timeGenerated],
            ['Total Records: ' . $this->totalRecords],
            ['Report ID: SCR-EXCEL-' . date('YmdHis')],
            [''],
            ['#', 'Queue #', 'Last Name', 'First Name', 'Middle Name', 'Suffix', 'Priority', 'Birthdate', 'TRN', 'PCN', 'Service', 'Served Time', 'Window', 'Status']
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
            ? Carbon::parse($appointment->time_catered)->setTimezone('Asia/Manila')->format('h:i A')
            : ($appointment->updated_at 
                ? Carbon::parse($appointment->updated_at)->setTimezone('Asia/Manila')->format('h:i A')
                : Carbon::parse($appointment->created_at)->setTimezone('Asia/Manila')->format('h:i A'));
        
        $priorityType = $appointment->priority_type ?? 'regular';
        $priorityDisplay = ucfirst($priorityType);
        
        $status = $appointment->status ?? 'pending';
        $statusDisplay = ucfirst(str_replace('_', ' ', $status));
        
        return [
            $rowNumber,
            $appointment->q_id,
            $appointment->lname ?? '',
            $appointment->fname ?? '',
            $appointment->mname ?? '',
            $appointment->suffix ?? '',
            $priorityDisplay,
            $birthdate,
            $appointment->trn ?? 'N/A',
            $appointment->PCN ?? 'N/A',
            $appointment->queue_for,
            $servedTime,
            $appointment->window_num ?? 'N/A',
            $statusDisplay,
        ];
    }

    public function title(): string
    {
        return 'Screener_Report_' . Carbon::now('Asia/Manila')->format('Y-m-d');
    }

    public function styles(Worksheet $sheet)
    {
        // Count statistics from the appointments collection
        $completedCount = $this->appointments->where('status', 'completed')->count();
        $cancelledCount = $this->appointments->where('status', 'cancelled')->count();
        $noShowCount = $this->appointments->where('status', 'no_show')->count();
        $pendingCount = $this->appointments->where('status', 'pending')->count();
        $servingCount = $this->appointments->where('status', 'serving')->count();
        
        // Insert summary statistics after the headers (at row 8)
        $sheet->insertNewRowBefore(8, 3);
        $sheet->setCellValue('A8', 'SUMMARY STATISTICS:');
        $sheet->setCellValue('A9', 'Completed: ' . $completedCount);
        $sheet->setCellValue('C9', 'Cancelled: ' . $cancelledCount);
        $sheet->setCellValue('E9', 'No Show: ' . $noShowCount);
        $sheet->setCellValue('G9', 'Pending: ' . $pendingCount);
        $sheet->setCellValue('I9', 'Serving: ' . $servingCount);
        
        // Merge cells for the title (14 columns from A to N)
        $sheet->mergeCells('A1:N1');
        $sheet->mergeCells('A2:N2');
        $sheet->mergeCells('A3:N3');
        $sheet->mergeCells('A4:N4');
        $sheet->mergeCells('A5:N5');
        $sheet->mergeCells('A6:N6');
        $sheet->mergeCells('A7:N7');
        
        // Title styling
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '0038A8'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Date range styling
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Filters styling
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Generated time styling
        $sheet->getStyle('A4')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Total records styling
        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Summary header styling
        $sheet->getStyle('A8')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8F0FE'],
            ],
        ]);
        
        // Summary rows styling
        $sheet->getStyle('A9:I9')->applyFromArray([
            'font' => [
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F5F5F5'],
            ],
        ]);

        // Table header styling (now at row 11 because we inserted 3 rows at position 8)
        $sheet->getStyle('A11:N11')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0038A8'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Add borders to the table
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A11:N' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        // Color code the status column (column N)
        for ($row = 12; $row <= $lastRow; $row++) {
            $status = $sheet->getCell('N' . $row)->getValue();
            if ($status === 'Completed') {
                $sheet->getStyle('N' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '2E7D32'], 'bold' => true],
                ]);
            } elseif ($status === 'Cancelled') {
                $sheet->getStyle('N' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'B71C1C'], 'bold' => true],
                ]);
            } elseif ($status === 'No show') {
                $sheet->getStyle('N' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'F57C00'], 'bold' => true],
                ]);
            } elseif ($status === 'Pending') {
                $sheet->getStyle('N' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'F59E0B'], 'bold' => true],
                ]);
            } elseif ($status === 'Serving') {
                $sheet->getStyle('N' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '2563EB'], 'bold' => true],
                ]);
            }
        }

        // Auto-size columns
        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}