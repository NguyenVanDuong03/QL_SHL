<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\StudentConductScoreRepository;
use Illuminate\Support\Arr;

class StudentConductScoreService extends BaseService
{
    protected function getRepository(): StudentConductScoreRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(StudentConductScoreRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');
        $relates = Arr::get($params, 'relates', []);
        $keywords = Arr::get($params, 'conductEvaluationPeriodId', null);

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates,
        ];
    }
}
