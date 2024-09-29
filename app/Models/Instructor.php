<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Instructor extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'experience',
        'specialty',
    ];

    protected $casts = ['experience' => 'integer'];

    /**
     * Relationship function
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * 
     */

    public function courses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_instructor', 'instructor_id', 'course_id');
    }

    /**
     * get student Names of specific instructor
     *
     * @param Instructor $instructor
     * @return void
     */

    public function studentNames(Instructor $instructor)
    {
        return Student::whereHas('courses', function ($query) use ($instructor) {
            $query->whereHas('instructors', function ($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            });
        });
    }

    /**
     * students of specific insturator function
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    
    public function students(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        #TODO should find way to fix hasManyThrough or delete it completely
        return $this->hasManyThrough(Student::class, courseInstructor::class, 'instructor_id', 'course_id', 'id', 'id');
    }
}
