<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\ClassSessionReportRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ClassSessionReportService extends BaseService
{
    protected function getRepository(): ClassSessionReportRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(ClassSessionReportRepository::class);
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

    public function getListReports($params)
    {
        return $this->getRepository()->getListReports($params);
    }

    public function storeReport($params)
    {
//        dd($params);
        $filePath = null;
        if (isset($params['path']) && $params['path'] instanceof \Illuminate\Http\UploadedFile) {
            // Lưu file vào disk 'public' trong thư mục 'reports'
            $filePath = $params['path']->store('reports', 'public');
        }

        $params['path'] = $filePath;

        return $this->getRepository()->create($params);
    }

    public function updateReport($params)
    {
        $filePath = null;

        $report = $this->getRepository()->find($params['id']);

        if (isset($params['path']) && $params['path'] instanceof \Illuminate\Http\UploadedFile) {
            if ($report && $report->path && Storage::disk('public')->exists($report->path)) {
                Storage::disk('public')->delete($report->path);
            }

            $filePath = $params['path']->store('reports', 'public');
        }

        if ($filePath) {
            $params['path'] = $filePath;
        } else {
            unset($params['path']);
        }

        return $this->getRepository()->update($params['id'], $params);
    }

    public function deleteReport($id)
    {
        $report = $this->getRepository()->find($id);

        if ($report && $report->path && Storage::disk('public')->exists($report->path)) {
            Storage::disk('public')->delete($report->path);
        }

        return $this->getRepository()->delete($id);
    }

}
