<?php

namespace App\Http\Controllers;

use App\Exports\AttendancesExport;
use App\Helpers\Constant;
use App\Http\Requests\ClassSessionRegistrationRequest;
use App\Http\Requests\SemesterRequest;
use App\Imports\LecturerImportByExcel;
use App\Imports\StudentImportByExcel;
use App\Services\AttendanceService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionReportService;
use App\Services\ClassSessionRequestService;
use App\Services\CohortService;
use App\Services\ConductEvaluationPeriodService;
use App\Services\DepartmentService;
use App\Services\FacultyService;
use App\Services\LecturerService;
use App\Services\MajorService;
use App\Services\SemesterService;
use App\Services\StudentService;
use App\Services\RoomService;
use App\Services\StudyClassService;
use App\Services\UserService;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentAffairsDepartmentController extends Controller
{
    public function __construct(
        protected SemesterService                 $semesterService,
        protected ClassSessionRegistrationService $classSessionRegistrationService,
        protected ClassSessionRequestService      $classSessionRequestService,
        protected StudentService                  $studentService,
        protected LecturerService                 $lecturerService,
        protected RoomService                     $roomService,
        protected DepartmentService               $titleService,
        protected FacultyService                  $facultyService,
        protected UserService                     $userService,
        protected CohortService                   $cohortService,
        protected StudyClassService               $studyClassService,
        protected ConductEvaluationPeriodService  $conductEvaluationPeriodService,
        protected ClassSessionReportService       $classSessionReportService,
        protected AttendanceService               $attendanceService,
        protected MajorService                   $majorService,
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

    public function flexibleClassActivities(Request $request)
    {
        $params = $request->all();
        $params['isEmptyRoom'] = true;
        $rooms = $this->roomService->get($params);
        $data = [
            'ListCSRs' => $this->classSessionRequestService->getListFlexibleClass()->toArray(),
            'rooms' => $rooms,
        ];
//        dd($data['ListCSRs']);

        return view('StudentAffairsDepartment.classSession.flexibleClassActivities', compact('data'));
    }

    public function confirmClassSession(Request $request, $id)
    {
        $params = $request->all();

        if ($params['position'] != Constant::CLASS_SESSION_POSITION['OFFLINE']) {
            $params['room_id'] = null;
        } else {
            if (!empty($params['rejection_reason'])) {
                if (!empty($params['room_id'])) {
                    $this->roomService->update($params['room_id'], [
                        'status' => Constant::ROOM_STATUS['AVAILABLE'],
                    ]);
                }
                $params['room_id'] = null;
            } else {
                if (!empty($params['room_id'])) {
                    $this->roomService->update($params['room_id'], [
                        'status' => Constant::ROOM_STATUS['UNAVAILABLE'],
                    ]);
                }
            }
        }

        if (!empty($params['rejection_reason'])) {
            $params['status'] = Constant::CLASS_SESSION_STATUS['REJECTED'];
        }

        $this->classSessionRequestService->update($id, $params);

        if ($params['type'] == 1) {
            return redirect()->route('student-affairs-department.class-session.flexibleClassActivities')
                ->with('success', 'Xác nhận thành công');
        }

        return redirect()->route('student-affairs-department.class-session.index')
                ->with('success', 'Xác nhận thành công');

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
//         dd($data['ConductEvaluationPeriods']);

        return view('StudentAffairsDepartment.conductScore.index', compact('data'));
    }

    public function createConductScore(Request $request)
    {
        $params = $request->all();
        // dd($params);
        $this->conductEvaluationPeriodService->create($params);

        return redirect()->route('student-affairs-department.conduct-score.index')->with('success', 'Thêm mới thành công');
    }

    public function editConductScore(Request $request, $id)
    {
        $params = $request->all();
//         dd($params);
        $this->conductEvaluationPeriodService->update($id, $params);

        $targetPage = $this->conductEvaluationPeriodService->targetPage($params);

        return redirect()->route('student-affairs-department.conduct-score.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteConductScore(Request $request, $id)
    {

        $this->conductEvaluationPeriodService->delete($id);

        return redirect()->route('student-affairs-department.conduct-score.index')->with('success', 'Xóa thành công');
    }

    public function infoConductScore(Request $request, $id)
    {
        $params = $request->all();
        $params['conduct_evaluation_period_id'] = $id;
        $getStudyClassList = $this->studyClassService->getStudyClassListByConductEvaluationPeriodId($params)->toArray();

        $data = [
            'getStudyClassList' => $getStudyClassList,
        ];
//        dd($getStudyClassList);

        return view('StudentAffairsDepartment.conductScore.list', compact('data'));
    }

    public function indexAcademicWarning(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Constant::DEFAULT_LIMIT_12;
//        $academicWarnings = $this->studentService->getAcademicWarning($params)->toArray();
        $data = [
//            'academicWarnings' => $academicWarnings,
        ];

        return view('StudentAffairsDepartment.academicWarning.index', compact('data'));
    }

    public function listReports(Request $request)
    {
        $params = $request->all();
        $countStudyClass = $this->studyClassService->get()->count();
        $getSemesters = $this->semesterService->get()->toArray();
        $department['isDepartment'] = true;
        $getMajors = $this->majorService->get($department)->toArray();
        $reports = $this->classSessionReportService->getListReports($params)->toArray();
        $data = [
            'reports' => $reports,
            'countStudyClass' => $countStudyClass,
            'getSemesters' => $getSemesters,
            'getMajors' => $getMajors,
        ];
//        dd($data['reports']);

        return view('StudentAffairsDepartment.classSession.listReports', compact('data'));
    }

    public function exportReport($classRequestId, $studyClassId)
    {
//        $report = $this->attendanceService->exportAttendanceReport($classRequestId, $studyClassId);
        $studyClass = $this->studyClassService->find($studyClassId);

        if (!$studyClassId || !$classRequestId) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin lớp học hoặc yêu cầu điểm danh.');
        }

        return Excel::download(new AttendancesExport($classRequestId, $studyClassId), 'attendance_' . $studyClass->name . '_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx');

    }
}
