<?php

namespace App\Services;

use App\Models\Course;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CoursesService
{

    /**
     * get all instructors with courses they belong to
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getCourses(): LengthAwarePaginator
    {
        try {
            return Course::with(['instructors' => fn($q) => $q->select('instructors.id', 'name')])->paginate(10);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * store new course in storage 
     *
     * @param [type] $courseData
     * @return Course
     * @throws Exception
     */
    public function storeCourse($courseData): Course
    {
        try {
            $course = Course::create($courseData);
            // Ensure 'course_id' is always an array, even if a single ID is provided
            if (isset($courseData['instructor_id'])) {
                $instructorIds = is_array($courseData['instructor_id']) ? $courseData['instructor_id'] : [$courseData['instructor_id']];
                $course->instructors()->sync($instructorIds);
            }
            return $course->load('instructors');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * update specific course 
     * @throws Exception
     * @param Course $course
     * @param [type] $courseData
     * @return Course
     */
    public function updateCourse(Course $course, $courseData): Course
    {
        try {
            $course->update(array_filter($courseData));
            if (isset($courseData['instructor_id'])) {
                $instructorIds = is_array($courseData['instructor_id']) ? $courseData['instructor_id'] : [$courseData['instructor_id']];
                $course->instructors()->sync($instructorIds);
            }
            return $course->load('instructors');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
