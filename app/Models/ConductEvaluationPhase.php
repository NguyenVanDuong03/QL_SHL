<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConductEvaluationPhase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'conduct_evaluation_period_id',
        'role',
        'open_date',
        'end_date',
    ];

    public function conductEvaluationPeriod()
    {
        return $this->belongsTo(ConductEvaluationPeriod::class);
    }
}
