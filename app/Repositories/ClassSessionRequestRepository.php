<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\ClassSessionRequest;
use http\Env\Request;

class ClassSessionRequestRepository extends BaseRepository
{
    protected function getModel(): ClassSessionRequest
    {
        if (empty($this->model)) {
            $this->model = app()->make(ClassSessionRequest::class);
        }

        return $this->model;
    }

    public function getClassSessionRequestBySclIdAndCsrId($params)
    {
        return $this->getModel()
            ->newQuery()
            ->where('study_class_id', $params['study_class_id'])
            ->where('class_session_registration_id', $params['class_session_registration_id'])
            ->first();
    }

    public function classSessionRequests($params)
    {
        return $this->getModel()
            ->with([
                'lecturer.user',
                'studyClass',
                'room',
                'attendances' => function ($query) use ($params) {
                    $query->where('student_id', $params['student_id'])
                        ->latest('id')
                        ->first();
                }
            ])
            ->where('study_class_id', $params['study_class_id'])
                ->whereIn('status', [
                    Constant::CLASS_SESSION_STATUS['ACTIVE'],
                    Constant::CLASS_SESSION_STATUS['REJECTED'],
                    Constant::CLASS_SESSION_STATUS['APPROVED'],
                ])
            ->orderBy('id', 'desc');
    }

    public function getAllClassSessionRequestsDone()
    {
        $query = $this->getModel()
            ->with([
                'lecturer.user',
                'studyClass',
                'classSessionRegistration',
                'classSessionRegistration.semester',
                'attendances'
            ])
            ->where('status', Constant::CLASS_SESSION_STATUS['DONE'])
            ->orderBy('proposed_at', 'desc');

        return $query;
    }

    public function getClassSessionRequestsDone($params)
    {
        $search = $params['search'] ?? '';

        $query = $this->getModel()
            ->with([
                'lecturer.user',
                'studyClass',
                'classSessionReport'
            ])
            ->where('study_class_id', $params['study_class_id'])
            ->where('status', Constant::CLASS_SESSION_STATUS['DONE'])
            ->orderBy('proposed_at', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    public function getTotalDoneSessionsByLecturer($lecturerId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('lecturer_id', $lecturerId)
            ->where('status', Constant::CLASS_SESSION_STATUS['DONE'])
            ->count();
    }

    public function getTotalSessionsByLecturer($lecturerId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('lecturer_id', $lecturerId)
            ->count();
    }

    public function countFlexibleClassSessionRequest($lecturerId)
    {
        return $this->getModel()
            ->newQuery()
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
            ->newQuery()
            ->where('type', Constant::CLASS_SESSION_TYPE['FIXED'])
            ->where('lecturer_id', $lecturerId)
            ->where('class_session_registration_id', $classSessionRegistrationId)
            ->where('status', Constant::CLASS_SESSION_STATUS['APPROVED'])
            ->count();
    }

    public function countApprovedByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('lecturer_id', $lecturerId)
            ->where('status', constant::CLASS_SESSION_STATUS['APPROVED'])
            ->whereHas('classSessionRegistration', function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            })
            ->count();
    }

    public function countFlexibleClassSessionRequestByLecturer($lecturerId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
            ->where('lecturer_id', $lecturerId)
            ->whereIn('status', [
                Constant::CLASS_SESSION_STATUS['APPROVED'],
            ])
            ->count();
    }

    public function countRejectedByLecturerAndSemester($lecturerId, $semesterId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('lecturer_id', $lecturerId)
            ->where('status', constant::CLASS_SESSION_STATUS['REJECTED'])
            ->whereHas('classSessionRegistration', function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            })
            ->count();
    }

    public function countFlexibleRejectedByLecturer($lecturerId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
            ->where('lecturer_id', $lecturerId)
            ->where('status', Constant::CLASS_SESSION_STATUS['REJECTED'])
            ->count();
    }

    public function getClassSessionRequestById($studyClassId, $class_session_registration_id)
    {
        return $this->getModel()
            ->newQuery()
            ->where('study_class_id', $studyClassId)
            ->where('class_session_registration_id', $class_session_registration_id)
            ->first();
    }

    public function createOrUpdateByClassAndSemester(array $params)
    {
        $instance = $this->getModel()
            ->newQuery()
            ->where('study_class_id', $params['study_class_id'])
            ->where('type', Constant::CLASS_SESSION_TYPE['FIXED'])
            ->where('class_session_registration_id', $params['class_session_registration_id'])
            ->whereIn('status', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
                Constant::CLASS_SESSION_STATUS['APPROVED'],
            ])
            ->first();

        return $this->createOrUpdate($params, $instance);
    }

    public function flexibleCreateOrUpdate(array $params)
    {
        $instance = $this->getModel()
            ->newQuery()
            ->where('study_class_id', $params['study_class_id'])
            ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
            ->whereIn('status', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
                Constant::CLASS_SESSION_STATUS['APPROVED'],
            ])
            ->first();

        return $this->createOrUpdate($params, $instance);
    }

    public function getListFlexibleClass()
    {
        return $this->getModel()
            ->with('studyClass', 'room', 'lecturer', 'studyClass.major.faculty.department')
            ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
            ->whereIn('status', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
                Constant::CLASS_SESSION_STATUS['APPROVED'],
            ])
            ->orderByRaw('
            CASE status
                WHEN ? THEN 1
                WHEN ? THEN 2
                WHEN ? THEN 3
                ELSE 4
            END', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
                Constant::CLASS_SESSION_STATUS['APPROVED']
            ])
            ->paginate(Constant::DEFAULT_LIMIT_12);
    }

    public function getAllClassSession()
    {
        return $this->getModel()
            ->with('studyClass', 'room', 'lecturer', 'lecturer.user', 'studyClass.major.faculty.department')
            ->whereIn('status', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
            ])
            ->orderByRaw('
            CASE status
                WHEN ? THEN 1
                WHEN ? THEN 2
                ELSE 3
            END', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
            ])
            ->limit(5)
            ->get();
    }

    public function getAllClassSessionByLecturer($lecturerId)
    {
        return $this->getModel()
            ->with('studyClass', 'room', 'lecturer', 'lecturer.user', 'studyClass.major.faculty.department')
            ->where('lecturer_id', $lecturerId)
            ->whereIn('status', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
            ])
            ->orderByRaw('
            CASE status
                WHEN ? THEN 1
                WHEN ? THEN 2
                ELSE 3
            END', [
                Constant::CLASS_SESSION_STATUS['ACTIVE'],
                Constant::CLASS_SESSION_STATUS['REJECTED'],
            ])
            ->limit(5)
            ->get();
    }

    public function countClassSession()
    {
        return [
            'fixed' => $this->getModel()
                ->newQuery()
                ->where('type', Constant::CLASS_SESSION_TYPE['FIXED'])
                ->count(),
            'flexible' => $this->getModel()
                ->newQuery()
                ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
                ->count(),
            'total' => $this->getModel()
                ->newQuery()
                ->count()
        ];
    }

    public function countClassSessionById($lecturerId)
    {
        return [
            'fixed' => $this->getModel()
                ->newQuery()
                ->where('type', Constant::CLASS_SESSION_TYPE['FIXED'])
                ->where('lecturer_id', $lecturerId)
                ->count(),
            'flexible' => $this->getModel()
                ->newQuery()
                ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
                ->where('lecturer_id', $lecturerId)
                ->count(),
            'total' => $this->getModel()
                ->newQuery()
                ->where('lecturer_id', $lecturerId)
                ->count()
        ];
    }

    public function deleteByClassSessionRegistrationId($classSessionRegistrationId)
    {
        return $this->getModel()
            ->newQuery()
            ->where('class_session_registration_id', $classSessionRegistrationId)
            ->whereIn('status', [Constant::CLASS_SESSION_STATUS['ACTIVE'], Constant::CLASS_SESSION_STATUS['REJECTED'], Constant::CLASS_SESSION_STATUS['APPROVED']])
            ->delete();
    }

//    public function StatisticalClassSessionRequests($params)
//    {
//        return $this->getModel()
//            ->with('studyClass', 'room', 'lecturer', 'studyClass.major.faculty.department')
//            ->where('lecturer_id', $params['lecturer_id'])
//            ->paginate(Constant::DEFAULT_LIMIT_12);
//    }

}
