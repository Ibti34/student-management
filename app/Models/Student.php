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
        'year_of_study',
        'role',
        'user_id',
        'school_class_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('verified_identity', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();

                if ($user->role !== 'admin' && $user->role !== 'teacher') {
                    $builder->where(function (Builder $query) use ($user) {
                        $query->where('user_id', $user->id)
                            ->orWhere(function (Builder $fallback) use ($user) {
                                $fallback->whereNull('user_id')
                                    ->where('email', $user->email)
                                    ->where('name', $user->name);
                            });
                    });
                }
            }
        });
    }
}
