<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Notifications\CreateAccount;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;

class UserService extends BaseService
{
    protected function getRepository(): UserRepository
    {
        if (empty($this->repository)) {
            $this->repository = app()->make(UserRepository::class);
        }

        return $this->repository;
    }

    protected function buildFilterParams(array $params): array
    {
        $wheres = Arr::get($params, 'wheres', []);
        $sort = Arr::get($params, 'sort', 'id:desc');

        $keyword = Arr::get($params, 'searchName', null);
        if ($keyword) {
            $wheres[] = ['name', 'LIKE', "%$keyword%"];
        };

        $keywordSearchAll = Arr::get($params, 'searchAll', null);
        if ($keywordSearchAll) {
            $wheres[] = [function ($q) use ($keywordSearchAll) {
                $q->where('name', 'LIKE', "%$keywordSearchAll%")
                    ->orWhere('email', 'LIKE', "%$keywordSearchAll%");
            }];
        }

        return [
            'wheres' => $wheres,
            'sort' => $sort,
        ];
    }

    public function createStudentUser($params)
    {
        if (!preg_match('/^[0-9]{10}@e\.tlu\.edu\.vn$/', $params['email'])) {
            return false;
        }

        $params['role'] = Constant::ROLE_LIST['STUDENT'];
        $params['password'] = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        $user = $this->create($params);

        try {
            $user->notify(new \App\Notifications\CreateAccount($params['email'], $params['password']));
        } catch (\Exception $e) {
            \Log::error('Failed to send account creation notification: ' . $e->getMessage());
            return false;
        }

        return true;
    }

    public function createTeacherUser($params)
    {
        if (!str_ends_with($params['email'], '@tlu.edu.vn')) {
            return false;
        }

        $params['role'] = Constant::ROLE_LIST['TEACHER'];
        $params['password'] = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        $user = $this->create($params);

        try {
            $user->notify(new \App\Notifications\CreateAccount($params['email'], $params['password']));
        } catch (\Exception $e) {
            \Log::error('Failed to send account creation notification: ' . $e->getMessage());
            return false;
        }

        return true;
    }

    public function redirectAuthPath()
    {
        if (auth()->user()->role == Constant::ROLE_LIST['TEACHER']) {
            return 'teacher.index';
        } else if (auth()->user()->role == Constant::ROLE_LIST['STUDENT_AFFAIRS_DEPARTMENT']) {
            return 'student-affairs-department.index';
        } else if (auth()->user()->role == Constant::ROLE_LIST['CLASS_STAFF']) {
            return 'class-staff.index';
        } else if (auth()->user()->role == Constant::ROLE_LIST['FACULTY_OFFICE']) {
            return 'faculty-office.index';
        }

        return 'student.index';
    }
}
