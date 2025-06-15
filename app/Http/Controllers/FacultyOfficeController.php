<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Hash;

class FacultyOfficeController extends Controller
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
        return view('facultyOffice.index');
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
//         dd($data['ConductEvaluationPeriods']);

        return view('facultyOffice.conductScore.index', compact('data'));
    }

    public function infoConductScore(Request $request)
    {
        $params = $request->all();
        $semesterId = $this->conductEvaluationPeriodService->find($params['conduct_evaluation_period_id'])->semester_id ?? null;
//        $findConductEvaluationPeriodBySemesterId = $this->conductEvaluationPeriodService->findConductEvaluationPeriodBySemesterId($semesterId);
        $params['semester_id'] = $params['semester_id'] ?? $semesterId;
        $params['department_id'] = auth()->user()->facultyOffice?->department_id;
//        dd($params['department_id']);
//        dd($findConductEvaluationPeriodBySemesterId);
        $params['study_class_id'] = $request->get('study_class_id', null);
        $getStudyClassList = $this->studyClassService->getStudyClassListByConductEvaluationPeriodIdByFacultyOffice($params)->toArray();
//        $infoByStudyClassListAndConductEvaluationPeriodId = $this->studyClassService->infoByStudyClassListAndConductEvaluationPeriodId($params);

        $data = [
            'getStudyClassList' => $getStudyClassList,
            'conduct_evaluation_period_id' => $params['conduct_evaluation_period_id'] ?? null,
        ];
//        dd($data['getStudyClassList']);

        return view('facultyOffice.conductScore.list', compact('data'));
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

        return view('facultyOffice.conductScore.listClass', compact('data'));
    }

    public function detailConductScore(Request $request)
    {
        $params = $request->all();
//        $detailConductScores = $this->detailConductScoreService->get($params)->toArray();
        $studentId = $params['student_id'] ?? null;
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'] ?? null;
        $conductEvaluationPeriod = $this->conductEvaluationPeriodService->find($conductEvaluationPeriodId);
        $checkConductEvaluationPeriodBySemesterId = $this->conductEvaluationPeriodService->findConductEvaluationPeriodBySemesterId($conductEvaluationPeriod?->semester_id);
        $studentConductScore = $this->studentConductScoreService->findStudentConductScore($conductEvaluationPeriodId, $studentId);
        $params['student_conduct_score_id'] = $studentConductScore?->id ?? null;
        $checkConductEvaluationPeriod = $this->conductEvaluationPeriodService->checkConductEvaluationPeriod();
        $getConductCriteriaData = $this->detailConductScoreService->getConductCriteriaDataByLecturer($params);
        $calculateTotalScore = $this->detailConductScoreService->calculateTotalScore($getConductCriteriaData);

        $data = [
            'getConductCriteriaData' => $getConductCriteriaData->toArray(),
            'calculateTotalScore' => $calculateTotalScore,
            'checkConductEvaluationPeriod' => $checkConductEvaluationPeriod,
            'student_conduct_score_id' => $params['student_conduct_score_id'],
            'conduct_evaluation_period_id' => $conductEvaluationPeriodId,
            'checkConductEvaluationPeriodBySemesterId' => $checkConductEvaluationPeriodBySemesterId,
        ];
//dd($data['getConductCriteriaData']);
        return view('facultyOffice.conductScore.detail', compact('data'));
    }

    public function saveConductScore(Request $request)
    {
        try {
            $params = $request->all();
            $details = $params['details'];
            $studentConductScoreId = $request->input('student_conduct_score_id');
            $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'];
            // Decode the details JSON string
            $details = json_decode($details, true);

            if (!is_array($details) || empty($details) || !$studentConductScoreId || !$conductEvaluationPeriodId) {
                return response()->json(['message' => 'Dữ liệu không hợp lệ'], 400);
            }

            DB::beginTransaction();

            $studentConductScore = \App\Models\StudentConductScore::where('id', $studentConductScoreId)
                ->update([
                    'status' => 2,
                    'updated_at' => now(),
                ]);

            // Update only final_score for each detail, preserving other fields
            foreach ($details as $detail) {
                if (!isset($detail['student_conduct_score_id'], $detail['conduct_criteria_id'], $detail['final_score'])) {
                    DB::rollBack();
                    return response()->json(['message' => 'Dữ liệu tiêu chí không hợp lệ'], 400);
                }

                \App\Models\DetailConductScore::where('student_conduct_score_id', $detail['student_conduct_score_id'])
                    ->where('conduct_criteria_id', $detail['conduct_criteria_id'])
                    ->update([
                        'final_score' => $detail['final_score'],
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();

            return response()->json(['message' => 'Cập nhật điểm rèn luyện thành công']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()], 500);
        }
    }

    public function showDetailConductScore(Request $request)
    {
        $params = $request->all();
//        $detailConductScores = $this->detailConductScoreService->get($params)->toArray();
        $studentId = $params['student_id'] ?? null;
        $conductEvaluationPeriodId = $params['conduct_evaluation_period_id'] ?? null;
        $conductEvaluationPeriod = $this->conductEvaluationPeriodService->find($conductEvaluationPeriodId);
        $checkConductEvaluationPeriodBySemesterId = $this->conductEvaluationPeriodService->findConductEvaluationPeriodBySemesterId($conductEvaluationPeriod?->semester_id);
        $studentConductScore = $this->studentConductScoreService->findStudentConductScore($conductEvaluationPeriodId, $studentId);
        $params['student_conduct_score_id'] = $studentConductScore?->id ?? null;
        $checkConductEvaluationPeriod = $this->conductEvaluationPeriodService->checkConductEvaluationPeriod();
        $getConductCriteriaData = $this->detailConductScoreService->getConductCriteriaDataByLecturer($params);
        $calculateTotalScore = $this->detailConductScoreService->calculateTotalScore($getConductCriteriaData);

        $data = [
            'getConductCriteriaData' => $getConductCriteriaData->toArray(),
            'calculateTotalScore' => $calculateTotalScore,
            'checkConductEvaluationPeriod' => $checkConductEvaluationPeriod,
            'student_conduct_score_id' => $params['student_conduct_score_id'],
            'conduct_evaluation_period_id' => $conductEvaluationPeriodId,
            'checkConductEvaluationPeriodBySemesterId' => $checkConductEvaluationPeriodBySemesterId,
        ];

        return view('facultyOffice.conductScore.showDetail', compact('data'));
    }

}
