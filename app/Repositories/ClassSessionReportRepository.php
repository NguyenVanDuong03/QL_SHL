<?php

namespace App\Repositories;

use App\Models\ClassSessionReport;

class ClassSessionReportRepository extends BaseRepository
{
    protected function getModel(): ClassSessionReport
    {
        if (empty($this->model)) {
            $this->model = app()->make(ClassSessionReport::class);
        }

        return $this->model;
    }

}
