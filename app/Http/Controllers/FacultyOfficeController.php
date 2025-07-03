<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Services\AcademicWarningService;
use App\Services\AttendanceService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionReportService;
use App\Services\ClassSessionRequestService;
use App\Services\CohortService;
use App\Services\ConductCriteriaService;
use App\Services\ConductEvaluationPeriodService;
use App\Services\ConductEvaluationPhaseService;
use App\Services\DepartmentService;
use App\Services\DetailConductScoreService;
use App\Services\FacultyService;
use App\Services\LecturerService;
use App\Services\MajorService;
use App\Services\RoomService;
use App\Services\SemesterService;
use App\Services\StudentConductScoreService;
use App\Services\StudentService;
use App\Services\StudyClassService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FacultyOfficeController extends Controller
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
        protected ConductEvaluationPhaseService  $conductEvaluationPhaseService,
        protected DetailConductScoreService      $detailConductScoreService,
        protected ConductCriteriaService         $conductCriteriaService,
    )
    {
    }

    public function index()
    {
        $totalStudyClasses = $this->studyClassService->get()->count();
        $semester = $this->semesterService->get()->first() ?? null;
        $totalAcademicWarnings = $this->academicWarningService->academicWarningBySemesterId($semester->id ?? null)->get()->count();
        $totalClassSessionReports = $this->classSessionReportService->countClassSessionReports($semester->id ?? null);
        $getAllClassSession = $this->classSessionRequestService->getAllClassSession()->toArray();
        $countClassSession = $this->classSessionRequestService->countClassSession();
        $statisticalUserByRole = $this->userService->statisticalUserByRole()->toArray();

        $data = [
            'totalStudyClasses' => $totalStudyClasses,
            'totalAcademicWarnings' => $totalAcademicWarnings,
            'semester' => $semester,
            'totalClassSessionReports' => $totalClassSessionReports,
            'getAllClassSession' => $getAllClassSession,
            'countClassSession' => $countClassSession,
            'statisticalUserByRole' => $statisticalUserByRole,
        ];

        return view('facultyOffice.index', compact('data'));
    }

    public function indexConductScore(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Constant::DEFAULT_LIMIT_12;
        $params['withSemester'] = true;
        $ConductEvaluationPeriods = $this->conductEvaluationPeriodService->paginate($params)->toArray();
        $semesters = $this->semesterService->getFourSemester()->get()->toArray();
        $data = [
            'ConductEvaluationPeriods' => $ConductEvaluationPeriods,
            'semesters' => $semesters,
        ];

        return view('facultyOffice.conductScore.index', compact('data'));
    }

    public function infoConductScore(Request $request)
    {
        $params = $request->all();
        $semesterId = $this->conductEvaluationPeriodService->find($params['conduct_evaluation_period_id'])->semester_id ?? null;
        $params['role'] = 2;
        $findConductEvaluationPeriodBySemesterId = $this->conductEvaluationPhaseService->findConductEvaluationPeriodBySemesterId($params);
        $params['semester_id'] = $params['semester_id'] ?? $semesterId;
        $params['department_id'] = auth()->user()->facultyOffice?->department_id;
        $params['study_class_id'] = $request->get('study_class_id', null);
        $getStudyClassList = $this->studyClassService->getStudyClassListByConductEvaluationPeriodIdByFacultyOffice($params)->toArray();
//        $infoByStudyClassListAndConductEvaluationPeriodId = $this->studyClassService->infoByStudyClassListAndConductEvaluationPeriodId($params);

        $data = [
            'getStudyClassList' => $getStudyClassList,
            'conduct_evaluation_period_id' => $params['conduct_evaluation_period_id'] ?? null,
            'findConductEvaluationPeriodBySemesterId' => $findConductEvaluationPeriodBySemesterId,
        ];

        return view('facultyOffice.conductScore.list', compact('data'));
    }

    public function listConductScore(Request $request)
    {
        $params = $request->all();
        $listConductScores = $this->studentService->listConductScores($params)->toArray();
        $countStudentsByConductStatus = $this->studentService->countStudentsByConductStatus($params);
        $data = [
            'listConductScores' => $listConductScores,
            'countStudentsByConductStatus' => $countStudentsByConductStatus,
            'conduct_evaluation_period_id' => $params['conduct_evaluation_period_id'] ?? null,
            'study_class_id' => $params['study_class_id'] ?? null,
        ];

        return view('facultyOffice.conductScore.listClass', compact('data'));
    }

    public function detailConductScore(Request $request)
    {
        $params = $request->all();
//        $detailConductScores = $this->detailConductScoreService->get($params)->toArray();
        $studentId = $params['student_id'] ?? null;
        $student = $this->studentService->infoStudent($studentId)->toArray();
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'] ?? null;
        $conductEvaluationPeriod = $this->conductEvaluationPeriodService->find($conductEvaluationPeriodId);
        $params['role'] = 2;
        $checkConductEvaluationPeriodBySemesterId = $this->conductEvaluationPhaseService->findConductEvaluationPeriodBySemesterId($params);
        $studentConductScore = $this->studentConductScoreService->findStudentConductScore($conductEvaluationPeriodId, $studentId);
        $params['student_conduct_score_id'] = $studentConductScore?->id ?? null;
        $checkConductEvaluationPeriod = $this->conductEvaluationPhaseService->checkConductEvaluationPeriod();
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
            'study_class_id' => $params['study_class_id'] ?? null,
        ];

        return view('facultyOffice.conductScore.detail', compact('data'));
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
                    'status' => 2,
                    'updated_at' => now(),
                ]
            );

            // Update only final_score for each detail, preserving other fields
            foreach ($details as $detail) {
                if (!isset($detail['conduct_criteria_id'], $detail['final_score'])) {
                    DB::rollBack();
                    return response()->json(['message' => 'Dữ liệu tiêu chí không hợp lệ'], 400);
                }

                \App\Models\DetailConductScore::updateOrCreate(
                    [
                        'student_conduct_score_id' => $studentConductScore->id,
                        'conduct_criteria_id' => $detail['conduct_criteria_id'],
                    ],
                    [
                        'final_score' => $detail['final_score'],
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

}
