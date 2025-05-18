<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = [
        'name',
        'school_year',
        'start_date',
        'end_date',
    ];

}
