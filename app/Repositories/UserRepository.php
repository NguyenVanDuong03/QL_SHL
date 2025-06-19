<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\User;

class UserRepository extends BaseRepository
{
    protected function getModel(): User
    {
        if (empty($this->model)) {
            $this->model = app()->make(User::class);
        }

        return $this->model;
    }

    public function statisticalUserByRole()
    {
        return $this->getModel()
            ->newQuery()
            ->selectRaw('role, COUNT(*) as total')
            ->where('role', '!=', Constant::ROLE_LIST['STUDENT_AFFAIRS_DEPARTMENT'])
            ->groupBy('role')
            ->get();
}


}
