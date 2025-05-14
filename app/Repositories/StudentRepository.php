<?php

namespace App\Repositories;

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

}
