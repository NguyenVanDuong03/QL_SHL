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
        $relates = Arr::get($params, 'relates', ['lecturer', 'studyClass', 'room', 'lecturer.user', 'attendances']);
        $list_study_class_by_lecturer = Arr::get($params, 'list_study_class_by_lecturer', false);
        if ($list_study_class_by_lecturer) {
            $wheres[] = ['lecturer_id', 'in', auth()->user()->lecturer?->id];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates
        ];
    }

    public function getClassSessionRequestBySclIdAndCsrId($params)
    {
        return $this->getRepository()->getClassSessionRequestBySclIdAndCsrId($params);
    }

    public function classSessionRequests($params)
    {
        return $this->getRepository()->classSessionRequests($params);
    }

    public function getClassSessionRequestsDone($params)
    {
        return $this->getRepository()->getClassSessionRequestsDone($params);
    }

    public function countFlexibleClassSessionRequest()
    {
        $lecturerId = auth()->user()->lecturer?->id;
        return $this->getRepository()->countFlexibleClassSessionRequest($lecturerId);
    }

    public function countFixeClassSessionRequest($lecturerId, $classSessionRegistrationId)
    {
        return $this->getRepository()->countFixeClassSessionRequest($lecturerId, $classSessionRegistrationId);
    }

    public function countApprovedByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getRepository()->countApprovedByLecturerAndSemester($lecturerId, $semesterId);
    }

    public function countFlexibleClassSessionRequestByLecturer($lecturerId)
    {
        return $this->getRepository()->countFlexibleClassSessionRequestByLecturer($lecturerId);
    }

    public function countRejectedByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getRepository()->countRejectedByLecturerAndSemester($lecturerId, $semesterId);
    }

    public function countFlexibleRejectedByLecturer($lecturerId)
    {
        return $this->getRepository()->countFlexibleRejectedByLecturer($lecturerId);
    }

    public function getTotalDoneSessionsByLecturer($lecturerId)
    {
        return $this->getRepository()->getTotalDoneSessionsByLecturer($lecturerId);
    }

    public function getTotalSessionsByLecturer($lecturerId)
    {
        return $this->getRepository()->getTotalSessionsByLecturer($lecturerId);
    }

    public function getClassSessionRequestById($studyClassId, $class_session_registration_id)
    {
        return $this->getRepository()->getClassSessionRequestById($studyClassId, $class_session_registration_id);
    }

    public function createOrUpdateByClassAndSemester(array $params)
    {
        return $this->getRepository()->createOrUpdateByClassAndSemester($params);

    }

    public function flexibleCreateOrUpdate(array $params)
    {
        return $this->getRepository()->flexibleCreateOrUpdate($params);
    }

    public function getListFlexibleClass()
    {
        return $this->getRepository()->getListFlexibleClass();
    }

    public function getAllClassSession()
    {
        return $this->getRepository()->getAllClassSession();
    }

    public function countClassSession()
    {
        return $this->getRepository()->countClassSession();
    }

    public function getAllClassSessionByLecturer($lecturerId)
    {
        return $this->getRepository()->getAllClassSessionByLecturer($lecturerId);
    }

    public function countClassSessionById($lecturerId)
    {
        return $this->getRepository()->countClassSessionById($lecturerId);
    }

    public function deleteByClassSessionRegistrationId($classSessionRegistrationId)
    {
        return $this->getRepository()->deleteByClassSessionRegistrationId($classSessionRegistrationId);
    }

//    public function StatisticalClassSessionRequests($params)
//    {
//        return $this->getRepository()->StatisticalClassSessionRequests($params);
//    }

}
