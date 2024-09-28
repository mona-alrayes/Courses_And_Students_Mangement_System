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
     * @throws Exception
     */
    public function storeInstructor(array $instructorData): Instructor
    {
        try {
            $instructor = Instructor::create($instructorData);

            // Use array syntax to access 'course_id'
            if (isset($instructorData['course_id'])) {
                $instructor->courses()->sync($instructorData['course_id']);
            }

            return $instructor->load('courses');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * @throws Exception
     */
    public function updateInstructor(Instructor $instructor, array $instructorData): Instructor
    {
        try{
            $instructor->update(array_filter($instructorData));
            if (isset($instructorData['course_id'])) {
                $instructor->courses()->sync($instructorData['course_id']);
            }
            return $instructor->load('courses');
        }catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
