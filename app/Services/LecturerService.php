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
        $relates = array_intersect(Arr::get($params, 'relates', []), ['user', 'faculty', 'faculty.department']);
        $relates[] = 'faculty.department';
        // $keyword = Arr::get($params, 'search', '');
        // if ($keyword) {
        //     $wheres[] = function ($query) use ($keyword) {
        //         $query->whereHas('user', function ($q) use ($keyword) {
        //             $q->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%");
        //         });
        //     };
        // }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => array_unique($relates),
        ];
    }
}
