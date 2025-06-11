<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Services\AcademicWarningService;
use App\Services\AttendanceService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionRequestService;
use App\Services\LecturerService;
use App\Services\RoomService;
use App\Services\SemesterService;
use App\Services\StudentService;
use App\Services\StudyClassService;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function __construct(
        protected ClassSessionRegistrationService $classSessionRegistrationService,
        protected ClassSessionRequestService $classSessionRequestService,
        protected StudyClassService $studyClassService,
        protected StudentService $studentService,
        protected RoomService $roomService,
        protected SemesterService $SemesterService,
        protected AcademicWarningService $academicWarningService,
        protected AttendanceService $attendanceService,
        protected SemesterService $semesterService,
        protected LecturerService $lecturerService,
        )
        {
        }
    public function index()
    {
        $lecturerId = auth()->user()->lecturer?->id;
        $totalClasses = $this->studyClassService->coutStudyClassListByLecturerId($lecturerId);
        $totalStudentWarning = $this->academicWarningService->getStudentWarningByStudyClassId($lecturerId)->count();
        $data = [
            'totalClasses' => $totalClasses,
            'totalStudentWarning' => $totalStudentWarning,
        ];
        // dd($data['totalStudentWarning']);

        return view('teacher.index', compact('data'));
    }

    public function indexClassSession()
    {

        $checkClassSessionRegistration = $this->classSessionRegistrationService->checkClassSessionRegistration();
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $getClassSessionRegistration = $this->classSessionRegistrationService->get()->first();
        $lecturerId = auth()->user()->lecturer?->id;
        $classSessionRegistrationId = $getClassSessionRegistration?->id;
        $countFlexibleClassSessionRequest = $this->classSessionRequestService->countFlexibleClassSessionRequest();
        $countFixeClassSessionRequest = $this->classSessionRequestService->countFixeClassSessionRequest($lecturerId, $classSessionRegistrationId);

        $data = [
            'checkClassSessionRegistration' => $checkClassSessionRegistration,
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'countFlexibleClassSessionRequest' => $countFlexibleClassSessionRequest,
            'countFixeClassSessionRequest' => $countFixeClassSessionRequest,
            'getClassSessionRegistration' => $getClassSessionRegistration,
        ];
//dd($data['countFixeClassSessionRequest']);
        return view('teacher.classSession.index', compact('data'));
    }

    public function indexClass()
    {
        $lecturerId = auth()->user()->lecturer?->id;
        $classes = $this->studyClassService->getStudyClassListByLecturerId($lecturerId)->paginate(Constant::DEFAULT_LIMIT_12)->toArray();
        $data = [
            'classes' => $classes,
        ];
        // dd($data['classes']);

        return view('teacher.class.index', compact('data'));
    }

    public function infoStudent(Request $request, $id)
    {
        $params = $request->all();
        $params['class_id'] = $id;
        $students = $this->studentService->getListStudentByClassId($params)->toArray();
        $getNoteStudentById = $this->studentService->getNoteStudentById($id)->toArray();
//        dd($students);
        $classInfo = $this->studyClassService->find($id);
        $data = [
            'students' => $students,
            'classInfo' => $classInfo,
            'getNoteStudentById' => $getNoteStudentById,
            'studyClassId' => $id,
        ];
//         dd($data['students']);

        return view('teacher.class.infoStudent', compact('data'));
    }

    public function saveNotes(Request $request, $id)
    {
        $params = $request->all();
//        dd($params);
        $this->studentService->update($id, [
            'note' => $params['note'],
        ]);

        return redirect()->back()->with('success', 'Lưu ghi chú thành công');
    }

    public function updateOfficers(Request $request)
    {
        $params = $request->all();
//dd($params);
        $this->studentService->updateOfficers($params);

        return redirect()->back()->with('success', 'Cập nhật cán bộ lớp thành công');
    }

    public function indexFixedClassActivitie(Request $request)
    {
        $params = $request->all();
        $lecturerId = auth()->user()->lecturer?->id;
        $getSemesterInfo = $this->SemesterService->get()->first();
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $params['lecturer_id'] = $lecturerId;
        $params['semester_id'] = $getSemesterInfo?->id;
        $getStudyClassByIds = $this->studyClassService->getStudyClassById($params)->toArray();
        $totalClasses = $this->studyClassService->coutStudyClassListByLecturerId($lecturerId);
        $countApprovedByLecturerAndSemester = $this->classSessionRequestService->countApprovedByLecturerAndSemester($lecturerId, $getSemesterInfo?->id);
        $countRejectedByLecturerAndSemester = $this->classSessionRequestService->countRejectedByLecturerAndSemester($lecturerId, $getSemesterInfo?->id);
        $checkClassSessionRegistration = $this->classSessionRegistrationService->checkClassSessionRegistration();
//        dd($getStudyClassByIds);
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'getStudyClassByIds' => $getStudyClassByIds,
            'totalClasses' => $totalClasses,
            'countApprovedByLecturerAndSemester' => $countApprovedByLecturerAndSemester,
            'countRejectedByLecturerAndSemester' => $countRejectedByLecturerAndSemester,
            'checkClassSessionRegistration' => $checkClassSessionRegistration,
        ];
//        dd($data['getStudyClassByIds']['total']);

        return view('teacher.classSession.fixedClassActivitie', compact('data'));
    }

    public function createClassSession(Request $request)
    {
        $studyClassId = $request->query('study-class-id');
        $sessionRequestId = $request->query('session-request-id');
//        dd($studyClassId, $sessionRequestId);
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $getStudyClassByIds = $this->studyClassService->find($studyClassId);
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'getStudyClassByIds' => $getStudyClassByIds,
        ];
        $data['getClassSessionRequest'] = null;
        if ($sessionRequestId) {
            $getClassSessionRequest = $this->classSessionRequestService->find($sessionRequestId);
            $rooms = $this->roomService->get();

            $data['getClassSessionRequest'] = $getClassSessionRequest;
            $data['rooms'] = $rooms;
        }
//        dd($data['getClassSessionRequest']);

        return view('teacher.classSession.create', compact('data'));
    }

    public function storeClassSession(Request $request)
    {
        $params = $request->all();
        $params['lecturer_id'] = auth()->user()->lecturer?->id;
        $params['type'] = Constant::CLASS_SESSION_TYPE['FIXED'];
        $classSessionRegistration = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $params['class_session_registration_id'] = $classSessionRegistration->id;
        if ($params['position'] == '2' || $params['position'] == '0')
            $params['meeting_type'] = null;
        if ($params['position'] != '0')
            $params['room_id'] = null;
//        dd($params);

        $this->classSessionRequestService->createOrUpdateByClassAndSemester($params);
        return redirect()->route('teacher.class-session.fixed-class-activitie')->with('success', 'Tạo yêu cầu thành công');
    }

    public function deleteClassSession(Request $request, $id)
    {
        $params = $request->all();
        $roomId = $params['room_id'] ?? null;
        if ($roomId) {
            $this->roomService->update($roomId, [
                'status' => Constant::ROOM_STATUS['AVAILABLE'],
            ]);
        }
        $this->classSessionRequestService->delete($id);

        return redirect()->back()->with('success', 'Xóa yêu cầu thành công');
    }

    public function detailClassSession(Request $request)
    {
        $studyClassId = $request->query('study-class-id');
        $sessionRequestId = $request->query('session-request-id');
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $getStudyClassByIds = $this->studyClassService->find($studyClassId);
//        $students = $this->studentService->getStudentsByClassId($studyClassId);
//        dd($students);
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'getStudyClassByIds' => $getStudyClassByIds,
        ];
        $data['getClassSessionRequest'] = null;
        if ($sessionRequestId != null) {
            $getClassSessionRequest = $this->classSessionRequestService->find($sessionRequestId);
            $rooms = $this->roomService->get();

            $data['getClassSessionRequest'] = $getClassSessionRequest;
            $data['rooms'] = $rooms;
//            dd($data['rooms']);
        }

//        dd($data['getClassSessionRequest']);
        return view('teacher.classSession.detail', compact('data'));
    }

    public function detailFixedClassActivitie(Request $request)
    {
        $params = $request->all();
        $lecturerId = auth()->user()->lecturer?->id;
        $getSemesterInfo = $this->SemesterService->get()->first();
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $params['lecturer_id'] = $lecturerId;
        $params['semester_id'] = $getSemesterInfo?->id;
        $getStudyClassByIds = $this->studyClassService->getStudyClassWithApprovedRequests($params)->toArray();
        $totalClasses = $this->studyClassService->coutStudyClassListByLecturerId($lecturerId);
        $countApprovedByLecturerAndSemester = $this->classSessionRequestService->countApprovedByLecturerAndSemester($lecturerId, $getSemesterInfo?->id);
        $countRejectedByLecturerAndSemester = $this->classSessionRequestService->countRejectedByLecturerAndSemester($lecturerId, $getSemesterInfo?->id);
        $checkClassSessionRegistration = $this->classSessionRegistrationService->checkClassSessionRegistration();
//        dd($getStudyClassByIds);
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'getStudyClassByIds' => $getStudyClassByIds,
            'totalClasses' => $totalClasses,
            'countApprovedByLecturerAndSemester' => $countApprovedByLecturerAndSemester,
            'countRejectedByLecturerAndSemester' => $countRejectedByLecturerAndSemester,
            'checkClassSessionRegistration' => $checkClassSessionRegistration,
        ];

        return view('teacher.classSession.detailFixedClassActivitie', compact('data'));
    }

    public function infoFixedClassActivitie(Request $request)
    {
        $params = $request->all();
//        $studyClassId = $request->query('study-class-id');
//        $sessionRequestId = $request->query('session-request-id');
        $infoClassRequestbyId = $this->classSessionRequestService->find($params['session-request-id']) ?? null;
        $params['class_session_registration_id'] = $infoClassRequestbyId->class_session_registration_id ?? null;
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $getStudyClassByIds = $this->studyClassService->find($params['study-class-id']);
        $students = $this->studentService->getStudentsByClassId($params);
        $getTotalStudentsByClass = $this->studentService->getTotalStudentsByClass($params);
        $getAttendanceStatusSummary = $this->studentService->getAttendanceStatusSummary($params);
//        dd($students);
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'getStudyClassByIds' => $getStudyClassByIds,
            'students' => $students,
            'getTotalStudentsByClass' => $getTotalStudentsByClass,
            'getAttendanceStatusSummary' => $getAttendanceStatusSummary,
        ];
        $data['getClassSessionRequest'] = null;
        if ($params['session-request-id']) {
            $getClassSessionRequest = $this->classSessionRequestService->find($params['session-request-id']);
            $rooms = $this->roomService->get();

            $data['getClassSessionRequest'] = $getClassSessionRequest;
            $data['rooms'] = $rooms;
        }

        return view('teacher.classSession.infoFixedClassActivitie', compact('data'));
    }

    public function doneSessionClass(Request $request,$id)
    {
        $params = $request->all();
        $roomId = $params['room_id'] ?? null;
        if ($roomId) {
            $this->roomService->update($roomId, [
                'status' => Constant::ROOM_STATUS['AVAILABLE'],
            ]);
        }
        $this->classSessionRequestService->update($id, [
            'status' => Constant::CLASS_SESSION_STATUS['DONE']
        ]);

        if ($params['type'] == Constant::CLASS_SESSION_TYPE['FLEXIBLE']) {
            return redirect()->route('teacher.class-session.flexible-class-activitie')->with('success', 'Kết thúc sinh hoạt lớp thành công');
        }

        return redirect()->route('teacher.class-session.detailFixedClassActivitie')->with('success', 'Kết thúc sinh hoạt lớp thành công');
    }

    public function updateAttendance(Request $request)
    {
        $params = $request->all();
        $params['session-request-id'] = $params['session_request_id'];
        $params['study-class-id'] = $params['study_class_id'];
//        dd($params);
        $result = $this->attendanceService->updateAttendance($params);

        if ($result) {
            return response()->json(['message' => 'Đã lưu điểm danh thành công!']);
        }

        return response()->json(['message' => 'Không thể lưu điểm danh'], 400);

    }

    public function indexFlexibleClassActivitie(Request $request)
    {
        $params = $request->all();
        $lecturerId = auth()->user()->lecturer?->id;
        $params['lecturer_id'] = $lecturerId;
        $getStudyClassByIds = $this->classSessionRequestService->getListFlexibleClass($params)->toArray();
        $totalClasses = $this->studyClassService->coutStudyClassListByLecturerId($lecturerId);
        $countFlexibleClassSessionRequestByLecturer = $this->classSessionRequestService->countFlexibleClassSessionRequestByLecturer($lecturerId);
        $countFlexibleRejectedByLecturer = $this->classSessionRequestService->countFlexibleRejectedByLecturer($lecturerId);
//        dd($getStudyClassByIds);
        $data = [
            'getStudyClassByIds' => $getStudyClassByIds,
            'totalClasses' => $totalClasses,
            'countFlexibleClassSessionRequestByLecturer' => $countFlexibleClassSessionRequestByLecturer,
            'countFlexibleRejectedByLecturer' => $countFlexibleRejectedByLecturer,
        ];

        return view('teacher.classSession.flexibleClassActivitie', compact('data'));
    }

    public function flexibleCreate(Request $request)
    {
        $studyClassId = $request->query('study-class-id');
        $sessionRequestId = $request->query('session-request-id');
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $getStudyClassByIds = $this->studyClassService->find($studyClassId);
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'getStudyClassByIds' => $getStudyClassByIds,
        ];
        $data['getClassSessionRequest'] = null;
        if ($sessionRequestId) {
            $getClassSessionRequest = $this->classSessionRequestService->find($sessionRequestId);
            $rooms = $this->roomService->get();

            $data['getClassSessionRequest'] = $getClassSessionRequest;
            $data['rooms'] = $rooms;
        }
//        dd($data['getClassSessionRequest']);

        return view('teacher.classSession.flexibleCreate', compact('data'));
    }

    public function flexibleCreateRequest()
    {
        $lecturerId = auth()->user()->lecturer?->id;
        $studyClasses = $this->studyClassService->getStudyClassListByLecturerId($lecturerId)->get();
//        dd(($studyClasses));
        return view('teacher.classSession.flexibleCreateRequest', compact('studyClasses'));
    }

    public function storeFlexibleClassSession(Request $request)
    {
        $params = $request->all();
        $params['lecturer_id'] = auth()->user()->lecturer?->id;
        $params['type'] = Constant::CLASS_SESSION_TYPE['FLEXIBLE'];
        $params['class_session_registration_id'] = null;
        if ($params['position'] == '2' || $params['position'] == '0')
            $params['meeting_type'] = null;
        if ($params['position'] != '0')
            $params['room_id'] = null;
//        dd($params);

        $this->classSessionRequestService->flexibleCreateOrUpdate($params);
        return redirect()->route('teacher.class-session.flexible-class-activitie')->with('success', 'Tạo yêu cầu thành công');
    }

    public function flexibleDetail(Request $request)
    {
        $params = $request->all();
//        dd($params);
//        $studyClassId = $request->query('study-class-id');
//        $sessionRequestId = $request->query('session-request-id');
        $infoClassRequestbyId = $this->classSessionRequestService->find($params['session-request-id']) ?? null;
        $params['class_session_registration_id'] = $infoClassRequestbyId->class_session_registration_id ?? null;
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $getStudyClassByIds = $this->studyClassService->find($params['study-class-id']);
        $students = $this->studentService->getStudentsByClassId($params);
        $getTotalStudentsByClass = $this->studentService->getTotalStudentsByClass($params);
        $getAttendanceStatusSummary = $this->studentService->getAttendanceStatusSummary($params);
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'getStudyClassByIds' => $getStudyClassByIds,
            'students' => $students,
            'getTotalStudentsByClass' => $getTotalStudentsByClass,
            'getAttendanceStatusSummary' => $getAttendanceStatusSummary,
        ];
        $data['getClassSessionRequest'] = null;
        if ($params['session-request-id']) {
            $getClassSessionRequest = $this->classSessionRequestService->find($params['session-request-id']);
            $rooms = $this->roomService->get();

            $data['getClassSessionRequest'] = $getClassSessionRequest;
            $data['rooms'] = $rooms;
//            dd($data['getStudyClassByIds']);
        }

        return view('teacher.classSession.flexibleDetail', compact('data'));
    }

    public function indexStatistical(Request $request)
    {
        $params = $request->all();
        $semesterId = $this->semesterService->get()->first()->id;
        $lecturerId = auth()->user()->lecturer?->id;
        $params['semester_id'] = $request->query('semester_id') ?? $semesterId;
        $semesters = $this->SemesterService->get()->toArray();
        $countStudyClassBySemester = $this->studyClassService->getStudyClassListByLecturerId($lecturerId)->count();
        $getTotalStudentsByLecturer = $this->lecturerService->getTotalStudentsByLecturer($lecturerId);
        $getTotalDoneSessionsByLecturer = $this->classSessionRequestService->getTotalDoneSessionsByLecturer($lecturerId);
        $getTotalSessionsByLecturer = $this->classSessionRequestService->getTotalSessionsByLecturer($lecturerId);
        $participationRate = $this->studyClassService->participationRate($lecturerId);
        $listStatisticsByLecturerId = $this->studyClassService->listStatisticsByLecturerId($lecturerId, $params['semester_id'])->toArray();

        $data = [
            'semesters' => $semesters,
            'countStudyClassBySemester' => $countStudyClassBySemester,
            'getTotalStudentsByLecturer' => $getTotalStudentsByLecturer,
            'getTotalDoneSessionsByLecturer' => $getTotalDoneSessionsByLecturer,
            'getTotalSessionsByLecturer' => $getTotalSessionsByLecturer,
            'participationRate' => $participationRate,
            'listStatisticsByLecturerId' => $listStatisticsByLecturerId,
        ];
//        dd($data['listStatisticsByLecturerId']);

        return view('teacher.statistical.index', compact('data'));
    }

}
