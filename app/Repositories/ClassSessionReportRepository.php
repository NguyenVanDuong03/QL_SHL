<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\ClassSessionReport;

class ClassSessionReportRepository extends BaseRepository
{
    protected function getModel(): ClassSessionReport
    {
        if (empty($this->model)) {
            $this->model = app()->make(ClassSessionReport::class);
        }

        return $this->model;
    }

    public function getListReports($params)
    {
        $semester_id = $params['semester_id'] ?? null;
        $major_id = $params['major_id'] ?? null;
        $study_class_name = $params['study_class_name'] ?? null;

        $query = $this->getModel()
            ->with([
                'classSessionRequest',
                'classSessionRequest.studyClass',
                'classSessionRequest.studyClass.major',
                'classSessionRequest.studyClass.major.faculty.department',
                'classSessionRequest.classSessionRegistration',
                'classSessionRequest.classSessionRegistration.semester'
            ])
            ->orderBy('id', 'desc');

        if ($semester_id) {
            $query->whereHas('classSessionRequest.classSessionRegistration.semester', function ($q) use ($semester_id) {
                $q->where('id', $semester_id);
            });
        }

        if ($major_id) {
            $query->whereHas('classSessionRequest.studyClass.major', function ($q) use ($major_id) {
                $q->where('id', $major_id);
            });
        }

        if ($study_class_name) {
            $query->whereHas('classSessionRequest.studyClass', function ($q) use ($study_class_name) {
                $q->where('name', 'LIKE', '%' . $study_class_name . '%');
            });
        }

        return $query->paginate(Constant::DEFAULT_LIMIT_12);
    }

    public function countClassSessionReports($semesterId)
    {
        return $this->getModel()
            ->newQuery()
            ->whereHas('classSessionRequest.classSessionRegistration.semester', function ($query) use ($semesterId) {
                $query->where('id', $semesterId);
            })
            ->count();
    }

    public function findReport($id)
    {
        return $this->getModel()
            ->newQuery()
            ->where('class_session_request_id', $id)
            ->first();
    }

}
