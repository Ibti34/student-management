<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('verified_identity', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();

                // If the logged-in user is NOT an admin and NOT a teacher,
                // then restrict them to only seeing their own record.
                if ($user->role !== 'admin' && $user->role !== 'teacher') {
                    $builder->where('email', $user->email)
                        ->where('name', $user->name);
                }
            }
        });
    }
}
