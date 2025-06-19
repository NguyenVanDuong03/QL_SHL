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
        $search = Arr::get($params, 'search', null);

        if ($withSemester) {
            $relates[] = 'semester';
        }

        $whereHas = Arr::get($params, 'where_has', []);
        if ($search) {
            $search = trim($search);
            $whereHas['semester'] = function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('school_year', 'LIKE', "%{$search}%");
            };
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates,
            'where_has' => $whereHas,
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
