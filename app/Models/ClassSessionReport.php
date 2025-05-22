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
        'value_1',
        'value_2',
        'value_3',
        'value_4',
        'value_5',
        'value_6',
        'value_7',
        'value_8',
        'value_9'
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
