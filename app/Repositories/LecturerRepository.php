<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Lecturer;

class LecturerRepository extends BaseRepository
{
    protected function getModel(): Lecturer
    {
        if (empty($this->model)) {
            $this->model = app()->make(Lecturer::class);
        }

        return $this->model;
    }

    public function getTotalStudentsByLecturer($lecturerId)
    {
        return $this->getModel()
            ->join('study_classes', 'lecturers.id', '=', 'study_classes.lecturer_id')
            ->join('students', 'study_classes.id', '=', 'students.study_class_id')
            ->where('lecturers.id', $lecturerId)
            ->count();
    }

}
