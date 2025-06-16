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

    public function infoStudent($studentId)
    {
        return $this->getModel()
            ->with(['user', 'studyClass'])
            ->find($studentId);
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
                    WHEN attendances.status = 0 THEN 'Xác nhận'
                    WHEN attendances.status = 1 THEN 'Xin vắng'
                    WHEN attendances.status = 2 THEN 'Có mặt'
                    WHEN attendances.status = 3 THEN 'Vắng mặt'
                    ELSE 'Chưa xác nhận'
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

    public function getListStudentByClassId($params)
    {
        {
            $search = $params['search'] ?? '';
            $classId = $params['class_id'] ?? null;

            $query = $this->getModel()
                ->with(['studyClass', 'user'])
                ->where('study_class_id', $classId);

            if (!empty($search)) {
                $query = $query->where(function ($q) use ($search) {
                    $q->where('student_code', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('email', 'like', '%' . $search . '%')
                                ->orWhere('name', 'like', '%' . $search . '%');
                        });
                });
            }

            return $query->paginate(Constant::DEFAULT_LIMIT_8);
        }
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
        $studentUserIds = $this->getModel()
            ->where('study_class_id', $studyClassId)
            ->whereIn('position', [
                Constant::STUDENT_POSITION['CLASS_PRESIDENT'],
                Constant::STUDENT_POSITION['VICE_PRESIDENT'],
                Constant::STUDENT_POSITION['SECRETARY'],
            ])
            ->pluck('user_id');

        $this->getModel()
            ->where('study_class_id', $studyClassId)
            ->whereIn('position', [
                Constant::STUDENT_POSITION['CLASS_PRESIDENT'],
                Constant::STUDENT_POSITION['VICE_PRESIDENT'],
                Constant::STUDENT_POSITION['SECRETARY'],
            ])
            ->update(['position' => Constant::STUDENT_POSITION['STUDENT']]);

        if ($studentUserIds->isNotEmpty()) {
            DB::table('users')
                ->whereIn('id', $studentUserIds)
                ->update(['role' => Constant::ROLE_LIST['STUDENT']]);
        }

        return true;
    }

    public function updateStudentPosition($studentId, $newPosition)
    {
        $updated = $this->getModel()
            ->where('id', $studentId)
            ->update(['position' => $newPosition]);

        if ($updated && in_array($newPosition, [
                Constant::STUDENT_POSITION['CLASS_PRESIDENT'],
                Constant::STUDENT_POSITION['VICE_PRESIDENT'],
                Constant::STUDENT_POSITION['SECRETARY'],
            ])) {
            $student = $this->getModel()->find($studentId);
            if ($student) {
                DB::table('users')
                    ->where('id', $student->user_id)
                    ->update(['role' => Constant::ROLE_LIST['CLASS_STAFF']]);
            }
        }

        return $updated;
    }

    public function listConductScores($params)
    {
        $classId = $params['study_class_id'] ?? null;
        $evaluationPeriodId = $params['conduct_evaluation_period_id'] ?? null;

        $query = Student::query()
            ->select([
                'students.id as student_id',
                'students.student_code',
                'users.name as student_name',
                'student_conduct_scores.status as evaluation_status',
                DB::raw("
                CASE
                    WHEN student_conduct_scores.status = 0 THEN 'Đã đánh giá (SV chấm)'
                    WHEN student_conduct_scores.status IS NULL THEN 'Chưa đánh giá'
                    WHEN student_conduct_scores.status = 1 THEN 'Đã đánh giá (GVCN chấm)'
                    WHEN student_conduct_scores.status = 2 THEN 'Đã đánh giá (CTSV chấm)'
                    WHEN student_conduct_scores.status = 3 THEN 'Bị từ chối'
                END AS status_description
            "),
                DB::raw("COALESCE(SUM(detail_conduct_scores.self_score), 0) as total_self_score"),
                DB::raw("COALESCE(SUM(detail_conduct_scores.class_score), 0) as total_class_score"),
                DB::raw("COALESCE(SUM(detail_conduct_scores.final_score), 0) as total_final_score")
            ])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('study_classes', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($evaluationPeriodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', $evaluationPeriodId);
            })
            ->leftJoin('detail_conduct_scores', 'student_conduct_scores.id', '=', 'detail_conduct_scores.student_conduct_score_id')
            ->where('study_classes.id', $classId)
            ->groupBy(
                'students.id',
                'students.student_code',
                'users.name',
                'student_conduct_scores.status'
            )
            ->orderByRaw("
            CASE
                WHEN student_conduct_scores.status = 0 THEN 0
                WHEN student_conduct_scores.status = 1 THEN 1
                WHEN student_conduct_scores.status = 2 THEN 2
                WHEN student_conduct_scores.status = 3 THEN 3
                WHEN student_conduct_scores.status IS NULL THEN 4
                ELSE 5
            END ASC
        ")
            ->orderBy('students.student_code');

        return $query->paginate(Constant::DEFAULT_LIMIT_12);
    }
    public function listConductScoresFacultyOffice($params)
    {
        $classId = $params['study_class_id'] ?? null;
        $evaluationPeriodId = $params['conduct_evaluation_period_id'] ?? null;

        $query = Student::query()
            ->select([
                'students.id as student_id',
                'students.student_code',
                'users.name as student_name',
                'student_conduct_scores.status as evaluation_status',
                DB::raw("
                CASE
                    WHEN student_conduct_scores.status = 0 THEN 'Đã đánh giá (SV chấm)'
                    WHEN student_conduct_scores.status IS NULL THEN 'Chưa đánh giá'
                    WHEN student_conduct_scores.status = 1 THEN 'Đã đánh giá (GVCN chấm)'
                    WHEN student_conduct_scores.status = 2 THEN 'Đã đánh giá (CTSV chấm)'
                    WHEN student_conduct_scores.status = 3 THEN 'Bị từ chối'
                END AS status_description
            "),
                DB::raw("COALESCE(SUM(detail_conduct_scores.self_score), 0) as total_self_score"),
                DB::raw("COALESCE(SUM(detail_conduct_scores.class_score), 0) as total_class_score"),
                DB::raw("COALESCE(SUM(detail_conduct_scores.final_score), 0) as total_final_score")
            ])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('study_classes', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($evaluationPeriodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', $evaluationPeriodId);
            })
            ->leftJoin('detail_conduct_scores', 'student_conduct_scores.id', '=', 'detail_conduct_scores.student_conduct_score_id')
            ->where('study_classes.id', $classId)
            ->groupBy(
                'students.id',
                'students.student_code',
                'users.name',
                'student_conduct_scores.status'
            )
            ->orderByRaw("
            CASE
                WHEN student_conduct_scores.status = 0 THEN 0
                WHEN student_conduct_scores.status = 1 THEN 1
                WHEN student_conduct_scores.status = 2 THEN 2
                WHEN student_conduct_scores.status = 3 THEN 3
                WHEN student_conduct_scores.status IS NULL THEN 4
                ELSE 5
            END ASC
        ")
            ->orderBy('students.student_code');

        return $query->paginate(Constant::DEFAULT_LIMIT_12);
    }

    public function countStudentsByConductStatus($params)
    {
        $classId = $params['study_class_id'] ?? null;
        $evaluationPeriodId = $params['conduct_evaluation_period_id'] ?? null;

        $query = DB::table('students')
            ->join('study_classes', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($evaluationPeriodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', $evaluationPeriodId);
            })
            ->where('study_classes.id', $classId)
            ->select([
                DB::raw("SUM(CASE WHEN student_conduct_scores.status = 0 THEN 1 ELSE 0 END) as count_self_evaluated"),
                DB::raw("SUM(CASE WHEN student_conduct_scores.status = 1 THEN 1 ELSE 0 END) as count_class_teacher_evaluated"),
                DB::raw("SUM(CASE WHEN student_conduct_scores.status = 2 THEN 1 ELSE 0 END) as count_student_affairs_evaluated")
            ])
            ->first();

        return [
            'self_evaluated' => $query->count_self_evaluated ?? 0,
            'class_teacher_evaluated' => $query->count_class_teacher_evaluated ?? 0,
            'student_affairs_evaluated' => $query->count_student_affairs_evaluated ?? 0,
        ];
    }

    public function statisticalAttendance($semesterId, $studyClassId)
    {
        return $this->getModel()
            ->select([
                'students.student_code',
                'users.name',
                DB::raw('COALESCE(attendances.status, 3) as attendance_status')
            ])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('study_classes', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('attendances', function ($join) use ($semesterId, $studyClassId) {
                $join->on('students.id', '=', 'attendances.student_id')
                    ->whereIn('attendances.class_session_request_id', function ($query) use ($semesterId, $studyClassId) {
                        $query->select('class_session_requests.id')
                            ->from('class_session_requests')
                            ->join('class_session_registrations', 'class_session_requests.class_session_registration_id', '=', 'class_session_registrations.id')
                            ->where('class_session_registrations.semester_id', $semesterId)
                            ->where('class_session_requests.study_class_id', $studyClassId)
                            ->where('class_session_requests.type', 0)
                            ->where('class_session_requests.status', 3);
                    });
            })
            ->where('study_classes.id', $studyClassId)
            ->groupBy('students.id', 'students.student_code', 'users.name', 'attendances.status')
            ->orderBy('students.student_code')
            ->get();
    }

}
