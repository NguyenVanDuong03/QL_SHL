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
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');
        $keywords = Arr::get($params, 'search', null);
        if ($keywords) {
            $wheres[] = [
                'field' => 'name',
                'operator' => 'like',
                'value' => '%' . $keywords . '%',
            ];
        }

        $isEmptyRooms = Arr::get($params, 'isEmptyRoom', false);
        if ($isEmptyRooms) {
            $wheres[] = [
                'field' => 'status',
                'operator' => '=',
                'value' => Constant::ROOM_STATUS['AVAILABLE'],
            ];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
        ];
    }
}
