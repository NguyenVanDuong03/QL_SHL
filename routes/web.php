<?php

use App\Http\Controllers\ClassStaffController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentAffairsDepartmentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
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
            Route::get('/class', function () {
                return view('teacher.class.index');
            })->name('class.index');
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

            Route::get('/account', [StudentAffairsDepartmentController::class, 'account'])->name('account.index');

            // Class session
            Route::group(
                [
                    'prefix' => 'class-session',
                    'as' => 'class-session.',
                ],
                function () {
                    Route::get('/', [StudentAffairsDepartmentController::class, 'classSession'])->name('index');
                    Route::get('/history', [StudentAffairsDepartmentController::class, 'history'])->name('history');
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
