<?php

namespace App\Http\Controllers;

use App\Services\AcademicWarningService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionRequestService;
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
        $countFlexibleClassSessionRequest = $this->classSessionRequestService->countFlexibleClassSessionRequest();
        $data = [
            'checkClassSessionRegistration' => $checkClassSessionRegistration,
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
            'countFlexibleClassSessionRequest' => $countFlexibleClassSessionRequest
        ];

        return view('teacher.classSession.index', compact('data'));
    }

    public function indexClass()
    {
        $lecturerId = auth()->user()->lecturer?->id;
        $classes = $this->studyClassService->getStudyClassListByLecturerId($lecturerId)->toArray();
        $data = [
            'classes' => $classes,
        ];
        // dd($data['classes']);

        return view('teacher.class.index', compact('data'));
    }

    public function infoStudent($id)
    {
        $students = $this->studentService->getStudentsByClassId($id)->toArray();
        $classInfo = $this->studyClassService->find($id);
        $data = [
            'students' => $students,
            'classInfo' => $classInfo,
        ];
        // dd($data['classInfo']->name);

        return view('teacher.class.infoStudent', compact('data'));
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

        return view('teacher.classSession.fixedClassActivitie', compact('data'));
    }

    public function indexFlexibleClassActivitie()
    {
        return view('teacher.classSession.flexibleClassActivitie');
    }

    public function createClassSession(Request $request)
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

        return view('teacher.classSession.create', compact('data'));
    }

    public function storeClassSession(Request $request)
    {
        $params = $request->all();
        $params['lecturer_id'] = auth()->user()->lecturer?->id;
        $params['type'] = 0;
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

    public function detailClassSession(Request $request)
    {
        $studyClassId = $request->query('study-class-id');
        $sessionRequestId = $request->query('session-request-id');
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $getStudyClassByIds = $this->studyClassService->find($studyClassId);
        $params['study_class_id'] = $studyClassId;
        $students = $this->studentService->get($params);
//        dd($students);
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

//        dd($data['getStudyClassByIds']);
        return view('teacher.classSession.detail', compact('data'));
    }

    public function indexStatistical()
    {
        return view('teacher.statistical.index');
    }

}
