<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\SemesterReponsitory;
use Carbon\Carbon;
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
        $wheres = Arr::get($params, 'wheres', []);
        $keywords = Arr::get($params, 'search', null);
        if($keywords) {
            $wheres[] = [function ($q) use ($keywords) {
                $q->whereAny([
                    'name',
                    'school_year',
                ], 'like', "%{$keywords}%");
            }];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
        ];
    }

    public function getFourSemester()
    {
        return $this->getRepository()->getFourSemester();
    }

    public function statisticalSemester($lecturerId)
    {
        return $this->getRepository()->statisticalSemester($lecturerId);
    }

    public function statisticalAllSemester()
    {
        return $this->getRepository()->statisticalAllSemester();
    }

    public function staticalAcademicWarningBySemester()
    {
        return $this->getRepository()->staticalAcademicWarningBySemester();
    }

}
