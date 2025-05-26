<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\CohortRepository;
use Illuminate\Support\Arr;

class CohortService extends BaseService
{
    protected function getRepository(): CohortRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(CohortRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');

        return [
            'sort' => $sort,
            'wheres' => $wheres
        ];
    }

}
