<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class SemesterReponsitory extends BaseRepository
{
    protected function getModel(): Semester
    {
        if (empty($this->model)) {
            $this->model = app()->make(Semester::class);
        }

        return $this->model;
    }

    public function getFourSemester()
    {
        return $this->getModel()
            ->newQuery()
            ->whereIn('name', [Constant::SEMESTER_TYPE['SEMESTER_1'], Constant::SEMESTER_TYPE['SEMESTER_2']])
            ->orderByDesc('id');
    }

    public function statisticalSemester($lecturerId)
    {
        return $this->getModel()
            ->select([
                'semesters.name as semester_name',
                'semesters.school_year',
                'study_classes.name as class_name',
                'study_classes.id as class_id',
                'class_session_requests.id as class_session_requests_id',
                'class_session_requests.proposed_at as proposed_at',
                DB::raw('COUNT(attendances.id) as attendance_count'),
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.study_class_id = study_classes.id) as total_students')
            ])
            ->join('class_session_registrations', 'semesters.id', '=', 'class_session_registrations.semester_id')
            ->join('class_session_requests', function ($join) {
                $join->on('class_session_registrations.id', '=', 'class_session_requests.class_session_registration_id')
                    ->where('class_session_requests.type', Constant::CLASS_SESSION_TYPE['FIXED']);
            })
            ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
            ->join('lecturers', 'study_classes.lecturer_id', '=', 'lecturers.id')
            ->leftJoin('attendances', function ($join) {
                $join->on('class_session_requests.id', '=', 'attendances.class_session_request_id')
                    ->where('attendances.status', 2);
            })
            ->where('lecturers.id', $lecturerId)
            ->groupBy(
                'semesters.name',
                'semesters.school_year',
                'study_classes.name',
                'study_classes.id',
                'class_session_requests.proposed_at',
                'study_classes.id',
                'class_session_requests.id'
            )
            ->orderByDesc('semesters.school_year')
            ->orderBy('semesters.name')
            ->orderBy('study_classes.name')
            ->get();
    }

    public function statisticalAllSemester()
    {
        return $this->getModel()
            ->select([
                'semesters.name as semester_name',
                'semesters.school_year',
                'study_classes.name as class_name',
                'study_classes.id as class_id',
                'class_session_requests.id as class_session_requests_id',
                'class_session_requests.proposed_at',
                DB::raw('COUNT(attendances.id) as attendance_count'),
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.study_class_id = study_classes.id) as total_students'),
                'class_session_requests.status' // Trạng thái của buổi sinh hoạt
            ])
            ->join('class_session_registrations', 'semesters.id', '=', 'class_session_registrations.semester_id')
            ->join('class_session_requests', function ($join) {
                $join->on('class_session_registrations.id', '=', 'class_session_requests.class_session_registration_id')
                    ->where('class_session_requests.type', Constant::CLASS_SESSION_TYPE['FIXED']);
            })
            ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
            ->leftJoin('attendances', function ($join) {
                $join->on('class_session_requests.id', '=', 'attendances.class_session_request_id')
                    ->where('attendances.status', 2); // status 2 là tham gia
            })
            ->groupBy(
                'semesters.name',
                'semesters.school_year',
                'study_classes.name',
                'study_classes.id',
                'class_session_requests.id',
                'class_session_requests.proposed_at',
                'class_session_requests.status'
            )
            ->orderByDesc('semesters.school_year')
            ->orderBy('semesters.name')
            ->orderBy('study_classes.name')
            ->get();
    }

    public function staticalAcademicWarningBySemester()
    {
        return $this->getModel()
            ->newQuery()
            ->leftJoin('academic_warnings as aw', 'aw.semester_id', '=', 'semesters.id')
            ->select(
                'semesters.id as semester_id',
                'semesters.name as semester_name',
                'semesters.school_year',
                DB::raw('COUNT(aw.id) as warning_count')
            )
            ->whereIn('semesters.name', ['Học kỳ 1', 'Học kỳ 2'])
            ->groupBy('semesters.id', 'semesters.name', 'semesters.school_year')
            ->orderBy('semesters.start_date')
            ->get();
    }


}
