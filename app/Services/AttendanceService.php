<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\AttendanceRepository;
use Illuminate\Support\Arr;

class AttendanceService extends BaseService
{
    protected function getRepository(): AttendanceRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(AttendanceRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        return $params;
    }

    public function getAttendanceStudent ($params)
    {
        $params['student_id'] = auth()->user()->student?->id;
        $attendanceStudentId = $this->getRepository()->getAttendanceStudent($params);
//        dd($attendanceStudentId);
        if (!isset($attendanceStudentId)) {
            return null;
        }

        return $attendanceStudentId;
    }

    public function confirmAttendance($params)
    {
        $params['session-request-id'] = $params['class_session_request_id'];
        $params = $this->buildFilterParams($params);
        $attendanceStudentId = $this->getAttendanceStudent($params);
        if (isset($attendanceStudentId)) {
            $attendanceStudentId->update([
                'status' => Constant::ATTENDANCE_STATUS['CONFIRM'],
                'reason' => null,
            ]);
        } else {
            $params['status'] = Constant::ATTENDANCE_STATUS['PRESENT'];
            $this->getRepository()->create($params);
        }

        return $attendanceStudentId;
    }

    public function updateAbsence($params)
    {
        $params['session-request-id'] = $params['class_session_request_id'];
        $params = $this->buildFilterParams($params);
        $attendanceStudentId = $this->getAttendanceStudent($params);
//        dd($attendanceStudentId);
        if (isset($attendanceStudentId)) {
            $attendanceStudentId->update([
                'status' => Constant::ATTENDANCE_STATUS['EXCUSED'],
                'reason' => $params['reason'],
            ]);
        } else {
            $params['status'] = Constant::ATTENDANCE_STATUS['EXCUSED'];
            $this->getRepository()->create($params);
        }

        return $attendanceStudentId;
    }

    public function updateAttendance($params)
    {
        return $this->getRepository()->updateAttendance($params);
    }

}
