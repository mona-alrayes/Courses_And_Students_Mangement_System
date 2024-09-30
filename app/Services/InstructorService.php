<?php

namespace App\Services;

use App\Models\Instructor;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InstructorService
{

    /**
     * get all instructors with courses they belong to
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getInstructors(): LengthAwarePaginator
    {
        try {
            return Instructor::with(['courses' => fn($q) => $q->select('courses.id', 'title')])->paginate(10);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
    /**
     * add new instructor
     * @throws Exception
     * @param array $instructorData
     * @return Instructor
     */
    public function storeInstructor(array $instructorData): Instructor
    {
        try {
            $instructor = Instructor::create($instructorData);

            // Ensure 'course_id' is always an array, even if a single ID is provided
            if (isset($instructorData['course_id'])) {
                $courseIds = is_array($instructorData['course_id']) ? $instructorData['course_id'] : [$instructorData['course_id']];
                $instructor->courses()->sync($courseIds);
            }

            return $instructor->load('courses');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Update instructor 
     *
     * @param Instructor $instructor
     * @param array $instructorData
     * @throws Exception
     * @return Instructor
     */
    public function updateInstructor(Instructor $instructor, array $instructorData): Instructor
    {
        try {
            $instructor->update(array_filter($instructorData));
            // Ensure 'course_id' is always an array, even if a single ID is provided
            if (isset($instructorData['course_id'])) {
                $courseIds = is_array($instructorData['course_id']) ? $instructorData['course_id'] : [$instructorData['course_id']];
                $instructor->courses()->sync($courseIds);
            }
            return $instructor->load('courses');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
