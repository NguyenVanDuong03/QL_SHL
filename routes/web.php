<?php

use App\Http\Controllers\ClassStaffController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentAffairsDepartmentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->role == 0) {
        return redirect()->route('student-affairs-department.index');
    } else if (Auth::check() && Auth::user()->role == 1) {
        return redirect()->route('teacher.index');
    } else if (Auth::check() && Auth::user()->role == 2) {
        return redirect()->route('class-staff.index');
    } else if (Auth::check() && Auth::user()->role == 3) {
        return redirect()->route('student.index');
    }

    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    // Route Teacher
    Route::group(
        [
            'prefix' => 'teacher',
            'as' => 'teacher.',
            'middleware' => ['role:0'],
        ],
        function () {
            Route::get('/', [LecturerController::class, 'index'])->name('index');

            // class session
            Route::group(
                [
                    'prefix' => 'class-session',
                    'as' => 'class-session.',
                ],
                function () {
                    Route::get('/', [LecturerController::class, 'indexClassSession'])->name('index');
                    Route::get('/fixed-class-activitie', [LecturerController::class, 'indexFixedClassActivitie'])->name('fixed-class-activitie');
                    Route::get('/flexible-class-activitie', [LecturerController::class, 'indexFlexibleClassActivitie'])->name('flexible-class-activitie');
                    Route::get('/create/{id}', [LecturerController::class, 'createClassSession'])->name('create');
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
//                    Route::get('/{id?}', [LecturerController::class, 'infoStatistical'])->name('infoStatistical');
                }
            );
        }
    );

    // Route Student Affairs Department
    Route::group(
        [
            'prefix' => 'student-affairs-department',
            'as' => 'student-affairs-department.',
            'middleware' => ['role:1'],
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
                    Route::get('/history/{id}', [StudentAffairsDepartmentController::class, 'history'])->name('history');
                    Route::post('/create-classSession-registration', [StudentAffairsDepartmentController::class, 'createClassSessionRegistration'])->name('createClassSessionRegistration');
                    Route::put('/edit-classSession-registration/{id?}', [StudentAffairsDepartmentController::class, 'editClassSessionRegistration'])->name('editClassSessionRegistration');
                    Route::put('/confirm-classSession-registration/{id?}', [StudentAffairsDepartmentController::class, 'comfirmClassSession'])->name('updateClassRequest');
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

                    // crud student
                    Route::post('/student', [StudentAffairsDepartmentController::class, 'studentImportByExcel'])->name('importStudent');
                    Route::put('/student/{id?}', [StudentAffairsDepartmentController::class, 'editAccountStudent'])->name('editStudent');
                    Route::delete('/student/{id?}', [StudentAffairsDepartmentController::class, 'deleteAccountStudent'])->name('deleteStudent');
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
                    Route::post('/', [StudentAffairsDepartmentController::class, 'createClass'])->name('create');
                    Route::put('/{id?}', [StudentAffairsDepartmentController::class, 'editClass'])->name('edit');
                    Route::delete('/{id?}', [StudentAffairsDepartmentController::class, 'deleteClass'])->name('delete');
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
                    Route::post('/', [StudentAffairsDepartmentController::class, 'createConductScore'])->name('create');
                    Route::get('/{id?}', [StudentAffairsDepartmentController::class, 'infoConductScore'])->name('infoConductScore');
                    // Route::put('/{id?}', [StudentAffairsDepartmentController::class, 'editConductScore'])->name('edit');
                    // Route::delete('/{id?}', [StudentAffairsDepartmentController::class, 'deleteConductScore'])->name('delete');
                }
            );

        }
    );

    // Route Class Staff
    Route::group(
        [
            'prefix' => 'class-staff',
            'as' => 'class-staff.',
            'middleware' => ['role:2'],
        ],
        function () {
            Route::get('/', [ClassStaffController::class, 'index'])->name('index');
        }
    );

    // Route Student
    Route::group(
        [
            'prefix' => 'student',
            'as' => 'student.',
            'middleware' => ['role:3'],
        ],
        function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
        }
    );
});
