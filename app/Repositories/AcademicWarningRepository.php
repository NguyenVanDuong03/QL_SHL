<?php

namespace App\Repositories;

use App\Models\AcademicWarning;

class AcademicWarningRepository extends BaseRepository
{
    protected function getModel(): AcademicWarning
    {
        if (empty($this->model)) {
            $this->model = app()->make(AcademicWarning::class);
        }

        return $this->model;
    }

}
