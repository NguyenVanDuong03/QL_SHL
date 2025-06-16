<?php

namespace App\Http\Controllers;

use App\Exports\AttendancesExport;
use App\Exports\StudentConductScoresExport;
use App\Helpers\Constant;
use App\Services\AcademicWarningService;
use App\Services\AttendanceService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionRequestService;
use App\Services\ConductCriteriaService;
use App\Services\ConductEvaluationPeriodService;
use App\Services\DetailConductScoreService;
use App\Services\LecturerService;
use App\Services\RoomService;
use App\Services\SemesterService;
use App\Services\StudentConductScoreService;
use App\Services\StudentService;
use App\Services\StudyClassService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LecturerController extends Controller
{
    public function __construct(
        protected ClassSessionRegistrationService $classSessionRegistrationService,
        protected ClassSessionRequestService      $classSessionRequestService,
        protected StudyClassService               $studyClassService,
        protected StudentService                  $studentService,
        protected RoomService                     $roomService,
        protected SemesterService                 $SemesterService,
        protected AcademicWarningService          $academicWarningService,
        protected AttendanceService               $attendanceService,
        protected SemesterService                 $semesterService,
        protected LecturerService                 $lecturerService,
        protected StudentConductScoreService      $studentConductScoreService,
        protected ConductEvaluationPeriodService  $conductEvaluationPeriodService,
        protected DetailConductScoreService       $detailConductScoreService,
        protected ConductCriteriaService          $conductCriteriaService
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
//         dd($data['classes']);

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

    public function doneSessionClass(Request $request, $id)
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

    public function indexConductScore(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Constant::DEFAULT_LIMIT_12;
        $params['withSemester'] = true;
        $ConductEvaluationPeriods = $this->conductEvaluationPeriodService->paginate($params)->toArray();
        $semesters = $this->semesterService->getFourSemester()->limit(4)->get();
        $data = [
            'ConductEvaluationPeriods' => $ConductEvaluationPeriods,
            'semesters' => $semesters,
        ];
//         dd($data['ConductEvaluationPeriods']);

        return view('teacher.conductScore.index', compact('data'));
    }

    public function infoConductScore(Request $request, $id)
    {
        $params = $request->all();
        $params['conduct_evaluation_period_id'] = $id;
        $semesterId = $this->conductEvaluationPeriodService->find($id)->semester_id ?? null;
        $findConductEvaluationPeriodBySemesterId = $this->conductEvaluationPeriodService->findConductEvaluationPeriodBySemesterId($semesterId);
        $params['semester_id'] = $params['semester_id'] ?? $semesterId;
        $params['lecturer_id'] = auth()->user()->lecturer?->id;
        $params['study_class_id'] = $request->get('study_class_id', null);
        $getStudyClassList = $this->studyClassService->getStudyClassListByConductEvaluationPeriodIdByLecturerId($params)->toArray();
//        $infoByStudyClassListAndConductEvaluationPeriodId = $this->studyClassService->infoByStudyClassListAndConductEvaluationPeriodId($params);

        $data = [
            'getStudyClassList' => $getStudyClassList,
            'conduct_evaluation_period_id' => $id,
            'semester_id' => $params['semester_id'],
            'findConductEvaluationPeriodBySemesterId' => $findConductEvaluationPeriodBySemesterId,
        ];
//        dd($data['getStudyClassList']);

        return view('teacher.conductScore.list', compact('data'));
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

    public function listConductScore(Request $request)
    {
        $params = $request->all();
        $listConductScores = $this->studentService->listConductScores($params)->toArray();
        $countStudentsByConductStatus = $this->studentService->countStudentsByConductStatus($params);
//        dd($listConductScores);
        $data = [
            'listConductScores' => $listConductScores,
            'countStudentsByConductStatus' => $countStudentsByConductStatus,
            'conduct_evaluation_period_id' => $params['conduct_evaluation_period_id'] ?? null,
        ];

        return view('teacher.conductScore.listClass', compact('data'));
    }

    public function detailConductScore(Request $request)
    {
        $params = $request->all();
//        $detailConductScores = $this->detailConductScoreService->get($params)->toArray();
        $studentId = $params['student_id'] ?? null;
        $student = $this->studentService->infoStudent($studentId)->toArray();
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'] ?? null;
        $conductEvaluationPeriod = $this->conductEvaluationPeriodService->find($conductEvaluationPeriodId);
        $checkConductEvaluationPeriodBySemesterId = $this->conductEvaluationPeriodService->findConductEvaluationPeriodBySemesterId($conductEvaluationPeriod?->semester_id);
        $studentConductScore = $this->studentConductScoreService->findStudentConductScore($conductEvaluationPeriodId, $studentId);
        $params['student_conduct_score_id'] = $studentConductScore?->id ?? null;
        $checkConductEvaluationPeriod = $this->conductEvaluationPeriodService->checkConductEvaluationPeriod();
        $getConductCriteriaData = $this->detailConductScoreService->getConductCriteriaDataByLecturer($params);
        $calculateTotalScore = $this->detailConductScoreService->calculateTotalScore($getConductCriteriaData);
        $conductCriterias = $this->conductCriteriaService->get()->toArray();
        $data = [
            'getConductCriteriaData' => $getConductCriteriaData->toArray(),
            'calculateTotalScore' => $calculateTotalScore,
            'checkConductEvaluationPeriod' => $checkConductEvaluationPeriod,
            'student_conduct_score_id' => $params['student_conduct_score_id'],
            'conduct_evaluation_period_id' => $conductEvaluationPeriodId,
            'checkConductEvaluationPeriodBySemesterId' => $checkConductEvaluationPeriodBySemesterId,
            'student' => $student,
            'conductCriterias' => $conductCriterias,
        ];
//dd($data['calculateTotalScore']);
        return view('teacher.conductScore.detail', compact('data'));
    }

    public function saveConductScore(Request $request)
    {
        try {
            $params = $request->all();
            $details = $params['details'];
            $studentConductScoreId = $request->input('student_conduct_score_id');
            $studentId = $params['student_id'];
            $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'];
            // Decode the details JSON string
            $details = json_decode($details, true);

            if (!is_array($details) || empty($details) || !$conductEvaluationPeriodId) {
                return response()->json(['message' => 'Dữ liệu không hợp lệ'], 400);
            }

            DB::beginTransaction();

            $studentConductScore = \App\Models\StudentConductScore::updateOrCreate(
                [
                    'conduct_evaluation_period_id' => $conductEvaluationPeriodId,
                    'student_id' => $studentId,
                ],
                [
                    'status' => 1,
                    'updated_at' => now(),
                ]
            );

            // Update only class_score for each detail, preserving other fields
            foreach ($details as $detail) {
                if (!isset($detail['conduct_criteria_id'], $detail['class_score'])) {
                    DB::rollBack();
                    return response()->json(['message' => 'Dữ liệu tiêu chí không hợp lệ'], 400);
                }

                \App\Models\DetailConductScore::updateOrCreate(
                    [
                        'student_conduct_score_id' => $studentConductScore->id,
                        'conduct_criteria_id' => $detail['conduct_criteria_id'],
                    ],
                    [
                        'class_score' => $detail['class_score'],
                        'updated_at' => now(),
                    ]
                );
            }

            DB::commit();

            return response()->json(['message' => 'Cập nhật điểm rèn luyện thành công']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()], 500);
        }
    }

    public function indexStatistical(Request $request)
    {
        $params = $request->all();
        $semesterId = $this->semesterService->get()->first()->id;
        $lecturerId = auth()->user()->lecturer?->id;
        $params['semester_id'] = $request->query('semester_id') ?? $semesterId;
        $page = $request->query('page', 1);
        $pageSize = 5;

        $statisticalSemester = $this->semesterService->statisticalSemester($lecturerId)
            ->when($request->has('page'), function ($query) use ($page, $pageSize) {
                return $query->skip(($page - 1) * $pageSize)->take($pageSize);
            })
            ->toArray();

        if ($request->ajax()) {
            return response()->json(['activities' => $statisticalSemester]);
        }

        $data = [
            'semesters' => $this->SemesterService->getFourSemester()->get()->toArray(),
            'countStudyClassBySemester' => $this->studyClassService->getStudyClassListByLecturerId($lecturerId)->count(),
            'getTotalStudentsByLecturer' => $this->lecturerService->getTotalStudentsByLecturer($lecturerId),
            'getTotalDoneSessionsByLecturer' => $this->classSessionRequestService->getTotalDoneSessionsByLecturer($lecturerId),
            'getTotalSessionsByLecturer' => $this->classSessionRequestService->getTotalSessionsByLecturer($lecturerId),
            'participationRate' => $this->studyClassService->participationRate($lecturerId),
            'statisticalSemester' => $statisticalSemester,
            'statisticalAttendance' => $this->studentService->statisticalAttendance($lecturerId, $params['semester_id'])->toArray(),
        ];

        return view('teacher.statistical.index', compact('data'));
    }

    public function exportAttendance(Request $request)
    {
        $classRequestId = $request->query('class_request_id');
        $studyClassId = $request->query('study_class_id');
        $studyClassName = $request->query('study_class_name');
//        dd($classRequestId, $studyClassId);
        try {
            $fileName = $studyClassName. '_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new AttendancesExport($classRequestId, $studyClassId), $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['export_error' => 'Xuất file thất bại: ' . $e->getMessage()]);
        }
    }

}
