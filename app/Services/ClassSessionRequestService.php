<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ClassSessionRequestRepository;
use Illuminate\Support\Arr;

class ClassSessionRequestService extends BaseService
{
    protected function getRepository(): ClassSessionRequestRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ClassSessionRequestRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $sort = Arr::get($params, 'sort', 'id:desc');
        $wheres = Arr::get($params, 'wheres', []);
        $relates = array_intersect(
        Arr::get($params, 'relates', []),
        ['lecturer', 'studyClass', 'room']
        );
        $flexibleClassActivities = Arr::get($params, 'flexibleClassActivities', null);
        // dd($flexibleClassActivities);
        if ($flexibleClassActivities) {
            $wheres[] = [ function ($q)  {
                $q->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
                    ->where('status', Constant::CLASS_SESSION_STATUS['ACTIVE']);
            }
            ];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates
        ];
    }

    public function countFlexibleClassSessionRequest()
    {
        return $this->getRepository()->countFlexibleClassSessionRequest();
    }

    public function countApprovedByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getRepository()->countApprovedByLecturerAndSemester($lecturerId, $semesterId);
    }

    public function countRejectedByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getRepository()->countRejectedByLecturerAndSemester($lecturerId, $semesterId);
    }

    public function getClassSessionRequestById($studyClassId, $class_session_registration_id)
    {
        return $this->getRepository()->getClassSessionRequestById($studyClassId, $class_session_registration_id);
    }

    public function createOrUpdateByClassAndSemester(array $params)
    {
        return $this->getRepository()->createOrUpdateByClassAndSemester($params);

    }


}
