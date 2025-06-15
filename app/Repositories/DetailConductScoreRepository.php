<?php

namespace App\Repositories;

use App\Models\DetailConductScore;
use Illuminate\Support\Facades\DB;

class DetailConductScoreRepository extends BaseRepository
{
    protected function getModel(): DetailConductScore
    {
        if (empty($this->model)) {
            $this->model = app()->make(DetailConductScore::class);
        }

        return $this->model;
    }

    public function getConductCriteriaData($params)
    {
        $studentId = $params['student_id'] ?? null;
        $semesterId = $params['semester_id'] ?? null;

        if (is_null($studentId) || is_null($semesterId)) {
            return collect();
        }

        $query = $this->getModel()->newQuery()
            ->select(
                'conduct_criterias.id as criterion_id',
                'conduct_criterias.content',
                'conduct_criterias.max_score',
                'detail_conduct_scores.self_score',
                'detail_conduct_scores.class_score',
                'detail_conduct_scores.final_score',
                'detail_conduct_scores.note',
                'detail_conduct_scores.path as evidence_path'
            )
            ->join('student_conduct_scores', 'detail_conduct_scores.student_conduct_score_id', '=', 'student_conduct_scores.id')
            ->join('conduct_criterias', 'detail_conduct_scores.conduct_criteria_id', '=', 'conduct_criterias.id')
            ->where('student_conduct_scores.student_id', $studentId)
            ->where('student_conduct_scores.conduct_evaluation_period_id', function ($query) use ($semesterId) {
                $query->select('id')
                    ->from('conduct_evaluation_periods')
                    ->where('semester_id', $semesterId)
                    ->limit(1);
            })
            ->whereNull('detail_conduct_scores.deleted_at')
            ->orderBy('conduct_criterias.id');

        return $query->get();
    }

    public function getConductCriteriaDataByLecturer($params)
    {
        $studentConductScoreId = $params['student_conduct_score_id'] ?? null;

        if (is_null($studentConductScoreId)) {
            return collect();
        }

        $query = $this->getModel()->newQuery()
            ->select(
                'conduct_criterias.id as criterion_id',
                'conduct_criterias.content',
                'conduct_criterias.max_score',
                'detail_conduct_scores.self_score',
                'detail_conduct_scores.class_score',
                'detail_conduct_scores.final_score',
                'detail_conduct_scores.note',
                'detail_conduct_scores.path as evidence_path'
            )
            ->join('student_conduct_scores', 'detail_conduct_scores.student_conduct_score_id', '=', 'student_conduct_scores.id')
            ->join('conduct_criterias', 'detail_conduct_scores.conduct_criteria_id', '=', 'conduct_criterias.id')
            ->where('student_conduct_scores.id', $studentConductScoreId)
            ->whereNull('detail_conduct_scores.deleted_at')
            ->orderBy('conduct_criterias.id');

        return $query->get();
    }

    public function calculateTotalScore($criteriaData)
    {
        return $criteriaData->sum(function ($record) {
            return $record->final_score ?? $record->self_score ?? 0;
        });
    }

}
