<?php

namespace App\Http\Controllers;

use App\Services\SemesterService;
use Illuminate\Http\Request;


class StudentAffairsDepartmentController extends Controller
{
        public function __construct(
        protected SemesterService $semesterService,
    )
    {
    }

    public function index()
    {
        return view('StudentAffairsDepartment.index');
    }

    public function account() {
        return view('StudentAffairsDepartment.account.index');
    }

    public function classSession(Request $request) {
        $params = $request->all();
        $params['type'] = 'ClassSessionRegistration';
        $semesters = $this->semesterService->get($params);

        return view('StudentAffairsDepartment.classSession.index', compact('semesters'));
    }

    public function history() {
        return view('StudentAffairsDepartment.classSession.history');
    }

    public function create() {
        return view('StudentAffairsDepartment.classSession.create');
    }

}
