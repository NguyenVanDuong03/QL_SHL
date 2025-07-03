<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'student_id',
        'class_session_request_id',
        'status',
        'reason'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classSessionRequest()
    {
        return $this->belongsTo(ClassSessionRequest::class);
    }

}
