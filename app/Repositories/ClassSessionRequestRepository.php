<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\ClassSessionRequest;

class ClassSessionRequestRepository extends BaseRepository
{
    protected function getModel(): ClassSessionRequest
    {
        if (empty($this->model)) {
            $this->model = app()->make(ClassSessionRequest::class);
        }

        return $this->model;
    }

    public function countFlexibleClassSessionRequest($lecturerId)
    {
        return $this->getModel()
            ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
            ->where('lecturer_id', $lecturerId)
            ->whereIn('status', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
                Constant::CLASS_SESSION_STATUS['APPROVED'],
            ])
            ->count();
    }


    public function countFixeClassSessionRequest($lecturerId, $classSessionRegistrationId)
    {
        return $this->getModel()
            ->where('type', Constant::CLASS_SESSION_TYPE['FIXED'])
            ->where('lecturer_id', $lecturerId)
            ->where('class_session_registration_id', $classSessionRegistrationId)
            ->where('status', Constant::CLASS_SESSION_STATUS['APPROVED'])
            ->count();
    }

    public function countApprovedByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getModel()
            ->where('lecturer_id', $lecturerId)
            ->where('status', constant::CLASS_SESSION_STATUS['APPROVED'])
            ->whereHas('classSessionRegistration', function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            })
            ->count();
    }

    public function countRejectedByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getModel()
            ->where('lecturer_id', $lecturerId)
            ->where('status', constant::CLASS_SESSION_STATUS['REJECTED'])
            ->whereHas('classSessionRegistration', function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            })
            ->count();
    }

    public function getClassSessionRequestById($studyClassId, $class_session_registration_id)
    {
        return $this->getModel()
            ->where('study_class_id', $studyClassId)
            ->where('class_session_registration_id', $class_session_registration_id)
            ->first();
    }

    public function createOrUpdateByClassAndSemester(array $params)
    {
        $modelClass = $this->getModel();

        $instance = $modelClass::where('study_class_id', $params['study_class_id'])
            ->where('class_session_registration_id', $params['class_session_registration_id'])
            ->first();

        return $this->createOrUpdate($params, $instance);
    }


}
