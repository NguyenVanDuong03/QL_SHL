<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\FacultyRepository;
use Illuminate\Support\Arr;

class FacultyService extends BaseService
{
    protected function getRepository(): FacultyRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(FacultyRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');
        $relates = Arr::get($params, 'relates', ['department']);

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates,
        ];
    }
}
