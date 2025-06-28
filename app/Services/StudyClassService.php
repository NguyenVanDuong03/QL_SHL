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
        $relates = [
            'lecturer',
            'lecturer.user',
            'major',
            'major.faculty',
            'major.faculty.department',
            'cohort',
            'students',
        ];
        $search = Arr::get($params, 'search', null);
        $cohortId = Arr::get($params, 'cohort_id', null);
        $majorId = Arr::get($params, 'major_id', null);
        if ($search) {
            $search = trim($search);
            $wheres[] = [function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            }];
        }
        if ($cohortId) {
            $wheres[] = ['cohort_id', '=', $cohortId];
        }
        if ($majorId) {
            $wheres[] = ['major_id', '=', $majorId];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates,
        ];
    }

    public function getStudyClassListByLecturerId($params)
    {
        return $this->getRepository()->getStudyClassListByLecturerId($params);
    }

    public function coutStudyClassListByLecturerId($params)
    {
        return $this->getRepository()->getStudyClassListByLecturerId($params)->get()->count();
    }

    public function getStudyClassById($params)
    {
        return $this->getRepository()->getStudyClassById($params);
    }

    public function getStudyClassByIdFlex($params)
    {
        return $this->getRepository()->getStudyClassByIdFlex($params);
    }

    public function getStudyClassWithApprovedRequests($params)
    {
        return $this->getRepository()->getStudyClassesWithApprovedRequestsOnly($params);
    }

    public function getStudyClassListByConductEvaluationPeriodId($params)
    {
        return $this->getRepository()->getStudyClassListByConductEvaluationPeriodId($params);
    }

    public function getStudyClassListByConductEvaluationPeriodIdByLecturerId($params)
    {
        return $this->getRepository()->getStudyClassListByConductEvaluationPeriodIdByLecturerId($params);
    }

    public function participationRate($lecturerId)
    {
        return $this->getRepository()->participationRate($lecturerId);
    }

    public function listStatisticsByLecturerId($lecturerId, $semesterId)
    {
        return $this->getRepository()->listStatisticsByLecturerId($lecturerId, $semesterId);
    }

    public function listStatisticsStudyClassByLecturerId($lecturerId, $semesterId)
    {
        return $this->getRepository()->listStatisticsStudyClassByLecturerId($lecturerId, $semesterId);
    }

//    public function infoStudyClassListByConductEvaluationPeriodId($params)
//    {
//        return $this->getRepository()->infoStudyClassListByConductEvaluationPeriodId($params);
//    }

    public function infoByStudyClassListAndConductEvaluationPeriodId($params)
    {
        return $this->getRepository()->infoByStudyClassListAndConductEvaluationPeriodId($params);
    }

    public function getStudyClassListByConductEvaluationPeriodIdByFacultyOffice($params)
    {
        return $this->getRepository()->getStudyClassListByConductEvaluationPeriodIdByFacultyOffice($params);
    }

    public function getStudentListByConductEvaluationPeriodIdByFacultyOffice($params)
    {
        return $this->getRepository()->getStudentListByConductEvaluationPeriodIdByFacultyOffice($params);
    }

    public function statisticalClassByDepartment()
    {
        return $this->getRepository()->statisticalClassByDepartment();
    }

    public function getAllStatisticsByStudyClass()
    {
        return $this->getRepository()->getAllStatisticsByStudyClass();
    }

    public function getAllStatisticsByStudyClassByLecturer()
    {
        return $this->getRepository()->getAllStatisticsByStudyClassByLecturer();
    }

}
