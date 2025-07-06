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
        $sort = Arr::get($params, 'sort', 'id:desc');

        return [
            'sort' => $sort,
        ];
    }

    public function checkClassSessionRegistration()
    {
        $currentSemester = $this->getCurrentSemester();
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

    public function checkClassSessionRegistrationPlus5days()
    {
        $currentSemester = $this->getCurrentSemester();
        if ($currentSemester) {
            $openDate = Carbon::parse($currentSemester->open_date);
            $endDate = Carbon::parse($currentSemester->end_date)->addDays(5);
            $now = Carbon::now();

            if ($now->isBetween($openDate, $endDate) || $now->lt($openDate)) {
                return true;
            }
        }

        return false;
    }

    public function getCurrentSemester()
    {
        return $this->getRepository()->getCurrentSemester();
    }

    public function getCSRSemesterInfo()
    {
        return $this->getRepository()->getCSRSemesterInfo();

    }

    public function getListCSR($params)
    {
        return $this->getRepository()->getListCSR($params);
    }

//    public function getListFlexibleClass()
//    {
//        return $this->getRepository()->getListFlexibleClass();
//    }

}
