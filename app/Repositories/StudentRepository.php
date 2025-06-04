<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Student;

class StudentRepository extends BaseRepository
{
    protected function getModel(): Student
    {
        if (empty($this->model)) {
            $this->model = app()->make(Student::class);
        }

        return $this->model;
    }

    public function getStudentListByClassId($params)
    {
        $search = $params['search'] ?? '';
        $classId = $params['class_id'] ?? null;

//        if (empty($classId)) {
//            return [];
//        }

        $query = $this->getModel()
            ->with(['studyClass', 'user'])
            ->where('study_class_id', $classId);

        if (!empty($search)) {
            $query = $query->where(function ($q) use ($search) {
                $q->where('student_code', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('email', 'like', '%' . $search . '%')
                      ->orWhere('name', 'like', '%' . $search . '%');
                  });
            });
        }

        return $query->paginate(Constant::DEFAULT_LIMIT_8);
    }

}
