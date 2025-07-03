<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    use VerifiesEmails;

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

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
