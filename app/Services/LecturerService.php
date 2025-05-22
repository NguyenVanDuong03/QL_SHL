<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\LecturerRepository;
use Illuminate\Support\Arr;

class LecturerService extends BaseService
{
    protected function getRepository(): LecturerRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(LecturerRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $sort = Arr::get($params, 'sort', 'id:desc');
        $wheres = Arr::get($params, 'wheres', []);
        $relates = array_intersect(
    Arr::get($params, 'relates', []),
    ['user', 'faculty']
);

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates
        ];
    }



}
