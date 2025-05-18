<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\AcademicWarningRepository;
use Illuminate\Support\Arr;

class AcademicWarningService extends BaseService
{
    protected function getRepository(): AcademicWarningRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(AcademicWarningRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        return $params;
    }

    public function getStudentWarningByStudyClassId($studyClassId)
    {
        return $this->getRepository()->getStudentWarningByStudyClassId($studyClassId);
    }

}
