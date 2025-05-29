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
        ->where('class_session_registration_id', $latestRegistration->id)
        ->count();

        return $this->getModel()
        ->newQuery()
        ->join('semesters', 'class_session_registrations.semester_id', '=', 'semesters.id')
        ->leftJoin('class_session_requests', 'class_session_requests.class_session_registration_id', '=', 'class_session_registrations.id')
        ->select(['class_session_registrations.id', 'semesters.name', 'school_year', 'class_session_registrations.open_date', 'class_session_registrations.end_date', DB::raw($count . ' as total_registered_classes')])
        ->where('class_session_registrations.id', $latestRegistration->id)
        ->first();
    }

    public function getListCSR()
    {
        $latestRegistration = $this->getModel()->newQuery()->orderByDesc('id')->first();

        return $this->getModel()
            ->newQuery()
            ->join('class_session_requests', 'class_session_requests.class_session_registration_id', '=', 'class_session_registrations.id')
             ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
            ->where('class_session_registration_id', $latestRegistration->id)
            ->where('class_session_requests.status', Constant::CLASS_SESSION_STATUS['ACTIVE'])
            ->orderBy('class_session_requests.id', 'desc')
            ->paginate(Constant::DEFAULT_LIMIT);
    }

    public function getListCSRHistory($class_session_registration_id)
    {
//        dd($semesterId);
        return $this->getModel()
            ->newQuery()
            ->join('class_session_requests', 'class_session_requests.class_session_registration_id', '=', 'class_session_registrations.id')
            ->join('study_classes', 'class_session_requests.study_class_id', '=', 'study_classes.id')
            ->where('class_session_registration_id', $class_session_registration_id)
            ->whereIn('class_session_requests.status', [Constant::CLASS_SESSION_STATUS['APPROVED'], Constant::CLASS_SESSION_STATUS['REJECTED']])
            ->orderBy('class_session_requests.id', 'desc')
            ->paginate(Constant::DEFAULT_LIMIT);
    }

}
