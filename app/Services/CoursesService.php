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
     * @throws Exception
     */
    public function storeCourse(array $courseData): Course
    {
        try {
            $course = Course::create($courseData);
            if (isset($courseData['instructor_id'])) {
                $course->instructors()->sync($courseData['instructor_id']);
            }
            return $course->load('instructors');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * @throws Exception
     */
    public function updateCourse(Course $course, array $courseData): Course
    {
        try{
            $course->update(array_filter($courseData));
            if (isset($courseData['instructor_id'])) {
                $course->instructors()->sync($courseData['instructor_id']);
            }
            return $course->load('instructors');
        }catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
