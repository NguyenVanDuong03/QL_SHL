<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository extends BaseRepository
{
    protected function getModel(): Room
    {
        if (empty($this->model)) {
            $this->model = app()->make(Room::class);
        }

        return $this->model;
    }

}
