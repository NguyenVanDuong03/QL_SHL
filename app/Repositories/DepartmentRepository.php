<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository extends BaseRepository
{
    protected function getModel(): Department
    {
        if (empty($this->model)) {
            $this->model = app()->make(Department::class);
        }

        return $this->model;
    }

}
