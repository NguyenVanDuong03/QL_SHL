<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ConductCriteriaRepository;
use Illuminate\Support\Arr;

class ConductCriteriaService extends BaseService
{
    protected function getRepository(): ConductCriteriaRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ConductCriteriaRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);

        return [
            'wheres' => $wheres,
        ];
    }
}
