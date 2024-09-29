<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;


class Instructor extends Model
{
    use HasFactory, softDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


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
     * Get all instructors with the given name.
     *
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     * 
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
    
    public function students():\Staudenmeir\EloquentHasManyDeep\HasManyDeep
    {
        return $this->hasManyDeep(Student::class, ['course_instructor', Course::class, 'course_student']);
    }

    /**
     * get students of specific insturator function
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function getStudents()
    {
        return $pivotData= $this->hasManyThrough(courseStudent::class, courseInstructor::class, 'instructor_id', 'course_id', 'id', 'course_id');
        // return Student::whereIn('id', $pivotData->pluck('student_id'));
    }
}
