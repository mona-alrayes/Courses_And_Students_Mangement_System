<?php

namespace App\Http\Controllers;

use App\Http\Requests\courses\StoreCourseRequest;
use App\Http\Requests\courses\UpdateCourseRequest;
use App\Models\Course;
use App\Services\CoursesService;

class CourseController extends Controller
{
    protected CoursesService $CoursesService;

    public function __construct(CoursesService $CoursesService){
        $this->CoursesService = $CoursesService;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
       $courses = $this->CoursesService->getCourses();
        return self::paginated($courses, 'Courses retrieved successfully.', 200);
    }

    /**
     * Store a newly created course in storage.
     *
     * @param StoreCourseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCourseRequest $request): \Illuminate\Http\JsonResponse
    {
       $course= $this->CoursesService->storeCourse($request);
        return self::success($course, 'Course created successfully.', 201);
    }

    /**
     * show course method
     *
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Course $course): \Illuminate\Http\JsonResponse
    {
        return self::success($course->load('instructors'), 'Course retrieved successfully.', );
    }

    /**
     * Update the specified course in storage.
     *
     * @param UpdateCourseRequest $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCourseRequest $request, Course $course): \Illuminate\Http\JsonResponse
    {
        $course= $this->CoursesService->updateCourse($course , $request);
        return self::success($course, 'Course updated successfully.', 201);
    }

    /**
     * Remove the specified course from storage.
     *
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Course $course): \Illuminate\Http\JsonResponse
    {
        $course->delete();
        return self::success( 'Course deleted successfully.');
    }
    
    /**
     * show softDeleted courses
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ShowSoftDeletedCourses(): \Illuminate\Http\JsonResponse
    {
        $softDeletedCourses = Course::onlyTrashed()->paginate(10);
        return self::paginated($softDeletedCourses, 'Deleted Courses retrieved successfully.', 200);
    }

    /**
     * restore softDeleted course
     *
     * @param [type] $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreCourse($id): \Illuminate\Http\JsonResponse
    {
        $course = Course::withTrashed()->findOrFail($id);
        $course->restore();
        return self::success( $course,'Course restored successfully.', 201);
    }

    /**
     * Delete forever softDeleted course 
     *
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDeleteCourse(Course $course): \Illuminate\Http\JsonResponse
    {
        $course->forceDelete();
        return self::success( 'Course deleted successfully.', 200);
    }
}
