<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Services\AttendanceService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionReportService;
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
use http\Env\Response;
use Illuminate\Http\Request;

class ClassStaffController extends Controller
{
    public function __construct(
        protected SemesterService                 $semesterService,
        protected ClassSessionRegistrationService $classSessionRegistrationService,
        protected ClassSessionRequestService      $classSessionRequestService,
        protected StudentService                 $studentService,
        protected LecturerService                $lecturerService,
        protected RoomService                    $roomService,
        protected DepartmentService              $titleService,
        protected FacultyService                 $facultyService,
        protected UserService                    $userService,
        protected CohortService                  $cohortService,
        protected StudyClassService              $studyClassService,
        protected ConductEvaluationPeriodService $conductEvaluationPeriodService,
        protected AttendanceService              $attendanceService,
        protected ClassSessionReportService      $classSessionReport,
    )
    {
    }
    public function index()
    {
        $params = request()->all();
        $getCurrentSemester = $this->classSessionRegistrationService->getCurrentSemester();
//        $params['class_session_registration_id'] = $getCurrentSemester->id ?? null;
        $params['study_class_id'] = auth()->user()->student?->studyClass?->id ?? null;
        $params['student_id'] = auth()->user()->student?->id ?? null;
        $classSessionRequests = $this->classSessionRequestService->ClassSessionRequests($params)->limit(Constant::DEFAULT_LIMIT)->get();
        $attendanceStatus = $classSessionRequests->first()?->attendances->first() ?? null;

        $data = [
            'classSessionRequests' => $classSessionRequests,
            'attendanceStatus' => $attendanceStatus,
        ];

        return view('classStaff.index', compact('data'));
    }

    public function indexClassSession(Request $request)
    {
        $params = $request->all();
        $params['study_class_id'] = auth()->user()->student?->studyClass?->id ?? null;
        $params['student_id'] = auth()->user()->student?->id ?? null;
        $classSessionRequests = $this->classSessionRequestService->ClassSessionRequests($params)->paginate(Constant::DEFAULT_LIMIT_12)->toArray();
        $attendanceStatus = $classSessionRequests['data'][0]['attendances'][0]['status'] ?? null;
//        dd($attendanceStatus);
        $data = [
            'classSessionRequests' => $classSessionRequests,
            'attendanceStatus' => $attendanceStatus,
        ];

        return view('classStaff.classSession.index', compact('data'));
    }

    public function history(Request $request)
    {
        $params = $request->all();
        $params['study_class_id'] = auth()->user()->student?->studyClass?->id ?? null;
        $params['student_id'] = auth()->user()->student?->id ?? null;
        $classSessionRequests = $this->classSessionRequestService->getClassSessionRequestsDone($params)->paginate(Constant::DEFAULT_LIMIT_12)->toArray();
        $data = [
            'classSessionRequests' => $classSessionRequests,
        ];
//        dd($data['classSessionRequests']['data']);

        return view('classStaff.classSession.history', compact('data'));
    }

    public function detailClassSession(Request $request)
    {
        $params = $request->all();
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

        $attendance = $this->attendanceService->confirmAttendance($params);

        return response()->json([
            'status' => 'success',
            'message' => 'Xác nhận tham gia thành công',
        ], 200); // Mã HTTP 200
    }

    public function updateAbsence(Request $request)
    {
        $params = $request->all();
        $params['student_id'] = auth()->user()->student?->id;
//        dd($params);
        $attendance = $this->attendanceService->updateAbsence($params);

        return response()->json([
            'status' => 'success',
            'message' => 'Gửi lý do vắng mặt thành công',
        ], 200);
    }

    public function report(Request $request)
    {
        $params = $request->all();
//        dd($params);
        $countAttendanceByClassSessionRequestId = $this->attendanceService->countAttendanceByClassSessionRequestId($params['class_session_request_id']);

        $data = [
            'report' => null,
            'countAttendanceByClassSessionRequestId' => $countAttendanceByClassSessionRequestId,
        ];
        if (isset($params['report_id'])) {
            $report = $this->classSessionReport->find($params['report_id']) ?? null;
            $report->path = $report->path ? asset('storage/' . $report->path) : null;
            $data['report'] = $report;
        }
//        dd($data['countAttendanceByClassSessionRequestId']);

        return view('classStaff.classSession.report', compact('data'));
    }

    public function storeReport(Request $request)
    {
        $params = $request->all();
//        dd($params);

        $classSessionReport = $this->classSessionReport->storeReport($params);

        return response()->json([
//            'status' => 'success',
            'message' => 'Báo cáo đã được gửi thành công',
            'data' => $classSessionReport,
        ], 200);
    }

    public function updateReport(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
//        dd($params, $id);

        $classSessionReport = $this->classSessionReport->updateReport($params);

        return response()->json([
            'status' => 'success',
            'message' => 'Báo cáo đã được cập nhật thành công',
            'data' => $classSessionReport,
        ], 200);
    }

    public function deleteReport($id)
    {
        $this->classSessionReport->deleteReport($id);

        return response()->json([
//            'status' => 'success',
            'message' => 'Báo cáo đã được xóa thành công',
        ], 200);
    }
}
