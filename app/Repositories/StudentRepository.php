<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Student;

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
        $classSessionRegistrationId = $params['class_session_registration_id'] ?? null;

        $query = Student::query()
            ->select([
                'students.id as student_id',
                'students.student_code',
                'users.name as student_name',
                'users.email',
                'users.gender',
                'users.phone',
                'attendances.status',
                'attendances.updated_at as attendance_recorded_at',
            ])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('attendances', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('class_session_requests', 'attendances.class_session_request_id', '=', 'class_session_requests.id')
            ->where('students.study_class_id', $studyClassId)
            ->where('class_session_requests.class_session_registration_id', $classSessionRegistrationId);

        // Phần tìm kiếm
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('students.student_code', 'like', '%' . $search . '%')
                    ->orWhere('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate(Constant::DEFAULT_LIMIT_8);
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

}
