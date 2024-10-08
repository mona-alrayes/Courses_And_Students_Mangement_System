<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courseStudent extends Model
{
    use HasFactory;
    
    protected $table = 'course_student';

    protected $fillable = [
        'course_id',
        'student_id',
    ];
}
