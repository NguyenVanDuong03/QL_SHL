<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ClassSessionRequestRepository;
use Illuminate\Support\Arr;

class ClassSessionRequestService extends BaseService
{
    protected function getRepository(): ClassSessionRequestRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ClassSessionRequestRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        return $params;
    }

    public function countFlexibleClassSessionRequest()
    {
        return $this->getRepository()->countFlexibleClassSessionRequest();
    }

}
