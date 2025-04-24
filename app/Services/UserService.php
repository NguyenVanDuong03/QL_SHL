<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Arr;

class UserService extends BaseService
{
    protected function getRepository(): UserRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(UserRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');

        $keyword = Arr::get($params, 'searchName', null);
        if ($keyword) {
            $wheres[] = ['name', 'LIKE', "%$keyword%"];
        };

        $keywordSearchAll = Arr::get($params, 'searchAll', null);
        if ($keywordSearchAll) {
            $wheres[] = [function ($q) use ($keywordSearchAll) {
                $q->where('name', 'LIKE', "%$keywordSearchAll%")
                    ->orWhere('email', 'LIKE', "%$keywordSearchAll%");
            }];
        }

        return [
            'wheres' => $wheres,
            'sort' => $sort,
        ];
    }

    public function redirectAuthPath()
    {
        if (auth()->user()->role == 0) {
            return 'teacher.index';
        } else if (auth()->user()->role == 1) {
            return 'studen-affairs-department.index';
        } else if (auth()->user()->role == 2) {
            return 'class-staff.index';
        }

        return 'student.index';
    }
}
