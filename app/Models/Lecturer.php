<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = [
        'user_id',
        'title_id',
        'faculty_id',
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

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function studyClasses()
    {
        return $this->hasMany(StudyClass::class);
    }
}
