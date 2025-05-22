<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassSessionRegistrationRequest;
use App\Http\Requests\SemesterRequest;
use App\Imports\LecturerImportByExcel;
use App\Imports\StudentImportByExcel;
use App\Services\ClassSessionRegistrationService;
use App\Services\LecturerService;
use App\Services\SemesterService;
use App\Services\StudentService;
use App\Services\RoomService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentAffairsDepartmentController extends Controller
{
    public function __construct(
        protected SemesterService $semesterService,
        protected ClassSessionRegistrationService $classSessionRegistrationService,
        protected StudentService $studentService,
        protected LecturerService $lecturerService,
        protected RoomService $roomService,
    )
    {
    }

    public function index()
    {
        return view('StudentAffairsDepartment.index');
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

    public function account(Request $request)
    {
        $params = $request->all();
        $params['relates'] = ['user', 'faculty'];
        // $students = $this->studentService->paginate($params)->toArray();
        $lecturers = $this->lecturerService->paginate($params)->toArray();
        // $data = [
        //     'students' => $students ?? [],
        //     'lecturers' => $lecturers ?? [],
        // ];
        // dd($data['lecturers']);

        return view('StudentAffairsDepartment.account.index', compact('lecturers'));
    }

    public function accountStudent(Request $request)
    {
        $params = $request->all();
        $params['relates'] = ['cohort', 'user', 'studyClass'];
        $students = $this->studentService->paginate($params)->toArray();
        // $data = [
        //     'students' => $students ?? [],
        // ];
        // dd($data['students']);

        return view('StudentAffairsDepartment.account.student', compact('students'));
    }

    public function lecturerImportByExcel(Request $request)
    {
        $file = $request->file('teacherExcelFile');
        if (!$file) {
            return redirect()->back()->with('error', 'Không có file được gửi lên');
        }

        try {
            Excel::import(new LecturerImportByExcel(), $file);
            return redirect()->back()->with('success', 'Import thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import thất bại, vui lòng kiểm tra lại file excel.');
        }
    }

    public function studentImportByExcel(Request $request)
    {
        $file = $request->file('studentExcelFile');
        if (!$file) {
            return redirect()->back()->with('error', 'Không có file được gửi lên');
        }

        try {
            Excel::import(new StudentImportByExcel(), $file);
            return redirect()->back()->with('success', 'Import thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import thất bại, vui lòng kiểm tra lại file excel.');
        }
    }

    public function indexRoom()
    {
        $rooms = $this->roomService->paginate()->toArray();
//        dd($rooms);

        return view('StudentAffairsDepartment.room.index', compact('rooms'));
    }
}
