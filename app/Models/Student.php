<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email',
        'age',
        'phone',
        'university',
        'department',
        'role',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
