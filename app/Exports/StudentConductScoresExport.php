<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentConductScoresExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $studyClassId;
    protected $semesterId;

    public function __construct($studyClassId, $semesterId)
    {
        $this->studyClassId = $studyClassId;
        $this->semesterId = $semesterId;
    }

    public function query()
    {
        return Student::query()
            ->select(
                'students.student_code',
                'users.name',
                DB::raw('COALESCE(SUM(detail_conduct_scores.final_score), 0) as total_conduct_score')
            )
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('study_classes', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->whereIn('student_conduct_scores.conduct_evaluation_period_id', function ($query) {
                        $query->select('id')
                            ->from('conduct_evaluation_periods')
                            ->where('semester_id', $this->semesterId);
                    });
            })
            ->leftJoin('detail_conduct_scores', 'student_conduct_scores.id', '=', 'detail_conduct_scores.student_conduct_score_id')
            ->where('study_classes.id', $this->studyClassId)
            ->groupBy('students.id', 'students.student_code', 'users.name')
            ->orderBy('students.student_code');
    }

    public function headings(): array
    {
        return [
            'Mã sinh viên',
            'Họ và tên',
            'Tổng điểm rèn luyện',
        ];
    }

    public function map($row): array
    {
        return [
            $row->student_code,
            $row->name,
            $row->total_conduct_score,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(25);

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

        return [
            // Định dạng tiêu đề
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD3D3D3'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Thêm viền cho toàn bộ bảng
                $event->sheet->getStyle(
                    'A1:C' . $event->sheet->getHighestRow()
                )->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}
