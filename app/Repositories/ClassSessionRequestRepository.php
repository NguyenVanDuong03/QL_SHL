<?php

namespace App\Repositories;

use App\Models\ClassSessionRequest;

class ClassSessionRequestRepository extends BaseRepository
{
    protected function getModel(): ClassSessionRequest
    {
        if (empty($this->model)) {
            $this->model = app()->make(ClassSessionRequest::class);
        }

        return $this->model;
    }
}
