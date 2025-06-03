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
        // $lecturerId = auth()->user()->lecturer?->id;
        $query = $this->getModel()
            ->with(['students'])
            ->where('lecturer_id', $lecturerId)
            ->orderBy('id', 'desc')
            ->paginate(constant::DEFAULT_LIMIT_12);

        return $query;
    }

//    public function getStudyClassById($params)
//    {
//        $query = $this->getModel()
//            ->with(['major.faculty.department', 'cohort'])
//            ->where('lecturer_id', $params['lecturer_id'])
//            ->whereDoesntHave('classSessionRequests', function ($q) use ($params) {
//                $q->whereHas('classSessionRegistration', function ($qr) use ($params) {
//                    $qr->where('semester_id', $params['semester_id']);
//                });
//            });
//
//        // Nếu có từ khóa tìm kiếm
//        if (!empty($params['search'])) {
//            $search = $params['search'];
//            $query->where(function ($q) use ($search) {
//                $q->where('name', 'like', "%$search%")
//                    ->orWhereHas('lecturer.user', function ($q2) use ($search) {
//                        $q2->where('name', 'like', "%$search%");
//                    });
//            });
//        }
//
//        return $query->paginate(constant::DEFAULT_LIMIT_12);
//    }

    public function getStudyClassById($params)
    {
        $lecturerId = $params['lecturer_id'];
        $semesterId = $params['semester_id'];

        $query = $this->getModel()
            ->with([
                'major.faculty.department',
                'cohort',
                'classSessionRequests' => function ($q) use ($semesterId) {
                    $q->whereHas('classSessionRegistration', function ($qr) use ($semesterId) {
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

        // Nếu có từ khóa tìm kiếm
        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('lecturer.user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%");
                    });
            });
        }

        return $query->paginate(constant::DEFAULT_LIMIT_12);
    }

}
