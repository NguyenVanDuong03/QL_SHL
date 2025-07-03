<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\DepartmentRepository;
use Illuminate\Support\Arr;

class DepartmentService extends BaseService
{
    protected function getRepository(): DepartmentRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(DepartmentRepository::class);
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
}
