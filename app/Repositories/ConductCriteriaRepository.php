<?php

namespace App\Repositories;

use App\Models\ConductCriteria;

class ConductCriteriaRepository extends BaseRepository
{
    protected function getModel(): ConductCriteria
    {
        if (empty($this->model)) {
            $this->model = app()->make(ConductCriteria::class);
        }

        return $this->model;
    }

}
