<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\ClassSessionRegistration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ClassSessionRegistrationRepository extends BaseRepository
{
    protected function getModel(): ClassSessionRegistration
    {
        if (empty($this->model)) {
            $this->model = app()->make(ClassSessionRegistration::class);
        }

        return $this->model;
    }

    public function getCurrentSemester()
    {
        return $this->getModel()->newQuery()->orderByDesc('id')->first();
    }

    public function getCSRSemesterInfo()
    {
        $latestRegistration = $this->getModel()->newQuery()->orderByDesc('id')->first();

        $count = DB::table('class_session_requests')
        ->where('class_session_registration_id', $latestRegistration->id ?? null)
        ->count();

        return $this->getModel()
        ->newQuery()
        ->join('semesters', 'class_session_registrations.semester_id', '=', 'semesters.id')
        ->leftJoin('class_session_requests', 'class_session_requests.class_session_registration_id', '=', 'class_session_registrations.id')
        ->select(['class_session_registrations.id', 'semesters.name', 'school_year', 'class_session_registrations.open_date', 'class_session_registrations.end_date', DB::raw($count . ' as total_registered_classes')])
        ->where('class_session_registrations.id', $latestRegistration->id ?? null)
        ->first();
    }

    public function getListCSR($params)
    {
        $search = $params['search'] ?? '';
        $latestRegistration = $this->getModel()->newQuery()->orderByDesc('id')->first();

        $query = $this->getModel()
            ->newQuery()
            ->join('class_session_requests', 'class_session_requests.class_session_registration_id', '=', 'class_session_registrations.id')
            ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
            ->leftJoin('rooms', 'class_session_requests.room_id', '=', 'rooms.id')
            ->leftJoin('students', 'students.study_class_id', '=', 'study_classes.id')
            ->where('class_session_registration_id', $latestRegistration->id)
            ->whereNull('class_session_registrations.deleted_at')
            ->groupBy(
                'class_session_requests.id',
                'class_session_requests.class_session_registration_id',
                'class_session_requests.study_class_id',
                'class_session_requests.lecturer_id',
                'class_session_requests.room_id',
                'class_session_requests.type',
                'class_session_requests.position',
                'class_session_requests.proposed_at',
                'class_session_requests.location',
                'class_session_requests.meeting_type',
                'class_session_requests.meeting_id',
                'class_session_requests.meeting_password',
                'class_session_requests.meeting_url',
                'class_session_requests.title',
                'class_session_requests.content',
                'class_session_requests.note',
                'class_session_requests.status',
                'class_session_requests.rejection_reason',
                'class_session_requests.created_at',
                'class_session_requests.updated_at',
                'class_session_requests.deleted_at',
                'class_session_registrations.semester_id',
                'study_classes.name',
                'rooms.name',
                'rooms.description'
            )
            ->orderByRaw('
            CASE
                WHEN class_session_requests.status IS NULL THEN 0
                WHEN class_session_requests.status = 0 THEN 1
                WHEN class_session_requests.status = 2 THEN 2
                WHEN class_session_requests.status = 1 THEN 3
                ELSE 4
            END
        ')
            ->orderByDesc('class_session_requests.id')
            ->select([
                'class_session_requests.*',
                'class_session_registrations.semester_id',
                'study_classes.name as study_class_name',
                'rooms.name as room_name',
                'rooms.description as room_description',
                DB::raw('COUNT(students.id) as total_students')
            ]);

        if (!empty($search)) {
            $query->where('study_classes.name', 'LIKE', '%' . $search . '%');
        }

        return $query;
    }

}
