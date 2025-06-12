<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConductCriteriaEvidence extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'student_conduct_score_id',
        'conduct_criteria_id',
        'path',
        'description',
    ];

    public function studentConductScore()
    {
        return $this->belongsTo(StudentConductScore::class);
    }

    public function conductCriteria()
    {
        return $this->belongsTo(ConductCriteria::class);
    }
}
