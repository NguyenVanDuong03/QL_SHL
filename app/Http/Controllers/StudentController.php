<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Services\AttendanceService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionReportService;
use App\Services\ClassSessionRequestService;
use App\Services\CohortService;
use App\Services\ConductCriteriaService;
use App\Services\ConductEvaluationPeriodService;
use App\Services\DepartmentService;
use App\Services\DetailConductScoreService;
use App\Services\FacultyService;
use App\Services\LecturerService;
use App\Services\RoomService;
use App\Services\SemesterService;
use App\Services\StudentConductScoreService;
use App\Services\StudentService;
use App\Services\StudyClassService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
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
        protected DetailConductScoreService      $detailConductScoreService,
        protected ConductCriteriaService         $conductCriteriaService,
        protected StudentConductScoreService     $studentConductScoreService,
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
        return view('student.index', compact('data'));
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

        return view('student.classSession.index', compact('data'));
    }

    public function history(Request $request)
    {
        $params = $request->all();
        $params['study_class_id'] = auth()->user()->student?->studyClass?->id ?? null;
        $params['student_id'] = auth()->user()->student?->id ?? null;
        $classSessionRequests = $this->classSessionRequestService->getClassSessionRequestsDone($params)->paginate(Constant::DEFAULT_LIMIT_12)->toArray();
//        dd($classSessionRequests);
        $data = [
            'classSessionRequests' => $classSessionRequests,
        ];

        return view('student.classSession.history', compact('data'));
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

        return view('student.classSession.detail', compact('data'));
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

    public function indexClass(Request $request)
    {
        $params = $request->all();
        $params['class_id'] = auth()->user()->student?->studyClass?->id ?? null;
        $studyClassName = auth()->user()->student?->studyClass?->name ?? null;
        $students = $this->studentService->getListStudentByClassId($params)->toArray();
//        dd($students);
        $data = [
            'students' => $students,
            'studyClassName' => $studyClassName,
        ];
//         dd($data['students']);

        return view('student.class.index', compact('data'));
    }

    public function indexConductScore(Request $request)
    {
        $params = $request->all();
        $params['student_id'] = auth()->user()->student?->id ?? null;
        $currentSemester = $this->semesterService->get()->first();
        $params['semester_id'] = $params['semester_id'] ?? $currentSemester->id;
        $semesters = $this->semesterService->getFourSemester()->toArray();
        $findConductEvaluationPeriodBySemesterId = $this->conductEvaluationPeriodService->findConductEvaluationPeriodBySemesterId($params['semester_id']);
//        $detailConductScores = $this->detailConductScoreService->get($params)->toArray();
        $checkConductEvaluationPeriod = $this->conductEvaluationPeriodService->checkConductEvaluationPeriod();
        $getConductCriteriaData = $this->detailConductScoreService->getConductCriteriaData($params);
        $conductCriterias = $this->conductCriteriaService->get()->toArray();
        $calculateTotalScore = $this->detailConductScoreService->calculateTotalScore($getConductCriteriaData);

        $data = [
            'semesters' => $semesters,
            'getConductCriteriaData' => $getConductCriteriaData->toArray(),
            'findConductEvaluationPeriodBySemesterId' => $findConductEvaluationPeriodBySemesterId,
            'calculateTotalScore' => $calculateTotalScore,
            'conductCriterias' => $conductCriterias,
            'checkConductEvaluationPeriod' => $checkConductEvaluationPeriod,
        ];
//dd($data['getConductCriteriaData']);
        return view('student.conductScore.index', compact('data'));
    }

    public function SaveConductScore(Request $request)
    {
        try {
            // Retrieve and validate input
            $params = $request->all();
            $studentId = auth()->user()->student?->id ?? null;
            $semesterId = $params['semester_id'] ?? null;
            $totalScore = $params['total_score'] ?? null;
            $classification = $params['classification'] ?? null;

            // Log input params for debugging
            \Log::info('Input Params:', ['params' => $params]);

            // Decode the details JSON string
            $details = isset($params['details']) ? json_decode($params['details'], true) : null;
dd($details);
            // Log the decoded details for debugging
            \Log::info('Decoded Details:', ['details' => $details]);

            // Validate required fields
            if (!$studentId || !$semesterId || !$details || !is_array($details)) {
                \Log::error('Invalid input data', [
                    'studentId' => $studentId,
                    'semesterId' => $semesterId,
                    'details' => $details
                ]);
                return response()->json(['message' => 'Dữ liệu không hợp lệ'], 400);
            }

            // Start a database transaction
            DB::beginTransaction();

            // Find the conduct evaluation period
            $conductEvaluationPeriod = \App\Models\ConductEvaluationPeriod::where('semester_id', $semesterId)
                ->where('open_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$conductEvaluationPeriod) {
                \Log::error('No matching conduct evaluation period found', [
                    'semesterId' => $semesterId,
                    'currentTime' => now()->toDateTimeString()
                ]);
                return response()->json(['message' => 'Không tìm thấy đợt đánh giá phù hợp cho học kỳ này hoặc thời gian đánh giá đã đóng'], 400);
            }

            // Create or update student_conduct_scores record
            $studentConductScore = \App\Models\StudentConductScore::updateOrCreate(
                [
                    'conduct_evaluation_period_id' => $conductEvaluationPeriod->id,
                    'student_id' => $studentId,
                ],
                [
                    'status' => 0, // SV chấm
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Process each detail
            foreach ($details as $detail) {
                // Validate detail fields
                if (!isset($detail['conduct_criteria_id'], $detail['self_score'])) {
                    \Log::error('Invalid detail data', ['detail' => $detail]);
                    DB::rollBack();
                    return response()->json(['message' => 'Dữ liệu tiêu chí không hợp lệ'], 400);
                }

                // Handle evidence (image)
                $evidenceKey = "evidence.{$detail['conduct_criteria_id']}";
                \Log::info('Checking for file', ['evidenceKey' => $evidenceKey, 'hasFile' => $request->hasFile($evidenceKey)]);
                $path = null;
                if ($request->hasFile($evidenceKey)) {
                    $file = $request->file($evidenceKey);
                    $fileName = 'evidence_' . time() . '_' . $detail['conduct_criteria_id'] . '_' . $studentId . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('evidences', $fileName, 'public');
                    \Log::info('File stored', ['path' => $path, 'fileName' => $fileName]);
                } else {
                    \Log::info('No file uploaded, setting path to null', ['criteriaId' => $detail['conduct_criteria_id']]);
                }

                // Update or create detail_conduct_scores
                \App\Models\DetailConductScore::updateOrCreate(
                    [
                        'student_conduct_score_id' => $studentConductScore->id,
                        'conduct_criteria_id' => $detail['conduct_criteria_id'],
                    ],
                    [
                        'self_score' => $detail['self_score'],
                        'note' => $detail['note'] ?? null,
                        'class_score' => null, // GVCN chấm later
                        'final_score' => null, // CTSV chấm later
                        'path' => $path,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            DB::commit();
            \Log::info('Conduct score saved successfully', ['studentId' => $studentId, 'semesterId' => $semesterId]);
            return response()->json(['message' => 'Lưu điểm rèn luyện thành công']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Save Conduct Score Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()], 500);
        }
    }

}
