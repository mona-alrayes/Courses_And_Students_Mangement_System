<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\student;
use Illuminate\Database\Seeder;

class CourseStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all courses and students
        $courses = Course::all();
        $students = Student::all();

        // Attach students to courses
        $courses->each(function ($course) use ($students) {
            // Randomly pick between 10 and 30 students for each course
            $course->students()->attach(
                $students->random(rand(10, 30))->pluck('id')->toArray()
            );
        });
    }
}
