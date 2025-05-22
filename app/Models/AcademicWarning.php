<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicWarning extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'student_id',
        'semester_id',
        'warning_date',
        'reason',
        'credits',
        'gpa_10',
        'gpa_4',
        'academic_status',
        'note'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
