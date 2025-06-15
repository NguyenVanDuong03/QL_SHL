<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->role == Constant::ROLE_LIST['TEACHER'])
            return route('teacher.index');
        else if ($user->role == Constant::ROLE_LIST['STUDENT_AFFAIRS_DEPARTMENT'])
            return route('student-affairs-department.index');
        else if ($user->role == Constant::ROLE_LIST['CLASS_STAFF'])
            return route('class-staff.index');
        else if ($user->role == Constant::ROLE_LIST['FACULTY_OFFICE'])
            return route('faculty-office.index');

        return route('student.index');
    }
}
