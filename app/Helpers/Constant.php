<?php

namespace App\Helpers;

class Constant
{
    const DEFAULT_LIMIT = 6;
    const DEFAULT_LIMIT_8 = 8;

    const DEFAULT_LIMIT_12 = 12;

    const ROLE_LIST = [
        'STUDENT' => 0,
        'TEACHER' => 1,
        'FACULTY_OFFICE' => 2,
//        'CLASS_STAFF' => 3,
        'STUDENT_AFFAIRS_DEPARTMENT' => 3,
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
        'DONE' => 3,
    ];

    const ATTENDANCE_STATUS = [
        'CONFIRM' => 0,
        'EXCUSED' => 1,
        'PRESENT' => 2,
        'ABSENT' => 3,
    ];

    const ROOM_STATUS = [
        'AVAILABLE' => 0,
        'UNAVAILABLE' => 1,
    ];

    const STUDENT_CONDUCT_SCORE_STATUS = [
        'STUDENT' => 0,
        'TEACHER' => 1,
        'DEPARTMENT' => 2,
    ];
}
