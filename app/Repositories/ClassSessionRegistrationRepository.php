<?php

namespace App\Repositories;

use App\Models\ClassSessionRegistration;

class ClassSessionRegistrationRepository extends BaseRepository
{
    protected function getModel(): ClassSessionRegistration
    {
        if (empty($this->model)) {
            $this->model = app()->make(ClassSessionRegistration::class);
        }

        return $this->model;
    }
}
