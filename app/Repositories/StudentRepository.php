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

    public function getNoteStudentById($class_id)
    {
        $query = $this->getModel()
            ->with(['studyClass', 'user'])
            ->where('study_class_id', $class_id)
            ->whereNotNull('note');

        return $query->get();
    }

    public function resetClassOfficers($studyClassId)
    {
        return $this->getModel()
            ->where('study_class_id', $studyClassId)
            ->whereIn('position', [
                Constant::STUDENT_POSITION['CLASS_PRESIDENT'],
                Constant::STUDENT_POSITION['VICE_PRESIDENT'],
                Constant::STUDENT_POSITION['SECRETARY'],
            ])
            ->update(['position' => Constant::STUDENT_POSITION['STUDENT']]);
    }

    public function updateStudentPosition($studentId, $newPosition)
    {
        return $this->getModel()
            ->where('id', $studentId)
            ->update(['position' => $newPosition]);
    }

}
