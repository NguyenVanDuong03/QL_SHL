<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConductCriteria extends Model
{
    protected $fillable = [
        'name',
        'description',
        'max_score',
    ];

}
