<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository
{
    protected function getModel(): Student
    {
        if (empty($this->model)) {
            $this->model = app()->make(Student::class);
        }

        return $this->model;
    }

    public function getStudentListByClassId($params)
    {
        $search = $params['search'] ?? '';
        $studyClassId = $params['study-class-id'] ?? null;
        $classSessionRequestId = $params['session-request-id'] ?? null;

        $query = $this->getModel()
            ->select([
                'students.id as student_id',
                'students.student_code',
                'users.name',
                'users.email',
                'students.note',
                DB::raw('COALESCE(attendances.status, -1) as attendance_status'),
                'attendances.reason',
                DB::raw("CASE
                    WHEN attendances.status = 0 THEN 'Xác nhận tham gia'
                    WHEN attendances.status = 1 THEN 'Vắng mặt có phép'
                    WHEN attendances.status = 2 THEN 'Có mặt'
                    WHEN attendances.status = 3 THEN 'Vắng mặt'
                    ELSE 'Chưa xác nhận tham gia'
                 END as attendance_status_text")
            ])
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('attendances', function ($join) use ($classSessionRequestId) {
                $join->on('students.id', '=', 'attendances.student_id')
                    ->where('attendances.class_session_request_id', '=', $classSessionRequestId);
            })
            ->where('students.study_class_id', $studyClassId)
            ->orderBy('students.student_code');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('students.student_code', 'like', '%' . $search . '%')
                    ->orWhere('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%');
            });
        }

        return $query->get();
    }

    public function getTotalStudentsByClass($params)
    {
        $studyClassId = $params['study-class-id'] ?? null;

        return $this->getModel()
            ->where('study_class_id', $studyClassId)
            ->count();
    }

//    public function getAttendanceStatusSummary($params, int $limit = 25, int $offset = 0): array
//    {
//        $studyClassId = $params['study-class-id'] ?? null;
//        $classSessionRequestId = $params['session-request-id'] ?? null;
//
//        return $this->getModel()
//            ->select([
//                DB::raw('COALESCE(attendances.status, -1) as status'),
//                DB::raw('COUNT(*) as count'),
//                DB::raw("CASE
//                            WHEN attendances.status = 0 THEN 'Xác nhận tham gia'
//                            WHEN attendances.status = 1 THEN 'Vắng mặt có phép'
//                            WHEN attendances.status = 2 THEN 'Có mặt'
//                            WHEN attendances.status = 3 THEN 'Vắng mặt'
//                            ELSE 'Chưa xác nhận tham gia'
//                         END as status_text")
//            ])
//            ->leftJoin('attendances', function ($join) use ($classSessionRequestId) {
//                $join->on('students.id', '=', 'attendances.student_id')
//                    ->where('attendances.class_session_request_id', '=', $classSessionRequestId);
//            })
//            ->where('students.study_class_id', $studyClassId)
//            ->groupBy(DB::raw('COALESCE(attendances.status, -1)'), DB::raw("CASE
//                                                                            WHEN attendances.status = 0 THEN 'Xác nhận tham gia'
//                                                                            WHEN attendances.status = 1 THEN 'Vắng mặt có phép'
//                                                                            WHEN attendances.status = 2 THEN 'Có mặt'
//                                                                            WHEN attendances.status = 3 THEN 'Vắng mặt'
//                                                                            ELSE 'Chưa xác nhận tham gia'
//                                                                         END"))
//            ->orderBy('status')
//            ->offset($offset)
//            ->limit($limit)
//            ->get()
//            ->toArray();
//    }

    public function getAttendanceStatusSummary($params, int $limit = 25, int $offset = 0): array
    {
        $studyClassId = $params['study-class-id'] ?? null;
        $classSessionRequestId = $params['session-request-id'] ?? null;

        return $this->getModel()
            ->select([
                DB::raw('COALESCE(attendances.status, -1) as status'),
                DB::raw('COUNT(*) as count')
            ])
            ->leftJoin('attendances', function ($join) use ($classSessionRequestId) {
                $join->on('students.id', '=', 'attendances.student_id')
                    ->where('attendances.class_session_request_id', '=', $classSessionRequestId);
            })
            ->where('students.study_class_id', $studyClassId)
            ->groupBy(DB::raw('COALESCE(attendances.status, -1)'))
            ->orderBy('status')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->toArray();
    }


    public function getNoteStudentById($class_id)
    {
        $query = $this->getModel()
            ->with(['studyClass', 'user'])
            ->where('study_class_id', $class_id)
            ->whereNotNull('note');

        return $query->get();
    }

    public function resetClassOfficers($studyClassId)
    {
        return $this->getModel()
            ->where('study_class_id', $studyClassId)
            ->whereIn('position', [
                Constant::STUDENT_POSITION['CLASS_PRESIDENT'],
                Constant::STUDENT_POSITION['VICE_PRESIDENT'],
                Constant::STUDENT_POSITION['SECRETARY'],
            ])
            ->update(['position' => Constant::STUDENT_POSITION['STUDENT']]);
    }

    public function updateStudentPosition($studentId, $newPosition)
    {
        return $this->getModel()
            ->where('id', $studentId)
            ->update(['position' => $newPosition]);
    }

    public function updateAttendance($params)
    {
        $classSessionRequestId = $params['class_session_request_id'] ?? null;
        $studentIds = $params['student_ids'] ?? null;

    }

}
