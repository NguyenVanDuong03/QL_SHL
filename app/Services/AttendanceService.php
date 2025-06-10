<?php

namespace App\Services;

use App\Exports\AttendancesExport;
use App\Helpers\Constant;
use App\Repositories\AttendanceRepository;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

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
        $wheres = Arr::get($params, 'wheres', []);
        $relates = Arr::get($params, 'relates', []);

        return [
            'wheres' => $wheres,
            'relates' => $relates,
        ];
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

    public function countAttendanceByClassSessionRequestId($classRequestId)
    {
        $studyClassId = auth()->user()->student?->study_class_id ?? null;

        $attendanceStudentId = $this->getRepository()->countAttendanceByClassSessionRequestId($classRequestId, $studyClassId);
        if (isset($attendanceStudentId)) {
            return $attendanceStudentId;
        }

        return 0;
    }

//    public function getAttendanceByClassSessionRequestId($classRequestId)
//    {
//        $studyClassId = auth()->user()->student?->study_class_id ?? null;
//
//        $attendanceStudentId = $this->getRepository()->getAttendanceByClassSessionRequestId($classRequestId, $studyClassId);
//        if (isset($attendanceStudentId)) {
//            return $attendanceStudentId;
//        }
//
//        return [];
//    }

//    public function exportAttendanceReport($classRequestId, $studyClassId)
//    {
//        if (!$studyClassId || !$classRequestId) {
//            return null;
//        }
//
//        return Excel::download(new AttendancesExport($classRequestId, $studyClassId), 'attendance.xlsx');
//    }

}
