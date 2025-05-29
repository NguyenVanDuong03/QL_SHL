<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ClassSessionRegistrationRepository;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;

class ClassSessionRegistrationService extends BaseService
{
    protected function getRepository(): ClassSessionRegistrationRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ClassSessionRegistrationRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        return $params;
    }

    public function checkClassSessionRegistration()
    {
        $currentSemester = $this->getRepository()->getCurrentSemester();
        if ($currentSemester) {
            $openDate = Carbon::parse($currentSemester->open_date);
            $endDate = Carbon::parse($currentSemester->end_date);
            $now = Carbon::now();

            if ($now->isBetween($openDate, $endDate) || $now->lt($openDate)) {
                return true;
            }
        }

        return false;
    }

    public function getCSRSemesterInfo()
    {
        return $this->getRepository()->getCSRSemesterInfo();

    }

    public function getListCSR()
    {
        return $this->getRepository()->getListCSR();
    }

    public function getListCSRHistory($class_session_registration_id)
    {
        return $this->getRepository()->getListCSRHistory($class_session_registration_id);
    }

}
