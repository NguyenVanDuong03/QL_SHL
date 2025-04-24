<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    )
    {
    }

    public function index()
    {
    //     if (auth()->user()->role == 0) {
    //         return redirect()->route('teacher.index');
    //     } else if (auth()->user()->role == 1) {
    //         return redirect()->route('student-affairs-department.index');
    //     } else if (auth()->user()->role == 2) {
    //         return redirect()->route('class-staff.index');
    //     }

    //     return redirect()->route('student.index');
    }
}
