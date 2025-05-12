<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConductEvaluationPeriod extends Model
{
    protected $fillable = [
        'semester_id',
        'start_date',
        'end_date',
    ];

}
