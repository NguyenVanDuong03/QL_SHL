<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->role == Constant::ROLE_LIST['TEACHER'])
            return route('teacher.index');
        else if ($user->role == Constant::ROLE_LIST['STUDENT_AFFAIRS_DEPARTMENT'])
            return route('student-affairs-department.index');
        else if ($user->role == Constant::ROLE_LIST['FACULTY_OFFICE'])
            return route('faculty-office.index');

        return route('student.index');
    }
}
