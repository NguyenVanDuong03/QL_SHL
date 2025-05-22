<?php

namespace App\Http\Controllers;

use App\Services\AcademicWarningService;
use App\Services\ClassSessionRegistrationService;
use App\Services\ClassSessionRequestService;
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

    public function indexFixedClassActivitie()
    {
        $getCSRSemesterInfo = $this->classSessionRegistrationService->getCSRSemesterInfo();
        $data = [
            'getCSRSemesterInfo' => $getCSRSemesterInfo,
        ];

        return view('teacher.classSession.fixedClassActivitie', compact('data'));
    }

    public function indexFlexibleClassActivitie()
    {
        return view('teacher.classSession.flexibleClassActivitie');
    }
}
