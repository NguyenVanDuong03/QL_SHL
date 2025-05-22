<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSessionRequest extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'study_class_id',
        'lecturer_id',
        'class_session_registration_id',
        'room_id',
        'type',
        'proposed_at',
        'location',
        'meeting_type',
        'meeting_id',
        'meeting_password',
        'meeting_url',
        'title',
        'content',
        'status',
        'rejection_reason'
    ];

    public function studyClass()
    {
        return $this->belongsTo(StudyClass::class);
    }
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
    public function classSessionRegistration()
    {
        return $this->belongsTo(ClassSessionRegistration::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
