<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'cohort_id',
        'study_class_id',
        'student_code',
        'position',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function studyClass()
    {
        return $this->belongsTo(StudyClass::class);
    }

    public function academicWarnings()
    {
        return $this->hasMany(AcademicWarning::class);
    }
}
