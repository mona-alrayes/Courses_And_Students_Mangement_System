<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\courseInstructor;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class CourseInstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all courses and instructors
        $courses = Course::all();
        $instructors = Instructor::all();

        // Attach instructors to courses
        $courses->each(function ($course) use ($instructors) {
            // Randomly pick between 1 and 5 instructors for each course
            $course->instructors()->attach(
                $instructors->random(rand(1, 5))->pluck('id')->toArray()
            );
        });
    }
}
