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

    public function getStudyClassListByLecturerId($lecturerId)
    {
        $query = $this->getModel()
            ->with(['students'])
            ->where('lecturer_id', $lecturerId)
            ->orderBy('id', 'desc');

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
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'];

        $query = $this->getModel()
            ->select([
                'study_classes.name as study_class_name',
                'majors.name as major_name',
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT students.id) as total_students'),
                DB::raw("COUNT(DISTINCT CASE WHEN student_conduct_scores.id IS NOT NULL AND student_conduct_scores.self_score IS NOT NULL THEN students.id END) as has_evaluated"),
                DB::raw("COUNT(DISTINCT CASE WHEN student_conduct_scores.id IS NULL OR student_conduct_scores.self_score IS NULL THEN students.id END) as not_evaluated"),
            ])
            ->leftJoin('majors', 'study_classes.major_id', '=', 'majors.id')
            ->leftJoin('faculties', 'majors.faculty_id', '=', 'faculties.id')
            ->leftJoin('departments', 'faculties.department_id', '=', 'departments.id')
            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
            ->leftJoin('student_conduct_scores', function ($join) use ($conductEvaluationPeriodId) {
                $join->on('students.id', '=', 'student_conduct_scores.student_id')
                    ->where('student_conduct_scores.conduct_evaluation_period_id', '=', $conductEvaluationPeriodId);
            })
            ->groupBy('study_classes.id', 'study_classes.name', 'majors.name', 'departments.name');

        return $query->paginate(Constant::DEFAULT_LIMIT_12);
    }


}
