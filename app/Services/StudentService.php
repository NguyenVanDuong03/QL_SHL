<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\StudentRepository;
use Illuminate\Support\Arr;

class StudentService extends BaseService
{
    protected function getRepository(): StudentRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(StudentRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $sort = Arr::get($params, 'sort', 'id:desc');
        $wheres = Arr::get($params, 'wheres', []);
        $relates = Arr::get($params, 'relates', []);
        $getAll = Arr::get($params, 'getAll', false);
        if ($getAll) {
            $relates = ['studyClass', 'user', 'cohort'];
        }

        return [
            'sort' => $sort,
            'wheres' => $wheres,
            'relates' => $relates
        ];
    }

    public function getStudentsByClassId($params)
    {
        return $this->getRepository()->getStudentListByClassId($params);
    }

    public function getListStudentByClassId($params)
    {
        return $this->getRepository()->getListStudentByClassId($params);
    }

    public function getNoteStudentById($class_id)
    {
        return $this->getRepository()->getNoteStudentById($class_id);
    }

    public function updateOfficers($params)
    {
        $studyClassId = $params['study_class_id'] ?? null;
        if (empty($studyClassId)) {
            return false;
        }

        // Reset tất cả cán sự cũ về sinh viên
        $this->getRepository()->resetClassOfficers($studyClassId);

        // Danh sách tương ứng các role
        $positionMap = [
            'classLeader' => Constant::STUDENT_POSITION['CLASS_PRESIDENT'],
            'classViceLeader' => Constant::STUDENT_POSITION['VICE_PRESIDENT'],
            'classSecretary' => Constant::STUDENT_POSITION['SECRETARY'],
        ];

        // Cập nhật từng vai trò nếu có
        foreach ($positionMap as $field => $positionValue) {
            if (!empty($params[$field])) {
                $this->getRepository()->updateStudentPosition($params[$field], $positionValue);
            }
        }

        return true;
    }

    public function getTotalStudentsByClass($params)
    {

        return $this->getRepository()->getTotalStudentsByClass($params);
    }

//    public function getAttendanceStatusSummary($params)
//    {
//        $limit = Arr::get($params, 'limit', 25);
//        $offset = Arr::get($params, 'offset', 0);
//
//        $rawData = $this->getRepository()->getAttendanceStatusSummary($params, $limit, $offset);
//
//        $defaultStatuses = [
//            -1 => 'Chưa xác nhận tham gia',
//            0 => 'Xác nhận tham gia',
//            1 => 'Vắng mặt có phép',
//            2 => 'Có mặt',
//            3 => 'Vắng mặt',
//        ];
//
//        $summary = [];
//        foreach ($defaultStatuses as $status => $text) {
//            $found = collect($rawData)->firstWhere('status', $status);
//            $summary[] = [
//                'status' => $status,
//                'status_text' => $text,
//                'count' => $found['count'] ?? 0,
//            ];
//        }
//
//        return $summary;
//    }

    public function getAttendanceStatusSummary($params)
    {
        $limit = Arr::get($params, 'limit', 25);
        $offset = Arr::get($params, 'offset', 0);

        $rawData = $this->getRepository()->getAttendanceStatusSummary($params, $limit, $offset);

        $defaultStatuses = [
            -1 => ['text' => 'Chưa xác nhận', 'badge' => 'warning'],
            0  => ['text' => 'Xác nhận',      'badge' => 'primary'],
            1  => ['text' => 'Xin vắng',      'badge' => 'secondary'],
            2  => ['text' => 'Có mặt',        'badge' => 'success'],
            3  => ['text' => 'Vắng mặt',      'badge' => 'danger'],
        ];

        $summary = [];
        foreach ($defaultStatuses as $status => $info) {
            $found = collect($rawData)->firstWhere('status', $status);
            $summary[] = [
                'status' => $status,
                'status_text' => $info['text'],
                'badge_class' => $info['badge'],
                'count' => $found['count'] ?? 0,
            ];
        }

        return $summary;
    }

    public function listConductScores($params)
    {
        return $this->getRepository()->listConductScores($params);
    }

    public function countStudentsByConductStatus($params)
    {
        return $this->getRepository()->countStudentsByConductStatus($params);
    }

}
