<?php

namespace App\Repositories;

use App\Models\ConductCriteriaEvidence;

class ConductCriteriaEvidenceRepository extends BaseRepository
{
    protected function getModel(): ConductCriteriaEvidence
    {
        if (empty($this->model)) {
            $this->model = app()->make(ConductCriteriaEvidence::class);
        }

        return $this->model;
    }

}
