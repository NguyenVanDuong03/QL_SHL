<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Attendance;

class AttendanceRepository extends BaseRepository
{
    protected function getModel(): Attendance
    {
        if (empty($this->model)) {
            $this->model = app()->make(Attendance::class);
        }

        return $this->model;
    }

    public function getAttendanceStudent ($params)
    {
        return $this->getModel()
            ->where('student_id', $params['student_id'])
            ->where('class_session_request_id', $params['session-request-id'])
            ->first();
    }


    public function updateAttendance($params)
    {
        $classSessionRequestId = $params['session-request-id'] ?? null;
//        $studyClassId = $params['study-class-id'] ?? null;
        $studentIds = $params['student_ids'] ?? null;

        if (!$classSessionRequestId || !$studentIds) {
            return false;
        }

        return $this->getModel()
            ->where('class_session_request_id', $classSessionRequestId)
            ->whereIn('student_id', $studentIds)
            ->update(['status' => Constant::ATTENDANCE_STATUS['PRESENT']]);
    }

}
