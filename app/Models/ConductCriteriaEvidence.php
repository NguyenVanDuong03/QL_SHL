<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConductCriteriaEvidence extends Model
{
    protected $fillable = [
        'student_conduct_id',
        'image_path',
        'description',
    ];

}
