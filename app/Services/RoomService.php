<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\RoomRepository;
use Illuminate\Support\Arr;

class RoomService extends BaseService
{
    protected function getRepository(): RoomRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(RoomRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        return $params;
    }

}
