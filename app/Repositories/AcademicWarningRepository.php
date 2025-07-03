<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\AcademicWarning;
use Illuminate\Support\Facades\DB;

class AcademicWarningRepository extends BaseRepository
{
    protected function getModel(): AcademicWarning
    {
        if (empty($this->model)) {
            $this->model = app()->make(AcademicWarning::class);
        }

        return $this->model;
    }

    public function getStudentWarningByStudyClassId($lecturerId)
    {
        $getAllStudentWarning = $this->getModel()
            ->newQuery()
            ->whereHas('student.studyClass', function ( $query) use ($lecturerId) {
                $query->where('lecturer_id', $lecturerId);
            })
            ->with([
                'student' => function ($q) {
                    $q->select('id', 'user_id', 'student_code', 'study_class_id')->with(['user:id,name,email', 'studyClass:id,name']);
                },
            ])
            ->get();

            return $getAllStudentWarning;
    }

    public function listStudyClassAcademicWarning($params)
    {
        $semester_id = $params['semester_id'] ?? null;
        $search = $params['search'] ?? null;

        $query = $this->getModel()
                ->with(['student', 'semester', 'student.user:id,name,email', 'student.studyClass:id,name'])
                ->orderByDesc('id');

        if (!empty($semester_id)) {
            $query->where('semester_id', $semester_id);
        }

        if (!empty($search)) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'LIKE', "%$search%");
                })->orWhereHas('studyClass', function ($q2) use ($search) {
                    $q2->where('name', 'LIKE', "%$search%");
                });
            });
        }

        return $query->paginate(Constant::DEFAULT_LIMIT_12);
    }

    public function academicWarningBySemesterId($semesterId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('semester_id', $semesterId)
            ->with(['student', 'semester', 'student.user:id,name,email', 'student.studyClass:id,name'])
            ->orderByDesc('id');
    }

    public function getAcademicWarningsCountByLecturerAndSemester($lecturerId, $semesterId)
    {
        return DB::table('students as s')
            ->join('academic_warnings as aw', 's.id', '=', 'aw.student_id')
            ->join('study_classes as sc', 's.study_class_id', '=', 'sc.id')
            ->join('lecturers as l', 'sc.lecturer_id', '=', 'l.id')
            ->join('semesters as sem', 'aw.semester_id', '=', 'sem.id')
            ->selectRaw('sem.id as semester_id, sem.name as semester_name, sem.school_year, COUNT(DISTINCT s.id) as total_students')
            ->where('l.id', $lecturerId)
            ->where('sem.id', $semesterId)
            ->groupBy('sem.id', 'sem.name', 'sem.school_year')
            ->first();
    }

}
