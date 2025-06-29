<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status'
    ];

    public function classSessionRequests()
    {
        return $this->hasOne(ClassSessionRequest::class);
    }

}
