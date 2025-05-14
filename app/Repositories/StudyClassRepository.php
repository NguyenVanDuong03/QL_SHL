<?php

namespace App\Repositories;

use App\Models\StudyClass;

class StudyClassRepository extends BaseRepository
{
    protected function getModel(): StudyClass
    {
        if (empty($this->model)) {
            $this->model = app()->make(StudyClass::class);
        }

        return $this->model;
    }

}
