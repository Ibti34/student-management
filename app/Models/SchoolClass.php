<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section',
        'academic_year',
        'homeroom_teacher',
        'capacity',
    ];

    protected $appends = [
        'display_name',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return trim("{$this->name} {$this->section}");
    }
}
