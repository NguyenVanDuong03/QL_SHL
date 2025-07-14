<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'school_year',
        'start_date',
        'end_date',
    ];

    public function academicWarnings()
    {
        return $this->hasMany(AcademicWarning::class);
    }

    public function classSessionRegistrations()
    {
        return $this->hasMany(ClassSessionRegistration::class);
    }

    public function conductEvaluationPeriods()
    {
        return $this->hasMany(ConductEvaluationPeriod::class);
    }

}
