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
            ['COMPLETED APPOINTMENTS REPORT'],
            ['Date: ' . $this->dateToday],
            [''],
            ['Summary Statistics'],
            ['Total Appointments Today:', $this->totalToday],
            ['Completed:', $this->completedCount],
            [''],
            ['#', 'Queue #', 'Last Name', 'First Name', 'Middle Name', 'Suffix', 'Service', 'Served Time', 'Window']
        ];
    }

    public function map($appointment): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        return [
            $rowNumber,
            $appointment->q_id,
            $appointment->lname,
            $appointment->fname,
            $appointment->mname ?? '',
            $appointment->suffix ?? '',
            $appointment->queue_for,
            Carbon::parse($appointment->time_catered)->setTimezone('Asia/Manila')->format('h:i A'),
            $appointment->window_num ?? 'N/A',
        ];
    }

    public function title(): string
    {
        return 'Completed Appointments';
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for the title
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A4:I4');
        
        // Title styling
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '2563EB'],
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
        $sheet->getStyle('A5:B6')->applyFromArray([
            'font' => [
                'size' => 11,
            ],
        ]);

        // Completed count styling
        $sheet->getStyle('B6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '059669'],
            ],
        ]);

        // Table header styling
        $sheet->getStyle('A8:I8')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Add borders to the table
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A8:I' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        return [];
    }
}