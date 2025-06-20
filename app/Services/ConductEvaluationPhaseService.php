<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ConductEvaluationPhaseRepository;
use Illuminate\Support\Arr;

class ConductEvaluationPhaseService extends BaseService
{
    protected function getRepository(): ConductEvaluationPhaseRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ConductEvaluationPhaseRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');

        return [
            'sort' => $sort,
            'wheres' => $wheres,
        ];
    }

    public function arrayUpdates($phases, $conductEvaluationPeriodId): bool
    {
        if (empty($conductEvaluationPeriodId)) {
            return false;
        }

        return $this->getRepository()->arrayUpdates($phases, $conductEvaluationPeriodId);
    }

    public function arrayDeleteByConductEvaluationPeriodId($id)
    {
        return $this->getRepository()->arrayDeleteByConductEvaluationPeriodId($id);
    }

    public function currentConductEvaluationPeriodByStudent()
    {
        return $this->getRepository()->currentConductEvaluationPeriodByStudent();
    }

    public function currentConductEvaluationPeriodByLecturer()
    {
        return $this->getRepository()->currentConductEvaluationPeriodByLecturer();
    }

    public function currentConductEvaluationPeriodByFacultyOffice()
    {
        return $this->getRepository()->currentConductEvaluationPeriodByFacultyOffice();
    }

    public function checkConductEvaluationPeriod()
    {
        $currentConductEvaluationPeriod = null;
        if (auth()->user()->role === Constant::ROLE_LIST['STUDENT'] || auth()->user()->role === Constant::ROLE_LIST['CLASS_STAFF'])
            $currentConductEvaluationPeriod = $this->getRepository()->currentConductEvaluationPeriodByStudent();
        elseif (auth()->user()->role === Constant::ROLE_LIST['TEACHER'])
            $currentConductEvaluationPeriod = $this->getRepository()->currentConductEvaluationPeriodByLecturer();
        elseif (auth()->user()->role === Constant::ROLE_LIST['FACULTY_OFFICE'])
            $currentConductEvaluationPeriod = $this->getRepository()->currentConductEvaluationPeriodByFacultyOffice();

        if ($currentConductEvaluationPeriod) {
            $openDate = $currentConductEvaluationPeriod->open_date;
            $endDate = $currentConductEvaluationPeriod->end_date;
            $now = now();

            if ($now->isBetween($openDate, $endDate) || $now->lt($openDate)) {
                return true;
            }
        }

        return false;
    }

    public function findConductEvaluationPeriodBySemesterId($params)
    {
        return $this->getRepository()->findConductEvaluationPeriodBySemesterId($params);
    }

}
