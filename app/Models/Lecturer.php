<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'title_id',
        'major_id',
    ];
}
