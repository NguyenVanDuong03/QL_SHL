<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

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
            ->newQuery()
            ->where('student_id', $params['student_id'])
            ->where('class_session_request_id', $params['class_session_request_id'])
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
        $studentIds = $params['student_ids'] ?? [];
        if (!$classSessionRequestId || !$studyClassId) {
            return false;
        }

        $allStudents = $this->getStudentModel()
            ->where('study_class_id', $studyClassId)
            ->pluck('id')
            ->toArray();

        DB::beginTransaction();

        try {
            if (!empty($studentIds)) {
                foreach ($studentIds as $studentId) {
                    $attendance = $this->getModel()
                        ->newQuery()
                        ->where('class_session_request_id', $classSessionRequestId)
                        ->where('student_id', $studentId)
                        ->first();

                    if ($attendance) {
                        $attendance->update([
                            'status' => Constant::ATTENDANCE_STATUS['PRESENT'],
                            'reason' => null,
                            'updated_at' => now(),
                        ]);
                    } else {
                        $this->create([
                            'student_id' => $studentId,
                            'class_session_request_id' => $classSessionRequestId,
                            'status' => Constant::ATTENDANCE_STATUS['PRESENT'],
                            'reason' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            $absentStudentIds = empty($studentIds) ? $allStudents : array_diff($allStudents, $studentIds);

            if (!empty($absentStudentIds)) {
                $existingAbsentAttendances = $this->getModel()
                    ->newQuery()
                    ->where('class_session_request_id', $classSessionRequestId)
                    ->whereIn('student_id', $absentStudentIds)
                    ->where('status', '!=', Constant::ATTENDANCE_STATUS['EXCUSED'])
                    ->get();

                foreach ($existingAbsentAttendances as $attendance) {
                    $attendance->update([
                        'status' => Constant::ATTENDANCE_STATUS['ABSENT'],
                        'reason' => null,
                        'updated_at' => now(),
                    ]);
                }

                $studentsWithoutAttendance = array_diff(
                    $absentStudentIds,
                    $this->getModel()
                        ->newQuery()
                        ->where('class_session_request_id', $classSessionRequestId)
                        ->whereIn('student_id', $absentStudentIds)
                        ->pluck('student_id')
                        ->toArray()
                );

                $newAbsentAttendances = [];
                foreach ($studentsWithoutAttendance as $studentId) {
                    $newAbsentAttendances[] = [
                        'student_id' => $studentId,
                        'class_session_request_id' => $classSessionRequestId,
                        'status' => Constant::ATTENDANCE_STATUS['ABSENT'],
                        'reason' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($newAbsentAttendances)) {
                    $this->getModel()->newQuery()->insert($newAbsentAttendances);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function countAttendanceByClassSessionRequestId($classRequestId, $studyClassId)
    {
        return $this->getModel()
            ->newQuery()
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('class_session_requests', 'attendances.class_session_request_id', '=', 'class_session_requests.id')
            ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
            ->where('study_classes.id', $studyClassId)
            ->where('class_session_requests.id', $classRequestId)
            ->where('attendances.status', Constant::ATTENDANCE_STATUS['PRESENT'])
            ->count();
    }

}
