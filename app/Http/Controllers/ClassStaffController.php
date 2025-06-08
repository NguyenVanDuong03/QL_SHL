<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Services\AttendanceService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionRequestService;
use App\Services\CohortService;
use App\Services\ConductEvaluationPeriodService;
use App\Services\DepartmentService;
use App\Services\FacultyService;
use App\Services\LecturerService;
use App\Services\RoomService;
use App\Services\SemesterService;
use App\Services\StudentService;
use App\Services\StudyClassService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ClassStaffController extends Controller
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
        protected AttendanceService                $attendanceService
    )
    {
    }
    public function index()
    {
        $params = request()->all();
        $getCurrentSemester = $this->classSessionRegistrationService->getCurrentSemester();
        $params['class_session_registration_id'] = $getCurrentSemester->id ?? null;
        $params['study_class_id'] = auth()->user()->student?->studyClass?->id ?? null;
        $params['student_id'] = auth()->user()->student?->id ?? null;
        $params['class_session_request_id'] = $this->classSessionRequestService->getClassSessionRequestBySclIdAndCsrId($params);
        $classSessionRequests = $this->classSessionRequestService->ClassSessionRequests($params);
        $attendanceStatus = $classSessionRequests->first()->attendances->first() ?? null;

        $data = [
            'classSessionRequests' => $classSessionRequests,
//            'getCurrentSemester' => $getCurrentSemester,
            'attendanceStatus' => $attendanceStatus,
        ];

        return view('classStaff.index', compact('data'));
    }

    public function indexClassSession(Request $request)
    {
        $params = $request->all();
//        $params['myClassSessionRequests'] = true;
//        $classSessionRequests = $this->classSessionRequestService->paginate($params);
//        $data = [
//            'classSessionRequests' => $classSessionRequests,
//        ];
        return view('classStaff.classSession.index');
    }

    public function detailClassSession(Request $request)
    {
        $params = $request->all();
//        $studyClassId = $request->query('study-class-id');
//        $sessionRequestId = $request->query('session-request-id');
        $infoClassRequestbyId = $this->classSessionRequestService->find($params['session-request-id']);
        $params['class_session_registration_id'] = $infoClassRequestbyId->class_session_registration_id ?? null;
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $getStudyClassByIds = $this->studyClassService->find($params['study-class-id']);
        $students = $this->studentService->getStudentsByClassId($params);
        $getTotalStudentsByClass = $this->studentService->getTotalStudentsByClass($params);
        $getAttendanceStatusSummary = $this->studentService->getAttendanceStatusSummary($params);
        $getAttendanceStudent = $this->attendanceService->getAttendanceStudent($params);
//        dd($getAttendanceStudent);
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'getStudyClassByIds' => $getStudyClassByIds,
            'students' => $students,
            'getTotalStudentsByClass' => $getTotalStudentsByClass,
            'getAttendanceStatusSummary' => $getAttendanceStatusSummary,
            'getAttendanceStudent' => $getAttendanceStudent,
        ];
        $data['getClassSessionRequest'] = null;
        if ($params['session-request-id']) {
            $getClassSessionRequest = $this->classSessionRequestService->find($params['session-request-id']);
            $rooms = $this->roomService->get();

            $data['getClassSessionRequest'] = $getClassSessionRequest;
            $data['rooms'] = $rooms;
        }

        return view('classStaff.classSession.detail', compact('data'));
    }

    public function confirmAttendance(Request $request)
    {
        $params = $request->all();
        $params['student_id'] = auth()->user()->student?->id;
        $params['reason'] = null;
//        dd($params);

        $attendance = $this->attendanceService->confirmAttendance($params);

        if (!$attendance) {
            return redirect()->back()->with('error', 'Xác nhận tham gia thất bại');
        }

        // Logic to confirm attendance
        // ...

        return redirect()->back()->with('success', 'Xác nhận tham gia thành công');
    }

    public function updateAbsence(Request $request)
    {
        $params = $request->all();
        $params['student_id'] = auth()->user()->student?->id;
//        dd($params);
        $attendance = $this->attendanceService->updateAbsence($params);

        if (!$attendance) {
            return redirect()->back()->with('error', 'Cập nhật vắng mặt thất bại');
        }

        return redirect()->back()->with('success', 'Cập nhật vắng mặt thành công');
    }
}
