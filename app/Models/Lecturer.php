<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecturer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'faculty_id',
        'title',
        'address',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyClasses()
    {
        return $this->hasMany(StudyClass::class);
    }
}
