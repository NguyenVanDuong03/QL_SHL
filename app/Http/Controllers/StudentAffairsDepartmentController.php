<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Http\Requests\ClassSessionRegistrationRequest;
use App\Http\Requests\SemesterRequest;
use App\Imports\LecturerImportByExcel;
use App\Imports\StudentImportByExcel;
use App\Models\StudyClass;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionRequestService;
use App\Services\CohortService;
use App\Services\ConductEvaluationPeriodService;
use App\Services\DepartmentService;
use App\Services\FacultyService;
use App\Services\LecturerService;
use App\Services\SemesterService;
use App\Services\StudentService;
use App\Services\RoomService;
use App\Services\StudyClassService;
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
        protected UserService $userService,
        protected CohortService $cohortService,
        protected StudyClassService $studyClassService,
        protected ConductEvaluationPeriodService $conductEvaluationPeriodService,
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
        $params['isEmptyRoom'] = true;
        $semesters = $this->semesterService->getFourSemester();
        $checkClassSessionRegistration = $this->classSessionRegistrationService->checkClassSessionRegistration();
        $rooms = $this->roomService->get($params);
        $data = [
            'semesters' => $semesters,
            'checkClassSessionRegistration' => $checkClassSessionRegistration,
        ];
        if ($checkClassSessionRegistration) {
            $data['classSessionRegistration'] = $this->classSessionRegistrationService->getCSRSemesterInfo();
            $data['ListCSRs'] = $this->classSessionRegistrationService->getListCSR()->toArray();
            $data['rooms'] = $rooms;
        }
//        dd($data['ListCSRs']);

        return view('StudentAffairsDepartment.classSession.index', compact('data'));
    }

    public function comfirmClassSession(Request $request, $id)
    {
        $params = $request->all();
        if ($params['position'] != Constant::CLASS_SESSION_POSITION['OFFLINE']) {
            $params['room_id'] = null;
        } else {
            $this->roomService->update($params['room_id'], [
                'status' => Constant::ROOM_STATUS['UNAVAILABLE'],
            ]);
        }

        if ($params['rejection_reason']) {
            $params['status'] = Constant::CLASS_SESSION_STATUS['REJECTED'];
            $params['room_id'] = null;
        }
//         dd($params, $id);
        $this->classSessionRequestService->update($id, $params);

        return redirect()->route('student-affairs-department.class-session.index')->with('success', 'Xác nhận thành công');
    }

    public function history($class_session_registration_id)
    {
        $data = $this->classSessionRegistrationService->getListCSRHistory($class_session_registration_id)->toArray();
//        dd($semesterId);

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
        // dd($params);
        $semesters = $this->semesterService->paginate($params)->toArray();
        $data = [
            'semesters' => $semesters,
        ];

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

        $targetPage = $this->semesterService->targetPage($params);
        // dd($targetPage);

        return redirect()->route('student-affairs-department.semester.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteSemester(Request $request, $id)
    {
        $params = $request->all();

        $this->semesterService->delete($id);

        $targetPage = $this->semesterService->targetPage($params);
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
        $data = [
            'lecturers' => $lecturers ?? [],
            'faculties' => $faculties ?? [],
            'departments' => $departments ?? [],
        ];
        // dd($data['lecturers']['data']);

        return view('StudentAffairsDepartment.account.index', compact('data'));
    }

    public function editAccount(Request $request, $id)
    {
        $params = $request->all();

        $updateLecturer = $this->lecturerService->update($id, $params);
        $updateuser = $this->userService->update($params['user_id'], $params);

        if (!$updateLecturer || !$updateuser) {
            return redirect()->back()->with('error', 'Cập nhật thất bại');
        }
        $targetPage = $this->lecturerService->targetPage($params);
        // dd($params);

        return redirect()->route('student-affairs-department.account.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteAccount(Request $request, $id)
    {
        $params = $request->all();

        $deleteLecturer = $this->lecturerService->delete($id);
        $deleteUser = $this->userService->delete($params['user_id']);

        if (!$deleteLecturer || !$deleteUser) {
            return redirect()->back()->with('error', 'Xóa thất bại');
        }
        $targetPage = $this->lecturerService->targetPage($params);

        return redirect()->route('student-affairs-department.account.index', $targetPage)->with('success', 'Xóa thành công');
    }

    public function accountStudent(Request $request)
    {
        $params = $request->all();
        $params['getAll'] = true;
        $students = $this->studentService->paginate($params)->toArray();
        $cohorts = $this->cohortService->get();
        $studyClasses = $this->studyClassService->get();
        $data = [
            'students' => $students ?? [],
            'cohorts' => $cohorts ?? [],
            'studyClasses' => $studyClasses ?? [],
        ];
//         dd($data['students']);

        return view('StudentAffairsDepartment.account.student', compact('data'));
    }

    public function editAccountStudent(Request $request, $id)
    {
        $params = $request->all();

        $updateStudent = $this->studentService->update($id, $params);
        $updateUser = $this->userService->update($params['user_id'], $params);

        if (!$updateStudent || !$updateUser) {
            return redirect()->back()->with('error', 'Cập nhật thất bại');
        }
        $targetPage = $this->studentService->targetPage($params);

        return redirect()->route('student-affairs-department.account.student', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteAccountStudent(Request $request, $id)
    {
        $params = $request->all();

        $deleteStudent = $this->studentService->delete($id);
        $deleteUser = $this->userService->delete($params['user_id']);
        if (!$deleteStudent || !$deleteUser) {
            return redirect()->back()->with('error', 'Xóa thất bại');
        }
        $targetPage = $this->studentService->targetPage($params);

        return redirect()->route('student-affairs-department.account.student', $targetPage)->with('success', 'Xóa thành công');
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
        // dd($params['search']);

        $update = $this->roomService->update($id, $params);

        if (!$update) {
            return redirect()->back()->with('error', 'Cập nhật thất bại');
        }
        $tagetpage = $this->roomService->targetPage($params);

        return redirect()->route('student-affairs-department.room.index', $tagetpage)->with('success', 'Cập nhật thành công');
    }

    public function deleteRoom(Request $request, $id)
    {
        $params = $request->all();

        $this->roomService->delete($id);

        $targetPage = $this->roomService->targetPage($params);

        return redirect()->route('student-affairs-department.room.index', $targetPage)->with('success', 'Xóa thành công');
    }

    public function indexClass(Request $request)
    {
        $params = $request->all();
        $studyClasses = $this->studyClassService->paginate($params)->toArray();
        $data = [
            'studyClasses' => $studyClasses,
        ];

        return view('StudentAffairsDepartment.class.index', compact('data'));
    }

    public function indexConductScore(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Constant::DEFAULT_LIMIT_12;
        $ConductEvaluationPeriods = $this->conductEvaluationPeriodService->paginate($params)->toArray();
        $semesters = $this->semesterService->getFourSemester();
        $data = [
            'ConductEvaluationPeriods' => $ConductEvaluationPeriods,
            'semesters' => $semesters,
        ];
        // dd($data['ConductEvaluationPeriods']['data'][0]['semester']['name']);

        return view('StudentAffairsDepartment.conductScore.index', compact('data'));
    }

    public function createConductScore(Request $request)
    {
        $params = $request->all();
        // dd($params);
        $this->conductEvaluationPeriodService->create($params);

        return redirect()->route('student-affairs-department.conduct-score.index')->with('success', 'Thêm mới thành công');
    }

    public function infoConductScore(Request $request, $id)
    {
        $params = $request->all();

        return view('StudentAffairsDepartment.conductScore.list');
    }
}
