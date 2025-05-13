<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicWarning extends Model
{
    protected $fillable = [
        'student_id',
        'warning_date',
        'reason',
        'semester_1_name',
        'semester_1_credits',
        'semester_1_gpa_10',
        'semester_1_gpa_4',
        'semester_2_name',
        'semester_2_credits',
        'semester_2_gpa_10',
        'semester_2_gpa_4',
        'total_credits_all',
        'total_credists_2_recent_semesters',
        'academic_status_latest',
        'academic_status_summary',
        'note'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
