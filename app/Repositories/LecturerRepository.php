<?php

namespace App\Repositories;

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

}
