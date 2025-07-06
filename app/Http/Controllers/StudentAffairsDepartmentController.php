<?php

namespace App\Http\Controllers;

use App\Exports\AttendancesExport;
use App\Exports\StudentConductScoresExport;
use App\Helpers\Constant;
use App\Http\Requests\ClassSessionRegistrationRequest;
use App\Http\Requests\SemesterRequest;
use App\Imports\LecturerImportByExcel;
use App\Imports\StudentImportByExcel;
use App\Notifications\DeleteAccount;
use App\Notifications\RestoreAccount;
use App\Services\AcademicWarningService;
use App\Services\AttendanceService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionReportService;
use App\Services\ClassSessionRequestService;
use App\Services\CohortService;
use App\Services\ConductEvaluationPeriodService;
use App\Services\ConductEvaluationPhaseService;
use App\Services\DepartmentService;
use App\Services\FacultyService;
use App\Services\LecturerService;
use App\Services\MajorService;
use App\Services\SemesterService;
use App\Services\StudentConductScoreService;
use App\Services\StudentService;
use App\Services\RoomService;
use App\Services\StudyClassService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        protected DepartmentService               $departmentService,
        protected FacultyService                  $facultyService,
        protected UserService                     $userService,
        protected CohortService                   $cohortService,
        protected StudyClassService               $studyClassService,
        protected ConductEvaluationPeriodService  $conductEvaluationPeriodService,
        protected ClassSessionReportService       $classSessionReportService,
        protected AttendanceService               $attendanceService,
        protected MajorService                    $majorService,
        protected AcademicWarningService          $academicWarningService,
        protected StudentConductScoreService      $studentConductScoreService,
        protected ConductEvaluationPhaseService $conductEvaluationPhaseService,
    )
    {
    }

    public function index()
    {
        $totalStudyClasses = $this->studyClassService->get()->count();
        $semester = $this->semesterService->get()->first() ?? null;
        $totalAcademicWarnings = $this->academicWarningService->academicWarningBySemesterId($semester->id ?? 0)->get()->count() ?? 0;
        $totalClassSessionReports = $this->classSessionReportService->countClassSessionReports($semester->id ?? 0);
        $getAllClassSession = $this->classSessionRequestService->getAllClassSession()->toArray();
        $countClassSession = $this->classSessionRequestService->countClassSession();

        $data = [
            'totalStudyClasses' => $totalStudyClasses,
            'totalAcademicWarnings' => $totalAcademicWarnings,
            'semester' => $semester,
            'totalClassSessionReports' => $totalClassSessionReports,
            'getAllClassSession' => $getAllClassSession,
            'countClassSession' => $countClassSession,
        ];

        return view('StudentAffairsDepartment.index', compact('data'));
    }

    public function classSession(Request $request)
    {
        $params = $request->all();
        $params['isEmptyRoom'] = true;
        $semesters = $this->semesterService->getFourSemester()->limit(4)->get();
        $checkClassSessionRegistration = $this->classSessionRegistrationService->checkClassSessionRegistrationPlus5days();
        $rooms = $this->roomService->get($params);
        $data = [
            'semesters' => $semesters,
            'checkClassSessionRegistration' => $checkClassSessionRegistration,
        ];
        if ($checkClassSessionRegistration) {
            $data['classSessionRegistration'] = $this->classSessionRegistrationService->getCSRSemesterInfo();
            $data['ListCSRs'] = $this->classSessionRegistrationService->getListCSR($params)->paginate(Constant::DEFAULT_LIMIT_12)->toArray();
            $data['rooms'] = $rooms;
        }

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
                ->with('success', 'Xét duyệt thành công');
        }

        return redirect()->route('student-affairs-department.class-session.index')
            ->with('success', 'Xét duyệt thành công');

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

    public function deleteClassSessionRegistration($id)
    {
        try {
            DB::beginTransaction();
            $this->classSessionRegistrationService->delete($id);
            $this->classSessionRequestService->deleteByClassSessionRegistrationId($id);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Xóa thất bại, vui lòng kiểm tra lại thông tin');
        }

        return redirect()->back()->with('success', 'Xóa thành công');
    }


    public function indexSemester(Request $request)
    {
        $params = $request->all();
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

        return redirect()->route('student-affairs-department.semester.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteSemester(Request $request, $id)
    {
        $params = $request->all();

        $this->semesterService->delete($id);

        $targetPage = $this->semesterService->targetPage($params);

        return redirect()->route('student-affairs-department.semester.index', $targetPage)->with('success', 'Xóa thành công');
    }

    public function account(Request $request)
    {
        $params = $request->all();
//        $params['relates'] = ['user', 'faculty'];
        $lecturers = $this->lecturerService->paginate($params)->toArray();
        $faculties = $this->facultyService->get()->toArray();
        $departments = $this->departmentService->get();
        $getAllWithTrashed = $this->lecturerService->getAllWithTrashed($params)->paginate(Constant::DEFAULT_LIMIT_12)->toArray();
        $data = [
            'lecturers' => $lecturers ?? [],
            'faculties' => $faculties ?? [],
            'departments' => $departments ?? [],
            'getAllWithTrashed' => $getAllWithTrashed ?? [],
        ];

        return view('StudentAffairsDepartment.account.index', compact('data'));
    }

    public function createAccount(Request $request)
    {
        $params = $request->all();
        if ($params['type'] == 0) {
            $studentUser = $this->userService->createStudentUser($params);
            if (!$studentUser) {
                return redirect()->back()->with('error', 'Email không hợp lệ, vui lòng kiểm tra lại thông tin email');
            }

            return redirect()->route('student-affairs-department.account.student')->with('success', 'Thêm Email thành công');
        }

        $lecturerUser = $this->userService->createTeacherUser($params);

        if (!$lecturerUser) {
            return redirect()->back()->with('error', 'Email không hợp lệ, vui lòng kiểm tra lại thông tin email');
        }

        return redirect()->route('student-affairs-department.account.index')->with('success', 'Thêm Email thành công');
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

        return redirect()->route('student-affairs-department.account.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteAccount(Request $request, $id)
    {
        $params = $request->all();
        $user = $this->userService->find($params['user_id']);
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng');
        }

        $deleteLecturer = $this->lecturerService->delete($id);
        $deleteUser = $this->userService->delete($params['user_id']);

        if (!$deleteLecturer || !$deleteUser) {
            return redirect()->back()->with('error', 'Xóa thất bại');
        }

        $user->notify(new DeleteAccount($user->email));
        $targetPage = $this->lecturerService->targetPage($params);

        return redirect()->route('student-affairs-department.account.index', $targetPage)->with('success', 'Xóa thành công');
    }

    public function restoreAccount(Request $request, $id)
    {
        $params = $request->all();

        $restoredLecturerCount = $this->lecturerService->restore($id);
        $restoredUserCount = $this->userService->restore($params['user_id']);
        if ($restoredLecturerCount === 0 || $restoredUserCount === 0) {
            return response()->json(['success' => false, 'message' => 'Khôi phục thất bại'], 400);
        }

        $user = $this->userService->find($params['user_id']);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng'], 404);
        }

        $user->notify(new RestoreAccount($user->email));
        $targetPage = $this->lecturerService->targetPage($params);

        return response()->json([
            'success' => true,
            'message' => 'Khôi phục tài khoản thành công!',
            'redirect' => route('student-affairs-department.account.index', $targetPage)
        ]);
    }

    public function accountStudent(Request $request)
    {
        $params = $request->all();
        $params['getAll'] = true;
        $students = $this->studentService->paginate($params)->toArray();
        $cohorts = $this->cohortService->get();
        $studyClasses = $this->studyClassService->get();
        $getAllWithTrashed = $this->studentService->getAllWithTrashed($params)->paginate(Constant::DEFAULT_LIMIT_12)->toArray();
        $data = [
            'students' => $students ?? [],
            'cohorts' => $cohorts ?? [],
            'studyClasses' => $studyClasses ?? [],
            'getAllWithTrashed' => $getAllWithTrashed ?? [],
        ];

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
        $user = $this->userService->find($params['user_id']);
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng');
        }

        $deleteStudent = $this->studentService->delete($id);
        $deleteUser = $this->userService->delete($params['user_id']);
        if (!$deleteStudent || !$deleteUser) {
            return redirect()->back()->with('error', 'Xóa thất bại');
        }

        $user->notify(new DeleteAccount($user->email));
        $targetPage = $this->studentService->targetPage($params);

        return redirect()->route('student-affairs-department.account.student', $targetPage)->with('success', 'Xóa thành công');
    }

    public function restoreAccountStudent(Request $request, $id)
    {
        $params = $request->all();

        $restoredStudentCount = $this->studentService->restore($id);
        $restoredUserCount = $this->userService->restore($params['user_id']);
        if ($restoredStudentCount === 0 || $restoredUserCount === 0) {
            return response()->json(['success' => false, 'message' => 'Khôi phục thất bại'], 400);
        }

        $user = $this->userService->find($params['user_id']);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng'], 404);
        }

        $user->notify(new RestoreAccount($user->email));
        $targetPage = $this->lecturerService->targetPage($params);

        return response()->json([
            'success' => true,
            'message' => 'Khôi phục tài khoản thành công!',
            'redirect' => route('student-affairs-department.account.index', $targetPage)
        ]);
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
        $this->roomService->create($params);

        return redirect()->route('student-affairs-department.room.index')->with('success', 'Thêm mới thành công');
    }

    public function editRoom(Request $request, $id)
    {
        $params = $request->all();

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
        $params['limit'] = Constant::DEFAULT_LIMIT_12;
        $studyClasses = $this->studyClassService->paginate($params)->toArray();
        $totalStudents = $this->studentService->get()->count();
        $majors = $this->majorService->get()->toArray();
        $cohorts = $this->cohortService->get()->toArray();
        $lecturers = $this->lecturerService->get()->toArray();
        $totalDepartments = $this->departmentService->get()->count();
        $data = [
            'studyClasses' => $studyClasses,
            'totalStudents' => $totalStudents,
            'majors' => $majors,
            'cohorts' => $cohorts,
            'lecturers' => $lecturers,
            'totalDepartments' => $totalDepartments,
        ];

        return view('StudentAffairsDepartment.class.index', compact('data'));
    }

    public function createClass(Request $request)
    {
        $params = $request->all();
        $params['name'] = strtoupper($params['name']);

        $this->studyClassService->create($params);

        return redirect()->route('student-affairs-department.class.index')->with('success', 'Thêm mới thành công');
    }

    public function editClass(Request $request, $id)
    {
        $params = $request->all();
        $params['name'] = strtoupper($params['name']);

        $this->studyClassService->update($id, $params);

        $targetPage = $this->studyClassService->targetPage($params);

        return redirect()->route('student-affairs-department.class.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteClass(Request $request, $id)
    {
        $params = $request->all();

        $this->studyClassService->delete($id);

        $targetPage = $this->studyClassService->targetPage($params);

        return redirect()->route('student-affairs-department.class.index', $targetPage)->with('success', 'Xóa thành công');
    }

    public function indexConductScore(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Constant::DEFAULT_LIMIT_12;
        $ConductEvaluationPeriods = $this->conductEvaluationPeriodService->paginate($params)->toArray();
        $semesters = $this->semesterService->getFourSemester()->limit(4)->get()->toArray();
        $data = [
            'ConductEvaluationPeriods' => $ConductEvaluationPeriods,
            'semesters' => $semesters,
        ];

        return view('StudentAffairsDepartment.conductScore.index', compact('data'));
    }

    public function createConductScore(Request $request)
    {
        $params = $request->all();
        $conduct_evaluation_period = $this->conductEvaluationPeriodService->create($params);
        $conduct_evaluation_period_id = $conduct_evaluation_period->id;
        if (!$conduct_evaluation_period) {
            return redirect()->back()->with('error', 'Thêm mới thất bại, vui lòng kiểm tra lại thông tin');
        }
        $phases = $params['phases'];
        $phases = array_map(function ($phase) use ($conduct_evaluation_period_id) {
            $phase['conduct_evaluation_period_id'] = $conduct_evaluation_period_id;
            $phase['created_at'] = now();
            return $phase;
        }, $phases);
        $conductEvaluationPhases = $this->conductEvaluationPhaseService->insert($phases);
        if (!$conductEvaluationPhases) {
            return redirect()->back()->with('error', 'Thêm mới thất bại, vui lòng kiểm tra lại thông tin');
        }

        return redirect()->route('student-affairs-department.conduct-score.index')->with('success', 'Thêm mới thành công');
    }

    public function editConductScore(Request $request, $id)
    {
        $params = $request->all();
        $conduct_evaluation_period = $this->conductEvaluationPeriodService->update($id, $params);
        if (!$conduct_evaluation_period) {
            return redirect()->back()->with('error', 'Cập nhật thất bại, vui lòng kiểm tra lại thông tin');
        }
        $phases = $params['phases'];

        $conductEvaluationPhases = $this->conductEvaluationPhaseService->arrayUpdates($phases, $id);
        if (!$conductEvaluationPhases) {
            return redirect()->back()->with('error', 'Cập nhật thất bại, vui lòng kiểm tra lại thông tin');
        }

        $targetPage = $this->conductEvaluationPeriodService->targetPage($params);

        return redirect()->route('student-affairs-department.conduct-score.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteConductScore($id)
    {
        $conduct_evaluation_period_id = $this->conductEvaluationPeriodService->delete($id);
        if (!$conduct_evaluation_period_id) {
            return redirect()->back()->with('error', 'Xóa thất bại, vui lòng kiểm tra lại thông tin');
        }

        $conductEvaluationPhases = $this->conductEvaluationPhaseService->arrayDeleteByConductEvaluationPeriodId($id);
        if (!$conductEvaluationPhases) {
            return redirect()->back()->with('error', 'Xóa thất bại, vui lòng kiểm tra lại thông tin');
        }

        return redirect()->route('student-affairs-department.conduct-score.index')->with('success', 'Xóa thành công');
    }

    public function infoConductScore(Request $request)
    {
        $params = $request->all();
        $semesterId = $this->conductEvaluationPeriodService->find($params['conduct_evaluation_period_id'])->semester_id;
        $getStudyClassList = $this->studyClassService->getStudyClassListByConductEvaluationPeriodId($params)->toArray();
        $majors = $this->majorService->get()->toArray();
        $cohorts = $this->cohortService->get()->toArray();
        $departments = $this->departmentService->get()->toArray();

        $data = [
            'getStudyClassList' => $getStudyClassList,
            'majors' => $majors,
            'cohorts' => $cohorts,
            'departments' => $departments,
            'semesterId' => $semesterId,
        ];

        return view('StudentAffairsDepartment.conductScore.list', compact('data'));
    }

    public function exportConductScore(Request $request)
    {
        $semesterId = $request->query('semester_id');
        $studyClassId = $request->query('study_class_id');
        $studyClassName = $request->query('study_class_name');

        try {
            $fileName = $studyClassName. '_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new StudentConductScoresExport($studyClassId, $semesterId), $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['export_error' => 'Xuất file thất bại: ' . $e->getMessage()]);
        }
    }

    public function indexAcademicWarning(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Constant::DEFAULT_LIMIT_12;
        $params['getAll'] = true;
        $students = $this->studentService->get($params)->toArray();
        $getSemesters = $this->semesterService->get()->toArray();
        $academicWarnings = $this->academicWarningService->listStudyClassAcademicWarning($params)->toArray();
        $data = [
            'academicWarnings' => $academicWarnings,
            'getSemesters' => $getSemesters,
            'students' => $students,
        ];

        return view('StudentAffairsDepartment.academicWarning.index', compact('data'));
    }

    public function createAcademicWarning(Request $request)
    {
        $params = $request->all();

        $this->academicWarningService->create($params);

        return redirect()->route('student-affairs-department.academic-warning.index')->with('success', 'Thêm mới thành công');
    }

    public function editAcademicWarning(Request $request, $id)
    {
        $params = $request->all();

        $this->academicWarningService->update($id, $params);

        $targetPage = $this->academicWarningService->targetPage($params);

        return redirect()->route('student-affairs-department.academic-warning.index', $targetPage)->with('success', 'Cập nhật thành công');
    }

    public function deleteAcademicWarning(Request $request, $id)
    {
        $params = $request->all();

        $this->academicWarningService->delete($id);

        $targetPage = $this->academicWarningService->targetPage($params);

        return redirect()->route('student-affairs-department.academic-warning.index', $targetPage)->with('success', 'Xóa thành công');
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

        return view('StudentAffairsDepartment.classSession.listReports', compact('data'));
    }

    public function exportReport($classRequestId, $studyClassId)
    {
        $studyClass = $this->studyClassService->find($studyClassId);

        if (!$studyClassId || !$classRequestId) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin lớp học hoặc yêu cầu điểm danh.');
        }

        return Excel::download(new AttendancesExport($classRequestId, $studyClassId), 'attendance_' . $studyClass->name . '_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx');
    }

    public function indexStatistical()
    {
        $statisticalClassByDepartment = $this->studyClassService->statisticalClassByDepartment()->toArray();
        $statisticalUserByRole = $this->userService->statisticalUserByRole()->toArray();
        $staticalAcademicWarningBySemester = $this->semesterService->staticalAcademicWarningBySemester()->toArray();
        $getAllClassSessionRequestsDone = $this->studyClassService->getAllStatisticsByStudyClass()->get()->toArray();

        $data = [
            'statisticalClassByDepartment' => $statisticalClassByDepartment,
            'statisticalUserByRole' => $statisticalUserByRole,
            'staticalAcademicWarningBySemester' => $staticalAcademicWarningBySemester,
            'getAllClassSessionRequestsDone' => $getAllClassSessionRequestsDone,
        ];

        return view('StudentAffairsDepartment.statistical.index', compact('data'));
    }

    public function exportAttendance(Request $request)
    {
        $classRequestId = $request->query('class_request_id');
        $studyClassId = $request->query('study_class_id');
        $studyClassName = $request->query('study_class_name');
        try {
            $fileName = $studyClassName. '_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new AttendancesExport($classRequestId, $studyClassId), $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['export_error' => 'Xuất file thất bại: ' . $e->getMessage()]);
        }
    }
}
