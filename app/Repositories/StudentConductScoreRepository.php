<?php

namespace App\Repositories;

use App\Models\StudentConductScore;
use Illuminate\Support\Facades\DB;

class StudentConductScoreRepository extends BaseRepository
{
    protected function getModel(): StudentConductScore
    {
        if (empty($this->model)) {
            $this->model = app()->make(StudentConductScore::class);
        }

        return $this->model;
    }

    public function findStudentConductScore($conductEvaluationPeriodId, $studentId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('conduct_evaluation_period_id', $conductEvaluationPeriodId)
            ->where('student_id', $studentId)
            ->first();
    }

    public function getAverageConductScores()
    {
        $subquery = DB::table('detail_conduct_scores as dcs')
            ->join('student_conduct_scores as scs', 'dcs.student_conduct_score_id', '=', 'scs.id')
            ->whereNotNull('dcs.final_score')
            ->groupBy('scs.conduct_evaluation_period_id', 'scs.student_id')
            ->selectRaw('scs.conduct_evaluation_period_id, SUM(dcs.final_score) as total_score');

        return DB::table(DB::raw('(' . $subquery->toSql() . ') as student_scores'))
            ->mergeBindings($subquery)
            ->join('conduct_evaluation_periods as cep', 'student_scores.conduct_evaluation_period_id', '=', 'cep.id')
            ->join('semesters as s', 'cep.semester_id', '=', 's.id')
            ->selectRaw('s.id as semester_id, s.name as semester_name, s.school_year, ROUND(AVG(student_scores.total_score), 2) as average_conduct_score')
            ->groupBy('s.id', 's.name', 's.school_year')
            ->orderBy('s.school_year')
            ->orderBy('s.name')
            ->get();
    }

    public function getOverallAverageConductScoreWithTotalStudents()
    {
        $subquery = DB::table('detail_conduct_scores as dcs')
            ->join('student_conduct_scores as scs', 'dcs.student_conduct_score_id', '=', 'scs.id')
            ->whereNotNull('dcs.final_score')
            ->groupBy('scs.student_id', 'scs.conduct_evaluation_period_id')
            ->selectRaw('scs.student_id, SUM(dcs.final_score) as total_score');

        return DB::table(DB::raw('(' . $subquery->toSql() . ') as student_scores'))
            ->mergeBindings($subquery)
            ->selectRaw('ROUND(AVG(total_score), 2) as average_conduct_score, COUNT(DISTINCT student_id) as total_students')
            ->first();
    }

}
