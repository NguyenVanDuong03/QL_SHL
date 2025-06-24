<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\StudyClass;
use Illuminate\Support\Facades\DB;

class StudyClassRepository extends BaseRepository
{
    protected function getModel(): StudyClass
    {
        if (empty($this->model)) {
            $this->model = app()->make(StudyClass::class);
        }

        return $this->model;
    }

    public function getStudyClassListByLecturerId($params)
    {
        $lecturerId = $params['lecturer_id'];
        $search = $params['search'] ?? '';

        $query = $this->getModel()
            ->with(['students'])
            ->where('lecturer_id', $lecturerId)
            ->orderBy('id', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        return $query;
    }

    public function getStudyClassById($params)
    {
        $lecturerId = $params['lecturer_id'];
        $semesterId = $params['semester_id'];

        $query = $this->getModel()
            ->with([
                'major.faculty.department',
                'cohort',
                'classSessionRequests' => function ($q) use ($semesterId) {
                    $q->where('type', Constant::CLASS_SESSION_TYPE['FIXED'])
                        ->whereHas('classSessionRegistration', function ($qr) use ($semesterId) {
                            $qr->where('semester_id', $semesterId);
                        });
                }
            ])
            ->where('lecturer_id', $lecturerId)
            ->withCount([
                'classSessionRequests as status_order' => function ($q) use ($semesterId) {
                    $q->select(DB::raw('
                CASE
                    WHEN status = 0 THEN 1
                    WHEN status = 2 THEN 2
                    WHEN status = 1 THEN 3
                    ELSE 4
                END
            '))
                        ->whereHas('classSessionRegistration', function ($qr) use ($semesterId) {
                            $qr->where('semester_id', $semesterId);
                        });
                }
            ])
            ->orderByRaw('
        CASE
            WHEN status_order IS NULL THEN 0
            ELSE status_order
        END ASC
    ');

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        return $query->paginate(constant::DEFAULT_LIMIT_12);
    }

    public function getStudyClassByIdFlex($params)
    {
//        Lọc ra các lớp học phần của gvcn có status là 0, 1, 2, 3
        $lecturerId = $params['lecturer_id'];

        $query = $this->getModel()
            ->with([
                'major.faculty.department',
                'cohort',
                'classSessionRequests' => function ($q) {
                    $q->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE']);
                }
            ])
            ->where('lecturer_id', $lecturerId)
            ->withCount([
                'classSessionRequests as status_order' => function ($q) {
                    $q->select(DB::raw('MIN(
            CASE
                WHEN status = 0 THEN 1
                WHEN status = 2 THEN 2
                WHEN status = 1 THEN 3
                ELSE 4
            END
        )'));
                }
            ])
            ->orderByRaw('
        CASE
            WHEN status_order IS NULL THEN 0
            ELSE status_order
        END ASC
    ');

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        return $query->paginate(constant::DEFAULT_LIMIT_12);
    }

//    public function approvedClassSessionRequestInSemester($semesterId)
//    {
//        return $this->hasOne(ClassSessionRequest::class, 'study_class_id')
//            ->where('status', 1)
//            ->whereHas('classSessionRegistration', function ($query) use ($semesterId) {
//                $query->where('semester_id', $semesterId);
//            });
//    }


    public function getStudyClassesWithApprovedRequestsOnly($params)
    {
        $lecturerId = $params['lecturer_id'];
        $semesterId = $params['semester_id'];

        $query = $this->getModel()
            ->newQuery()
            ->where('lecturer_id', $lecturerId)
            ->whereHas('classSessionRequests', function ($q) use ($semesterId) {
                $q->where('status', 1)
                    ->whereHas('classSessionRegistration', function ($qr) use ($semesterId) {
                        $qr->where('semester_id', $semesterId);
                    });
            })
            ->with([
                'major.faculty.department',
                'cohort',
                'classSessionRequests' => function ($q) use ($semesterId) {
                    $q->where('status', 1)
                        ->where('type', Constant::CLASS_SESSION_TYPE['FIXED'])
                        ->whereHas('classSessionRegistration', function ($qr) use ($semesterId) {
                            $qr->where('semester_id', $semesterId);
                        });
                }
            ]);

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        return $query->paginate(Constant::DEFAULT_LIMIT_12);
    }

    public function getStudyClassListByConductEvaluationPeriodId($params)
    {
        $periodId = $params['conduct_evaluation_period_id'];
        $search = $params['search'] ?? '';
        $departmentId = $params['department_id'] ?? null;
        $cohortId = $params['cohort_id'] ?? null;

        $query = $this->getModel()
            ->newQuery()
            ->select([
                'study_classes.id as class_id',
                'study_classes.name as study_class_name',
                'majors.name as major_name',
                'departments.name as department_name',
                'users.name as lecturer_name',
                'cohorts.name as cohort_name',
                DB::raw('COUNT(DISTINCT students.id) as total_students'),
                DB::raw('COUNT(DISTINCT student_conduct_scores.student_id) as has_evaluated'),
                DB::raw('(COUNT(DISTINCT students.id) - COUNT(DISTINCT student_conduct_scores.student_id)) as not_evaluated'),
                DB::raw('SUM(CASE WHEN total_score >= 90 THEN 1 ELSE 0 END) as outstanding'),
                DB::raw('SUM(CASE WHEN total_score >= 80 AND total_score < 90 THEN 1 ELSE 0 END) as good'),
                DB::raw('SUM(CASE WHEN total_score >= 70 AND total_score < 80 THEN 1 ELSE 0 END) as fair'),
                DB::raw('SUM(CASE WHEN total_score >= 50 AND total_score < 70 THEN 1 ELSE 0 END) as average'),
                DB::raw('SUM(CASE WHEN total_score < 50 THEN 1 ELSE 0 END) as poor')
            ])
            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
            ->leftJoin('faculties', 'majors.faculty_id', '=', 'faculties.id')
            ->leftJoin('departments', 'faculties.department_id', '=', 'departments.id')
            ->leftJoin('lecturers', 'study_classes.lecturer_id', '=', 'lecturers.id')
            ->leftJoin('users', 'lecturers.user_id', '=', 'users.id')
            ->leftJoin('cohorts', 'study_classes.cohort_id', '=', 'cohorts.id')
            ->leftJoin('students', 'study_classes.id', '=', 'students.study_class_id')
            ->leftJoin('student_conduct_scores', function ($join) use ($periodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', '=', $periodId);
            })
            ->leftJoin(DB::raw('(SELECT student_conduct_score_id, SUM(final_score) as total_score
                            FROM detail_conduct_scores
                            GROUP BY student_conduct_score_id) as scores'),
                'student_conduct_scores.id', '=', 'scores.student_conduct_score_id')
            ->groupBy('study_classes.id', 'study_classes.name', 'majors.name', 'departments.name', 'users.name', 'cohorts.name')
            ->orderBy('study_classes.id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('study_classes.name', 'like', "%$search%");
            });
        }

        if (!empty($departmentId)) {
            $query->where('departments.id', $departmentId);
        }

        if (!empty($cohortId)) {
            $query->where('study_classes.cohort_id', $cohortId);
        }

        try {
            return $query->paginate(Constant::DEFAULT_LIMIT_12);
        } catch (\Exception $e) {
            \Log::error('Error in getStudyClassListByConductEvaluationPeriodId: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve study class list');
        }
    }

    public function getStudyClassListByConductEvaluationPeriodIdByLecturerId($params)
    {
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'];
        $lecturerId = $params['lecturer_id'];
        $search = $params['search'] ?? '';

        $query = $this->getModel()
            ->newQuery()
            ->select([
                'study_classes.id as class_id',
                'study_classes.name as study_class_name',
                'majors.name as major_name',
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT students.id) as total_students'),
                DB::raw('COUNT(DISTINCT student_conduct_scores.student_id) as has_evaluated'),
                DB::raw('(COUNT(DISTINCT students.id) - COUNT(DISTINCT student_conduct_scores.student_id)) as not_evaluated')
            ])
            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
            ->leftJoin('faculties', 'majors.faculty_id', '=', 'faculties.id')
            ->leftJoin('departments', 'faculties.department_id', '=', 'departments.id')
            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($conductEvaluationPeriodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', '=', $conductEvaluationPeriodId);
            })
            ->where('study_classes.lecturer_id', $lecturerId)
            ->groupBy('study_classes.id', 'study_classes.name', 'majors.name', 'departments.name')
            ->orderBy('study_classes.id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('study_classes.name', 'like', "%$search%")
                    ->orWhere('majors.name', 'like', "%$search%");
            });
        }

        try {
            return $query->paginate(Constant::DEFAULT_LIMIT_12);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve study class list');
        }
    }

    public function getStudentListByConductEvaluationPeriodIdByFacultyOffice($params)
    {
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'];
        $departmentId = $params['department_id'];

        $query = $this->getModel()
            ->newQuery()
            ->select([
                'study_classes.id as class_id',
                'study_classes.name as study_class_name',
                'majors.name as major_name',
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT students.id) as total_students'),
                DB::raw('COUNT(DISTINCT student_conduct_scores.student_id) as has_evaluated'),
                DB::raw('(COUNT(DISTINCT students.id) - COUNT(DISTINCT student_conduct_scores.student_id)) as not_evaluated')
            ])
            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
            ->leftJoin('faculties', 'majors.faculty_id', '=', 'faculties.id')
            ->leftJoin('departments', 'faculties.department_id', '=', 'departments.id')
            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($conductEvaluationPeriodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', '=', $conductEvaluationPeriodId);
            })
            ->where('departments.id', $departmentId)
            ->groupBy('study_classes.id', 'study_classes.name', 'majors.name', 'departments.name')
            ->orderBy('study_classes.id');

        try {
            return $query->paginate(Constant::DEFAULT_LIMIT_12);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve study class list');
        }
    }

    public function getStudyClassListByConductEvaluationPeriodIdByFacultyOffice($params)
    {
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'];
        $departmentId = $params['department_id'];
        $search = $params['search'] ?? '';

        $query = $this->getModel()
            ->newQuery()
            ->select([
                'study_classes.id as class_id',
                'study_classes.name as study_class_name',
                'majors.name as major_name',
                'faculties.name as faculty_name',
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT students.id) as total_students'),
                DB::raw('COUNT(DISTINCT student_conduct_scores.student_id) as has_evaluated'),
                DB::raw('(COUNT(DISTINCT students.id) - COUNT(DISTINCT student_conduct_scores.student_id)) as not_evaluated')
            ])
            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
            ->leftJoin('faculties', 'majors.faculty_id', '=', 'faculties.id')
            ->leftJoin('departments', 'faculties.department_id', '=', 'departments.id')
            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($conductEvaluationPeriodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', '=', $conductEvaluationPeriodId);
            })
            ->where('departments.id', $departmentId)
            ->groupBy('study_classes.id', 'study_classes.name', 'majors.name', 'faculties.name', 'departments.name') // Thêm faculties.name vào groupBy
            ->orderBy('study_classes.id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('study_classes.name', 'like', "%$search%")
                    ->orWhere('majors.name', 'like', "%$search%");
            });
        }

        try {
            return $query->paginate(Constant::DEFAULT_LIMIT_12);
        } catch (\Exception $e) {
            \Log::error('Không thể lấy danh sách lớp học: ' . $e->getMessage()); // Cải tiến ghi log lỗi
            throw new \Exception('Không thể lấy danh sách lớp học');
        }
    }

    public function participationRate($lecturerId)
    {
        $result = DB::table('study_classes as sc')
            ->join('students as s', 's.study_class_id', '=', 'sc.id')
            ->leftJoin('attendances as a', 'a.student_id', '=', 's.id')
            ->where('sc.lecturer_id', $lecturerId)
            ->selectRaw('ROUND(COUNT(CASE WHEN a.status = 2 THEN 1 END) * 100.0 / COUNT(DISTINCT s.id), 2) as attendance_rate')
            ->first();

        return $result;
    }

    public function listStatisticsByLecturerId($lecturerId, $semesterId)
    {
        $query = $this->getModel()
            ->newQuery()
            ->select(
                'study_classes.id AS class_id',
                'study_classes.name AS class_name',
                'departments.name AS department_name',
                DB::raw('COUNT(DISTINCT students.id) AS total_students'),
                DB::raw('COUNT(DISTINCT CASE WHEN class_session_requests.type = 0 AND class_session_requests.status = 3 THEN class_session_requests.id END) AS fixed_sessions'),
                DB::raw('COUNT(DISTINCT CASE WHEN class_session_requests.type = 1 AND class_session_requests.status = 3 THEN class_session_requests.id END) AS flexible_sessions'),
                DB::raw('COUNT(DISTINCT CASE WHEN (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) >= 90 THEN student_conduct_scores.student_id END) AS high_conduct_students'),
                DB::raw('COUNT(DISTINCT academic_warnings.student_id) AS warned_students')
            )
            ->join('lecturers', 'study_classes.lecturer_id', '=', 'lecturers.id')
            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
            ->leftJoin('faculties', 'majors.faculty_id', '=', 'faculties.id')
            ->leftJoin('departments', 'faculties.department_id', '=', 'departments.id')
            ->leftJoin('class_session_requests', function ($join) use ($semesterId) {
                $join->on('study_classes.id', '=', 'class_session_requests.study_class_id')
                    ->where('class_session_requests.status', '=', 3)
                    ->whereExists(function ($query) use ($semesterId) {
                        $query->select(DB::raw(1))
                            ->from('class_session_registrations')
                            ->whereColumn('class_session_registrations.id', 'class_session_requests.class_session_registration_id')
                            ->where('class_session_registrations.semester_id', '=', $semesterId);
                    });
            })
            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($semesterId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->whereIn('student_conduct_scores.conduct_evaluation_period_id', function ($query) use ($semesterId) {
                        $query->select('id')
                            ->from('conduct_evaluation_periods')
                            ->where('semester_id', '=', $semesterId);
                    });
            })
            ->leftJoin('academic_warnings', function ($join) use ($semesterId) {
                $join->on('students.id', '=', 'academic_warnings.student_id')
                    ->where('academic_warnings.semester_id', '=', $semesterId);
            })
            ->where('lecturers.id', '=', $lecturerId)
            ->groupBy('study_classes.id', 'study_classes.name', 'departments.name')
            ->orderBy('study_classes.id');

        return $query->get();
    }

//    public function listStatisticsStudyClassByLecturerId($lecturerId, $semesterId)
//    {
//        $query = $this->getModel()
//            ->newQuery()
//            ->select(
//                'study_classes.id AS class_id',
//                'study_classes.name AS class_name',
//                'departments.name AS department_name',
//                DB::raw('COUNT(DISTINCT students.id) AS total_students'),
//                DB::raw('COUNT(DISTINCT CASE WHEN class_session_requests.status = 3 THEN class_session_requests.id END) AS completed_sessions'),
//                DB::raw('COUNT(DISTINCT academic_warnings.student_id) AS warned_students')
//            )
//            ->join('lecturers', 'study_classes.lecturer_id', '=', 'lecturers.id')
//            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
//            ->leftJoin('faculties', 'majors.faculty_id', '=', 'faculties.id')
//            ->leftJoin('departments', 'faculties.department_id', '=', 'departments.id')
//            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
//            ->leftJoin('class_session_requests', function ($join) use ($semesterId) {
//                $join->on('study_classes.id', '=', 'class_session_requests.study_class_id')
//                    ->where('class_session_requests.status', '=', 3)
//                    ->whereExists(function ($query) use ($semesterId) {
//                        $query->select(DB::raw(1))
//                            ->from('class_session_registrations')
//                            ->whereColumn('class_session_registrations.id', 'class_session_requests.class_session_registration_id')
//                            ->where('class_session_registrations.semester_id', '=', $semesterId);
//                    });
//            })
//            ->leftJoin('academic_warnings', function ($join) use ($semesterId) {
//                $join->on('students.id', '=', 'academic_warnings.student_id')
//                    ->where('academic_warnings.semester_id', '=', $semesterId);
//            })
//            ->where('lecturers.id', '=', $lecturerId)
//            ->groupBy('study_classes.id', 'study_classes.name', 'departments.name')
//            ->orderBy('study_classes.id');
//
//        try {
//            return $query->get();
//        } catch (\Exception $e) {
//            \Log::error('Error in listStatisticsByLecturerId: ' . $e->getMessage());
//            throw new \Exception('Failed to retrieve class statistics');
//        }
//    }

    public function listStatisticsStudyClassByLecturerId($lecturerId, $semesterId)
    {
        $query = $this->getModel()
            ->newQuery()
            ->select(
                'study_classes.id AS class_id',
                'study_classes.name AS class_name',
                'departments.name AS department_name',
                DB::raw('COUNT(DISTINCT students.id) AS total_students'),
                DB::raw('COUNT(DISTINCT CASE WHEN class_session_requests.status = 3 THEN class_session_requests.id END) AS completed_sessions'),
                DB::raw('COUNT(DISTINCT academic_warnings.student_id) AS warned_students'),
                DB::raw('COALESCE((
                SELECT ROUND(AVG(attendance_rate), 2)
                FROM (
                    SELECT
                        class_session_requests.study_class_id,
                        class_session_requests.id AS session_id,
                        COUNT(CASE WHEN attendances.status = 2 THEN attendances.student_id END) * 100.0 / NULLIF(
                            (SELECT COUNT(DISTINCT s.id)
                             FROM students s
                             WHERE s.study_class_id = class_session_requests.study_class_id),
                            0
                        ) AS attendance_rate
                    FROM class_session_requests
                    LEFT JOIN attendances ON attendances.class_session_request_id = class_session_requests.id
                    LEFT JOIN class_session_registrations ON class_session_registrations.id = class_session_requests.class_session_registration_id
                    WHERE class_session_requests.status = 3
                    AND class_session_registrations.semester_id = ?
                    GROUP BY class_session_requests.study_class_id, class_session_requests.id
                ) AS AttendanceStats
                WHERE AttendanceStats.study_class_id = study_classes.id
            ), 0) AS average_attendance_rate')
            )
            ->setBindings([$semesterId])
            ->join('lecturers', 'study_classes.lecturer_id', '=', 'lecturers.id')
            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
            ->leftJoin('faculties', 'majors.faculty_id', '=', 'faculties.id')
            ->leftJoin('departments', 'faculties.department_id', '=', 'departments.id')
            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('class_session_requests', function ($join) use ($semesterId) {
                $join->on('study_classes.id', '=', 'class_session_requests.study_class_id')
                    ->where('class_session_requests.status', '=', 3)
                    ->whereExists(function ($query) use ($semesterId) {
                        $query->select(DB::raw(1))
                            ->from('class_session_registrations')
                            ->whereColumn('class_session_registrations.id', 'class_session_requests.class_session_registration_id')
                            ->where('class_session_registrations.semester_id', '=', $semesterId);
                    });
            })
            ->leftJoin('academic_warnings', function ($join) use ($semesterId) {
                $join->on('students.id', '=', 'academic_warnings.student_id')
                    ->where('academic_warnings.semester_id', '=', $semesterId);
            })
            ->where('lecturers.id', '=', $lecturerId)
            ->groupBy('study_classes.id', 'study_classes.name', 'departments.name')
            ->orderBy('study_classes.id');

        try {
            return $query->get();
        } catch (\Exception $e) {
            \Log::error('Error in listStatisticsStudyClassByLecturerId: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve class statistics');
        }
    }

//    thống kê các mức phân loại thang điểm rèn luyện của từng lớp học theo kỳ học cụ thể
//    public function infoStudyClassListByConductEvaluationPeriodId($params)
//    {
//        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'];
//
//        if (!$conductEvaluationPeriodId) {
//            throw new \InvalidArgumentException('Conduct Evaluation Period ID is required');
//        }
//
//        $query = $this->getModel()
//            ->newQuery()
//            ->select([
//                'study_classes.id as class_id',
//                'study_classes.name as study_class_name',
//                'majors.name as major_name',
//                'departments.name as department_name',
//                DB::raw('COUNT(DISTINCT students.id) as total_students'),
//                DB::raw('COUNT(DISTINCT CASE WHEN (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) >= 90 AND (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) <= 100 THEN student_conduct_scores.student_id END) as outstanding'),
//                DB::raw('COUNT(DISTINCT CASE WHEN (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) >= 80 AND (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) < 90 THEN student_conduct_scores.student_id END) as good'),
//                DB::raw('COUNT(DISTINCT CASE WHEN (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) >= 65 AND (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) < 80 THEN student_conduct_scores.student_id END) as fair'),
//                DB::raw('COUNT(DISTINCT CASE WHEN (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) >= 50 AND (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) < 65 THEN student_conduct_scores.student_id END) as average'),
//                DB::raw('COUNT(DISTINCT CASE WHEN (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) >= 35 AND (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) < 50 THEN student_conduct_scores.student_id END) as weak'),
//                DB::raw('COUNT(DISTINCT CASE WHEN (
//                SELECT SUM(detail_conduct_scores.final_score)
//                FROM detail_conduct_scores
//                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
//            ) < 35 THEN student_conduct_scores.student_id END) as poor'),
//                DB::raw('(COUNT(DISTINCT students.id) - COUNT(DISTINCT student_conduct_scores.student_id)) as not_evaluated')
//            ])
//            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
//            ->leftJoin('faculties', 'faculties.id', '=', 'majors.faculty_id')
//            ->leftJoin('departments', 'departments.id', '=', 'faculties.department_id')
//            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
//            ->leftJoin('student_conduct_scores', function ($join) use ($conductEvaluationPeriodId) {
//                $join->on('students.id', '=', 'student_conduct_scores.student_id')
//                    ->where('student_conduct_scores.conduct_evaluation_period_id', '=', $conductEvaluationPeriodId);
//            })
//            ->groupBy('study_classes.id', 'study_classes.name', 'majors.name', 'departments.name')
//            ->orderBy('study_classes.id');
//
//        try {
//            return $query->paginate(Constant::DEFAULT_LIMIT_12);
//        } catch (\Exception $e) {
//            \Log::error('Error in getStudyClassListByConductEvaluationPeriodId: ' . $e->getMessage());
//            throw new \Exception('Failed to retrieve study class conduct statistics');
//        }
//    }

    public function infoByStudyClassListAndConductEvaluationPeriodId($params)
    {
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'] ?? null;
        $studyClassId = $params['study_class_id'] ?? null;

        if (!$conductEvaluationPeriodId || !$studyClassId) {
            throw new \InvalidArgumentException('Conduct Evaluation Period ID and Study Class ID are required');
        }

        $query = $this->getModel()
            ->newQuery()
            ->select([
                'study_classes.id as class_id',
                'study_classes.name as study_class_name',
                'majors.name as major_name',
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT students.id) as total_students'),
                DB::raw('COUNT(DISTINCT CASE WHEN (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) >= 90 AND (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) <= 100 THEN student_conduct_scores.student_id END) as outstanding'),
                DB::raw('COUNT(DISTINCT CASE WHEN (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) >= 80 AND (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) < 90 THEN student_conduct_scores.student_id END) as good'),
                DB::raw('COUNT(DISTINCT CASE WHEN (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) >= 65 AND (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) < 80 THEN student_conduct_scores.student_id END) as fair'),
                DB::raw('COUNT(DISTINCT CASE WHEN (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) >= 50 AND (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) < 65 THEN student_conduct_scores.student_id END) as average'),
                DB::raw('COUNT(DISTINCT CASE WHEN (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) >= 35 AND (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) < 50 THEN student_conduct_scores.student_id END) as weak'),
                DB::raw('COUNT(DISTINCT CASE WHEN (
                SELECT SUM(detail_conduct_scores.final_score)
                FROM detail_conduct_scores
                WHERE detail_conduct_scores.student_conduct_score_id = student_conduct_scores.id
            ) < 35 THEN student_conduct_scores.student_id END) as poor'),
                DB::raw('(COUNT(DISTINCT students.id) - COUNT(DISTINCT student_conduct_scores.student_id)) as not_evaluated')
            ])
            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
            ->leftJoin('faculties', 'faculties.id', '=', 'majors.faculty_id')
            ->leftJoin('departments', 'departments.id', '=', 'faculties.department_id')
            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($conductEvaluationPeriodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', '=', $conductEvaluationPeriodId);
            })
            ->where('study_classes.id', '=', $studyClassId)
            ->groupBy('study_classes.id', 'study_classes.name', 'majors.name', 'departments.name');

        try {
            return $query->first();
        } catch (\Exception $e) {
            \Log::error('Error in getStudyClassListByConductEvaluationPeriodId: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve study class conduct statistics');
        }
    }

    public function statisticalClassByDepartment()
    {
        $query = $this->getModel()
            ->newQuery()
            ->select(
                'departments.id as department_id',
                'departments.name as department_name',
                DB::raw('COUNT(study_classes.id) as total_classes')
            )
            ->join('majors', 'study_classes.major_id', '=', 'majors.id')
            ->join('faculties', 'majors.faculty_id', '=', 'faculties.id')
            ->join('departments', 'faculties.department_id', '=', 'departments.id')
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('total_classes')
            ->get();

        return $query;
    }

}
