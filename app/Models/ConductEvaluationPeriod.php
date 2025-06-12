<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConductEvaluationPeriod extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'semester_id',
        'open_date',
        'end_date',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function studentConductScores()
    {
        return $this->hasMany(StudentConductScore::class);
    }
}
