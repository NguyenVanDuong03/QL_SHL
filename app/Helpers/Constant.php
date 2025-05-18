<?php

namespace App\Helpers;

class Constant
{
    const DEFAULT_LIMIT = 6;
    const DEFAULT_LIMIT_8 = 8;
    const DEFAULT_LIMIT_12 = 12;
    const ROLE_LIST = [
        'TEACHER' => 0,
        'STUDENT_AFFAIRS_DEPARTMENT' => 1,
        'CLASS_STAFF' => 2,
        'STUDENT' => 3,
    ];

    const SEMESTER_TYPE = [
        'SEMESTER_1' => 'Học kỳ 1',
        'SEMESTER_2' => 'Học kỳ 2',
        'SUPPLEMENTARY_SEMESTER' => 'Học kỳ phụ',
        'SUMMER_SEMESTER' => 'Học kỳ hè',
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

    const CLASS_SESSION_POSITION = [
        'OFFLINE' => 0,
        'ONLINE' => 1,
        'OTHER' => 2,
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
