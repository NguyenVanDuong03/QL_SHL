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

    public function arrayUpdates(array $phases, int $conductEvaluationPeriodId): bool
    {
        if (empty($phases) || empty($conductEvaluationPeriodId)) {
            return false;
        }

        foreach ($phases as $index => $phase) {
            $this->getModel()
                ->newQuery()
                ->where('conduct_evaluation_period_id', $conductEvaluationPeriodId)
                ->where('role', $index)
                ->update([
                    'open_date' => $phase['open_date'],
                    'end_date' => $phase['end_date'],
                ]);
        }

        return true;
    }

    public function arrayDeleteByConductEvaluationPeriodId($id)
    {
        if (empty($id)) {
            return false;
        }

        return $this->getModel()
            ->newQuery()
            ->where('conduct_evaluation_period_id', $id)
            ->delete();
    }

}
