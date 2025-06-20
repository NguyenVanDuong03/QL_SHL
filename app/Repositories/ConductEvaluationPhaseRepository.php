<?php

namespace App\Repositories;

use App\Models\ConductEvaluationPhase;

class ConductEvaluationPhaseRepository extends BaseRepository
{
    protected function getModel(): ConductEvaluationPhase
    {
        if (empty($this->model)) {
            $this->model = app()->make(ConductEvaluationPhase::class);
        }

        return $this->model;
    }

}
