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
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');
        $relates = Arr::get($params, 'relates', []);

        return [
            'wheres' => $wheres,
            'sort' => $sort,
            'relates' => $relates,
        ];
    }

    public function getStudentWarningByStudyClassId($studyClassId)
    {
        return $this->getRepository()->getStudentWarningByStudyClassId($studyClassId);
    }

    public function listStudyClassAcademicWarning($params)
    {
        return $this->getRepository()->listStudyClassAcademicWarning($params);
    }

    public function academicWarningBySemesterId($semesterId)
    {
        return $this->getRepository()->academicWarningBySemesterId($semesterId);
    }

    public function getAcademicWarningsCountByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getRepository()->getAcademicWarningsCountByLecturerAndSemester($lecturerId, $semesterId);
    }

}
