<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentAffairsDepartmentController extends Controller
{
    public function index()
    {
        return view('StudentAffairsDepartment.index');
    }

    public function account() {
        return view('StudentAffairsDepartment.account.index');
    }

    public function classSession() {
        return view('StudentAffairsDepartment.classSession.index');
    }

    public function history() {
        return view('StudentAffairsDepartment.classSession.history');
    }
}
