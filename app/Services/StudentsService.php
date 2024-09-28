<?php
namespace App\Services;

use App\Models\student;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudentsService
{

    /**
     * get all instructors with courses they belong to
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getStudents(): LengthAwarePaginator
    {
        try {
            return Student::with(['courses' => fn($q) => $q->select('courses.id', 'title')])->paginate(10);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function storeCourse(array $studentData): Student
    {
        try {
            $student = Student::create($studentData);
            if (isset($studentData['course_id'])) {
                $student->courses()->sync($studentData['course_id']);
            }
            return $student->load('courses');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * @throws Exception
     */
    public function updateStudent(Student $student, array $studentData): Student
    {
        try{
            $student->update(array_filter($studentData));
            if (isset($studentData['course_id'])) {
                $student->courses()->sync($studentData['course_id']);
            }
            return $student->load('courses');
        }catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
