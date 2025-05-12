<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSessionRegistration extends Model
{
    protected $fillable = [
        'semester_id',
        'open_date',
        'close_date',
    ];

}
