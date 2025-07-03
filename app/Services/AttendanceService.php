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
        $attendanceStudentId = $this->getRepository()->getAttendanceStudent($params);
        if (!isset($attendanceStudentId)) {
            return null;
        }

        return $attendanceStudentId;
    }

    public function confirmAttendance($params)
    {
        $attendanceStudent = $this->getAttendanceStudent($params);
        $params['status'] = Constant::ATTENDANCE_STATUS['CONFIRM'];
        if (isset($attendanceStudent)) {
            $confirmAttendance = $attendanceStudent->update([
                'status' => $params['status'],
                'reason' => null,
            ]);

            return $confirmAttendance;
        }

        $confirmAttendance = $this->getRepository()->create($params);

        return $confirmAttendance;
    }

    public function updateAbsence($params)
    {
        $attendanceStudentId = $this->getAttendanceStudent($params);
        $params['status'] = Constant::ATTENDANCE_STATUS['EXCUSED'];
        if (isset($attendanceStudentId)) {
            $updateAbsence = $attendanceStudentId->update([
                'status' => $params['status'],
                'reason' => $params['reason'],
            ]);

            return $updateAbsence;
        }

        $updateAbsence = $this->getRepository()->create($params);

        return $updateAbsence;
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

}
