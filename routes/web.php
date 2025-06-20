<?php

use \App\Helpers\Constant;
use App\Http\Controllers\ClassStaffController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\FacultyOfficeController;
use App\Http\Controllers\StudentAffairsDepartmentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->role == Constant::ROLE_LIST['TEACHER']) {
        return redirect()->route('teacher.index');
    } else if (Auth::check() && Auth::user()->role == Constant::ROLE_LIST['STUDENT_AFFAIRS_DEPARTMENT']) {
        return redirect()->route('student-affairs-department.index');
    } else if (Auth::check() && Auth::user()->role == Constant::ROLE_LIST['CLASS_STAFF']) {
        return redirect()->route('class-staff.index');
    } else if (Auth::check() && Auth::user()->role == Constant::ROLE_LIST['STUDENT']) {
        return redirect()->route('student.index');
    } else if (Auth::check() && Auth::user()->role == Constant::ROLE_LIST['FACULTY_OFFICE']) {
        return redirect()->route('faculty-office.index');
    }

    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Route Teacher
    Route::group(
        [
            'prefix' => 'teacher',
            'as' => 'teacher.',
            'middleware' => ['role:1'],
        ],
        function () {
            Route::get('/', [LecturerController::class, 'index'])->name('index');
            Route::post('/', [LecturerController::class, 'createOrUpdateLecturer'])->name('createOrUpdateLecturer');

            // class session
            Route::group(
                [
                    'prefix' => 'class-session',
                    'as' => 'class-session.',
                ],
                function () {
                    Route::get('/', [LecturerController::class, 'indexClassSession'])->name('index');
                    Route::get('/fixed-class-activitie', [LecturerController::class, 'indexFixedClassActivitie'])->name('fixed-class-activitie');
                    Route::get('/fixed-class-activitie/create', [LecturerController::class, 'createClassSession'])->name('create');
                    Route::post('/fixed-class-activitie', [LecturerController::class, 'storeClassSession'])->name('store');
                    Route::delete('/session-class-activitie/{id}', [LecturerController::class, 'deleteClassSession'])->name('delete');
                    Route::get('/fixed-class-activitie/detail', [LecturerController::class, 'detailClassSession'])->name('detail');
                    Route::get('/detail-fixed-class-activitie', [LecturerController::class, 'detailFixedClassActivitie'])->name('detailFixedClassActivitie');
                    Route::get('/info-fixed-class-activitie', [LecturerController::class, 'infoFixedClassActivitie'])->name('infoFixedClassActivitie');
                    Route::patch('/done-session-class/{id}', [LecturerController::class, 'doneSessionClass'])->name('doneSessionClass');

                    Route::get('/flexible-class-activitie', [LecturerController::class, 'indexFlexibleClassActivitie'])->name('flexible-class-activitie');
                    Route::get('/flexible-class-activitie/create', [LecturerController::class, 'flexibleCreate'])->name('flexibleCreate');
                    Route::get('/flexible-create-request', [LecturerController::class, 'flexibleCreateRequest'])->name('flexibleCreateRequest');
                    Route::post('/flexible-class-activitie', [LecturerController::class, 'storeFlexibleClassSession'])->name('storeFlexibleClassSession');
                    Route::get('/flexible-class-activitie/detail', [LecturerController::class, 'flexibleDetail'])->name('flexibleDetail');
                }
            );

            // Attendance
            Route::group(
                [
                    'prefix' => 'attendance',
                    'as' => 'attendance.',
                ],
                function () {
                    Route::get('/', [LecturerController::class, 'indexAttendance'])->name('index');
                    Route::patch('/', [LecturerController::class, 'updateAttendance'])->name('updateAttendance');
                    Route::post('/{id?}', [LecturerController::class, 'saveAttendance'])->name('saveAttendance');
                }
            );

            // Class
            Route::group(
                [
                    'prefix' => 'class',
                    'as' => 'class.',
                ],
                function () {
                    Route::get('/', [LecturerController::class, 'indexClass'])->name('index');
                    Route::get('/{id?}', [LecturerController::class, 'infoStudent'])->name('infoStudent');
                    Route::patch('note/{id}', [LecturerController::class, 'saveNotes'])->name('saveNotes');
                    Route::patch('updateOfficers', [LecturerController::class, 'updateOfficers'])->name('updateOfficers');
                }
            );

            // Conduct Score
            Route::group(
                [
                    'prefix' => 'conduct-score',
                    'as' => 'conduct-score.',
                ],
                function () {
                    Route::get('/', [LecturerController::class, 'indexConductScore'])->name('index');
                    Route::get('/list', [LecturerController::class, 'listConductScore'])->name('list');
                    Route::get('/list/detail', [LecturerController::class, 'detailConductScore'])->name('detail');
                    Route::post('/list/detail', [LecturerController::class, 'saveConductScore'])->name('save');
                    Route::get('/export-conduct-score', [LecturerController::class, 'exportConductScore'])->name('exportConductScore');
                    Route::get('/information', [LecturerController::class, 'infoConductScore'])->name('infoConductScore');
                }
            );

            // Statistical
            Route::group(
                [
                    'prefix' => 'statistical',
                    'as' => 'statistical.',
                ],
                function () {
                    Route::get('/', [LecturerController::class, 'indexStatistical'])->name('index');
                    Route::get('/export-attendance', [LecturerController::class, 'exportAttendance'])->name('exportAttendance');
                }
            );
        }
    );

    // Route Student Affairs Department
    Route::group(
        [
            'prefix' => 'student-affairs-department',
            'as' => 'student-affairs-department.',
            'middleware' => ['role:4'],
        ],
        function () {
            Route::get('/', [StudentAffairsDepartmentController::class, 'index'])->name('index');

            // Class session
            Route::group(
                [
                    'prefix' => 'class-session',
                    'as' => 'class-session.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'classSession'])->name('index');
                    Route::get('/flexible-class-activities', [StudentAffairsDepartmentController::class, 'flexibleClassActivities'])->name('flexibleClassActivities');
                    Route::post('/create-classSession-registration', [StudentAffairsDepartmentController::class, 'createClassSessionRegistration'])->name('createClassSessionRegistration');
                    Route::put('/edit-classSession-registration/{id?}', [StudentAffairsDepartmentController::class, 'editClassSessionRegistration'])->name('editClassSessionRegistration');
                    Route::patch('/confirm-classSession/{id?}', [StudentAffairsDepartmentController::class, 'confirmClassSession'])->name('updateClassRequest');
                    Route::get('/list-reports', [StudentAffairsDepartmentController::class, 'listReports'])->name('listReports');
                    Route::get('/export-attendance/{requestId?}/{classId?}', [StudentAffairsDepartmentController::class, 'exportReport'])->name('exportReport');
                }
            );

            // Semester
            Route::group(
                [
                    'prefix' => 'semester',
                    'as' => 'semester.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'indexSemester'])->name('index');
                    Route::post('/', [StudentAffairsDepartmentController::class, 'createSemester'])->name('create');
                    Route::put('/edit-semester/{id?}', [StudentAffairsDepartmentController::class, 'editSemester'])->name('edit');
                    Route::delete('/{id?}', [StudentAffairsDepartmentController::class, 'deleteSemester'])->name('delete');
                }
            );

            // Account
            Route::group(
                [
                    'prefix' => 'account',
                    'as' => 'account.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'account'])->name('index');
                    Route::get('/student', [StudentAffairsDepartmentController::class, 'accountStudent'])->name('student');
                    // crud lecturer
                    Route::post('/lecturer', [StudentAffairsDepartmentController::class, 'lecturerImportByExcel'])->name('importLecturer');
                    Route::put('/lecturer/{id?}', [StudentAffairsDepartmentController::class, 'editAccount'])->name('editLecturer');
                    Route::delete('/lecturer/{id?}', [StudentAffairsDepartmentController::class, 'deleteAccount'])->name('deleteLecturer');
                    Route::post('/lecturer/{id}/restore', [StudentAffairsDepartmentController::class, 'restoreAccount'])->name('restoreAccount');

                    // crud student
                    Route::post('/student', [StudentAffairsDepartmentController::class, 'studentImportByExcel'])->name('importStudent');
                    Route::post('/student/create', [StudentAffairsDepartmentController::class, 'createAccount'])->name('createAccount');
                    Route::put('/student/{id?}', [StudentAffairsDepartmentController::class, 'editAccountStudent'])->name('editStudent');
                    Route::delete('/student/{id?}', [StudentAffairsDepartmentController::class, 'deleteAccountStudent'])->name('deleteStudent');
                    Route::post('/student/{id}/restore', [StudentAffairsDepartmentController::class, 'restoreAccountStudent'])->name('restoreAccountStudent');
                }
            );


            // Room
            Route::group(
                [
                    'prefix' => 'room',
                    'as' => 'room.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'indexRoom'])->name('index');
                    Route::post('/', [StudentAffairsDepartmentController::class, 'createRoom'])->name('create');
                    Route::put('/{id?}', [StudentAffairsDepartmentController::class, 'editRoom'])->name('edit');
                    Route::delete('/{id?}', [StudentAffairsDepartmentController::class, 'deleteRoom'])->name('delete');
                }
            );

            // Class
            Route::group(
                [
                    'prefix' => 'class',
                    'as' => 'class.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'indexClass'])->name('index');
                    Route::post('/', [StudentAffairsDepartmentController::class, 'createClass'])->name('store');
                    Route::put('/{id}', [StudentAffairsDepartmentController::class, 'editClass'])->name('update');
                    Route::delete('/{id}', [StudentAffairsDepartmentController::class, 'deleteClass'])->name('delete');
                }
            );

            // Conduct Score
            Route::group(
                [
                    'prefix' => 'conduct-score',
                    'as' => 'conduct-score.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'indexConductScore'])->name('index');
                    Route::get('/information', [StudentAffairsDepartmentController::class, 'infoConductScore'])->name('infoConductScore');
                    Route::get('/export-conduct-score', [StudentAffairsDepartmentController::class, 'exportConductScore'])->name('exportConductScore');
                    Route::post('/', [StudentAffairsDepartmentController::class, 'createConductScore'])->name('create');
                     Route::put('/{id}', [StudentAffairsDepartmentController::class, 'editConductScore'])->name('update');
                     Route::delete('/{id}', [StudentAffairsDepartmentController::class, 'deleteConductScore'])->name('delete');
                }
            );

//            Academic warning
            Route::group(
                [
                    'prefix' => 'academic-warning',
                    'as' => 'academic-warning.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'indexAcademicWarning'])->name('index');
                    Route::post('/', [StudentAffairsDepartmentController::class, 'createAcademicWarning'])->name('store');
                    Route::get('/{id?}', [StudentAffairsDepartmentController::class, 'infoAcademicWarning'])->name('infoAcademicWarning');
                     Route::put('/{id?}', [StudentAffairsDepartmentController::class, 'editAcademicWarning'])->name('update');
                     Route::delete('/{id?}', [StudentAffairsDepartmentController::class, 'deleteAcademicWarning'])->name('delete');
                }
            );

            // Statistical
            Route::group(
                [
                    'prefix' => 'statistical',
                    'as' => 'statistical.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'indexStatistical'])->name('index');
                    Route::get('/export-attendance', [StudentAffairsDepartmentController::class, 'exportAttendance'])->name('exportAttendance');
                }
            );

        }
    );

    // Route Class Staff
    Route::group(
        [
            'prefix' => 'class-staff',
            'as' => 'class-staff.',
            'middleware' => ['role:3'],
        ],
        function () {
            Route::get('/', [ClassStaffController::class, 'index'])->name('index');

            // class session
            Route::group(
                [
                    'prefix' => 'class-session',
                    'as' => 'class-session.',
                ],
                function () {
                    Route::get('/', [ClassStaffController::class, 'indexClassSession'])->name('index');
                    Route::get('/detail', [ClassStaffController::class, 'detailClassSession'])->name('detailClassSession');
                    Route::post('/', [ClassStaffController::class, 'confirmAttendance'])->name('confirmAttendance');
                    Route::patch('/', [ClassStaffController::class, 'updateAbsence'])->name('updateAbsence');
                    Route::get('/history', [ClassStaffController::class, 'history'])->name('history');
                    Route::get('/report', [ClassStaffController::class, 'report'])->name('report');
                    Route::post('/report', [ClassStaffController::class, 'storeReport'])->name('storeReport');
                    Route::put('/report/{id}', [ClassStaffController::class, 'updateReport'])->name('updateReport');
                    Route::delete('/report/{id}', [ClassStaffController::class, 'deleteReport'])->name('deleteReport');
                }
            );

            // Class
            Route::group(
                [
                    'prefix' => 'class',
                    'as' => 'class.',
                ],
                function () {
                    Route::get('/', [ClassStaffController::class, 'indexClass'])->name('index');
                }
            );

            // Conduct Score
            Route::group(
                [
                    'prefix' => 'conduct-score',
                    'as' => 'conduct-score.',
                ],
                function () {
                    Route::get('/', [ClassStaffController::class, 'indexConductScore'])->name('index');
                    Route::post('/', [ClassStaffController::class, 'SaveConductScore'])->name('save');
                }
            );
        }

    );

    // Route Student
    Route::group(
        [
            'prefix' => 'student',
            'as' => 'student.',
            'middleware' => ['role:0'],
        ],
        function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::post('/', [StudentController::class, 'createOrUpdateStudent'])->name('createOrUpdateStudent');

//            Class session
            Route::group(
                [
                    'prefix' => 'class-session',
                    'as' => 'class-session.',
                ],
                function () {
                    Route::get('/', [StudentController::class, 'indexClassSession'])->name('index');
                    Route::get('/detail', [StudentController::class, 'detailClassSession'])->name('detailClassSession');
                    Route::post('/', [StudentController::class, 'confirmAttendance'])->name('confirmAttendance');
                    Route::patch('/', [StudentController::class, 'updateAbsence'])->name('updateAbsence');
                    Route::get('/history', [StudentController::class, 'history'])->name('history');
                }
            );

            // Class
            Route::group(
                [
                    'prefix' => 'class',
                    'as' => 'class.',
                ],
                function () {
                    Route::get('/', [StudentController::class, 'indexClass'])->name('index');
                }
            );

            // Conduct Score
            Route::group(
                [
                    'prefix' => 'conduct-score',
                    'as' => 'conduct-score.',
                ],
                function () {
                    Route::get('/', [StudentController::class, 'indexConductScore'])->name('index');
                    Route::post('/', [StudentController::class, 'SaveConductScore'])->name('save');
                }
            );
        }
    );

    // Route Faculty Office
    Route::group([
        'prefix' => 'faculty-office',
        'as' => 'faculty-office.',
        'middleware' => ['role:2'],
    ], function () {
        Route::get('/', [FacultyOfficeController::class, 'index'])->name('index');

        // Conduct Score
        Route::group(
            [
                'prefix' => 'conduct-score',
                'as' => 'conduct-score.',
            ],
            function () {
                Route::get('/', [FacultyOfficeController::class, 'indexConductScore'])->name('index');
                Route::get('/list', [FacultyOfficeController::class, 'listConductScore'])->name('list');
                Route::get('/list/detail', [FacultyOfficeController::class, 'detailConductScore'])->name('detail');
                Route::post('/list/detail', [FacultyOfficeController::class, 'saveConductScore'])->name('save');
                Route::get('/information', [FacultyOfficeController::class, 'infoConductScore'])->name('infoConductScore');
            }
        );
    });
});
