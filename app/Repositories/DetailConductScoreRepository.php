<?php

namespace App\Repositories;

use App\Models\DetailConductScore;

class DetailConductScoreRepository extends BaseRepository
{
    protected function getModel(): DetailConductScore
    {
        if (empty($this->model)) {
            $this->model = app()->make(DetailConductScore::class);
        }

        return $this->model;
    }

}
