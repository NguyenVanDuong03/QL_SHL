<?php

namespace App\Repositories;

use App\Models\ConductEvaluationPeriod;

class ConductEvaluationPeriodRepository extends BaseRepository
{
    protected function getModel(): ConductEvaluationPeriod
    {
        if (empty($this->model)) {
            $this->model = app()->make(ConductEvaluationPeriod::class);
        }

        return $this->model;
    }

    public function currentConductEvaluationPeriod()
    {
        return $this->getModel()->orderBy('id', 'desc')
            ->get()
            ->first();
    }

    public function conductEvaluationPeriodBySemesterId($semesterId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('semester_id', $semesterId)
            ->orderBy('id', 'desc')
            ->first();
    }

}
