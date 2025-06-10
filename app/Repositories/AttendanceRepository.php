<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Attendance;
use App\Models\Student;

class AttendanceRepository extends BaseRepository
{
    protected function getModel(): Attendance
    {
        if (empty($this->model)) {
            $this->model = app()->make(Attendance::class);
        }

        return $this->model;
    }

    protected function getStudentModel(): Student
    {
        if (empty($this->studentModel)) {
            $this->studentModel = app()->make(Student::class);
        }
        return $this->studentModel;
    }

    public function getAttendanceStudent ($params)
    {
        return $this->getModel()
            ->where('student_id', $params['student_id'])
            ->where('class_session_request_id', $params['session-request-id'])
            ->first();
    }


//    public function updateAttendance($params)
//    {
//        $classSessionRequestId = $params['session-request-id'] ?? null;
////        $studyClassId = $params['study-class-id'] ?? null;
//        $studentIds = $params['student_ids'] ?? null;
//
//        if (!$classSessionRequestId || !$studentIds) {
//            return false;
//        }
//
//        return $this->getModel()
//            ->where('class_session_request_id', $classSessionRequestId)
//            ->whereIn('student_id', $studentIds)
//            ->update(['status' => Constant::ATTENDANCE_STATUS['PRESENT']]);
//    }

    public function updateAttendance($params)
    {
        $classSessionRequestId = $params['session-request-id'] ?? null;
        $studyClassId = $params['study-class-id'] ?? null;
        $studentIds = $params['student_ids'] ?? null;

        if (!$classSessionRequestId || !$studyClassId || !$studentIds) {
            return false;
        }

        $allStudents = $this->getStudentModel()
            ->where('study_class_id', $studyClassId)
            ->pluck('id')
            ->toArray();

        $existingAttendances = $this->getModel()
            ->where('class_session_request_id', $classSessionRequestId)
            ->whereIn('student_id', $studentIds)
            ->get();

        foreach ($existingAttendances as $attendance) {
            $attendance->update(['status' => Constant::ATTENDANCE_STATUS['PRESENT']]);
        }

        $absentStudentIds = array_diff($allStudents, $studentIds);

        $existingAbsentAttendances = $this->getModel()
            ->where('class_session_request_id', $classSessionRequestId)
            ->whereIn('student_id', $absentStudentIds)
            ->whereNotIn('status', [Constant::ATTENDANCE_STATUS['EXCUSED'], Constant::ATTENDANCE_STATUS['PRESENT']])
            ->get();

        foreach ($existingAbsentAttendances as $attendance) {
            $attendance->update(['status' => Constant::ATTENDANCE_STATUS['ABSENT']]);
        }

        $studentsWithoutAttendance = $this->getModel()
            ->where('class_session_request_id', $classSessionRequestId)
            ->whereIn('student_id', $absentStudentIds)
            ->doesntExist();

        if ($studentsWithoutAttendance) {
            $newAttendances = [];
            foreach ($absentStudentIds as $studentId) {
                $existing = $this->getModel()
                    ->where('class_session_request_id', $classSessionRequestId)
                    ->where('student_id', $studentId)
                    ->first();

                if (!$existing) {
                    $newAttendances[] = [
                        'student_id' => $studentId,
                        'class_session_request_id' => $classSessionRequestId,
                        'status' => Constant::ATTENDANCE_STATUS['ABSENT'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($newAttendances)) {
                $this->getModel()->insert($newAttendances);
            }
        }

        return true;
    }

    public function countAttendanceByClassSessionRequestId($classRequestId, $studyClassId)
    {
        return $this->getModel()
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('class_session_requests', 'attendances.class_session_request_id', '=', 'class_session_requests.id')
            ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
            ->where('study_classes.id', $studyClassId)
            ->where('class_session_requests.id', $classRequestId)
            ->where('attendances.status', Constant::ATTENDANCE_STATUS['PRESENT'])
            ->count();
    }

//    public function getAttendanceByClassSessionRequestId($studyClassId, $classSessionRequestId)
//    {
//        return $this->getModel()
//            ->select('students.student_code', 'users.name as student_name', 'attendances.status as attendance_status', 'attendances.reason')
//            ->join('students', 'attendances.student_id', '=', 'students.id')
//            ->join('users', 'students.user_id', '=', 'users.id')
//            ->join('class_session_requests', 'attendances.class_session_request_id', '=', 'class_session_requests.id')
//            ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
//            ->where('study_classes.id', $studyClassId)
//            ->where('class_session_requests.id', $classSessionRequestId)
//            ->whereIn('attendances.status', [Constant::ATTENDANCE_STATUS['CONFIRM'], Constant::ATTENDANCE_STATUS['EXCUSED'], Constant::ATTENDANCE_STATUS['PRESENT'], Constant::ATTENDANCE_STATUS['ABSENT']])
//            ->get();
//    }

}
