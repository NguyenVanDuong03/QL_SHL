<?php

namespace App\Repositories;

use App\Models\Attendance;

class AttendanceRepository extends BaseRepository
{
    protected function getModel(): Attendance
    {
        if (empty($this->model)) {
            $this->model = app()->make(Attendance::class);
        }

        return $this->model;
    }

}
