<?php

namespace App\Repositories;

use App\Models\Cohort;

class CohortRepository extends BaseRepository
{
    protected function getModel(): Cohort
    {
        if (empty($this->model)) {
            $this->model = app()->make(Cohort::class);
        }

        return $this->model;
    }

}
