<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory , softDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];


    public function instructors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Instructor::class , 'course_instructor', 'course_id', 'instructor_id');
    }
    public function students(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Student::class , 'course_student', 'course_id', 'student_id');
    }

}
