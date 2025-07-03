<?php

namespace App\Repositories;

use App\Models\Major;

class MajorRepository extends BaseRepository
{
    protected function getModel(): Major
    {
        if (empty($this->model)) {
            $this->model = app()->make(Major::class);
        }

        return $this->model;
    }

}
