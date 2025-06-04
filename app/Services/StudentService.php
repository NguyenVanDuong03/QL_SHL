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
        $getAll = Arr::get($params, 'getAll', false);
        if ($getAll) {
            $relates = [...$relates, 'studyClass', 'user', 'cohort'];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates
        ];
    }

    public function getStudentsByClassId($params)
    {
        return $this->getRepository()->getStudentListByClassId($params);
    }

    public function getNoteStudentById($class_id)
    {
        return $this->getRepository()->getNoteStudentById($class_id);
    }

    public function updateOfficers($params)
    {
        $studyClassId = $params['study_class_id'] ?? null;
        if (empty($studyClassId)) {
            return false;
        }

        // Reset tất cả cán sự cũ về sinh viên
        $this->getRepository()->resetClassOfficers($studyClassId);

        // Danh sách tương ứng các role
        $positionMap = [
            'classLeader' => Constant::STUDENT_POSITION['CLASS_PRESIDENT'],
            'classViceLeader' => Constant::STUDENT_POSITION['VICE_PRESIDENT'],
            'classSecretary' => Constant::STUDENT_POSITION['SECRETARY'],
        ];

        // Cập nhật từng vai trò nếu có
        foreach ($positionMap as $field => $positionValue) {
            if (!empty($params[$field])) {
                $this->getRepository()->updateStudentPosition($params[$field], $positionValue);
            }
        }

        return true;
    }

}
