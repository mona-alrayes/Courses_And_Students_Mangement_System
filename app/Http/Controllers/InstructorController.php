<?php

namespace App\Http\Controllers;

use App\Http\Requests\instructors\StoreInstructorRequest;
use App\Http\Requests\instructors\UpdateInstructorRequest;
use App\Models\Instructor;
use App\Services\InstructorService;
use Exception;

class InstructorController extends Controller
{
    protected InstructorService $instructorService;

    public function __construct(InstructorService $instructorService)
    {
        $this->instructorService = $instructorService;
    }

    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $instructors = $this->instructorService->getInstructors();
        return self::paginated($instructors, 'instructors retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function store(StoreInstructorRequest $request): \Illuminate\Http\JsonResponse
    {
        $instructor = $this->instructorService->storeInstructor($request->validated());
        return self::success($instructor, 'instructor created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        return self::success($instructor, 'instructor retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        $instructor = $this->instructorService->updateInstructor($instructor, $request->validated());
        return self::success($instructor, 'instructor updated successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        $instructor->delete();
        return self::success($instructor, 'instructor deleted successfully');
    }

    public function ShowSoftDeletedInstructors(): \Illuminate\Http\JsonResponse
    {
        $softDeletedInstructors = Instructor::onlyTrashed()->paginate(10);
        return self::paginated($softDeletedInstructors, 'instructors retrieved successfully', 200);
    }
    public function restoreInstructor($id): \Illuminate\Http\JsonResponse
    {
        $instructor = Instructor::withTrashed()->findOrFail($id); // Include soft-deleted records
        $instructor->restore();
        return self::success($instructor, 'Instructor restored successfully');
    }

    public function forceDeleteInstructor(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        $instructor->forceDelete();
        return self::success($instructor, 'instructor deleted successfully');
    }

    public function showCourses(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        //        $courses = $instructor->hasMany(Course::class)->paginate(10);
        $courses = $instructor->courses()->paginate(10);
        return self::paginated($courses, 'courses retrieved successfully', 200);
    }
    public function showStudents(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        //       $students = $instructor->students()->paginate(10);
        $students = $instructor->studentNames($instructor)->paginate(10);
        return self::paginated($students, 'students retrieved successfully', 200);
    }
}
