<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConductCriteria extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'content',
        'max_score',
    ];

}
