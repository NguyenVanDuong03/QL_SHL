<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSessionRequest extends Model
{
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
    
}
