<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
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
            ->select('students.student_code', 'users.name', DB::raw('COALESCE(attendances.status, 0) as status'), 'attendances.reason')
            ->rightJoin('students', 'attendances.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('study_classes', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('class_session_requests', function ($join) {
                $join->on('attendances.class_session_request_id', '=', 'class_session_requests.id')
                    ->where('class_session_requests.id', '=', $this->classRequestId)
                    ->where('class_session_requests.type', '=', 0)
                    ->where('class_session_requests.status', '=', 3);
            })
            ->where('study_classes.id', $this->studyClassId)
            ->groupBy('students.id', 'students.student_code', 'users.name', 'attendances.status', 'attendances.reason')
            ->orderBy('students.student_code');
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
