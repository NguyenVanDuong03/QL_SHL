<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentConductScore extends Model
{
    protected $fillable = [
        'semester_id',
        'student_id',
        'conduct_criteria_id',
        'self_score',
        'class_score',
        'final_score',
        'note',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function conductCriteria()
    {
        return $this->belongsTo(ConductCriteria::class);
    }
}
