<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Lecturer;
use Illuminate\Support\Facades\DB;

class LecturerRepository extends BaseRepository
{
    protected function getModel(): Lecturer
    {
        if (empty($this->model)) {
            $this->model = app()->make(Lecturer::class);
        }

        return $this->model;
    }

    public function getTotalStudentsByLecturer($lecturerId)
    {
        return $this->getModel()
            ->newQuery()
            ->join('study_classes', 'lecturers.id', '=', 'study_classes.lecturer_id')
            ->join('students', 'study_classes.id', '=', 'students.study_class_id')
            ->where('lecturers.id', $lecturerId)
            ->count();
    }

    public function getAverageConductScoreByLecturer($lecturerId)
    {
        return $this->getModel()
            ->newQuery()
            ->join('study_classes as sc', 'lecturers.id', '=', 'sc.lecturer_id')
            ->join('students as s', 's.study_class_id', '=', 'sc.id')
            ->leftJoin('student_conduct_scores as scs', 'scs.student_id', '=', 's.id')
            ->leftJoin('conduct_evaluation_periods as cep', 'scs.conduct_evaluation_period_id', '=', 'cep.id')
            ->leftJoin('semesters as sem', 'cep.semester_id', '=', 'sem.id')
            ->leftJoin(DB::raw('(
            SELECT
                student_conduct_scores.student_id,
                SUM(detail_conduct_scores.final_score) AS total_score
            FROM detail_conduct_scores
            INNER JOIN student_conduct_scores
                ON detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            GROUP BY student_conduct_scores.student_id
        ) as student_total_score'), 'student_total_score.student_id', '=', 's.id')
            ->where('lecturers.id', $lecturerId)
            ->select(
                'sem.name as semester_name',
                'sem.school_year',
                'sc.name as class_name',
                DB::raw('COUNT(DISTINCT s.id) as total_students'),
                DB::raw('ROUND(AVG(student_total_score.total_score), 2) as average_conduct_score')
            )
            ->groupBy('sem.name', 'sem.school_year', 'sc.name')
            ->orderByDesc('sem.school_year')
            ->orderBy('sem.name')
            ->orderBy('sc.name')
            ->get();
    }

    public function getAllWithTrashed($params)
    {
        $query = $this->getModel()
            ->newQuery()
            ->with([
                'user' => function ($query) {
                    $query->withTrashed();
                },
                'faculty',
                'faculty.department'
            ])
            ->orderByDesc('id')
            ->withTrashed();

        return $query;
    }

}
