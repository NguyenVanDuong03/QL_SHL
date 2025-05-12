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
}
