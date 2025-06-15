<?php

namespace App\Repositories;

use App\Models\StudentConductScore;

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

}
