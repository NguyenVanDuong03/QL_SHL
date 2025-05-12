<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSessionReport extends Model
{
    protected $table = 'class_session_reports';

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

}
