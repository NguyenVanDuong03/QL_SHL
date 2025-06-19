<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\StudentConductScoreRepository;
use Illuminate\Support\Arr;

class StudentConductScoreService extends BaseService
{
    protected function getRepository(): StudentConductScoreRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(StudentConductScoreRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');
        $relates = Arr::get($params, 'relates', []);
        $keywords = Arr::get($params, 'conductEvaluationPeriodId', null);

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates,
        ];
    }

    public function findStudentConductScore($conductEvaluationPeriodId, $studentId)
    {
        return $this->getRepository()->findStudentConductScore(
            $conductEvaluationPeriodId,
            $studentId
        );
    }

    public function getAverageConductScores()
    {
        return $this->getRepository()->getAverageConductScores();
    }

    public function getOverallAverageConductScoreWithTotalStudents()
    {
        return $this->getRepository()->getOverallAverageConductScoreWithTotalStudents();
    }

//    public function createOrUpdateStudentConductScore($params)
//    {
//        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'];
//        $studentId = $params['student_id'];
//        $teacherScore = $params['class_score'] ?? null;
//        $departmentScore = $params['final_score'] ?? null;
//
//        $attributes = [
//            'conduct_evaluation_period_id' => $conductEvaluationPeriodId,
//            'student_id' => $studentId,
//        ];
//
//        $values = [
//            'status' => Constant::STUDENT_CONDUCT_SCORE_STATUS['STUDENT'],
//        ];
//
//        if ($teacherScore !== null) {
//            $values['class_score'] = $teacherScore;
//            $values['status'] = Constant::STUDENT_CONDUCT_SCORE_STATUS['TEACHER'];
//        }
//
//        if ($departmentScore !== null) {
//            $values['final_score'] = $departmentScore;
//            $values['status'] = Constant::STUDENT_CONDUCT_SCORE_STATUS['DEPARTMENT'];
//        }
//
//        return $this->getRepository()->createOrUpdate(
//            $attributes,
//            $values
//        );
//    }

}
