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

    public function isValidEmailByRole($email, $role)
    {
        if ($role === Constant::ROLE_LIST['STUDENT']) {
            return preg_match('/^[0-9]{10}@e\.tlu\.edu\.vn$/', $email);
        }

        if ($role === Constant::ROLE_LIST['TEACHER']) {
            return preg_match('/^[a-zA-Z0-9._%+-]+@tlu\.edu\.vn$/', $email) && !str_contains($email, '@e.tlu.edu.vn');
        }

        return false;
    }


    public function createStudentUser($params)
    {
        if (!$this->isValidEmailByRole($params['email'], Constant::ROLE_LIST['STUDENT'])) {
            return false;
        }

        $params['role'] = Constant::ROLE_LIST['STUDENT'];
        $params['password'] = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        $user = $this->create($params);

        try {
            $user->notify(new CreateAccount($params['email'], $params['password']));
        } catch (\Exception $e) {
            \Log::error('Failed to send account creation notification: ' . $e->getMessage());
            return false;
        }

        return true;
    }

    public function createTeacherUser($params)
    {
        if ($this->isValidEmailByRole($params['email'], Constant::ROLE_LIST['TEACHER'])) {
            return false;
        }

        $params['role'] = Constant::ROLE_LIST['TEACHER'];
        $params['password'] = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        $user = $this->create($params);

        try {
            $user->notify(new CreateAccount($params['email'], $params['password']));
        } catch (\Exception $e) {
            \Log::error('Failed to send account creation notification: ' . $e->getMessage());
            return false;
        }

        return true;
    }

    public function statisticalUserByRole()
    {
        return $this->getRepository()->statisticalUserByRole();
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
