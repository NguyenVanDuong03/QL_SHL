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

    public function findConductEvaluationPeriodBySemesterId($semesterId)
    {
        return $this->getModel()->where('semester_id', $semesterId)
            ->where('open_date', '<=', now('Asia/Ho_Chi_Minh'))
            ->where('end_date', '>=', now('Asia/Ho_Chi_Minh'))
            ->first();
    }

}
