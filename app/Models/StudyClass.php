<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyClass extends Model
{
    protected $fillable = [
        'major_id',
        'cohort_id',
        'name',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }
}
