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
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function conductEvaluationPeriod()
    {
        return $this->belongsTo(ConductEvaluationPeriod::class);
    }
}
