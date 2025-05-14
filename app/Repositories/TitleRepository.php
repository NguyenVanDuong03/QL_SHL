<?php

namespace App\Repositories;

use App\Models\Title;

class TitleRepository extends BaseRepository
{
    protected function getModel(): Title
    {
        if (empty($this->model)) {
            $this->model = app()->make(Title::class);
        }

        return $this->model;
    }

}
