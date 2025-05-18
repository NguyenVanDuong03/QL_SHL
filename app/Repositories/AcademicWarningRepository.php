<?php

namespace App\Repositories;

use App\Models\AcademicWarning;

class AcademicWarningRepository extends BaseRepository
{
    protected function getModel(): AcademicWarning
    {
        if (empty($this->model)) {
            $this->model = app()->make(AcademicWarning::class);
        }

        return $this->model;
    }

    public function getStudentWarningByStudyClassId($lecturerId)
    {
        $getAllStudentWarning = $this->getModel()
            ->whereHas('student.studyClass', function ( $query) use ($lecturerId) {
                $query->where('lecturer_id', $lecturerId);
            })
            ->with([
                'student' => function ($q) {
                    $q->select('id', 'user_id', 'student_code', 'study_class_id')->with(['user:id,name,email', 'studyClass:id,name']);
                },
            ])
            ->paginate();

            return $getAllStudentWarning;
    }
}
