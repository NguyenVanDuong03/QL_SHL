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
//        $relates = [
//            'student',
//            'student.user:id,name,email',
//            'student.studyClass:id,name',
//            'semester:id,name,school_year',
//        ];
//        $semester = Arr::get($params, 'semester_id', '');
//        if ($semester) {
//            $wheres[] = ['semester_id', '=', $semester];
//        }
//
//        $search = Arr::get($params, 'search', null);
//        if ($search) {
//            $wheres[] = function ($query) use ($search) {
//                $query->whereHas('student', function ($q) use ($search) {
//                    $q->whereHas('user', function ($q2) use ($search) {
//                        $q2->where('name', 'LIKE', "%$search%")
//                            ->orWhere('email', 'LIKE', "%$search%");
//                    })->orWhereHas('studyClass', function ($q2) use ($search) {
//                        $q2->where('name', 'LIKE', "%$search%");
//                    });
//                });
//            };
//        }

        return [
            'wheres' => $wheres,
            'sort' => $sort,
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

}
