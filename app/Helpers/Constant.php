<?php

namespace App\Helpers;

class Constant
{
    const DEFAULT_LIMIT = 5;

    const ROLE_LIST = [
        'TEACHER' => 0,
        'STUDENT_AFFAIRS_DEPARTMENT' => 1,
        'CLASS_STAFF' => 2,
        'STUDENT' => 3,
    ];

    const SEMESTER_TYPE = [
        'SEMESTER_1' => 1,
        'SEMESTER_2' => 2,
        'SUPPLEMENTARY_SEMESTER' => 3,
        'SUMMER_SEMESTER' => 4,
    ];

    const STUDENT_POSITION = [
        'STUDENT' => 0,
        'CLASS_PRESIDENT' => 1,
        'VICE_PRESIDENT' => 2,
        'SECRETARY' => 3,
    ];

    const CLASS_SESSION_TYPE = [
        'FIXED' => 0,
        'FLEXIBLE' => 1,
    ];

    const CLASS_SESSION_STATUS = [
        'ACTIVE' => 0,
        'APPROVED' => 1,
        'REJECTED' => 2,
    ];

    const ATTENDANCE_STATUS = [
        'PRESENT' => 0,
        'ABSENT' => 1,
        'LATE' => 2,
        'EXCUSED' => 3,
    ];

    const CLASS_SESSION_VALUE_2 = [
        'YES' => 0,
        'NO' => 1,
    ];
}
