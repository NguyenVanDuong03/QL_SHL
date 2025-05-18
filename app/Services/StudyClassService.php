<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\StudyClassRepository;
use Illuminate\Support\Arr;

class StudyClassService extends BaseService
{
    protected function getRepository(): StudyClassRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(StudyClassRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');

        return [
            'sort' => $sort,
            'wheres' => $wheres
        ];
    }

    public function getStudyClassListByLecturerId($lecturerId)
    {
        return $this->getRepository()->getStudyClassListByLecturerId($lecturerId);
    }

    public function coutStudyClassListByLecturerId($lecturerId)
    {
        return count($this->getRepository()->getStudyClassListByLecturerId($lecturerId));
    }

}
