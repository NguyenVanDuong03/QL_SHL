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

}
