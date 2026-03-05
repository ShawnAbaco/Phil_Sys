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
            ['PSA PHILSYS - OPERATOR RECENT TRANSACTIONS REPORT'],
            ['Window Number: ' . $this->windowNum],
            ['Date: ' . $this->dateToday . ' | Generated: ' . $this->timeGenerated],
            ['Total Records: ' . $this->totalCompleted],
            ['Report ID: OPR-EXCEL-' . date('YmdHis')],
            [''],
            ['#', 'Queue #', 'Last Name', 'First Name', 'Middle Name', 'Suffix', 'Age Category', 'Birthdate', 'TRN', 'PCN', 'Service', 'Served Time', 'Window']
        ];
    }

    public function map($appointment): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        $birthdate = $appointment->birthdate 
            ? Carbon::parse($appointment->birthdate)->format('Y-m-d') 
            : 'N/A';
        
        $servedTime = Carbon::parse($appointment->time_catered)
            ->setTimezone('Asia/Manila')
            ->format('h:i A');
        
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
            $appointment->window_num ?? 'N/A',
        ];
    }

    public function title(): string
    {
        return 'Window ' . $this->windowNum . ' Transactions';
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for the title
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');
        $sheet->mergeCells('A4:M4');
        $sheet->mergeCells('A5:M5');
        
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

        // Window number styling
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '059669'],
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

        // Table header styling
        $sheet->getStyle('A7:M7')->applyFromArray([
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
        $sheet->getStyle('A7:M' . $lastRow)->applyFromArray([
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