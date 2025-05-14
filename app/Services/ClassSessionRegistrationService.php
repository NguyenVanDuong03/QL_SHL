<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ClassSessionRegistrationRepository;
use Illuminate\Support\Arr;

class ClassSessionRegistrationService extends BaseService
{
    protected function getRepository(): ClassSessionRegistrationRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ClassSessionRegistrationRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        return $params;
    }

}
