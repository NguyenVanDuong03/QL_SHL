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
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        protected ConductCriteriaService         $conductCriteriaService,
        protected StudentConductScoreService     $studentConductScoreService,
        protected DetailConductScoreService      $detailConductScoreService
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

        return view('classStaff.class.index', compact('data'));
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
//dd($data['findConductEvaluationPeriodBySemesterId']);
        return view('classStaff.conductScore.index', compact('data'));
    }

    public function SaveConductScore(Request $request)
    {
        try {
            $params = $request->all();
            $studentId = auth()->user()->student?->id ?? null;
            $semesterId = $params['semester_id'] ?? null;
            $totalScore = $params['total_score'] ?? null;
            $classification = $params['classification'] ?? null;

            $details = isset($params['details']) ? json_decode($params['details'], true) : null;

            if (!$studentId || !$semesterId || !$details || !is_array($details)) {
                return response()->json(['message' => 'Dữ liệu không hợp lệ'], 400);
            }

            DB::beginTransaction();

            $conductEvaluationPeriod = \App\Models\ConductEvaluationPeriod::where('semester_id', $semesterId)
                ->where('open_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$conductEvaluationPeriod) {
                return response()->json(['message' => 'Không tìm thấy đợt đánh giá phù hợp cho học kỳ này hoặc thời gian đánh giá đã đóng'], 400);
            }

            $studentConductScore = \App\Models\StudentConductScore::updateOrCreate(
                [
                    'conduct_evaluation_period_id' => $conductEvaluationPeriod->id,
                    'student_id' => $studentId,
                ],
                [
                    'total_score' => $totalScore,
                    'classification' => $classification,
                    'status' => 0, // SV chấm
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $existingDetails = \App\Models\DetailConductScore::where('student_conduct_score_id', $studentConductScore->id)
                ->pluck('conduct_criteria_id')
                ->toArray();

            foreach ($details as $detail) {
                if (!isset($detail['conduct_criteria_id'], $detail['self_score'])) {
                    DB::rollBack();
                    return response()->json(['message' => 'Dữ liệu tiêu chí không hợp lệ'], 400);
                }

                $evidenceKey = "evidence.{$detail['conduct_criteria_id']}";
                $path = null;

                if (isset($detail['evidence_removed']) && $detail['evidence_removed']) {
                    $existingRecord = \App\Models\DetailConductScore::where([
                        'student_conduct_score_id' => $studentConductScore->id,
                        'conduct_criteria_id' => $detail['conduct_criteria_id'],
                    ])->first();

                    if ($existingRecord && $existingRecord->path) {
                        Storage::disk('public')->delete($existingRecord->path);
                    }
                } elseif ($request->hasFile($evidenceKey)) {
                    // Delete old file if it exists
                    $existingRecord = \App\Models\DetailConductScore::where([
                        'student_conduct_score_id' => $studentConductScore->id,
                        'conduct_criteria_id' => $detail['conduct_criteria_id'],
                    ])->first();

                    if ($existingRecord && $existingRecord->path) {
                        Storage::disk('public')->delete($existingRecord->path);
                    }

                    $file = $request->file($evidenceKey);
                    $fileName = 'evidence_' . time() . '_' . $detail['conduct_criteria_id'] . '_' . $studentId . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('evidences', $fileName, 'public');
                } else {
                    $existingRecord = \App\Models\DetailConductScore::where([
                        'student_conduct_score_id' => $studentConductScore->id,
                        'conduct_criteria_id' => $detail['conduct_criteria_id'],
                    ])->first();
                    $path = $existingRecord->path ?? null;
                }

                $data = [
                    'self_score' => $detail['self_score'],
                    'note' => $detail['note'] ?? null,
                    'class_score' => null, // GVCN chấm later
                    'final_score' => null, // CTSV chấm later
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($path !== null || (isset($detail['evidence_removed']) && $detail['evidence_removed'])) {
                    $data['path'] = $path;
                }

                \App\Models\DetailConductScore::updateOrCreate(
                    [
                        'student_conduct_score_id' => $studentConductScore->id,
                        'conduct_criteria_id' => $detail['conduct_criteria_id'],
                    ],
                    $data
                );

                $index = array_search($detail['conduct_criteria_id'], $existingDetails);
                if ($index !== false) {
                    unset($existingDetails[$index]);
                }
            }

            if (!empty($existingDetails)) {
                \App\Models\DetailConductScore::where('student_conduct_score_id', $studentConductScore->id)
                    ->whereIn('conduct_criteria_id', $existingDetails)
                    ->get()
                    ->each(function ($record) {
                        if ($record->path) {
                            Storage::disk('public')->delete($record->path);
                        }
                        $record->delete();
                    });
            }

            DB::commit();

            return response()->json(['message' => 'Lưu điểm rèn luyện thành công']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()], 500);
        }
    }
}
