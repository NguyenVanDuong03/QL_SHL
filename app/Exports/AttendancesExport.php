<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendancesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $studyClassId;
    protected $classRequestId;

    public function __construct($classRequestId, $studyClassId)
    {
        $this->studyClassId = $studyClassId;
        $this->classRequestId = $classRequestId;
    }

    public function query()
    {
        return Attendance::query()
            ->select('students.student_code', 'users.name', 'attendances.status', 'attendances.reason')
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('class_session_requests', 'attendances.class_session_request_id', '=', 'class_session_requests.id')
            ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
            ->where('study_classes.id', $this->studyClassId)
            ->where('class_session_requests.id', $this->classRequestId);
    }

    public function headings(): array
    {
        return [
            'Mã sinh viên',
            'Tên sinh viên',
            'Trạng thái điểm danh',
            'Lý do',
        ];
    }

    public function map($row): array
    {
        $statusMap = [
            0 => 'Chưa xác nhận điểm danh',
            1 => 'Xin vắng',
            2 => 'Đã điểm danh',
            3 => 'Vắng mặt',
        ];

        return [
            $row->student_code,
            $row->name,
            $statusMap[$row->status] ?? 'Không xác định',
            $row->reason ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(50);

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $statusText = $sheet->getCell('C' . $row)->getValue();

                    $colorMap = [
                        'Chưa xác nhận điểm danh' => 'FFCCE5FF',
                        'Xin vắng'                => 'FFD9D9D9',
                        'Đã điểm danh'            => 'FFD4EDDA',
                        'Vắng mặt'                => 'FFF8D7DA',
                    ];

                    $fillColor = $colorMap[$statusText] ?? null;

                    if ($fillColor) {
                        $sheet->getStyle('C' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($fillColor);
                    }
                }
            }
        ];
    }
}
