<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\StudentRepository;
use Illuminate\Support\Arr;

class StudentService extends BaseService
{
    protected function getRepository(): StudentRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(StudentRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $sort = Arr::get($params, 'sort', 'id:desc');
        $wheres = Arr::get($params, 'wheres', []);
        $relates = Arr::get($params, 'relates', []);
        $studentClassId = Arr::get($params, 'student_class_id', null);
        if ($studentClassId) {
            $wheres[] = ['study_class_id', '=', $studentClassId];
            $relates[] = ['user'];
        }

//        dd($params);
        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates
        ];
    }

    public function getStudentsByClassId($classId)
    {
        return $this->getRepository()->getStudentListByClassId($classId);
    }

}
