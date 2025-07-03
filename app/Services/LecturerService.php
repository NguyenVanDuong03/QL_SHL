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
        $relates = ['user', 'faculty', 'faculty.department'];
        $whereHas = Arr::get($params, 'where_has', []);
        $search = Arr::get($params, 'search', null);
        if (trim($search)) {
            $search = trim($search);
            $whereHas['user'] = function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            };
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => array_unique($relates),
        ];
    }

    public function getTotalStudentsByLecturer($lecturerId)
    {
        return $this->getRepository()->getTotalStudentsByLecturer($lecturerId);
    }

    public function getAverageConductScoreByLecturer($lecturerId)
    {
        return $this->getRepository()->getAverageConductScoreByLecturer($lecturerId);
    }

    public function getAllWithTrashed($params)
    {
        return $this->getRepository()->getAllWithTrashed($params);
    }

}
