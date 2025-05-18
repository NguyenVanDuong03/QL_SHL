<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\StudyClass;
use Illuminate\Support\Facades\DB;

class StudyClassRepository extends BaseRepository
{
    protected function getModel(): StudyClass
    {
        if (empty($this->model)) {
            $this->model = app()->make(StudyClass::class);
        }

        return $this->model;
    }

    public function getStudyClassListByLecturerId($lecturerId)
    {
        // $lecturerId = auth()->user()->lecturer?->id;
        $query = $this->getModel()
            ->with(['students'])
            ->where('lecturer_id', $lecturerId)
            ->orderBy('id', 'desc')
            ->paginate(constant::DEFAULT_LIMIT_12);

        return $query;
    }

}
