<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\DetailConductScoreRepository;
use Illuminate\Support\Arr;

class DetailConductScoreService extends BaseService
{
    protected function getRepository(): DetailConductScoreRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(DetailConductScoreRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');
        $relates = Arr::get($params, 'relates', []);

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates,
        ];
    }

    public function getConductCriteriaData($params)
    {
        return $this->getRepository()->getConductCriteriaData($params);
    }

    public function calculateTotalScore($criteriaData)
    {
        return $this->getRepository()->calculateTotalScore($criteriaData);
    }

//    public function saveDetailConductScores($params)
//    {
//        $details = $params['details'] ?? [];
//        $studentId = $params['student_id'];
//        $studentConductScoreId = $params['student_conduct_score_id'];
////dd($details);
//        if (is_null($studentId) || is_null($studentConductScoreId)) {
//            \Log::error('Thiếu ID học sinh hoặc ID điểm rèn luyện', ['params' => $params]);
//            throw new \Exception('Thiếu thông tin học sinh hoặc điểm rèn luyện');
//        }
//
//        foreach ($details as $detail) {
//            $attributes = [
//                'conduct_criteria_id' => $detail['conduct_criteria_id'],
//                'student_conduct_score_id' => $studentConductScoreId,
//            ];
//
//            // Khởi tạo giá trị cho cơ sở dữ liệu
//            $values = [
//                'conduct_criteria_id' => $detail['conduct_criteria_id'],
//                'student_conduct_score_id' => $studentConductScoreId,
//                'self_score' => $detail['self_score'] ?? 0,
//                'class_score' => $detail['class_score'] ?? null,
//                'final_score' => $detail['final_score'] ?? null,
//                'note' => $detail['note'] ?? null,
//                'path' => null, // Mặc định là null
//            ];
//
//            // Xử lý tải tệp cho tiêu chí này
//            $evidenceKey = "evidence.{$detail['conduct_criteria_id']}";
//            if ($params instanceof \Illuminate\Http\Request && $params->hasFile($evidenceKey)) {
//                $file = $params->file($evidenceKey);
//                if ($file->isValid()) {
//                    try {
//                        $fileName = 'evidence_' . time() . '_' . $detail['conduct_criteria_id'] . '_' . $studentId . '.' . $file->getClientOriginalExtension();
//                        $path = $file->storeAs('evidences', $fileName, 'public');
//                        $values['path'] = $path; // Lưu đường dẫn tệp
//                        \Log::info('Tệp đã được lưu', ['path' => $path, 'fileName' => $fileName]);
//                    } catch (\Exception $e) {
//                        \Log::error('Lỗi khi lưu tệp', [
//                            'criteriaId' => $detail['conduct_criteria_id'],
//                            'error' => $e->getMessage()
//                        ]);
//                        throw new \Exception('Lỗi khi lưu tệp minh chứng: ' . $e->getMessage());
//                    }
//                } else {
//                    \Log::warning('Tệp tải lên không hợp lệ', ['criteriaId' => $detail['conduct_criteria_id']]);
//                }
//            } else {
//                \Log::info('Không có tệp được tải lên, đặt path là null', ['criteriaId' => $detail['conduct_criteria_id']]);
//            }
//
//            // Lưu hoặc cập nhật bản ghi chi tiết
//            if (empty($attributes)) {
//                \Log::error('Thuộc tính rỗng', ['params' => $params]);
//                throw new \Exception('Thiếu thông tin chi tiết điểm rèn luyện');
//            }
//
//            $this->updateOrCreate($attributes, $values);
//        }
//
//        return true;
//    }

}
