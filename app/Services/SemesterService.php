<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\SemesterReponsitory;
use Illuminate\Support\Arr;

class SemesterService extends BaseService
{
    protected function getRepository(): SemesterReponsitory
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(SemesterReponsitory::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $sort = Arr::get($params, 'sort', 'id:desc');

        $ClassSessionRegistration = Arr::get($params, 'type');
        if ($ClassSessionRegistration === 'ClassSessionRegistration') {
            $wheres[] = [function ($q) {
                $q->whereIn('type', [Constant::SEMESTER_TYPE['SEMESTER_1'], Constant::SEMESTER_TYPE['SEMESTER_2']]);
            }];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres ?? [],
        ];
    }
}
