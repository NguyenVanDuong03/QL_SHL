<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentConductScore extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'conduct_evaluation_period_id',
        'student_id',
        'conduct_criteria_id',
        'self_score',
        'class_score',
        'final_score',
        'note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function conductCriteria()
    {
        return $this->belongsTo(ConductCriteria::class);
    }

    public function conductEvaluationPeriod()
    {
        return $this->belongsTo(ConductEvaluationPeriod::class);
    }
}
