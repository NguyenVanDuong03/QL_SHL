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

    public function getStudentListByClassId($classId)
    {
        $query = $this->getModel()
            ->with(['studyClass', 'user'])
            ->where('study_class_id', $classId)
            ->paginate(Constant::DEFAULT_LIMIT_8);

        return $query;
    }

}
