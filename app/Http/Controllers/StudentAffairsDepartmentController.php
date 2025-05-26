<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassSessionRegistrationRequest;
use App\Http\Requests\SemesterRequest;
use App\Imports\LecturerImportByExcel;
use App\Imports\StudentImportByExcel;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionRequestService;
use App\Services\DepartmentService;
use App\Services\FacultyService;
use App\Services\LecturerService;
use App\Services\SemesterService;
use App\Services\StudentService;
use App\Services\RoomService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentAffairsDepartmentController extends Controller
{
    public function __construct(
        protected SemesterService $semesterService,
        protected ClassSessionRegistrationService $classSessionRegistrationService,
        protected ClassSessionRequestService $classSessionRequestService,
        protected StudentService $studentService,
        protected LecturerService $lecturerService,
        protected RoomService $roomService,
        protected DepartmentService $titleService,
        protected FacultyService $facultyService,
        protected UserService $userService
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
        $flexibleClassActivities = $this->classSessionRequestService->paginate([
            'flexibleClassActivities' => true,
            'relates' => ['lecturer', 'studyClass', 'room']
        ])->toArray();
        // dd($flexibleClassActivities);
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
        $params['current_page'] ?? 1;
        $targetPage = $this->semesterService->targetPage($params['current_page']);

        return redirect()->route('student-affairs-department.semester.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteSemester(Request $request, $id)
    {
        $params = $request->all();
        $this->semesterService->delete($id);
        $params['current_page'] ?? 1;
        $targetPage = $this->semesterService->targetPage($params['current_page']);
        // dd($targetPage);

        return redirect()->route('student-affairs-department.semester.index', $targetPage)->with('success', 'Xóa thành công');
    }

    public function account(Request $request)
    {
        $params = $request->all();
        $params['relates'] = ['user', 'faculty'];
        $lecturers = $this->lecturerService->paginate($params)->toArray();
        $faculties = $this->facultyService->get()->toArray();
        $departments = $this->titleService->get();
        // if ($request->ajax()) {
        //     return response()->json([
        //         'lecturers' => $lecturers['data'] ?? [],
        //         'faculties' => $faculties,
        //         'departments' => $departments,
        //         'current_page' => $lecturers['current_page'] ?? 1,
        //     ]);
        // }
        $data = [
            'lecturers' => $lecturers ?? [],
            'faculties' => $faculties ?? [],
            'departments' => $departments ?? [],
        ];
        // dd($data['lecturers']['data'][0]['user_id']);

        return view('StudentAffairsDepartment.account.index', compact('data'));
    }

    public function editAccount(Request $request, $id)
    {
        $params = $request->all();
        $params['current_page'] ?? 1;
        $updateLecturer = $this->lecturerService->update($id, $params);
        $updateuser = $this->userService->update($params['user_id'], $params);
        if (!$updateLecturer || !$updateuser) {
            return redirect()->back()->with('error', 'Cập nhật thất bại');
        }
        $targetPage = $this->lecturerService->targetPage($params['current_page']);

        return redirect()->route('student-affairs-department.account.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteAccount(Request $request, $id)
    {
        $params = $request->all();
        $params['current_page'] ?? 1;
        $deleteLecturer = $this->lecturerService->delete($id);
        $deleteUser = $this->userService->delete($params['user_id']);
        if (!$deleteLecturer || !$deleteUser) {
            return redirect()->back()->with('error', 'Xóa thất bại');
        }
        $targetPage = $this->lecturerService->targetPage($params['current_page']);

        return redirect()->route('student-affairs-department.account.index', $targetPage)->with('success', 'Xóa thành công');
    }

    public function accountStudent(Request $request)
    {
        $params = $request->all();
        $params['relates'] = ['cohort', 'user', 'studyClass'];
        $students = $this->studentService->paginate($params)->toArray();
        $faculties = $this->facultyService->get()->toArray();
        $departments = $this->titleService->get();
        $data = [
            'students' => $students ?? [],
        ];
//         dd($data['students']);

        return view('StudentAffairsDepartment.account.student', compact('data'));
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

    public function indexRoom(Request $request)
    {
        $params = $request->all();
        $rooms = $this->roomService->paginate($params)->toArray();
        if ($request->ajax()) {
            return response()->json([
                'data' => $rooms['data'] ?? [],
            ]);
        }

        return view('StudentAffairsDepartment.room.index', compact('rooms'));
    }

    public function createRoom(Request $request)
    {
        $params = $request->all();
        // dd($params);
        $this->roomService->create($params);

        return redirect()->route('student-affairs-department.room.index')->with('success', 'Thêm mới thành công');
    }

    public function editRoom(Request $request, $id)
    {
        $params = $request->all();
        $params['current_page'] ?? 1;
        $update = $this->roomService->update($id, $params);
        if (!$update) {
            return redirect()->back()->with('error', 'Cập nhật thất bại');
        }
        $tagetpage = $this->roomService->targetPage($params['current_page']);

        return redirect()->route('student-affairs-department.room.index', $tagetpage)->with('success', 'Cập nhật thành công');
    }

    public function deleteRoom(Request $request, $id)
    {
        $params = $request->all();
        $params['current_page'] ?? 1;
        $this->roomService->delete($id);
        $targetPage = $this->roomService->targetPage($params['current_page']);

        return redirect()->route('student-affairs-department.room.index', $targetPage)->with('success', 'Xóa thành công');
    }
}
