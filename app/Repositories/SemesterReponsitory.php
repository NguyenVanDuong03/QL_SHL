<?php

namespace App\Repositories;

use App\Models\Semester;

class SemesterReponsitory extends BaseRepository
{
    protected function getModel(): Semester
    {
        if (empty($this->model)) {
            $this->model = app()->make(Semester::class);
        }

        return $this->model;
    }

}
