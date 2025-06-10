<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConductCriteriaEvidence extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'student_conduct_id',
        'path',
        'description',
    ];

    public function studentConduct()
    {
        return $this->belongsTo(StudentConductScore::class);
    }
}
