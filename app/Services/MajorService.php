<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\MajorRepository;
use Illuminate\Support\Arr;

class MajorService extends BaseService
{
    protected function getRepository(): MajorRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(MajorRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');
        $relates = Arr::get($params, 'relates', []);
        $isDepartment = Arr::get($params, 'isDepartment', false);
        if ($isDepartment) {
            $relates = [
                'faculty',
                'faculty.department'
            ];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates,
        ];
    }
}
