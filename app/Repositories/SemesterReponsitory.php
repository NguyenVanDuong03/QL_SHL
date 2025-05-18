<?php

namespace App\Repositories;

use App\Helpers\Constant;
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

    public function getFourSemester()
    {
        return $this->getModel()
            ->newQuery()
            ->whereIn('name', [Constant::SEMESTER_TYPE['SEMESTER_1'], Constant::SEMESTER_TYPE['SEMESTER_2']])
            ->orderByDesc('id')
            ->limit(4)
            ->get();
    }

}
