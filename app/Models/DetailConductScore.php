<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailConductScore extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'conduct_criteria_id',
        'student_conduct_score_id',
        'self_score',
        'class_score',
        'final_score',
        'path',
        'note',
    ];

    public function conductCriteria()
    {
        return $this->belongsTo(ConductCriteria::class, 'conduct_criteria_id');
    }

    public function studentConductScore()
    {
        return $this->belongsTo(StudentConductScore::class, 'student_conduct_score_id');
    }
}
