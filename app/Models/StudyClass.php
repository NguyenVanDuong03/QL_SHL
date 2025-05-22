<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyClass extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'major_id',
        'cohort_id',
        'lecturer_id',
        'name'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function classSessionRequests()
    {
        return $this->hasMany(ClassSessionRequest::class, 'study_class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'study_class_id');
    }
}
