<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassSessionRegistrationRequest;
use App\Http\Requests\SemesterRequest;
use App\Services\ClassSessionRegistrationService;
use App\Services\SemesterService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentAffairsDepartmentController extends Controller
{
    public function __construct(
        protected SemesterService $semesterService,
        protected ClassSessionRegistrationService $classSessionRegistrationService,
        )
        {
        }

    public function index()
    {
        return view('StudentAffairsDepartment.index');
    }

    public function account()
    {
        return view('StudentAffairsDepartment.account.index');
    }

    public function classSession(Request $request)
    {
        $params = $request->all();
        $semesters = $this->semesterService->getFourSemester();
        $checkClassSessionRegistration = $this->classSessionRegistrationService->checkClassSessionRegistration();
        $data = [
            'semesters' => $semesters,
            'checkClassSessionRegistration' => $checkClassSessionRegistration,
        ];
        if ($checkClassSessionRegistration) {
            $data['classSessionRegistration'] = $this->classSessionRegistrationService->getCSRSemesterInfo();
            $data['ListCSRs'] = $this->classSessionRegistrationService->getListCSR();
        }

        return view('StudentAffairsDepartment.classSession.index', compact('data'));
    }

    public function history()
    {
        $data = $this->classSessionRegistrationService->getListCSRHistory()->toArray();

        return view('StudentAffairsDepartment.classSession.history', compact('data'));
    }

    public function createClassSessionRegistration(ClassSessionRegistrationRequest $request)
    {
        $params = $request->all();
        $this->classSessionRegistrationService->create($params);

        return redirect()->route('student-affairs-department.class-session.index')->with('success', 'Thêm mới thành công');
    }

    public function editClassSessionRegistration(ClassSessionRegistrationRequest $request, $id)
    {
        $params = $request->all();
        $this->classSessionRegistrationService->update($id, $params);

        return redirect()->route('student-affairs-department.class-session.index')->with('success', 'Cập nhật thành công');
    }

    public function indexSemester(Request $request)
    {
        $params = $request->all();
        $semesters = $this->semesterService->paginate($params)->toArray();
        if ($request->ajax()) {
            return response()->json([
                'data' => $semesters['data'] ?? [],
                'current_page' => $semesters['current_page'] ?? 1,
                'last_page' => $semesters['last_page'] ?? 1,
            ]);
        }
        $data = [
            'semesters' => $semesters,
        ];
        // dd($data['semesters']);
        return view('StudentAffairsDepartment.semester.index', compact('data'));
    }

    public function createSemester(SemesterRequest $request)
    {
        $params = $request->all();
        $this->semesterService->create($params);

        return redirect()->route('student-affairs-department.semester.index')->with('success', 'Thêm mới thành công');
    }

    public function editSemester(SemesterRequest $request, $id)
    {
        $params = $request->all();
        $this->semesterService->update($id, $params);

        return redirect()->route('student-affairs-department.semester.index')->with('success', 'Cập nhật thành công');
    }

    public function deleteSemester($id)
    {
        // dd($id);
        $this->semesterService->delete($id);

        return redirect()->route('student-affairs-department.semester.index')->with('success', 'Xóa thành công');
    }
}
