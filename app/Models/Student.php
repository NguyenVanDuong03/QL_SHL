<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'cohort_id',
        'study_class_id',
        'student_code',
        'position',
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
}
