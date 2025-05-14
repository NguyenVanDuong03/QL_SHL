<?php

namespace App\Repositories;

use App\Models\StudentConductScore;

class StudentConductScoreRepository extends BaseRepository
{
    protected function getModel(): StudentConductScore
    {
        if (empty($this->model)) {
            $this->model = app()->make(StudentConductScore::class);
        }

        return $this->model;
    }

}
