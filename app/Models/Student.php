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

}
