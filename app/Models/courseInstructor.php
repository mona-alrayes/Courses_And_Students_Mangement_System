<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class courseInstructor extends Pivot
{
    protected $table = 'course_instructor';
    
    protected $fillable = [
        'course_id',
        'instructor_id',
    ];

}
