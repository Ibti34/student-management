<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'score',
        'semester',
        'academic_year',
        'term',
        'teacher_id'
    ];

    protected $appends = [
        'letter_grade',
        'grade_points',
        'status_label',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getLetterGradeAttribute(): string
    {
        return match (true) {
            $this->score >= 90 => 'A',
            $this->score >= 80 => 'B+',
            $this->score >= 70 => 'B',
            $this->score >= 60 => 'C+',
            $this->score >= 50 => 'C',
            $this->score >= 45 => 'D',
            default => 'F',
        };
    }

    public function getGradePointsAttribute(): float
    {
        return match (true) {
            $this->score >= 90 => 4.0,
            $this->score >= 80 => 3.5,
            $this->score >= 70 => 3.0,
            $this->score >= 60 => 2.5,
            $this->score >= 50 => 2.0,
            $this->score >= 45 => 1.0,
            default => 0.0,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->score >= 50 ? 'PASS' : 'FAIL';
    }
}
