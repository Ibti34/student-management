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
        'user_id',
    ];

    // Student → User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Student → Attendance
    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }
}
