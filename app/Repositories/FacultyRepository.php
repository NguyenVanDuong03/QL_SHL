<?php

namespace App\Repositories;

use App\Models\Faculty;

class FacultyRepository extends BaseRepository
{
    protected function getModel(): Faculty
    {
        if (empty($this->model)) {
            $this->model = app()->make(Faculty::class);
        }

        return $this->model;
    }

}
