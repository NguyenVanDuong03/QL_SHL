<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ConductEvaluationPhaseRepository;
use Illuminate\Support\Arr;

class ConductEvaluationPhaseService extends BaseService
{
    protected function getRepository(): ConductEvaluationPhaseRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ConductEvaluationPhaseRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');

        return [
            'sort' => $sort,
            'wheres' => $wheres,
        ];
    }

    public function arrayUpdates($phases, $conductEvaluationPeriodId): bool
    {
        if (empty($conductEvaluationPeriodId)) {
            return false;
        }

        return $this->getRepository()->arrayUpdates($phases, $conductEvaluationPeriodId);
    }

    public function arrayDeleteByConductEvaluationPeriodId($id)
    {
        return $this->getRepository()->arrayDeleteByConductEvaluationPeriodId($id);
    }
}
