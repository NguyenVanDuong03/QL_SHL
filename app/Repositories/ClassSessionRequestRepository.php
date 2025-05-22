<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\ClassSessionRequest;

class ClassSessionRequestRepository extends BaseRepository
{
    protected function getModel(): ClassSessionRequest
    {
        if (empty($this->model)) {
            $this->model = app()->make(ClassSessionRequest::class);
        }

        return $this->model;
    }

    public function countFlexibleClassSessionRequest()
    {
        return $this->getModel()
            ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
            ->whereIn('status', [Constant::CLASS_SESSION_STATUS['APPROVED'], Constant::CLASS_SESSION_STATUS['ACTIVE']])
            ->count();
    }

    // public function getFlexibleClassSessionRequest()
    // {
    //     return $this->getModel()
    //         ->where('type', Constant::CLASS_SESSION_TYPE['FLEXIBLE'])
    //         ->whereIn('status', [Constant::CLASS_SESSION_STATUS['APPROVED'], Constant::CLASS_SESSION_STATUS['ACTIVE']])
    //         ->paginate(Constant::DEFAULT_LIMIT);
    // }
}
