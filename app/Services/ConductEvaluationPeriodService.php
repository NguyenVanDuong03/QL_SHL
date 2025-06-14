<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ConductEvaluationPeriodRepository;
use Illuminate\Support\Arr;

class ConductEvaluationPeriodService extends BaseService
{
    protected function getRepository(): ConductEvaluationPeriodRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ConductEvaluationPeriodRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');
        $relates = Arr::get($params, 'relates', []);
        $withSemester = Arr::get($params, 'withSemester', null);
        if ($withSemester) {
            $relates = ['semester'];
        }


        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates,
        ];
    }

    public function currentConductEvaluationPeriod()
    {
        return $this->getRepository()->currentConductEvaluationPeriod();
    }

    public function checkConductEvaluationPeriod()
    {
        $currentConductEvaluationPeriod = $this->currentConductEvaluationPeriod();
        if ($currentConductEvaluationPeriod) {
            $openDate = $currentConductEvaluationPeriod->open_date;
            $endDate = $currentConductEvaluationPeriod->end_date;
            $now = now();

            if ($now->isBetween($openDate, $endDate) || $now->lt($openDate)) {
                return true;
            }
        }

        return false;
    }

    public function findConductEvaluationPeriodBySemesterId($semesterId)
    {
        return $this->getRepository()->findConductEvaluationPeriodBySemesterId($semesterId);
    }

}
