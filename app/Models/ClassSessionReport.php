<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSessionReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_session_request_id',
        'reporter_id',
        'attending_students',
        'teacher_attendance',
        'politics_ethics_lifestyle',
        'academic_training_status',
        'on_campus_student_status',
        'off_campus_student_status',
        'other_activities',
        'suggestions_to_faculty_university',
        'path'
    ];

    public function classSessionRequest()
    {
        return $this->belongsTo(ClassSessionRequest::class);
    }
    public function reporter()
    {
        return $this->belongsTo(Student::class, 'reporter_id');
    }
}
